<?php
namespace Tex\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Utils\UserComponent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;



class DefaultController extends Controller
{

    var $user_comp;

    public function __construct(){
        $this->user_comp = new UserComponent($this);
    }

    public function indexAction(Request $request){

        $locale = $request->getLocale();

	    $em = $this->get('doctrine')->getManager();
        $u = $this->get('security.context')->getToken()->getUser();
        if(is_object($u)){
            $id = $u->getId();
            $result = $this->user_comp->getPersonalsInfo($id);
        }

         //all members
          $members =$em->getRepository('AdminBundle:Member')->findAll();
		    //echo count($members);die;


        $result["login"] = ($this->get('security.context')->isGranted('ROLE_ADMIN') || $this->get('security.context')->isGranted('ROLE_USER')) ? 1 : 0;
        $result['menu_home']= "active";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_opportunita']= "";
        $result['menu_work']= "";
        $result['menu_gara']= "";

        return $this->render('UsuarioBundle:Default:index.html.twig',array('datos'=>$result,'count_member'=>count($members)));
    }

    public function loginAction(Request $request){

       if (!$this->get('security.context')->isGranted('ROLE_ADMIN') && !$this->get('security.context')->isGranted('ROLE_USER')) {

            $peticion = $this->get('request');
            $sesion = $peticion->getSession();
            $error = $peticion->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR,
                $sesion->get(SecurityContext::AUTHENTICATION_ERROR)
            );
           //echo '<pre>';print_r($error);die;
            $lastUsername = (null === $sesion) ? '' : $sesion->get(SecurityContextInterface::LAST_USERNAME);
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;

           if(isset($lastUsername)){
               $em = $this->get('doctrine')->getManager();
              // echo '<pre>';print_r($sesion);die;
               $user = $em->getRepository('UsuarioBundle:User')->findOneBy(array(
                   'username'=>$lastUsername
               ));

               if(is_object($user)){
                   $token = new UsernamePasswordToken($user, null, 'frontend', $user->getRoles());
                   $this->container->get('security.context')->setToken($token);
                   //echo 'Hola '.$lastUsername;die
                   return $this->redirect($this->generateUrl('admin_site'));
               }


           }

            return $this->render('UsuarioBundle:Default:login.html.twig', array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken
            ));
        }
        else {
            $this->get('session')->getFlashBag()->add(
                'error',
                'Your user has been authenticated previously'
            );
            return $this->redirect($this->generateUrl('Base_site'));
        }
    }
}