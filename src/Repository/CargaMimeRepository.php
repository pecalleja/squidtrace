<?php

namespace App\Repository;

use App\Entity\CargaMime;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * CargaMimeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CargaMimeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CargaMime::class);
    }

    public function GetMimes(Usuario $usuario, $filtros)
    {
        $repository = $this->getEntityManager()->getRepository(CargaMime::class);
        $query = $repository->createQueryBuilder('mimes')
        ->select("SUM(mimes.carga) as carga")
        ->leftJoin('mimes.log', 'log')
        ->leftJoin('mimes.mime', 'mime')
        ->addSelect("mime.mime")
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

        if( isset($filtros['url']) && $filtros['url'] != "" ){
            $query->andwhere("log.url = :url")
            ->setParameter('url',$filtros['url']);
        }

        $query = $query
            ->groupBy("mime.mime")
            ->orderBy('mimes.carga', 'DESC')
            ->getQuery();

        $mimes = $query->getResult();

        return $mimes;
    }
}