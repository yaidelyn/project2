<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/08/2016
 * Time: 22:23
 */

namespace Tex\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tex\AdminBundle\Entity\Answers;
use Tex\AdminBundle\Entity\Offer;
use Tex\AdminBundle\Entity\Opere;
use Tex\AdminBundle\Entity\Questions;
use Tex\AdminBundle\Entity\SkillOffer;
use Tex\AdminBundle\Entity\Test;
use Tex\UsuarioBundle\Entity\User;
use Tex\UsuarioBundle\Entity\Formation;
use Tex\UsuarioBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Utils\UserComponent;

class AdminController  extends Controller
{
    var $user_comp;

    public function __construct(){
        $this->user_comp = new UserComponent($this);
    }
      public function createOfferAction(){

        if(!empty($_POST)){


         //echo '<pre>';print_r($_POST);die;

            $em = $this->get('doctrine')->getManager();
            $user = $this->get('security.context')->getToken()->getUser();
            $id = $user->getId();

            //echo $id;die;

            $offer = new Offer();

            $category = $em->getRepository('AdminBundle:CategOffer' )->find( $_POST['data']['categoria'] );
            $subcategory = $em->getRepository('AdminBundle:SubCategory' )->find( $_POST['data']['subcategory'] );



            //set value offer
            $offer->setTitle($_POST['data']['title']);
            $offer->setCategory($category);
            $offer->setSubcategory($subcategory);
            $offer->setActive($_POST['data']['active']);
            $offer->setBudget($_POST['data']['budget']);
            $offer->setDescription($_POST['data']['description']);
            $offer->setUser($user);


            $em->persist($offer);
            $em->flush();

            //add skills
            $skills = $_POST['data']['skill'];
            foreach($skills as $skill){

                $obj = new SkillOffer();
                $obj->setName($skill);
                $obj->setOffer($offer);

                $em->persist($obj);
                $em->flush();

            }

            //crear cuestionario si existe

           if(isset($_POST['data']['questions'])){
                $test = new Test();

               $test->setName($_POST['data']['name_test']);
               $test->setOffer($offer);

               $em->persist($test);
               $em->flush();

               $cont = 0;

               $typequest = $em->getRepository('AdminBundle:TypeQuestions' )->find(1);

               while($cont < count($_POST['data']['questions'])){

                   $question = new Questions();

                 //echo $test->getId();

                   $question->setTitle($_POST['data']['questions'][$cont]['question']);
                   $question->setTest($test);
                   $question->setType($typequest);

                   $em->persist($question);
                   $em->flush();

                   //inserto las respuestas
                   for($a = 0;$a<3;$a++){

                       $answer = new Answers();

                       $answer->setValue($_POST['data']['questions'][$cont]['answers'][$a]['answer']);
                       $answer->setIscorrect($_POST['data']['questions'][$cont]['answers'][$a]['istrue']);
                       $answer->setQuestions($question);

                       $em->persist($answer);
                       $em->flush();
                   }

                   $cont++;
               }
           }//end create custionario

            $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
            return new Response(json_encode($response));

        }

    }


    /*Update Offer*/
    public function updateOfferAction(){

       //print_r($_POST);die;
        $em = $this->get('doctrine')->getManager();
        if(!empty($_POST)){

            $offer = $em->getRepository('AdminBundle:Offer' )->find($_POST['data']['id']);

            $offer->setTitle($_POST['data']['title']);
            $offer->setCategoria($_POST['data']['category']);
            $offer->setBudget($_POST['data']['budget']);
            $offer->setDescription($_POST['data']['description']);

            $em->persist($offer);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
            return new Response(json_encode($response));
        }
    }

    /*Delete Offer*/
    public function deleteOfferAction(){

        $em = $this->get('doctrine')->getManager();
        $offer = $em->getRepository('AdminBundle:Offer')->find($_POST['id']);

        $em->remove($offer);
        $em->flush();

        $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
        return new Response(json_encode($response));


        //echo '<pre>';print_r($_POST);die;
    }

    public function formTestAction(){

        $html = "";
        $value = $_POST['value'];

       // echo $value;die;

        for($i = 1;$i<=$value;$i++){

            $question = 'q'.$i;


            $html='<div class="row">
                             <div class="col-lg-6">
                                            <section class="panel">
                                                <header class="panel-heading">
                                                    Question #'.($i).'
                                                </header>
                                                <div class="panel-body">
                                                    <form role="form" class="form-horizontal">
                                                        <div class="form-group">
                                                            <!--label class="col-lg-2 col-sm-2 control-label" for="inputEmail1">Email</label-->
                                                            <div class="col-lg-10">
                                                                <input type="text" placeholder="Question" id="'.$question.'" class="form-control">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <section class="panel">
                                                <header class="panel-heading">
                                                    Answers
                                                </header>
                                                <div class="panel-body">
                                                    <form role="form" class="form-horizontal">
                                                        <div class="form-group">
                                                            <!--label class="col-lg-2 col-sm-2 control-label" for="inputEmail1">Email</label-->
                                                            <div class="col-lg-10">
                                                                <input type="text" placeholder="Answers 1" id="'.$question.'answers_1" class="form-control">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input id="ch'.$question.'1" type="checkbox"> True
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--label class="col-lg-2 col-sm-2 control-label" for="inputEmail1">Email</label-->
                                                            <div class="col-lg-10">
                                                                <input type="text" placeholder="Answers 2" id="'.$question.'answers_2" class="form-control">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input id="ch'.$question.'2" type="checkbox"> True
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--label class="col-lg-2 col-sm-2 control-label" for="inputEmail1">Email</label-->
                                                            <div class="col-lg-10">
                                                                <input type="text" placeholder="Answers 3" id="'.$question.'answers_3" class="form-control">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input id="ch'.$question.'3" type="checkbox"> True
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </section>
                                        </div>
                                    </div>';

            $rseult[] = $html;
            $html = '';
        }

       // echo '<pre>';print_r($rseult);die;

        $response = array("code" => 1, "success" => true,'mensaje'=>'OK!!!','result'=>$rseult);
        return new Response(json_encode($response));

        //print_r($_POST);die;

    }
    public function findUserByCriterioAction(){
        $em = $this->get('doctrine')->getManager();
        $u = $this->get('security.context')->getToken()->getUser();
        $id = $u->getId();
        $criterios = array();
        $i = 0;
        if(!empty($_POST)){
            //echo '<pre>'; print_r($_POST);die;
            //if(isset($_POST['name'])|| isset($_POST['occupation'])|| isset($_POST['skill'])){
            if(!empty($_POST['name'])){
                $criterios = array(
                    'name'=>$_POST['name'],
                );
                $profiles = $em->getRepository('UsuarioBundle:Profile')->findBy($criterios);
                foreach($profiles as $item){
                    $array_user[$i]  = array(
                        'iduser'=>$item->getUser()->getId(),
                        'fullname'=>$item->getName().' '.$item->getLastname(),
                        'avatar'=>$item->getAvatar(),
                        'email'=>$item->getUser()->getEmail(),
                        'rol'=>$this->getNameRol($item->getUser()->getRoles()[0]),
                        'location'=>$item->getCity().','.$item->getCountry(),
                        'team'=>''
                    );
                    $formation =  $em->getRepository('UsuarioBundle:Formation')->findOneBy(array(
                        'user'=>$item->getId()
                    ));
                    $ocupation =(isset($formation))?$formation->getNameOcupation():'N/A';
                    // echo $ocupation;die;
                    $array_user[$i]['occupation'] = $ocupation;
                    $i++;
                }
            }elseif(!empty($_POST['occupation'])){
                $criterios = array(
                    'nameOcupation'=>$_POST['occupation'],
                );
                $formation = $em->getRepository('UsuarioBundle:Formation')->findBy($criterios);
                foreach($formation as $item){
                    $userprofile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array(
                            'user'=>$item->getUser()->getId()
                        )
                    );
                   // echo '<pre>'; print_r($formation);die;
                   $array_user[$i] =  array(
                        'iduser'=>$item->getUser()->getId(),
                       'fullname'=>$userprofile->getName().' '.$userprofile->getLastname(),
                        'avatar'=>$userprofile->getAvatar(),
                        'email'=>$userprofile->getUser()->getEmail(),
                       'rol'=>$this->getNameRol($item->getUser()->getRoles()[0]),
                       'location'=>$userprofile->getCity().','.$userprofile->getCountry(),
                       'team'=>''
                   );
                    $a = $item->getNameOcupation();
                    $ocupation =(isset($a))?$item->getNameOcupation():'N/A';
                    // echo $ocupation;die;
                    $array_user[$i]['occupation'] = $ocupation;
                    $i++;
                }

            }elseif(!empty($_POST['skill'])){
                $criterios = array(
                    'nameSkill'=>$_POST['skill'],
                );
                $skills = $em->getRepository('UsuarioBundle:Skill')->findOneBy($criterios);
                $skilluser = $em->getRepository('UsuarioBundle:Skillusers')->findBy(array(
                    'skillId'=>$skills->getId()
                ));
                foreach($skilluser as $item){
                    $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array(
                        'user'=>$item->getUserId()
                    ));
                    $formation =  $em->getRepository('UsuarioBundle:Formation')->findOneBy(array(
                        'user'=>$item->getId()
                    ));
                    $array_user[$i]  = array(
                        'iduser'=>$item->getUserId(),
                        'fullname'=>$profile->getName().' '.$profile->getLastname(),
                        'avatar'=>$profile->getAvatar(),
                        'email'=>$profile->getUser()->getEmail(),
                        'rol'=>$this->getNameRol($profile->getUser()->getRoles()[0]),
                        'location'=>$profile->getCity().','.$profile->getCountry(),
                        'team'=>''
                    );
                    $ocupation =(isset($formation))?$formation->getNameOcupation():'N/A';
                    // echo $ocupation;die;
                    $array_user[$i]['occupation'] = $ocupation;

                    $i++;
                }
            }elseif(!empty($_POST['country'])){
                $criterios = array(
                    'pais'=>$_POST['country'],
                );
                $country =  $em->getRepository('UsuarioBundle:Paises')->findOneBy($criterios);
                $name_country = $country->getCodigo();
                $profile = $em->getRepository('UsuarioBundle:Profile')->findBy(array(
                    'country'=>$name_country
                ));

                foreach($profile as $item){
                    $formation =  $em->getRepository('UsuarioBundle:Formation')->findOneBy(array(
                        'user'=>$item->getId()
                    ));
                    $array_user[$i]  = array(
                        'iduser'=>$item->getUser()->getId(),
                        'fullname'=>$item->getName().' '.$item->getLastname(),
                        'avatar'=>$item->getAvatar(),
                        'email'=>$item->getUser()->getEmail(),
                        'rol'=>$this->getNameRol($item->getUser()->getRoles()[0]),
                        'location'=>$item->getCity().','.$item->getCountry(),
                        'team'=>''
                    );
                    $ocupation =(isset($formation))?$formation->getNameOcupation():'N/A';
                    // echo $ocupation;die;
                    $array_user[$i]['occupation'] = $ocupation;

                    $i++;
                }
            }elseif(!empty($_POST['city'])){
                $criterios = array(
                    'city'=>$_POST['city'],
                );
                $profile = $em->getRepository('UsuarioBundle:Profile')->findBy(array(
                    'city'=>$_POST['city']
                ));
                foreach($profile as $item){
                    $formation =  $em->getRepository('UsuarioBundle:Formation')->findOneBy(array(
                        'user'=>$item->getId()
                    ));
                    $array_user[$i]  = array(
                        'iduser'=>$item->getUser()->getId(),
                        'fullname'=>$item->getName().' '.$item->getLastname(),
                        'avatar'=>$item->getAvatar(),
                        'email'=>$item->getUser()->getEmail(),
                        'rol'=>$this->getNameRol($item->getUser()->getRoles()[0]),
                        'location'=>$item->getCity().','.$item->getCountry(),
                        'team'=>''
                    );
                    $ocupation =(isset($formation))?$formation->getNameOcupation():'N/A';
                    // echo $ocupation;die;
                    $array_user[$i]['occupation'] = $ocupation;

                    $i++;
                }

            }elseif(!empty($_POST['category'])){
                $user = $em->getRepository('UsuarioBundle:User')->findBy(array(
                    'categoria_work'=>$_POST['category']
                ));
				//echo count($user);die;
				
                foreach($user as $item){

                    $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array(
                        'user'=>$item->getId()
                    ));

                    $formation =  $em->getRepository('UsuarioBundle:Formation')->findOneBy(array(
                        'user'=>$item->getId()
                    ));
					
					//echo $i;
					//echo $profile->getName();
					
					//$name = (isset($profile->getName()))?$profile->getName():'';
					if(!empty($profile)){
					 	$name =$profile->getName();
						$lastname =$profile->getLastname();
						$avatar = $profile->getAvatar();
						$email = $profile->getUser()->getEmail();
						$rol = $this->getNameRol($profile->getUser()->getRoles()[0]);
						$location = $profile->getCity().','.$profile->getCountry();
					}
					/*if($profile->getName()!=null)
						
					else
						$name = "";
					
					if($profile->getLastname()!=null)
						$
					else
						$lastname = "";*/
					//$lastname = (!is_null($profile->getLastname()))?$profile->getLastname():'';
						
                    $array_user[$i]  = array(
                        'iduser'=>$item->getId(),
                        'fullname'=>$name.' '.$lastname,
                        'avatar'=>$avatar,
                        'email'=>$email,
                        'rol'=>$rol,
                        'location'=>$location,
                        'team'=>''
                    );
                    $ocupation =(isset($formation))?$formation->getNameOcupation():'N/A';

                    $array_user[$i]['occupation'] = $ocupation;
                    $i++;
                }
            }
        }
       // echo '<pre>';print_r($array_user);die;
       $result = $this->user_comp->getPersonalsInfo();
        $result["projects_active"] = "";
        $result["create_pro_activite"] = "";
        $result["create_emplo_activite"] = "";
        $result["list_emplo_activite"] = "active";
        $result["employee_active"] = "active";
        $result["team_active"] = "";
        $result["create_team_activite"] = "";
        $result["list_active"] = "";
        $result["create_offer_job"] = "";
        $result["offer_job"] = "";
        $result["view_offer_job"] = "";
        $result["display"] = "";
        //$projects = $this->getAllProjects();

        return $this->render('UsuarioBundle:Usuario:list_user.html.twig', array('datos'=>$result,'users'=>$array_user, 'projects'=>array()));


    }
    public function getNameRol($rol){

        //echo $rol;
        $name = "";
        switch($rol){
            case 'ROLE_ADMIN':
                $name = 'Administrator';
                break;
            case 'ROLE_USER':
                $name = 'User';
                break;
            default:
                break;
        }
        return $name;
    }
    private function getPersonalsInfo($iduser = 0){
        if ($iduser)
            $id = $iduser;
        else{
            $user = $this->get('security.context')->getToken()->getUser();
            $id = $user->getId();
        }
        $em = $this->get('doctrine')->getManager();
        $user = $em->getRepository('UsuarioBundle:User')->find($id);
        $result['email'] = $user->getEmail();

        $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$id));
        // echo '<pre>';print_r($profile);die;
        $num = $profile->getUser()->getCategory();
        /*PROFESSIONAL CATEGORY*/
        //$category = $this->getNameCategoryUser($num);
        $result['name'] = $profile->getName();
        $result['lastname'] = $profile->getLastname();
        $result['fullname'] = $profile->getName().' '.$profile->getLastname();
        $result['country'] =  $profile->getCountry();
        $result["avatar"] = $profile->getAvatar();
        $result['phone'] = $profile->getPhone();
        $result['mobile'] = $profile->getMobile();
        $result['address'] = $profile->getAddrees();
        $result['birthday'] = $profile->getBirthday();
        $result['latitud'] = $profile->getUser()->getLatitud();
        $result['longitud'] = $profile->getUser()->getLongitud();
       //$result['category'] = $category;
        $result["profile_active"] = "";
        $result["projects_active"] = "";
        $result["create_pro_activite"] = "";
        $result["create_emplo_activite"] = "";
        $result["employee_active"] = "";
        $result["list_emplo_activite"] = "";
        $result["team_active"] = "";
        $result["create_team_active"] = "";
        $result["list_active"] = "";
        $result["create_offer_job"] = "";
        $result["offer_job"] = "";
        $result["display"] = "";
        return $result;
    }

    public function addOpereAction(){
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        if(!empty($_POST)){

            //echo '<pre>';print_r($_POST);die;
            $opere = new Opere();

            $subcateg = $em->getRepository('AdminBundle:SubCategory' )->find($_POST['data']['subcategory']);

            $opere->setSubcategory($subcateg);
            $opere->setCode($_POST['data']['code']);
            $opere->setGradi($_POST['data']['gradi']);
            $opere->setIdentificazione($_POST['data']['identificazione']);

            $em->persist($opere);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'OKKK!!!');
            return new Response(json_encode($response));

        }else{
            $result = $this->user_comp->getPersonalsInfo($id);
            $result['category'] = $this->getAllCategory();

          //  $projects = $this->getAllProjects();
            //echo '<pre>';print_r($user);die;

            return $this->render('AdminBundle:Admin:opere.html.twig', array(
                'datos'=>$result,
                'projects'=>array()
            ));
        }

    }

    public function getAllCategory(){

        $em = $this->get('doctrine')->getManager();
        $categories = $em->getRepository('AdminBundle:CategOffer' )->findAll();

        foreach($categories as $item){

            $array['id'] = $item->getId();
            $array['name'] = $item->getName();

            $result[] = $array;
        }

        //echo '<pre>';print_r($result);die;
        return $result;

    }


} 