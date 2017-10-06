<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 05/02/2017
 * Time: 16:41
 */

namespace Tex\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Utils\UserComponent;
use Tex\AdminBundle\Entity\News;
use Symfony\Component\HttpFoundation\Response;


class NewsController  extends  Controller
{

    var $user_comp;

    public function __construct(){
        $this->user_comp = new UserComponent($this);
    }

    public function indexAction(){

    }

    public function addAction(){

        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        if(!empty($_POST)){
            $news = new News();

           // echo '<pre>';print_r($_FILES);die;

            $news->setTitle($_POST['title']);
            $news->setText($_POST['text']);
            $news->setSunmary($_POST['sunmary']);
            $news->setCreated(new \DateTime('now'));
            $news->setCreateby($user);
            $news->setImage($_FILES['file']['name']);

            //save news
            $em->persist($news);
            $em->flush();

            $this->uploadFile($_FILES);

            $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
            return new Response(json_encode($response));

        }else{
            $result = $this->user_comp->getPersonalsInfo($id);
            $result['active_new'] = "active";
            $result['create_news'] = "active";
            $projects = $this->user_comp->getAllProjects();
            return $this->render('AdminBundle:News:add_news.html.twig', array('datos'=>$result,'projects'=>$projects));
        }

    }

    function uploadFile($file = array()){
        $allowedExtensions = array('jpg','jpeg','png','gif','bmp');
        $flag = (in_array($this->extension($file['file']['name']),$allowedExtensions))?true:false;
        if($flag){
            $dir = $_SERVER["DOCUMENT_ROOT"].'/web/upload/files/news/';
            //$path = $dir.'/'.$name;
             if (!file_exists($dir)) {
                 mkdir($dir, 0777, true);
             }
            if(move_uploaded_file($file['file']['tmp_name'],$dir.'/'.$file['file']['name'])){
                // echo 111;
                sleep(3);
            }
        }
        // $file = $path.''.$file['file']['name'];
    //return $file;
}

    function extension($filename){
        return substr(strrchr($filename, '.'), 1);
    }

} 