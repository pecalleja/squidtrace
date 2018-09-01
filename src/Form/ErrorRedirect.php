<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;


class ErrorRedirect extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuario',HiddenType::class)
            ->add('ip',HiddenType::class)
            ->add('url', HiddenType::class)
            ->add('clasificacion',HiddenType::class)
            ->add('observaciones',TextareaType::class);
    }

}