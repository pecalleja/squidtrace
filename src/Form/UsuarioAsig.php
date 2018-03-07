<?php
/**
 * Created by PhpStorm.
 * User: pecalleja
 * Date: 11/12/2017
 * Time: 09:40 AM
 */

namespace App\Form;

use App\Form\Type\SizeType\Type\SizeType;
use App\Util\Util;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioAsig extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kuota', SizeType::class, array(
                'label' => "Kuota",
                'use_binary' => true
            ))
            ->add('grupo',EntityType::class,array(
                'class' => 'App\Entity\Grupo',
                'choice_label' => 'nombre',
                'required' => false,
                'placeholder' => '',
                'empty_data' => null
            ))
            ->add('expira', DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
                'required' => false,
            ))
            ->add('rol', ChoiceType::class,array(
                'choices' => array(
                    'Administrador' => 'ROLE_ADMIN',
                    'Usuario' => 'ROLE_USER'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => "Salvar",
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Usuario'
        ));
    }

}