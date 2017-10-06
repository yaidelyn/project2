<?php

namespace Admin\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends Controller
{

    public function indexAction()
    {
        //echo 'Hola Pagina Admin Panel';

        $em = $this->get('doctrine')->getManager();

        $users = $em->getRepository('UsuarioBundle:User' )->findAll();

       return $this->render('BackendBundle:Admin:index.html.twig', array('user_cont' => count($users)));
    }
}
