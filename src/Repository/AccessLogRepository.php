<?php

namespace App\Repository;

use App\Entity\AccessLog;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * AccessLogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccessLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessLog::class);
    }

    public function LastAccessJoined($fecha, $hora)
    {

        $query = $this->createQueryBuilder('l')
            ->leftJoin('l.contenidos', 'c')
            ->addSelect('c')
            ->leftJoin('l.ips', 'i')
            ->addSelect('i')
            ->leftJoin('l.usuario', 'u')
            ->addSelect('u')
            ->where('l.fecha = :fecha AND l.hora = :hora')
            ->setParameters(array('fecha' => $fecha,
            'hora' => &$hora))->getQuery();
        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function UpdateAccessLog(Usuario $usuario, $url, $alias, $fecha, $hora, $carga, $free)
    {
        $em = $this->getEntityManager();

        //obtengo el access
        $access = $em->getRepository(AccessLog::class)->findOneBy(array(
            'usuario' => $usuario->getId(),
            'url' => $url,
            'fecha' => $fecha,
            'hora' => $hora));

        if (!$access) { //si el registro esta vacio lo inserto en la lista.
            $access = new AccessLog();
            $access->setUsuario($usuario);
            $access->setUrl($url);
            $access->setAlias($alias);
            $access->setFecha($fecha);
            $access->setHora($hora);
            $access->setCarga($carga);
            $access->setFree($free);
            $access->setVisitas(1);
            $em->persist($access);
            //$em->flush();
        }
        /*else{
                    $access->setCarga( $access->getCarga() + $carga );
                    $access->setVisitas( $access->getVisitas() + 1);
                }
        */
        //primero agregar el mime y el log y luego actualizar el mime carga


        return $access;
    }

    public function GetMeses(Usuario $usuario)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql =
            'SELECT DISTINCT YEAR(log.fecha) AS anno, MONTH(log.fecha) AS mes
             FROM accesslog log
             WHERE log.usuario_id = :usuario
             ORDER BY log.fecha DESC';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['usuario' => $usuario->getId()]);

        return $stmt->fetchAll();
    }

    public function GetDias(Usuario $usuario, $anno, $mes)
    {
        $repository = $this->getEntityManager()->getRepository(AccessLog::class);

        $query = $repository->createQueryBuilder('log')
        ->select("log.fecha")
        ->addSelect("SUM(log.carga) as carga")
        ->where("log.usuario = :usuario")
        ->andwhere("YEAR(log.fecha) = :anno")
        ->andwhere("MONTH(log.fecha) = :mes")
        ->groupBy("log.fecha")
        ->setParameter('usuario', $usuario->getId())
        ->setParameter('anno',$anno)
         ->setParameter('mes',$mes)
        ->orderBy('log.fecha', 'ASC')
        ->getQuery();

        $access = $query->getResult();

        return $access;
    }

    public function GetHoras(Usuario $usuario, $anno, $mes, $dia)
    {
        $repository = $this->getEntityManager()->getRepository(AccessLog::class);

        $query = $repository->createQueryBuilder('log')
        ->select("log.hora")
        ->addSelect("SUM(log.carga) as carga")
        ->where("log.usuario = :usuario")
        ->andwhere("YEAR(log.fecha) = :anno")
        ->andwhere("MONTH(log.fecha) = :mes")
        ->andWhere("DAY(log.fecha) = :dia")
        ->groupBy("log.hora")
        ->setParameter('usuario', $usuario->getId())
        ->setParameter('anno',$anno)
         ->setParameter('mes',$mes)
        ->setParameter("dia", $dia)
        ->orderBy('log.hora', 'ASC')
        ->getQuery();

        $access = $query->getResult();

        return $access;
    }

    public function GetAccess(Usuario $usuario, $filtros)
    {
        $repository = $this->getEntityManager()->getRepository(AccessLog::class);


        $query = $repository->createQueryBuilder('log')
        ->select("log.url")
        ->addSelect("SUM(log.carga) as carga")
        ->addSelect("SUM(log.visitas) as visitas")
        ->addSelect("log.free")
        ->where('log.usuario = :usuario')
        ->setParameter('usuario', $usuario->getId());

        if( isset($filtros['anno']) && $filtros['anno']!=0 ){
            $query->andwhere("YEAR(log.fecha) = :anno")
            ->setParameter('anno',$filtros['anno']);
        }

        if( isset($filtros['mes']) && $filtros['mes']!=0 ){
            $query->andwhere("MONTH(log.fecha) = :mes")
            ->setParameter('mes',$filtros['mes']);
        }

        if( isset($filtros['dia']) && $filtros['dia']!=0 ){
            $query->andwhere("DAY(log.fecha) = :dia")
            ->setParameter('dia',$filtros['dia']);
        }

        if( isset($filtros['hora']) && $filtros['hora']!=0 ){
            $query->andwhere("log.hora = :hora")
            ->setParameter('hora',$filtros['hora']);
        }

        if( isset($filtros['ip']) ){
            $query->leftJoin('log.ips', 'ip')
            ->andwhere("ip.id = :ip")
            ->setParameter('ip',$filtros['ip']);
        }

        if( isset($filtros['url']) && $filtros['url']!="" ){
            $query->andwhere("log.url = :url")
            ->setParameter('url',$filtros['url']);
        }

        $query = $query
            ->groupBy("log.url")
            ->orderBy('log.fecha', 'DESC')
            ->getQuery();

        $access = $query->getResult();

        return $access;
    }

    public function GetDireccionesIp(Usuario $usuario, $filtros)
    {
        $repository = $this->getEntityManager()->getRepository(AccessLog::class);

        $query = $repository->createQueryBuilder('log')
        ->distinct()->select('ip.ip')
        ->addSelect('ip.id')
        ->leftJoin('log.ips', 'ip')
        ->where('log.usuario = :usuario')
        ->setParameter('usuario', $usuario->getId());

        if( isset($filtros['anno']) ){
            $query->andwhere("YEAR(log.fecha) = :anno")
            ->setParameter('anno',$filtros['anno']);
        }

        if( isset($filtros['mes']) ){
            $query->andwhere("MONTH(log.fecha) = :mes")
            ->setParameter('mes',$filtros['mes']);
        }

        if( isset($filtros['dia']) ){
            $query->andwhere("DAY(log.fecha) = :dia")
            ->setParameter('dia',$filtros['dia']);
        }

        if( isset($filtros['hora']) ){
            $query->andwhere("log.hora = :hora")
            ->setParameter('hora',$filtros['hora']);
        }

        $query = $query->orderBy('log.fecha', 'DESC')->getQuery();

        $access = $query->getResult();

        return $access;
    }

}