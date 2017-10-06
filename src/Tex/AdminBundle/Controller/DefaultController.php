<?php

namespace Tex\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tex\UsuarioBundle\Entity\Team;
use Tex\UsuarioBundle\Entity\User;
use Tex\UsuarioBundle\Entity\Formation;
use Tex\UsuarioBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Utils\UserComponent;

class DefaultController extends Controller   
{
	 var $user_comp;	

    public function __construct(){
        $this->user_comp = new UserComponent($this);
    }
    public function  indexAction()
    {

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {	
		
 
            return $this->redirect($this->generateUrl('Base_site'));

        }elseif ($this->get('security.context')->isGranted('ROLE_USER')) {

            return $this->redirect($this->generateUrl('edit_profile'));
        }else{
            return $this->redirect($this->generateUrl('login'));
        }

    }

    public function adminAction(){
        $em = $this->get('doctrine')->getManager();
        $u = $this->get('security.context')->getToken()->getUser();
        $id = $u->getId();
        $result = $this->user_comp->getPersonalsInfo($id);
        $messages =  $this->getMsgUser();
        $result["formation_active"] = "";
        $result["create_active"] = "";
        $user = $em->getRepository('UsuarioBundle:User' )->find($id);
        //contar numero de user
        $users = $em->getRepository('UsuarioBundle:User' )->findAll();
        $garas = $em->getRepository('AdminBundle:Gare' )->findAll();
        $teams = $em->getRepository('UsuarioBundle:Team' )->findAll();

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $projects = $this->getAllProjects();
            //echo '<pre>';print_r($user);die;
            return $this->render('AdminBundle:Admin:index.html.twig', array(
                'datos'=>$result,
                'user_cont' => count($users),
                'cont_project'=>count($projects),
				'cont_team'=>count($teams),
                'projects'=>$projects,
                'messages'=>$messages,
                'cant_gara'=>count($garas)
            ));

        }elseif($this->get('security.context')->isGranted('ROLE_USER')){
            $projects = $this->getAllProjects();
            $result = $this->user_comp->getPersonalsInfo($id);

            //echo $result['fullname'];die;

            $formation = $em->getRepository('UsuarioBundle:Formation' )->findOneBy(array(
                'user'=>$id
            ));

            $teams = $em->getRepository('UsuarioBundle:Team' )->findBy(array(
                'user'=>$id
            ));

            foreach($teams as $team){
                $data['idteam'] = $team->getId();
                $data['name'] = $team->getName();
                $data['description'] = $team-> getDescription();
                $data['countperson'] = count($team->getProfiles());

                $arr_team[] = $data;
            }

           // echo '<pre>';print_r($arr_team);die;

            $result['team'] =(!empty($arr_team))?$arr_team:'';

            if(!empty($formation)){
                $result['formation'] = array(
                    'idocupation'=>$formation->getId(),
                    'nameOcupation'=> $formation-> getNameOcupation()
                );
            }

            $offers = $em->getRepository('AdminBundle:Offer' )->findBy(array(
                'user'=>$id
            ));

            if(!empty($offers)){
                foreach($offers as $ofer){
                    $dataofer['id'] = $ofer->getId();
                    $dataofer['name'] = $ofer->getTitle();

                    $arr_ofer[] = $dataofer;


                }

                $result['offer'] = $arr_ofer;

            }



            $result["formation_active"] = "";
            $result["create_active"] = "";

            //buscar gara result
            $gara_result = $em->getRepository('AdminBundle:ResultGara')->findBy(array(
                'nameUser'=>$result['fullname']
            ));

            foreach($gara_result  as $val){
                $arr_result_gara['id'] = $val->getId();
                $arr_result_gara['namegara'] = $val->getNameGara();
                $arr_result_gara['percent'] =intval($val->getPercent()) ;

                $temp_resgara[] = $arr_result_gara;
            }

            return $this->render('AdminBundle:Admin:index_user.html.twig', array(
                'datos'=>$result,
                'user_cont' => count($users),
                'cont_project'=>count($projects),
                'projects'=>$projects,
                'messages'=>$messages,
                'cant_team'=>count($arr_team),
                'cant_offer'=>count($arr_ofer),
                'result_gara'=>$temp_resgara
            ));

        }

    }


    public function getAllProjects(){
        $em = $this->get('doctrine')->getManager();
        $projects = $em->getRepository('ProyectoBundle:Project' )->findAll();

        $result = array();
        foreach($projects as $obj){
            $result[] = array(
                'id_project'=>$obj->getId(),
                'name'=>$obj->getName());
        }

        return $result;


    }
	public function getNameCategoryUser($num){
        $name_category = "";
        if($num == 1){
            $name_category = "Professional Junior";
        }elseif($num == 2){
            $name_category = "Professional Senior";
        }elseif($num == 3){
            $name_category = "Volunteer";
        }

        return $name_category;


    }
	function getSkillsUser($id){
        $em = $this->get('doctrine')->getManager();
        $user_skills = $em->getRepository('UsuarioBundle:Skillusers')->findBy(array("userId"=>$id));
        $skills = "";

        foreach($user_skills as $k=>$v){
            $skill = $em->getRepository('UsuarioBundle:Skill')->find($v->getSkillId());
            $skills .= $skill->getNameSkill().", ";
        }

       return $skills;

      // echo '<pre>';print_r($user_skills);die;

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
		
		$pais =  $countries = $em->getRepository('UsuarioBundle:Paises')->findOneBy(array(
            'pais'=>$profile->getCountry()
        ));

        $result = $this->user_comp->getPersonalsInfo($id);
		
		// echo '<pre>';print_r($pais);die;
		 $num = $profile->getUser()->getCategoriaWork();
        /*PROFESSIONAL CATEGORY*/
        $category = $this->getNameCategoryUser($num);
        $result['name'] = $profile->getName();
        $result['lastname'] = $profile->getLastname();
        $result['fullname'] = $profile->getName().' '.$profile->getLastname();
        $result['country'] =  $pais->getPais();
        $result["avatar"] = $profile->getAvatar();
        $result['phone'] = $profile->getPhone();
        $result['mobile'] = $profile->getMobile();
        $result['address'] = $profile->getAddrees();
        $result['birthday'] = $profile->getBirthday();
		$result['ciudad'] = $profile->getCity();
		$result['latitud'] = $profile->getLatitud();
        $result['longitud'] = $profile->getLongitud();
	    $result['category'] = array('id'=>$num,'category'=>$category);
        $result['skills'] = $this->getSkillsUser($id);

       /* $result["profile_active"] = "";
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
        $result["display"] = "";*/
        return $result;
    }
	
	    //User Login
    private function getPersonalsInfoLogin($iduser = 0){
        if ($iduser)
            $id = $iduser;
        else{
            $user = $this->get('security.context')->getToken()->getUser();
            $id = $user->getId();
        }
        $em = $this->get('doctrine')->getManager();
        $user = $em->getRepository('UsuarioBundle:User')->find($id);
        // echo '<pre>';print_r($user);die;
        $result['email'] = $user->getEmail();
        // echo $id;die;
        $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$id));
        $num = $profile->getUser()->getCategoriaWork();
        /*PROFESSIONAL CATEGORY*/
        $category = $this->getNameCategoryUser($num);
        $result['name'] = $profile->getName();
        $result['lastname'] = $profile->getLastname();
        $result['fullname'] = $profile->getName().' '.$profile->getLastname();
        $result['country'] =  $profile->getCountry();
        $result["avatar"] = $profile->getAvatar();
        $result['phone'] = $profile->getPhone();
        $result['mobile'] = $profile->getMobile();
        $result['address'] = $profile->getAddrees();
        $result['birthday'] = $profile->getBirthday();
        $result['latitud'] = $profile->getLatitud();
        $result['longitud'] = $profile->getLongitud();
        $result['category'] = array('id'=>$num,'category'=>$category);
        $result['skills'] = $this->getSkillsUser($id);
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

    public function getAllEmployes(){

        $em = $this->get('doctrine')->getManager();
        $profiles = $em->getRepository('UsuarioBundle:Profile' )->findAll();

        foreach($profiles as  $item){
            $data['id'] = $item->getId();
            $data['name'] = $item->getName().' '.$item->getLastname();

            $result [] = $data;
        }
       // echo '<pre>';print_r($result);die;
        return $result;
    }

    public function createTeamAction(){

        $em = $this->get('doctrine')->getManager();
        if(!empty($_POST)) {
            $u = $this->get('security.context')->getToken()->getUser();
            $id = $u->getId();

            $team = $em->getRepository('UsuarioBundle:Team')->findBy(
                array(),
                array('id' => 'ASC') ,
                1,
                0
            ) ;
			
			if(is_object($team)){
			echo 'Hola team';die;
				$code = $this->generate_numbers(substr($team[count($team)-1]->getCode(),-1)+1,1,3)[0];
			}else{
				$code = '001';			
			}

           // echo '<pre>';print_r($team[0]);die;

            //echo $this->generate_numbers(substr($team[count($team)-1]->getCode(),-1)+1,1,3)[0];die;
            $name = $_POST['data']['name'];
            
            $description = $_POST['data']['description'];
            $createby = $id;
            $active = 1;
            $user = $em->getRepository('UsuarioBundle:User' )->find($_POST['data']['user_leader']);


            //create Team
            $team = new Team();
            $team->setName($name);
            $team->setCode($code);
            $team->setDescription($description);
            $team->setCreateBy($createby);
            $team->setActivate($active);
            $team->setUser($user);

            foreach($_POST['data']['data_user'] as $user){
                $profile = $em->getRepository('UsuarioBundle:Profile' )->find($user);
                $team->addProfile($profile);
            }
            $em->persist($team);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'Ha inserito correttamente el team!!');
            return new Response(json_encode($response));

          //echo '<pre>';print_r($_POST['data']);die;
        }


        //echo '<pre>';print_r($team);die;
        $result = $this->user_comp->getPersonalsInfo();
        $result['user'] = $this->getAllEmployes();
        $projects = $this->getAllProjects();


        return $this->render('AdminBundle:Admin:create_team.html.twig', array('datos'=>$result, 'projects'=>$projects));
    }

    public function createEmployeeAction(){

        $result = $this->user_comp->getPersonalsInfo();

        $result["create_emplo_activite"] = "active";
        $result["employee_active"] = "active";

        $projects = $this->getAllProjects();

        return $this->render('AdminBundle:Admin:create_employee.html.twig', array('datos'=>$result, 'projects'=>$projects));
    }

    public  function createProjectAction(){
       // echo 'Holaaa';die;
        $result = $this->getPersonalsInfo();
        $result["projects_active"] = "active";
        $result["create_pro_activite"] = "active";
        $result["create_emplo_activite"] = "";
        $result["employee_active"] = "";
        $result["list_emplo_activite"] = "";
        $result["team_active"] = "";
        $result["create_team_activite"] = "";
        $result["list_active"] = "";
        $result["display"] = "";
        $projects = $this->getAllProjects();

        return $this->render('AdminBundle:Admin:create_project.html.twig', array('datos'=>$result, 'projects'=>$projects));


    }

    /*List all user from administrator*/
    public function listAllUserAction(){

        //list_emplo_activite
        $array_user = array();

        $em = $this->get('doctrine')->getManager();
        $users = $em->getRepository('UsuarioBundle:Profile')->findAll();
        $i = 0;
        foreach($users as $item){
            $team = isset($item->getTeams()[0])?$item->getTeams()[0]->getName().'_'.$item->getTeams()[0]->getCode():"";
            $array_user[$i]  = array(
                'iduser'=>$item->getUser()->getId(),
                'fullname'=>$item->getName().' '.$item->getLastname(),
                'avatar'=>$item->getAvatar(),
                'email'=>$item->getUser()->getEmail(),
                'rol'=>$this->getNameRol($item->getUser()->getRoles()[0]),
                'location'=>$item->getCity().','.$item->getCountry(),
                'team'=> $team
            );

            $formation =  $em->getRepository('UsuarioBundle:Formation')->findOneBy(array(
                'user'=>$item->getId()
            ));

            $ocupation =(isset($formation))?$formation->getNameOcupation():'N/A';
           // echo $ocupation;die;
            $array_user[$i]['occupation'] = $ocupation;

            $i++;
        }
       //echo '<pre>';print_r($array_user);die;

        $result = $this->user_comp->getPersonalsInfo();
        $result["list_emplo_activite"] = "active";
        $result["employee_active"] = "active";
        $projects = $this->getAllProjects();

        return $this->render('UsuarioBundle:Usuario:list_user.html.twig', array('datos'=>$result,'users'=>$array_user, 'projects'=>$projects));

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


    public function getMsgUser(){

        $em = $this->get('doctrine')->getManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();

        $messages = $em->getRepository('UsuarioBundle:Chat')->findAll();
        
        //echo '<pre>';print_r($messages);die;

        $array = array();

        foreach($messages as $item){

            //$userdata = $em->getRepository('UsuarioBundle:Profile' )->findOneBy(array('user'=>$id));
            $userdata = $em->getRepository('UsuarioBundle:Profile')->findBy(array('user'=>$item->getUserId()));

            //echo '<pre>';print_r($userdata );die;
           $array[] = array(
                'name'=>$userdata[0]->getName().' '.$userdata[0]->getLastname(),
                'avatar'=>$userdata[0]->getAvatar(),
                'text'=>$item->getText(),
                'chatdate'=>$item->getChatdate(),

            );

        }

        return $array;

      //echo '<pre>';  print_r($array);die;


    }

    /*Editar User from Admin*/
    public function adminEeditUserAction($id){

        $em = $this->get('doctrine')->getManager();
        $user = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$id));      

        $datauser =  $this->user_comp->getPersonalsInfo($id);
        $datauser['skills'] = $this->getSkillsUser($id);
		$datalogin = $this->user_comp->getPersonalsInfo();
        $datalogin["projects_active"] = "";
        $datalogin["create_pro_activite"] = "";
        $datalogin["create_emplo_activite"] = "";
        $datalogin["list_emplo_activite"] = "active";
        $datalogin["employee_active"] = "active";
        $datalogin["team_active"] = "";
        $datalogin["create_team_activite"] = "";
        $datalogin["list_active"] = "";
        $datalogin["view_offer_job"] = "";
        $datalogin["active_gare"] = "";
        $datalogin["create_gare"] = "";
        $datalogin["view_create"] = "";
        $datalogin["view_result"] = "";
        $datalogin["active_new"] = "";
        $datalogin["create_news"] = "";
        $datalogin["display"] = "";
        $projects = $this->getAllProjects();
		$datalogin['roles'] = $this->getSelectedRol($id);


        if(!empty($user)){

           // echo $user->getUser()->getId();die;
            $data['iduser'] = $user->getUser()->getId();
            $data['firtname'] = $user->getName();
            $data['lastname'] =$user->getLastname();
            $data['birthday'] =$user->getBirthday();
            $data['country'] =$user->getCountry();
            $data['phone'] =$user->getPhone();
            $data['email'] =$user->getUser()->getEmail();
            $data['mobile'] =$user->getMobile();
            $data['address'] =$user->getAddrees();

        }

        //echo '<pre>';print_r($datalogin);die;

        return $this->render('AdminBundle:Admin:admin_edit_user.html.twig', array(
			'datos'=>$datalogin,
			'user'=>$datauser,
			'datacustom'=>$data,
			'categories'=>$this->getCategories($id),
			'projects'=>$projects
			)
		);

    }
	    public function getCategories($id){

        $em = $this->get('doctrine')->getManager();
        $user = $em->getRepository('UsuarioBundle:User')->find($id);
        $category = $user->getCategoriaWork();
        $categories = array(array(
            'id'=>1,
            'name'=>'Professional Junior'
        ),
            array(
                'id'=>2,
                'name'=>'Professional Senior'
            ),
            array(
                'id'=>3,
                'name'=>'Volunteer'
            )
        );

        foreach($categories as $item){
            $data['id'] = $item['id'];
            $data['name'] = $item['name'];
            if($item['id'] == $category->getId())
                $data['selected'] = 'selected';
            else
                $data['selected'] = '';

            $opts[] = $data;
        }
        //echo '<pre>';print_r($opts);die;
        return $opts;
    }

	
	    function getSelectedRol($id){
        
        $em = $this->get('doctrine')->getManager();
        $user = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$id));

        $rol = $user->getUser()->getRoles()[0];
        $roles = array(array(
            'id_rol'=>'ROLE_ADMIN',
            'value'=>'Administrator'
        ),
            array(
                'id_rol'=>'ROLE_USER',
                'value'=>'User'
            )
        );
        $opts = '';
        foreach($roles as $item){
            $data['id_rol'] = $item['id_rol'];
            $data['value'] = $item['value'];
            if($item['id_rol'] == $rol)
                $data['selected'] = 'selected';
            else
                $data['selected'] = '';

            $opts[] = $data;
        }

        return $opts;
    }

    public function adminUpdateProfileAction(){

        if(!empty($_POST)){
          //echo '<pre>';print_r($_POST['data']);die;
            $em = $this->get('doctrine')->getManager();
            $user = $em->getRepository('UsuarioBundle:User' )->find($_POST['data']['iduser']);
          // echo '<pre>';print_r($user);die;
            $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array(
                'user'=>$_POST['data']['iduser']
            ));
			
			 $cadena ="".$_POST['data']['address'].",".$_POST['data']['city'].",".$_POST['data']['country']."";
             $coordenadas = $this->getCoordinates($cadena);


            $pais =  $em->getRepository('UsuarioBundle:Paises')->findOneBy(array(
                'pais'=>$_POST['data']['country']
            ));

            $category = $em->getRepository('UsuarioBundle:CategWork')->find($_POST['data']['category']);
            $birthday = explode('/', $_POST['data']['birthday']);

            $profile->setName($_POST['data']['name']);
            $profile->setLastname($_POST['data']['lastname']);
            $profile->setBirthday(new \DateTime("$birthday[2]-$birthday[1]-$birthday[0]"));
            $profile->setCountry($pais->getCodigo());
			$profile->setCity($_POST['data']['city']);
            $profile->setPhone($_POST['data']['phone']);
            $profile->setMobile($_POST['data']['mobile']);
            $user->setEmail($_POST['data']['email']);
			$profile->setLatitud($coordenadas['latitud']);
            $profile->setLongitud($coordenadas['longitud']);
			$user->setCategoriaWork($category);
			$user->setRoles($_POST['data']['rol']);
            $profile->setAddrees($_POST['data']['address']);
            $em->persist($user);
            $em->flush();

            $em->persist($profile);
            $em->flush();
            $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
            return new Response(json_encode($response));
        }

    }

    public function adminDeleteUserAction($id){

       // echo $id;die;
       if(isset($id)){

           $em = $this->getDoctrine()->getManager();

           $usuario = $em->getRepository('UsuarioBundle:User')->find($id);
			$profile= $em->getRepository('UsuarioBundle:Profile')->findOneBy(array(
               'user'=>$id
           ));

           $em->remove($usuario);
           $em->remove($profile);
           $em->flush();

           return $this->redirect(
               $this->generateUrl('list_user')
           );

       }

    }


    public function offerJobAction(){

        $result = $this->user_comp->getPersonalsInfo();
        $result["create_offer_job"] = "active";
        $projects = $this->getAllProjects();
        $categories = $this->getAllCategory();
        //$types = $this->getTypeQuestions();


        return $this->render('AdminBundle:Admin:admin_create_offer.html.twig', array(
            'datos'=>$result,
            'projects'=>$projects,
            'categories'=>$categories            
        )
        );

    }



    public function getAllOfferAction(){

        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();

        $result = $this->user_comp->getPersonalsInfo();

       $projects = $this->getAllProjects();

        $offers =  $em->getRepository('AdminBundle:Offer' )->findAll();
		
		 //echo '<pre>';print_r($offers);die;

        foreach($offers as $item){
            $array['id'] = $item->getId();
            $array['title'] = $item->getTitle();
            $array['categoria'] = $item->getCategory()->getName();
            $array['budget'] = $item->getBudget();
            $array['description'] =$this->resumir($item->getDescription(),50," ",".") ;

            $result['offer'][] = $array;
        }

        return $this->render('AdminBundle:Default:view_all_offer.html.twig', array('datos'=>$result,'projects'=>$projects));
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

    public function getSubCategoryByIdAction(){

       // echo '<pre>';print_r($_POST);die;

        $em = $this->get('doctrine')->getManager();

        $subcateg = $em->getRepository('AdminBundle:SubCategory' )->findBy(array(
            'category'=>$_POST['id']
        ));


        foreach($subcateg as $item){
            $array['id'] = $item->getId();
            $array['description'] = $item->getDescription();

            $result[] = $array;
        }


        //echo '<pre>';print_r($result);die;


        $response = array("code" => 1, "success" => true,'mensaje'=>'OKKK!!!','result'=>$result);
        return new Response(json_encode($response));


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

    //get all Types Questions
    public function getTypeQuestions(){

        $em = $this->get('doctrine')->getManager();
        $types = $em->getRepository('AdminBundle:TypeQuestions' )->findAll();

        foreach($types as $item){

            $array['id'] = $item->getId();
            $array['type'] = $item->getType();

            $result[] = $array;
        }
        //echo '<pre>';print_r($result);die;
        return $result;

    }
	//get coordenates x user registered
    function getCoordinates($address){
        $address = urlencode($address);
        $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
        $response = file_get_contents($url);
        $json = json_decode($response,true);

        $lat = $json['results'][0]['geometry']['location']['lat'];
        $lng = $json['results'][0]['geometry']['location']['lng'];

        return array('latitud'=>$lat,'longitud'=>$lng);
    }

    //genertae code Team
    function generate_numbers($start, $count, $digits) {
        $result = array();
        for ($n = $start; $n < $start + $count; $n++) {
            $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);
        }
        return $result;
    }
   /* function randomString($length = 3) {
        $str = "";
        $characters = array_merge(range('A','Z'),range('0','9'));
        //$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }*/
    //get all Team
    public function listTeamAction(){

        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();

        $em = $this->get('doctrine')->getManager();
        $teams = $em->getRepository('UsuarioBundle:Team')->findAll();

        foreach($teams as $item){
            $data['idteam'] = $item->getId();
            $data['name'] = $item->getName().'_'.$item->getCode();
            $profiles = $item->getProfiles();
            foreach($profiles as $prof){

                $user_skills = $em->getRepository('UsuarioBundle:Skillusers')->findBy(array("userId"=>$prof->getId()));
                foreach($user_skills as $k=>$v){
                    $skill = $em->getRepository('UsuarioBundle:Skill')->find($v->getSkillId());
                    $skills[] = $skill->getNameSkill();
                }
            }
            $data['skills'] = $skills;					
            $user_profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array("user"=>$item->getUser()->getId()));
			if(is_object($user_profile))
				$data['leader'] = array('id'=>$user_profile->getUser()->getId(),'name'=>$user_profile->getName().' '.$user_profile->getLastname());

            $temp[] = $data;
        }
        $result = $this->user_comp->getPersonalsInfo();
        $result['team'] = $temp;
        $result["team_active"] = "active";
        $result["create_team_activite"] = "active";
        $projects = $this->getAllProjects();

        //echo '<pre>';print_r($result['team']);die;

        return $this->render('AdminBundle:Default:view_all_team.html.twig', array('datos'=>$result,'projects'=>$projects));

    }

    public function viewTeamAction($id){

        $em = $this->get('doctrine')->getManager();
        $team = $em->getRepository('UsuarioBundle:Team')->findOneBy(array('id'=>$id));

       //echo $team->getUser()->getId();die;

        $leader = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$team->getUser()->getId()));

        $data['name_team'] = $team->getName();
        $data['name_leader'] =  $leader->getName().' '.$leader->getLastname();

        foreach($team->getProfiles() as $profile){

            $formation = $em->getRepository('UsuarioBundle:Formation')->findOneBy(array('user'=>$profile->getUser()->getId()));
           // $education =(isset($formation))?$formation->getEducation():'N/A';
            $ocupation =(isset($formation))?$formation->getNameOcupation():'N/A';

           //echo '<pre>';print_r($formation);die;
            $temp [] = array(
                'iduser'=>$profile->getUser()->getId(),
                'name'=>$profile->getName().' '.$profile->getLastname(),
                'ocupation'=>$ocupation,
                'lat'=>$profile->getLatitud(),
                'lng'=>$profile->getLongitud(),
                'avatar'=>$profile->getAvatar()

            );
            $data['users'] = $temp;
        }
        //echo '<pre>';print_r($data);die;
        $result = $this->user_comp->getPersonalsInfo();
        $result['team'] = $data;
        $result["projects_active"] = "";
        $result["create_pro_activite"] = "";
        $result["create_emplo_activite"] = "";
        $result["list_emplo_activite"] = "";
        $result["create_offer_job"] = "";
        $result["offer_job"] = "";
        $result["view_offer_job"] = "";
        $result["employee_active"] = "";
        $result["team_active"] = "active";
        $result["create_team_activite"] = "active";
        $result["list_active"] = "";
        $result["display"] = "";
        $projects = $this->getAllProjects();

        foreach($result['team']['users'] as $user){
            $array[] = array(
                'name'=>$user['name'],
                'ocupation'=>$user['ocupation'],
                'avatar'=>$user['avatar'],
                'lat'=>$user['lat'],
                'lng'=>$user['lng']
            );
        }

       //echo '<pre>';print_r($array);die;
        $jsonencoder = new JsonEncoder();
        $jsonCoordenadas = $jsonencoder->encode($array,$format = 'json');

        return $this->render('AdminBundle:Default:view_team_id.html.twig', array(
            'datos'=>$result,
            'projects'=>$projects,
            'coordenadas'=>$jsonCoordenadas
        ));
    }

    //actualizar el Team
    public function updateTeamAction($id){
        $em = $this->get('doctrine')->getManager();
		$user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        if(!empty($_POST)){

             //echo '<pre>';print_r($_POST);die;
            $team = $em->getRepository('UsuarioBundle:Team')->find($id);
            //$user = $em->getRepository('UsuarioBundle:User' )->find($_POST['data']['user_leader']);

           //actualizar Team
            $team->setName($_POST['data']['name']);
            $team->setDescription($_POST['data']['description']);
            $team->setUser($user);

           foreach($_POST['data']['data_user'] as $user){
                $profile = $em->getRepository('UsuarioBundle:Profile' )->find($user);
                $team->addProfile($profile);
            }
            $em->persist($team);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'Ha inserito correttamente el team!!');
            return new Response(json_encode($response));
        }
        $team = $em->getRepository('UsuarioBundle:Team')->findOneBy(array('id'=>$id));
        $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$team->getUser()->getId()));

        $data['name'] = $team->getName();
        $data['description'] = $team->getDescription();
        $data['leader'] = array('id'=>$team->getUser()->getId(),'name'=>$profile->getName().' '.$profile->getLastname());

        $profiles = $team->getProfiles();
        foreach($profiles as $prof){
            $profile = $em->getRepository('UsuarioBundle:Profile')->find($prof->getId());
            $user_team[]= array('id'=>$prof->getUser()->getId(),'name'=>$prof->getName().' '.$prof->getLastname());

        }

       // echo '<pre>';print_r($user_team);die;
        $data['user'] = $user_team;
        $data['user_all'] = $this->getAllEmployes();

        $result = $this->user_comp->getPersonalsInfo();
        $result['team'] = $data;
        $projects = $this->getAllProjects();

       //echo '<pre>';print_r($result);die;
        return $this->render('AdminBundle:Admin:update_team.html.twig', array('datos'=>$result,'projects'=>$projects,'idteam'=>$id));

    }
    //eliminar los team!!!
    public function deleteTeamAction($id){
        $em = $this->get('doctrine')->getManager();
        $team = $em->getRepository('UsuarioBundle:Team')->find($id);
        $em->remove($team);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('list_team')
        );

    }

    //get all Team
    public function updateTeamUserAction($id){

        $em = $this->get('doctrine')->getManager();
        if(empty($_POST)){
            $teams = $em->getRepository('UsuarioBundle:Team')->findAll();
            foreach($teams as $item){
                $data['idteam'] = $item->getId();
                $data['name'] = $item->getName().'_'.$item->getCode();
                $profiles = $item->getProfiles();
                foreach($profiles as $prof){

                    $user_skills = $em->getRepository('UsuarioBundle:Skillusers')->findBy(array("userId"=>$prof->getId()));
                    foreach($user_skills as $k=>$v){
                        $skill = $em->getRepository('UsuarioBundle:Skill')->find($v->getSkillId());
                        $skills[] = $skill->getNameSkill();
                    }
                }
                $data['skills'] = $skills;
                $user_profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array("id"=>$item->getUser()->getId()));
                if(!empty($user_profile))
                     $data['leader'] = array('id'=>$item->getUser()->getId(),'name'=>$user_profile->getName().' '.$user_profile->getLastname()) ;
                else
                    $data['leader'] = 'Empty';

                $temp[] = $data;
            }
           // $result['team'] = $temp;
        }else{
           // echo '<pre>';print_r($_POST);die;
            $team = $em->getRepository('UsuarioBundle:Team')->find($_POST['data']['id_team']);
           // echo $id;die;
            $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$id));
            if(isset($team) && isset($profile)){
                $team->addProfile($profile);
                $em->persist($team);
                $em->flush();

                $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
                return new Response(json_encode($response));

            }else{
                $response = array("code" => 0, "success" => false,'mensaje'=>'Errooorrr!!!');
                return new Response(json_encode($response));
            }

        }
        $result =  $this->user_comp->getPersonalsInfo();
        $result["projects_active"] = "";
        $result["create_pro_activite"] = "";
        $result["create_emplo_activite"] = "";
        $result["list_emplo_activite"] = "";
        $result["create_offer_job"] = "";
        $result["offer_job"] = "";
        $result["view_offer_job"] = "";
        $result["employee_active"] = "";
        $result["team_active"] = "active";
        $result["create_team_activite"] = "active";
        $result["list_active"] = "";
        $result["display"] = "";
        $result['team'] = $temp;
        $projects = $this->getAllProjects();
        //echo '<pre>';print_r($result);die;

        return $this->render('AdminBundle:Admin:update_team_user.html.twig', array('datos'=>$result,'projects'=>$projects,'user_id'=>$id));
    }

    public function getUserTeamByIdAction($id){

        $em = $this->get('doctrine')->getManager();
        $team = $em->getRepository('UsuarioBundle:Team')->find($id);
        $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$team->getUser()->getId()));

        $data["id"] = $team->getUser()->getId();
        $data["name"] = $profile->getName().' '.$profile->getLastname();

        $response = array("code" => 1, "success" => true,'data'=>$data);
        return new Response(json_encode($response));

    }
}
