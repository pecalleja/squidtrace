<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioAsig;
use App\Form\UsuarioCambiarPassword;
use App\Form\UsuarioData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Usuario controller.
 */
class UsuarioController extends Controller
{
    /**
    * My custom Usuario
    *
    * @Route("/info", name="usuario_info")
    */
    public function infoAction(Request $request)
    {
        $user_auth = $this->getUser();
        return $this->usuarioAction($request, $user_auth);
    }

    /**
     * Edit Usuario Data and change password
     *
     * @Route("/{login}", name="usuario")
     */
    public function usuarioAction(Request $request, Usuario $user)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var Usuario $user_auth **/
        $user_auth = $this->getUser();

        if ($user_auth->getRol() === "ROLE_USER" and $user_auth->getLogin() != $user->getLogin())
        {
            throw $this->createAccessDeniedException();
        }

        $form_pass = $this->manageUsuarioPass($request, $user);
        $form_user = $this->manageUsuarioData($request, $user);
        $form_asig = $this->manageUsuarioAsig($request, $user, $user_auth);

        return $this->render('usuario/usuario.html.twig', array(
            'form_pass' => $form_pass->createView(),
            'form_user' => $form_user->createView(),
            'form_asig' => $form_asig->createView()
        ));

    }

    public function manageUsuarioPass(Request $request, Usuario $user)
    {
        //parametros por defecto
        $defaultData = array();

        //Aqui manejo el formulario para el cambio de password
        $form_pass = $this->createForm(UsuarioCambiarPassword::class,$defaultData);
        $form_pass->handleRequest($request);

        if ($form_pass->isSubmitted())
        {
            // $contraint = new Assert\EqualToValidator()
            if($form_pass->isValid()){
                $data = $form_pass->getData();

                $em = $this->getDoctrine()->getManager();

                $password = $this->get('security.password_encoder')->encodePassword($user, $data['plainPassword']);
                $user->setPassword($password);

                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Se ha cambiado la contraseña del usuario '. $user->getNombre()
                );
            }else{
                $this->addFlash(
                    'danger',
                    'Error cambiado la contraseña del usuario '. $user->getNombre()
                );
            }
        }

        return $form_pass;
    }

    public function manageUsuarioData(Request $request, Usuario $user)
    {
        //manejo del formulario para los datos
        $form_user = $this->createForm(UsuarioData::class,$user);
        $form_user->handleRequest($request);

        if($form_user->isSubmitted())
        {
            if($form_user->isValid())
            {
                //Guardo los datos del usuario
                $em = $this->getDoctrine()->getManager();
                $em->persist($form_user->getData());
                $em->flush();

                $this->addFlash(
                    'success',
                    'Se guardaron los datos del usuario '. $user->getNombre()
                );
            }else{
                $this->addFlash(
                    'danger',
                    'Error guardando los datos del usuario '. $user->getNombre()
                );
            }
        }

        return $form_user;
    }

    public function manageUsuarioAsig(Request $request, Usuario $user, Usuario $user_auth)
    {
        //manejo del formulario para los datos asignados
        if ($user_auth->getRol() === "ROLE_ADMIN" )
        {
            $form_user = $this->createForm(UsuarioAsig::class,$user,array('disabled' => false));
        }else{
            $form_user = $this->createForm(UsuarioAsig::class,$user,array('disabled' => true));
        }

        $form_user->handleRequest($request);

        if($form_user->isSubmitted())
        {
            if($form_user->isValid())
            {
                //Guardo los datos del usuario
                $em = $this->getDoctrine()->getManager();
                $em->persist($form_user->getData());
                $em->flush();

                $this->addFlash(
                    'success',
                    'Se guardaron los datos del usuario '. $user->getNombre()
                );
            }else{
                $this->addFlash(
                    'danger',
                    'Error guardando los datos del usuario '. $user->getNombre()
                );
            }
        }

        return $form_user;
    }

    /**
     * Lists all Usuario entities.
     *
     * @Route("/admin/usuario/list", name="admin_usuario_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository(Usuario::class)->findAll();

        return $this->render('usuario/admin/index.html.twig', array(
            'usuarios' => $usuarios,
        ));
    }

    /**
     * Creates a new Usuario entity.
     *
     * @Route("/admin/usuario/new", name="admin_usuario_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        /*
        $usuario = new Usuario();
        $form = $this->createForm('AppBundle\Form\UsuarioType', $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            return $this->redirectToRoute('admin_usuario_show', array('id' => $usuario->getId()));
        }

        return $this->render('@App/usuario/admin/usuario.html.twig', array(
            'usuario' => $usuario,
            'form' => $form->createView(),
        ));
        */
    }

    //esta es la ruta por defecto para mostrar
//    /**
//     * Finds and displays a Usuario entity.
//     *
//     * @Route("/{id}", name="admin_usuario_show")
//     * @Method("GET")
//     */
//    public function showAction(Usuario $usuario)
//    {
//        $deleteForm = $this->createDeleteForm($usuario);
//
//        return $this->render('@App/usuario/admin/show.html.twig', array(
//            'usuario' => $usuario,
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/admin/usuario/{id}/edit", name="admin_usuario_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Usuario $usuario)
    {
        /*
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('AppBundle\Form\UsuarioType', $usuario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            return $this->redirectToRoute('admin_usuario_edit', array('id' => $usuario->getId()));
        }

        return $this->render('@App/usuario/admin/usuario.html.twig', array(
            'usuario' => $usuario,
            'form' => $editForm->createView(),
        ));
        */
    }

    /**
     * Deletes a Usuario entity.
     *
     * @Route("/admin/usuario/{id}/delete", name="admin_usuario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Usuario $usuario)
    {
        $form = $this->createDeleteForm($usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($usuario);
            $em->flush();
        }

        return $this->redirectToRoute('admin_usuario_index');
    }

    /**
     * Creates a form to delete a Usuario entity.
     *
     * @param Usuario $usuario The Usuario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Usuario $usuario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_usuario_delete', array('id' => $usuario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }




    /**
     * @Route("/admin/{login}", name="admin_usuario")
     */
    public function adminusuarioAction($login)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository(Usuario::class)->findOneByLogin($login);
        return $this->render('usuario/usuario.html.twig', array('usuario' => $usuario));
    }

    /**
     * @Route("/usuario/new", name="user_new")
     */
    public function usernewAction()
    {
        /* $user_auth = $this->get('security.context')->getToken()->getUser();
         return $this->render('AppBundle:usuario:usuario.html.twig', array( 'usuario' => $user_auth));
 */
        return $this->render('usuario/usuario.html.twig');
        /* $user = new Usuario('new User');

         $form = $this->createFormBuilder($user)
             ->add('login', 'text')
             ->add('nombre', 'text')
             ->add('correo', 'email')
             ->add('password', 'repeated', array(
                 'type' => 'password',
                 'invalid_message' => 'Las dos contraseñas deben coincidir',
                 'options' => array('label' => 'Contraseña')
             ))
             ->add('grupo','choice',array(
                 'choices' => array(
                     '1' => 'I+D',
                     '2' => 'Tecnologia',
                     '3' => 'Direccion')
             ))
             ->add('kuota','number')
             ->add('expira', 'date')
             ->add('rol','text')
             ->getForm();
         */
        /* if ($request->isMethod('POST')) {
             $form->bind($request);

             if ($form->isValid()) {

                 // guardar la tarea en la base de datos

                 return $this->redirect($this->generateUrl('task_success'));
             }
         }*/
        /*
        return $this->render('@App/Default/usuario/user_form.html.twig', array(
            'form' => $form->createView(),
        ));*/

    }

}
