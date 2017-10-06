<?php

namespace Tex\ProyectoBundle\Controller;

use Tex\ProyectoBundle\Entity\Project;
use Tex\UsuarioBundle\Entity\User;
use Tex\UsuarioBundle\Entity\Profile;
use Tex\UsuarioBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {

       foreach($this->getAllCategory() as $categ){
           $array_categoria[] = array('id_categ'=>$categ->getId(),'name_categ'=>$categ->getNameCat());
       }

        //echo '<pre>';print_r($array_categoria);die;

        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        
 
        $profile = $em->getRepository('UsuarioBundle:Profile')->findBy(array("user"=>$id));
        
       //echo '<pre>';print_r($profile);die;
        $result['name'] = $profile[0]->getName();
        $result['lastname'] = $profile[0]->getLastname();
        $result['fullname'] = $profile[0]->getName().' '.$profile[0]->getLastname();
        $result['country'] =  $profile[0]->getCountry();
        $result["avatar"] = $profile[0]->getAvatar();
        $result['phone'] = $profile[0]->getPhone();
        $result['mobile'] = $profile[0]->getMobile();
        $result['address'] = $profile[0]->getAddrees();
        $result['birthday'] = $profile[0]->getBirthday();
        $result["profile_active"] = "";
        $result["projects_active"] = "";
        $result["create_pro_activite"] = "";
        $result["create_emplo_activite"] = "";
        $result["employee_active"] = "";
        $result["list_emplo_activite"] = "";
        $result["team_active"] = "";
        $result["create_team_active"] = "";
        $result["formation_active"] = "";
        $result["create_active"] = "active";
        $result["list_active"] = "";
        $result["display"] = "";
        $projects = $this->getProjects($id);
        $categoria = $array_categoria;
        return $this->render('ProyectoBundle:Default:index.html.twig',array('datos'=>$result, 'projects'=>$projects,'categoria'=>$categoria));
    }

    public function createProjectAction(Request $request){

        if(!empty($_POST)){

            $em = $this->get('doctrine')->getManager();
            $category = $em->getRepository('ProyectoBundle:Category')->find($_POST['data']['category_id']);
            $user = $this->get('security.context')->getToken()->getUser();
            $id = $user->getId();

            //echo $id;die;

            //print_r($_POST['data']);die;
            $proyecto = new Project();

            $proyecto->setUser($user);
            $proyecto->setCategory($category);
            $proyecto->setName($_POST['data']['name']);
            $proyecto->setDuration($_POST['data']['duration']);
            $proyecto->setStatus($_POST['data']['status']);
            $proyecto->setCantProf($_POST['data']['cant_prof']);

            $em->persist($proyecto);
            $em->flush();
            $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
            return new Response(json_encode($response));
        }


    }

    private function getProjects($iduser){
        $em = $this->get('doctrine')->getManager();
       $projects = $em->getRepository('ProyectoBundle:Project')->findBy(array(
               'user'=>$iduser
           )
       );
        //echo '<pre>';print_r($projects);die;

        //$projects[] = array("name"=>'Designer for work');
        $result = array();
      foreach($projects as $obj){
            $result[] = array(
                'id_project'=>$obj->getId(),
                'name'=>$obj->getName());
        }

        //echo '<pre>';print_r($result);die;

        return $result;
    }

    public function getAllCategory(){
        $em = $this->get('doctrine')->getManager();

        $category = $em->getRepository('ProyectoBundle:Category')->findAll();

        return $category;


    }
}
