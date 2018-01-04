<?php
namespace App\DataFixtures;

use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UsuarioFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new Usuario();
        $admin->setNombre('Administrador');
        $admin->setLogin('admin');
        $admin->setCorreo('it@cce.cu');
        $admin->setIsActive(true);
        $admin->setPassword('$2y$13$VYyxpQ.gGZxm76qxMtf/3uJ1zlZzLy0w0RP/tnSQf2XbnQB2Hr8Bm');//123
        $admin->setRol('ROLE_ADMIN');
        $manager->persist($admin);

        $manager->flush();
    }
}
