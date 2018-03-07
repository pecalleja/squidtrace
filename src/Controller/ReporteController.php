<?php

namespace App\Controller;

use App\Entity\AccessLog;
use App\Entity\CargaMime;
use App\Entity\Usuario;
use App\Util\Util;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;

class ReporteController extends Controller
{
    /**
     * @Route("/reporte/{login}", name="reporte")
     *
     */
    public function reporteAction($login)
    {
        $user_auth = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');
        $filtros = $session->get('filtros');

        if(!isset($filtros['mes']))
        {
            $filtros['mes'] = (new \DateTime())->format("m");
        }
        if(!isset($filtros['anno']))
        {
            $filtros['anno'] = (new \DateTime())->format("y");
        }

        if ($login === $user_auth->getUsername()) {
            $usuario = $user_auth;
        } else {
            if(false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
        		throw new AccessDeniedException();
        	}else {
                $usuario = $em->getRepository(Usuario::class)->findOneByLogin($login);
            }
        }

        $meses = $em->getRepository(AccessLog::class)->GetMeses($usuario);
        $dias = array();
        $horas = array();
        $mimes = array();
        $mime_url = null;
        $registros = array();
        $mes_mostrar = "Ninguno";

        if( !empty($meses) ) {
            $mes_mostrar = Util::getMonthName($filtros['mes']) . " " . $filtros['anno'];
            if (isset($filtros['mes'])) {
                $dias = $em->getRepository(AccessLog::class)->GetDias($usuario, $filtros['anno'], $filtros['mes']);
                if (!empty($dias) && isset($filtros['dia'])) {
                    $horas = $em->getRepository(AccessLog::class)->GetHoras($usuario, $filtros['anno'], $filtros['mes'], $filtros['dia']);
                }
            }
        }

        $filtros['mes_mostrar'] = $mes_mostrar;
        $session->set('filtros', $filtros);
        $filtros_opcionales = array('dia','hora','ip','url');

        //dump($filtros);

        $registros = $em->getRepository(AccessLog::class)->GetAccess($usuario,$filtros);
        $direcciones = $em->getRepository(AccessLog::class)->GetDireccionesIp($usuario,$filtros);
        $ip_listado = [];
        foreach ($direcciones as $ipip){
            $ip_listado[$ipip["id"]]=$ipip["ip"];
        }
        
        if( isset($filtros['url']) && $filtros['url'] != "" ){
            $carga_mime = $em->getRepository(CargaMime::class);
            $mimes = $carga_mime->GetMimes($usuario,$filtros);
            $mime_url = $filtros['url'];
        }

        $cant_dias = cal_days_in_month(CAL_GREGORIAN, $filtros['mes'], $filtros['anno']);

        //dump($cant_dias);

        return $this->render('usuario/reporte/reporte.html.twig', array(
            'usuario' => $usuario,
            'meses' => $meses,
            'dias' => $dias,
            'cant_dias' => $cant_dias,
            'mimes' => $mimes,
            'mime_url' => $mime_url,
            'direcciones' => $direcciones,
            'ip_listado' => $ip_listado,
            'horas' => $horas,
            'filtros'=>$filtros,
            'filtros_opcionales'=>$filtros_opcionales,
            'registros'=>$registros
            ));

    }

    /**
     * @Route("/reporte/{login}/set-dia-filter/{filter}", name="dia")
     * @Route("/reporte/{login}/set-hora-filter/{filter}", name="hora")
     * @Route("/reporte/{login}/set-ip-filter/{filter}", name="ip")
     * @Route("/reporte/{login}/set-url-filter/{filter}", name="url")
     */
    public function setFilterAction($login,$filter, Request $request)
    {
        $session = $this->get('session');
        $filtros = $session->get('filtros');
        $routeName = $request->get('_route');
        $filtros[$routeName] = $filter;
        $session->set('filtros', $filtros);
        return $this->redirect($this->generateUrl('reporte',array('login'=>$login)), 301);
    }

    /**
     * @Route("/reporte/{login}/set-mes-filter/{anno}/{mes}", name="anno-mes")
     */
    public function setMesAnnoFilterAction($login,$anno,$mes)
    {
        $session = $this->get('session');
        $filtros = $session->get('filtros');
        $filtros['mes'] = $mes;
        $filtros['anno'] = $anno;
        if( isset($filtros['dia'])){
            unset($filtros['dia']);
        }
        if( isset($filtros['hora'])) {
            unset($filtros['hora']);
        }
        $session->set('filtros', $filtros);
        return $this->redirect($this->generateUrl('reporte',array('login'=>$login)), 301);
    }

    /**
     * @Route("/reporte/{login}/delete-filter/{filter}", name="delete-filter")
     */
    public function deleteFilterAction($login,$filter)
    {
        $session = $this->get('session');
        $filtros = $session->get('filtros');
        if( isset($filtros[$filter]))
            unset($filtros[$filter]);
        $session->set('filtros', $filtros);
        return $this->redirect($this->generateUrl('reporte',array('login'=>$login)), 301);
    }

}
