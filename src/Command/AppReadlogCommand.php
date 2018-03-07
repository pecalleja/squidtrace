<?php

namespace App\Command;

use App\Entity\Registro;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppReadlogCommand extends Command
{
    protected static $defaultName = 'app:readlog';

    private $em;
    private $nombre;//nombre del fichero a leer
    /** @var Registro $registro**/
    private $registro;

    static $AGREGA = 0;
    static $ROTADO = 1;
    static $IGUAL = 2;
    static $ERROR = 3;
    static $INIT = 4;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('A partir de los datos en el fichero acces.log llena la BD.')
            ->addArgument('file', InputArgument::REQUIRED, 'Fichero a procesar')
            ->addOption('inicializar', 'i', InputOption::VALUE_OPTIONAL, 'Inicializar el fichero y guarda en la bd el tamaño');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $name = $input->getArgument('file');

        $this->nombre = $name;
        // Get a file handler.
        $handle = @fopen($name, "r");

        //get file size
        $size = filesize($name);

        //obtengo el timestamp de la pimera linea del log
        if (($buffer = fgets($handle, 4096)) !== false) {
            $array_log_first = preg_split("/\s+/", $buffer);
        }else{
            //tira una exepcion, no hay nada que leer
            echo "nada que leer";
            return 1;
        }

        //the first timestamp
        $timestamp = $array_log_first[0];

        //get registro size
        $registro_repo = $this->em->getRepository(Registro::class);
        $registro = $registro_repo->getRegistro($timestamp);
        $this->registro = $registro;

        $last_position = $registro->getSize();

        $init = $input->getOption('inicializar');

        if($init){
            $state = self::$INIT;
        }else{
            $state = $this->getState($timestamp, $size);
        }

        switch ($state) {
            case self::$AGREGA:
                $cant = $this->loadAccessLog($handle, $last_position);
                $io->success("AGREGADO. $cant Lineas procesadas");
                //$output->writeln("AGREGADO. $cant Lineas procesadas");
                break;
            case self::$ROTADO:
                $cant = $this->loadAccessLog($handle);
                $io->success("ROTADO. $cant Lineas procesadas");
                //$output->writeln("ROTADO. $cant Lineas procesadas");
                break;
            case self::$IGUAL:
                $io->success("IGUAL. Nada que hacer");
                //$output->writeln("IGUAL. Nada que hacer");
                break;
            case self::$INIT:
                $io->success("INIT. Fichero Inicializado");
                //$output->writeln("INIT. Fichero Inicializado");
                break;
            case self::$ERROR:
            default:
                $io->error("ERROR. No se ha realizado la operacion");
                //$output->writeln("ERROR. No se ha realizado la operacion");
                break;
        }

        if( $state == self::$INIT || ($state != self::$ERROR && $state != self::$IGUAL) ){
            //si no fue error guardo el estado actual en el registro
            $registro_repo->setRegistro($timestamp, $size);
            $io->success("Timestamp and size: $size saved in database");
        }
        /*
        $io->section("section 2");
        $io->block(array("comentario1", "comentario2"));

        $io->title("titulo");
        $io->note("Hola esto es una nota");
        */
        return 0;

    }

    public function loadAccessLog($handle, $last_position=0)
    {
        date_default_timezone_set("EST");

        $lines_total = 0;
        $lines_valid = 0;

        //rebobino a donde se quedo
        fseek($handle,$last_position);

        $fecha_start = new \DateTime();

        gc_enable();

        $conn = $this->em->getConnection();
        $users = $conn->fetchAll('SELECT * FROM usuario');
        $contens = $conn->fetchAll('SELECT * FROM contenidos');
        $ip_list = $conn->fetchAll('SELECT * FROM lista_ip');
        $aliases = $conn->fetchAll('SELECT * FROM alias_www');
        $freesites = $conn->fetchAll('SELECT * FROM freesites');
        $usuarios = array();
        $contenidos = array();
        $ip_listado = array();
        foreach ($users as $usuario){
            $usuarios[$usuario["id"]]=$usuario["login"];
        }
        foreach ($contens as $contecon){
            $contenidos[$contecon["id"]]=$contecon["mime"];
        }
        foreach ($ip_list as $ipip){
            $ip_listado[$ipip["id"]]=$ipip["ip"];
        }

        while (($buffer = fgets($handle, 4096)) !== false) {
            $lines_total++;

            $log = preg_split("/\s+/", $buffer);
            if(count($log)<10){
                continue;
            }

            $invalid_line = (preg_match('(TCP_DENIED|_HIT|555)',$log[3]) || (preg_match('(^cache_object)',$log[6])));

            if($invalid_line == 1){
                continue;
            }
            $lines_valid++;

            $user=strtolower($log[7]);
            $conte=strtolower(preg_replace('/(\"|\\\\)/','',$log[9]));
            $ipp=$log[2];

            //select user if not insert
            $key = array_search($user, $usuarios);
            if ($key === false) {
                $conn->insert('usuario',array('login'=>$user,'creado'=>$fecha_start->format("Y-m-d"), 'rol'=>'ROLE_USER'));
                //$users=$conn->fetchAssoc('SELECT * FROM usuario WHERE `login` LIKE "'.$user.'"');
                $user_id = $conn->lastInsertId();
                $usuarios[$user_id]=$user;
            }else{
                $user_id = $key;
            }

            //select contenido if not insert
            $key = array_search($conte, $contenidos);
            if ($key === false) {
                $conn->insert('contenidos',array('mime'=>$conte));
                $mime_id = $conn->lastInsertId();
                $contenidos[$mime_id]=$conte;
            }else{
                $mime_id = $key;
            }

            //select ip if not insert
            $key = array_search($ipp, $ip_listado);
            if ($key === false) {
                $conn->insert('lista_ip',array('ip'=>$ipp));
                $ip_id = $conn->lastInsertId();
                $ip_listado[$ip_id]=$ipp;
            }else{
                $ip_id = $key;
            }

            //obtengo la url(en el caso de que coincida con un alias devuelvo el alias)
            $url_host = parse_url($log[6], PHP_URL_HOST);
            if (is_null($url_host)) {
                continue;
            }
            $url = $url_host;
            $is_alias = 0; //me dice si es un alias o no
            foreach ($aliases as $alias) {
                $pattern = $alias['patron'];
                if (strpos($url_host, $pattern) !== false) {
                    $url = $alias['alias'];
                    $is_alias = 1;
                    break;
                }
            }

            //veo si es un free site
            $free = 0;
            foreach ($freesites as $free_site) {
                $pattern = $free_site['patron'];
                if (strpos($url_host, $pattern) !== false) {
                    $free = 1;
                    break;
                }
            }

            //carga del acceso
            $carga = $log[4];

            //fecha y hora
            $fecha = date('Y-m-d', $log[0]);
            $hora = (int)date('H', $log[0]);

            //si ya existe el access lo actualizo, si no lo creo.
            $access = $conn->fetchAssoc('SELECT * FROM accesslog WHERE 
                            usuario_id = :user_id AND 
                            url = :url AND 
                            fecha = :fecha AND 
                            hora = :hora',array(
                "user_id" => $user_id,
                "url" => $url,
                "fecha" => $fecha,
                "hora" => $hora
            ));
            if($access === false){
                $conn->insert('accesslog',array(
                    'usuario_id'=>$user_id,
                    'url' => $url,
                    'alias' => $is_alias,
                    'fecha' => $fecha,
                    'hora' => $hora,
                    'carga' => $carga,
                    'free' => $free,
                    'visitas' => 1
                ));
                $access_id = $conn->lastInsertId();
            }else{
                $access_id=$access["id"];
                $conn->update('accesslog',array(
                    'carga'=>(int)$access["carga"]+(int)$carga,
                    'visitas'=>$access["visitas"]+1
                ),array('id'=>$access_id));
            }

            //si ya existe el mime carga lo actualizo, si no lo creo
            $mime_carga = $conn->fetchAssoc('SELECT * FROM cargamime WHERE
                            log_id = :access_id AND
                            mime_id = :mime_id
        ',
                array(
                    "access_id" => $access_id,
                    "mime_id" => $mime_id
                ));
            if($mime_carga === false){
                $conn->insert('cargamime',array(
                    'log_id' => $access_id,
                    'mime_id' => $mime_id,
                    'carga' => $carga
                ));
            }else{
                $conn->update('cargamime',array(
                    'carga' => (int)$mime_carga['carga']+(int)$carga
                ),
                    array('id'=>$mime_carga['id']));
            }

            //actualizo lista ip
            $acceso_ip = $conn->fetchAssoc('SELECT * FROM logs_ips WHERE
                  access_log_id = :access_id AND 
                  lista_ip_id = :ip_id',
                array('access_id'=>$access_id,
                    'ip_id' => $ip_id));
            if($acceso_ip === false){
                $conn->insert('logs_ips',array('access_log_id'=>$access_id,'lista_ip_id'=>$ip_id));
            }

            unset($access);
            unset($mime_carga);
            unset($acceso_ip);
            gc_collect_cycles();

            //echo "total: ".$lines_total.", proce: ".$lines_valid.", posi: ".ftell($handle)."\n";
            //echo $buffer;
        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }
        fclose($handle);


        //guardo en el registro la cantidad de lineas
        $this->registro->setLineas($lines_total);

        $this->em->persist($this->registro);
        $this->em->flush();

        //actualizo bloqueados y exedidos
        //Por hacer

        $fecha_end = new \DateTime();

        echo "file name: ".$this->nombre."\n";
        print "filesize: ".filesize($this->nombre)."\n";
        print "lineas total: ".$lines_total."\n";
        print "lineas valid: ".$lines_valid."\n";
        echo "comenzo: ".$fecha_start->format("H:i:s")."\n";
        echo "termino: ".$fecha_end->format("H:i:s")."\n";

        //  $this->em->flush();
        //  $this->em->clear();

        //$this->em->getConnection()->getConfiguration()->setSQLLogger(true);

        return $lines_valid;
    }

    public function getState($timestamp, $size)
    {

        //$line_num_ant = $registro->getLineas();
        $size_ant = $this->registro->getSize();
        $timestamp_ant = $this->registro->getTimestamp();

        if ($timestamp_ant == $timestamp) { //Si tienen el mismo timestamp es el mismo fichero
            if ($size < $size_ant) { //es menor actualmente?
                $state = self::$ERROR; //no pueden haber datos de menos
            } elseif ($size > $size_ant) { //es mayor actualmente?
                $state = self::$AGREGA; //Se han agregado lineas nuevas al fichero
            } else {
                //if ($size == $size_ant) {
                $state = self::$IGUAL; //el fichero se ha mantenido igual
                //} else {
                //    $state = "ERROR"; //para que sea igual el fichero a su estado anteior
                //} //tiene que tener size, cantidad de lineas y timestamp iguales
            }
        } elseif ($timestamp_ant > $timestamp) { //el fichero tiene un timestamp anterior?
            $state = self::$ERROR; //comprobar que no se halla cambiado la hora en el sistema.
        } else {
            $state = self::$ROTADO; //el fichero ha rotado
        }

        //si no fue error guardo el timestamp actual en el registro
        /*
        if ($state != "ERROR") {
            $registro->setTimestamp($timestamp);
        }
        */

        return $state;
    }

    protected function readlastline($handler,$position=0)
    {
        //rebobino al principio
        rewind($handler);
        $pos = $position-2;
        $t = " ";
        while ($t != "\n") {
            fseek($handler, $pos, SEEK_END);
            $t = fgetc($handler);
            $pos = $pos - 1;
        }
        $t = fgets($handler);
        return $t;
    }
}
