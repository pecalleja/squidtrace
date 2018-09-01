<?php

namespace App\Controller;

use App\Form\ErrorRedirect;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * @Route("/", name="portada")
     */
    public function portada(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('portada/portada.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));
    }

    /**
     * @Route("/error_redirect", name="error_redirect")
     */
    public function errorRedirect(Request $request)
    {
        //set header to 555 to parse in readlog
        $response = new Response();
        $response->setStatusCode(555, 'CCE-denied-access');

        //get the variables


        $form_error_redirect = $this->manageErrorRedirect($request);

        if($form_error_redirect===true)
        {
            $this->addFlash('success', 'Se envio correctamente el mensaje');
            return $this->redirectToRoute('portada');
        }

        return $this->render('portada/error-redirect.html.twig',array(
            'usuario' => $request->query->get('usuario'),
            'ip' => $request->query->get('ip'),
            'url' => $request->query->get('url'),
            'clasificacion' => $request->query->get('clasificacion'),
            'form_error_redirect' => $form_error_redirect->createView(),
        ), $response);
    }

    public function manageErrorRedirect(Request $request)
    {
        //parametros por defecto
        $defaultData = array();

        //Aqui manejo el formulario
        $form_error_redirect = $this->createForm(ErrorRedirect::class,$defaultData);
        $form_error_redirect->handleRequest($request);

        if ($form_error_redirect->isSubmitted())
        {
            if($form_error_redirect->isValid()){
                $data = $form_error_redirect->getData();
                $message = (new \Swift_Message("Solicitar acceso a URL"))
                    ->setFrom('admin@cce.cu')
                    ->setTo('informatica@cce.cu')
                    ->setBody(
                        $this->renderView(
                            'email/solicitar-acceso-email.html.twig',$data
                        ),
                        'text/html'
                    )
                ;

                $this->mailer->send($message);

                return true;
            }
        }

        return $form_error_redirect;
    }
}
