<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 05/02/2017
 * Time: 23:54
 */

namespace Tex\FrontendBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Utils\UserComponent;

class NewsController extends Controller
{

	var $user_comp;

    public function __construct(){
        $this->user_comp = new UserComponent($this);
    }


    public function indexAction()
    {
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $iduser = $user->getId();
			 $result = $this->user_comp->getPersonalsInfo($iduser);
        }

        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;


        $news = $em->getRepository('AdminBundle:News')->findAll();
        foreach($news as $item){
            $data['id'] = $item->getId();
            $data['title'] = $item->getTitle();
            $data['text'] = $item->getText();
            $data['img'] = $item->getImage();
            $data['sunmary'] = $item->getSunmary();
            $data['created'] = $item->getCreated()->format('d/m/Y');

            $profile =   $news = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$item->getCreateby()->getId()));
            $data['fullname'] = $profile->getName().' '.$profile->getLastname();

            $arr_result[] = $data;

        }

        //echo '<pre>';print_r($arr_result);die;

        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_opportunita']= "";
        $result['menu_gara']= "";
        $result['menu_work']= "active";
        $result['news'] = $arr_result;

        return $this->render('FrontendBundle:News:news.html.twig',array(
            'datos'=>$result
        ));

    }

    public function uploadFileAction(){

        if(!empty($_FILES)){
            //echo '<pre>';print_r($_FILES);die;

            if($this->uploadFile($_FILES)){

                $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
                return new Response(json_encode($response));
            }
        }

    }

    function uploadFile($file = array()){
        $allowedExtensions = array('pdf','docx','doc');
        $flag = (in_array($this->extension($file['file']['name']),$allowedExtensions))?true:false;
        if($flag){
            $dir = $_SERVER["DOCUMENT_ROOT"].'/GestionTex/web/upload/files/news';
            //$path = $dir.'/'.$name;
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            if(move_uploaded_file($file['file']['tmp_name'],$dir.'/'.$file['file']['name'])){
                // echo 111;
                sleep(3);
            }
            return true;
        }

        return false;
        // $file = $path.''.$file['file']['name'];
        //return $file;
    }

    function extension($filename){
        return substr(strrchr($filename, '.'), 1);
    }

} 