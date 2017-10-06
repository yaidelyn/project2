<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/08/2016
 * Time: 9:58
 */

namespace Tex\UsuarioBundle\Controller;

use Tex\UsuarioBundle\Entity\Chat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tex\UsuarioBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;


class ChatController  extends  Controller{


    public function insertMsgAction(){
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        $em = $this->get('doctrine')->getManager();

        if(!empty($_POST)){

           // echo '<pre>';print_r($_POST['data']);die;


            $chat = new Chat();

            $chat->setText($_POST['data']['msg']);
            $date = date('d-m-Y h:i:s');
           // echo $date;die;
            $chat->setChatdate(new \DateTime($date));
            $chat->setUserId($id);

            $em->persist($chat);
            $em->flush();
        }

        $response = array("code" => 1, "success" => true,'mensaje'=>'OK!!!');
        return new Response(json_encode($response));
    }

    public function loadMsgAction(){
        $em = $this->get('doctrine')->getManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();

        $messages = $em->getRepository('UsuarioBundle:Chat')->findAll();

        foreach($messages as $item){

             $userdata = $em->getRepository('UsuarioBundle:Profile')->findBy(array('user'=>$item->getUserId()));
            /*$userdata = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array(
                'user'=>$user->getId()
            ));*/

           // echo '<pre>';print_r($userdata);die;
            $array[] = array(
                'name'=>$userdata[0]->getName().' '.$userdata[0]->getLastname(),
                'avatar'=>$userdata[0]->getAvatar(),
                'text'=>$item->getText(),
                'chatdate'=>$item->getChatdate(),

            );

        }
        //creamos el html para cada mensaje

        return new Response(json_encode(array('result'=>$array)));
    }




} 