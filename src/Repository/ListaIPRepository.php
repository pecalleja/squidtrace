<?php

namespace App\Repository;

use App\Entity\ListaIP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ListaIPRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ListaIPRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListaIP::class);
    }

    //busca el ip en el listado, si no lo encuentra lo agrega
    public function getIP($host)
    {
        $em = $this->getEntityManager();

        //obtengo el IP
        $ip = $em->getRepository(ListaIP::class)->findOneBy(array('ip' => $host));

        if (!$ip) { //si el registro esta vacio lo inserto en la lista.
            $ip = new ListaIP($host);
            $em->persist($ip);
            $em->flush();
        }

        return $ip;
    }
}