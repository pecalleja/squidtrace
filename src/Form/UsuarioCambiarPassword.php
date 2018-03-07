<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsuarioCambiarPassword extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'La Nueva Contraseña debe coincidir en ambos campos',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'La Nueva Contraseña no puede estar en blanco'
                    )),
                    new Length(array(
                        'min' => 3,
                        'minMessage'=>'La Nueva Constraseña demasiado corta, debe tener al menos 3 caracteres'))
                    )
                )
            )->add('cambiar', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
        {
            $form = $event->getForm();

            // grab the user, do a quick sanity check that one exists
            /** @var Usuario $user_auth **/
            $user_auth = $this->tokenStorage->getToken()->getUser();

            if ($user_auth->getRol() === 'ROLE_USER')
            {
                $form->add('actualPassword', PasswordType::class, array(
                    'constraints' => array(
                        new UserPassword(array(
                            'message' => 'La Contraseña Actual no es válida'
                        ))
                    ),
                    'mapped' => false
                ));
            }
        });
    }
}
