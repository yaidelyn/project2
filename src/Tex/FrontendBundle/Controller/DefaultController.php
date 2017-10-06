<?php

namespace Tex\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Utils\UserComponent;

class DefaultController extends Controller
{
	
	var $user_comp;

    public function __construct(){
        $this->user_comp = new UserComponent($this);
    }


    public function showPageOfferAction(){

        $em = $this->get('doctrine')->getManager();

        $categoria =  $em->getRepository('AdminBundle:CategOffer')->findAll();
		  $u = $this->get('security.context')->getToken()->getUser();
		
		  if(is_object($u)){
            $id = $u->getId();
            $result = $this->user_comp->getPersonalsInfo($id);
        }

        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;

        $offers = $em->getRepository('AdminBundle:Offer')->findAll();

        //echo '<pre>';print_r( $offers);die;

        foreach($offers as $item){

            $array['id'] = $item->getId();
            $array['idcategoria'] = $item-> getCategory()->getId();
            $array['title'] = $item->getTitle();
            $array['description'] = $this->resumir($item->getDescription(),150," ",".");
            if($item->getBudget())
                $array['budget'] = $item->getBudget();
            else
                $array['budget'] = 0;

            foreach($item->getSkills() as $skill)
                $array['skill'][] = $skill->getName();

            $result['offer'][] = $array;

            unset($array);
        }

      //echo '<pre>';print_r( $result['offer']);die;

        //echo '<pre>';print_r($result);die;
        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_opportunita']= "active";
        $result['menu_gara']= "";
		$result['menu_work']= "";
        $result['categories'] = $this->getAllCategories();
        //  $result['menu_opportunita']= "active";



        return $this->render('FrontendBundle:Default:page_show.html.twig',array(
            'datos'=>$result,
            'category'=>'Offerte',
        ));

    }
	//get offers x subcategory
    public function getOferBySubCategoryAction($id_category,$id_subcategory){

        $em = $this->get('doctrine')->getManager();

        //echo $id_subcategory;die;
        //get name subcategory
        $categoria =  $em->getRepository('AdminBundle:CategOffer')->find($id_category);
        $subcategoria =  $em->getRepository('AdminBundle:SubCategory')->find($id_subcategory);

        $title_category =$categoria->getName().'/'.$subcategoria->getDescription();
        $title_page =$categoria->getName().'-'.$subcategoria->getDescription();


       // echo '<pre>';print_r($subcategoria);die;

        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;

        $offers = $em->getRepository('AdminBundle:Offer')->findBy(
            array('subcategory'=>$id_subcategory),
            array('id' => 'DESC')
        );
        if(!empty($offers)){

        }
        foreach($offers as $item){

            $array['id'] = $item->getId();
            $array['title'] = $item->getTitle();
            $array['description'] = $this->resumir($item->getDescription(),150," ",".");
            if($item->getBudget())
                $array['budget'] = $item->getBudget();
            else
                $array['budget'] = 0;

            foreach($item->getSkills() as $skill)
                $array['skill'][] = $skill->getName();

            $result['offer'][] = $array;

            unset($array);
        }
        //echo '<pre>';print_r($result);die;
		 $u = $this->get('security.context')->getToken()->getUser();
		
		  if(is_object($u)){
            $id = $u->getId();
            $result = $this->user_comp->getPersonalsInfo($id);
        }
        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_gara']= "";
        $result['menu_opportunita']= "active";
        $result['categories'] = $this->getAllCategories();
        $result['title_subcategory'] = $title_category;
        //  $result['menu_opportunita']= "active";

        //echo '<pre>';print_r($result);die;
       return $this->render('FrontendBundle:Default:page_show_subcategory.html.twig',array('datos'=>$result,'id_category'=>$id_category,
           'id_subcategory'=>$id_subcategory,'category'=>$title_category,'title_page'=>$title_page));

    }
    public function getAllCategories()
    {
        $em = $this->get('doctrine')->getManager();

        $categories = $em->getRepository('AdminBundle:CategOffer')->findAll();

         foreach($categories as $categ){
             $array['id']= $categ->getId();
             $array['name'] = $categ->getName();

             $subcategories = $em->getRepository('AdminBundle:SubCategory')->findBy(array(
                 'category'=>$categ->getId()
             ));

             foreach($subcategories as $sub){
                 $array_sub['id'] = $sub->getId();
                 $array_sub['name'] = $sub->getDescription();

                 $result_sub[] = $array_sub;
             }

             $array['subcategory'] = $result_sub;
             unset($result_sub);

             $result[] = $array;
             unset($array);
         }

        return $result;


    }
    public function aboutAction(){

        $locale = $this->get('request')->getLocale();
		 $u = $this->get('security.context')->getToken()->getUser();
		
		  if(is_object($u)){
            $id = $u->getId();
            $result = $this->user_comp->getPersonalsInfo($id);
        }
        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;
        $result['menu_home']= "";
        $result['menu_about']= "active";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_gara']= "";
        $result['menu_opportunita']= "";
        return $this->render('UsuarioBundle:Default:about.html.twig',array('datos'=>$result));
    }

    public function contactAction(){


        $locale = $this->get('request')->getLocale();
		$u = $this->get('security.context')->getToken()->getUser();
		
		  if(is_object($u)){
            $id = $u->getId();
            $result = $this->user_comp->getPersonalsInfo($id);
        }
        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;
        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "active";
        $result['menu_gara']= "";
        $result['menu_opportunita']= "";
		$result['menu_work']= "";

        if(!empty($_POST)){

            //echo '<pre>';print_r($_POST);die;
            //echo 'Contatc Info';

            if($this->sendMessage($_POST)){

                return $this->render('UsuarioBundle:Default:contact.html.twig',array('datos'=>$result));

            }
        }

        return $this->render('UsuarioBundle:Default:contact.html.twig',array('datos'=>$result));

    }

    public function sendMessage($message = array()){

        //echo $message['name'];die;

       // echo '<pre>';print_r($message);die;

        $msg = array(
            'date'=>date('l').','.date('d').' '.date('F'),
            'name'=>$message['name'],
            'phone'=>$message['phone'],
            'email'=>$message['email'],
            'message'=>$message['message']

        );		
		
		  //message for the cliente
        $msg2 = array(
            'date'=>date('l').','.date('d').' '.date('F'),
            'message'=>'Grazie per averci contattato a breve sarà contattato da un nostro commerciale'
        );

        $email = $message['email'];

        //send email
        $email1 = \Swift_Message::newInstance()
            ->setSubject('Test')
            ->setFrom("".$email."")
            ->setTo("tex07556@gmail.com")
            ->setBody(
                $this->renderView(
                    'UsuarioBundle:Default:email_tex.html.twig',
                    array('data_client'=>$msg)
                ),
                'text/html'
            );

          $email2 = \Swift_Message::newInstance()
            ->setSubject('Test')
            ->setFrom("tex07556@gmail.com")
            ->setTo("".$email."")
            ->setBody(
                $this->renderView(
                    'UsuarioBundle:Default:email_client.html.twig',
                    array('data_client'=>$msg2)
                ),
                'text/html'
            );

        return ($this->get('mailer')->send($email1) && $this->get('mailer')->send($email2));

    }


    //get all questions and answers x test
    public function getQuestionsAnswers($id){
	
		//echo $id;die;

        $em = $this->get('doctrine')->getManager();

        $questions = $em->getRepository('AdminBundle:Questions')->findBy(array(
            'test'=>$id
        ));
		
		 //echo '<pre>';print_r($message);die;

        foreach($questions as $item){
            $array['id'] = $item->getId();
            $array['title'] = $item->getTitle();

            //busco todas sus respuestas
            $answers = $em->getRepository('AdminBundle:Answers')->findBy(array(
                'questions'=>$item->getId()
            ));

            foreach($answers as $elem){
                $temp['id'] = $elem->getId();
                $temp['value']= $elem->getValue();
                $temp['iscorrect'] = $elem->getIscorrect();

                $temp2[] = $temp;

            }
            $array['questions'] = $temp2;
            $result[] = $array;

            unset($temp2);
        }
		
		//echo '<pre>';print_r($result);die;

        return $result;

    }
   

    //get short news
    function resumir($texto, $limite, $car=".", $final="…")
    {
        if(strlen($texto) <= $limite) return $texto;
        if(false !== ($breakpoint = strpos($texto, $car, $limite)))
        {
            $val=strlen($texto)-1;
            if( $breakpoint < $val)
            {
                $texto= substr($texto, 0, $breakpoint) . $final;
            }
        }
        return $texto;
    }

    //get offer por id
    public function getOfferByIdAction($id){


        $em = $this->get('doctrine')->getManager();
        $oferta = $em->getRepository('AdminBundle:Offer')->find($id);
		
		$u = $this->get('security.context')->getToken()->getUser();
		
		  if(is_object($u)){
            $id = $u->getId();
            $result = $this->user_comp->getPersonalsInfo($id);
        }


        $test = $em->getRepository('AdminBundle:Test')->findOneBy(array(
            'offer'=>$oferta->getId()
        ));

        //echo '<pre>';print_r($test);die;

        // echo $oferta->getDescription();die;
        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;

        $result['id'] = $oferta->getId();
        $result['title'] = $oferta->getTitle();
        $result['description'] = $oferta->getDescription();
        $result['budget'] = $oferta->getBudget();
        foreach($oferta->getSkills() as $skill){
            $result['skills'][] = $skill->getName();
        }

		if(isset($test)){
            $result['id_test'] = $test->getId();
            //$result['name_test'] = $test->getName();
        }

       // $result['test'] = $this->getQuestionsAnswers($id);
        // $result['offer'][] = $array;
		

        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_opportunita']= "active";
        $result['categories'] = $this->getAllCategories();
        $result['menu_gara'] = "";
        //$result['menu_opportunita']= "active";

       //echo '<pre>';print_r($result);die;

        return $this->render('FrontendBundle:Default:show_offer.html.twig',array('datos'=>$result));
    }
	
	public function getTestByOfferAction($id_offer,$id_test){


	$em = $this->get('doctrine')->getManager();
	$oferta = $em->getRepository('AdminBundle:Offer')->find($id_offer);
	
	$u = $this->get('security.context')->getToken()->getUser();
		
		  if(is_object($u)){
            $id = $u->getId();
            $result = $this->user_comp->getPersonalsInfo($id);
        }

	//echo '<pre>';print_r($oferta);die;

	$test = $em->getRepository('AdminBundle:Test')->findOneBy(array(
		'offer'=>$id_offer
	));

	 /*chekear si existe el user*/
	$user = $this->get('security.context')->getToken()->getUser();
	$id = $user->getId();

	$usertest = $em->getRepository('AdminBundle:UserTest')->findBy(array(
		'user'=>$id,
		'test'=>$id_test
	));

	//echo '<pre>';print_r($usertest);die;

	$result['menu_home']= "";
	$result['menu_about']= "";
	$result['menu_project']= "";
	$result['menu_contact']= "";
	$result['menu_opportunita']= "active";
	$result['menu_gara']= "active";
	$result['categories'] = $this->getAllCategories();



	$result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
		$this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;

	$result['id'] = $oferta->getId();
	$result['title'] = $oferta->getTitle();
	$result['description'] = $oferta->getDescription();
	$result['budget'] = $oferta->getBudget();
	foreach($oferta->getSkills() as $skill){
		$result['skills'][] = $skill->getName();
	}

	if(isset($test)){
		$result['id_test'] = $test->getId();
		$result['name_test'] = $test->getName();
	}

	$result['test'] = $this->getQuestionsAnswers($id_test);
	// $result['offer'][] = $array;

	//return $this->render('FrontendBundle:Default:test_offer.html.twig',array('datos'=>$result));

   if(empty($usertest)){

		return $this->render('FrontendBundle:Default:test_offer.html.twig',array('datos'=>$result));
	}else{
			$message = 'Spiacenti, lei ha già inoltrato la sua candidatura per questa offerta';
            return $this->render('FrontendBundle:Default:show_offer.html.twig',array('datos'=>$result,'message'=>$message));
	}
	}

    public function aboutPageAction(){



        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;
		$u = $this->get('security.context')->getToken()->getUser();
		
		  if(is_object($u)){
            $id = $u->getId();
            $result = $this->user_comp->getPersonalsInfo($id);
        }	
        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "active";
        $result['menu_opportunita']= "";


        return $this->render('UsuarioBundle:Frontend:page_about.html.twig',array('datos'=>$result));
    }
    public function getAllGara($id){


        $em = $this->get('doctrine')->getManager();
       // $categoria =  $em->getRepository('AdminBundle:CategOffer')->find($id);
        $garas = $em->getRepository('AdminBundle:Gare')->findBy(
            array('category'=>$id),
            array('id' => 'DESC')
        );

        $roles = $em->getRepository('AdminBundle:GareTeam')->findAll();

        //echo '<pre>';print_r($roles);die;

        foreach($garas as $elem){
            $id = $elem->getId();

            //$roles = $em->getRepository('AdminBundle:GareTeam')->findBy( array('gare'=>))
        }

        foreach($garas as $item){

            foreach($item->getGareteams() as $team)
            {
                $rol = $em->getRepository('AdminBundle:GareTeam')->find($team->getId());

                $role['name'] = $rol->getNameRol();
                $role['active'] = $rol->getActive();

                $data_rol[] = $role;

            }
            //echo '<pre>';print_r($data_rol);die;
           /* foreach($roles as $rol){
                $role['name'] = $rol->getNameRol();
                $role['active'] = $rol->getActive();

                $data_rol[] = $role;

            }*/
			
			$u = $this->get('security.context')->getToken()->getUser();
			
			  if(is_object($u)){
				$id = $u->getId();
				$result = $this->user_comp->getPersonalsInfo($id);
			}

            $data['id'] = $item->getId();
            $data['title'] = $item->getTitle();
            $data['description'] = $item->getDescription();
            $data['capteam'] = $item->getCapTeam();
            $data['scadenzagrara'] = $item->getScadenzaGara()->format('d/m/Y h:m:s');
            $data['scadenzacanditura'] = $item->getScadenzaCanditura()->format('d/m/Y h:m:s');
            $data['rol'] = $data_rol;

            $result_gara[] = $data;
            unset($data_rol);
        }
       //echo '<pre>';print_r($result_gara);die;

        return $result_gara;


    }
}
