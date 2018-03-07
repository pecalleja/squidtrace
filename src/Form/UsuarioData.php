<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UsuarioData extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //Campos de los datos del usuario
            ->add('nombre',TextType::class)
            ->add('correo',EmailType::class)
            ->add('save', SubmitType::class, array(
                'label' => "Salvar"
            ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $user = $event->getData();
            $form_user = $event->getForm();

            //el campo login no permite cambiarse una vez creado
            if (!$user || null === $user->getId()) {
                $options = array();
            }else{
                $options = array(
                    'disabled' => true
                );
            }

            //campo del login del usuario
            $form_user->add('login',TextType::class, $options);
        });

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
