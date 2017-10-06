<?php
namespace Tex\UsuarioBundle\Controller;

use Tex\UsuarioBundle\Entity\User;
use Tex\UsuarioBundle\Entity\Profile;
use Tex\AdminBundle\Entity\UserTest;
use Tex\UsuarioBundle\Entity\Skill;
use Tex\UsuarioBundle\Entity\Formation;
use Tex\UsuarioBundle\Entity\Skillusers;
use Tex\UsuarioBundle\Entity\CategWork;
use Tex\UsuarioBundle\Entity\Paises;
use Tex\UsuarioBundle\Entity\Ciudades;
use Tex\ProyectoBundle\Entity\Project;
use Tex\AdminBundle\Entity\EvaluateTest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Utils\UserComponent;


class UsuarioController extends Controller
{
    var $user_comp;

    public function __construct(){
        $this->user_comp = new UserComponent($this);
    }

    public function createUserAction(Request $request){
	//get all countries
        $em = $this->get('doctrine')->getManager();
        if($request->isMethod('POST')){
            //echo '<pre>';print_r($_POST);die;
            $em = $this->get('doctrine')->getManager();
            $user = new User();
            /*Datos User*/
			$category = $em->getRepository('UsuarioBundle:CategWork')->find(1);
            $user->setUsername($request->get('email'));
            $user->setEmail($request->get('email'));
            $user->setCategoriaWork($category);
            //Codificacion del password
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $password = $encoder->encodePassword($request->get('password'), $user->getSalt());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
			
		    $actual_link = "http://$_SERVER[HTTP_HOST]/demo/"."active_account/?id=" . $user->getId();

            //enviar email de activación de cuenta
            $msg = array(
                'date'=>date('l').','.date('d').' '.date('F'),
                'message'=>"Active Account ".$actual_link."");

            $email = \Swift_Message::newInstance()
                ->setSubject("Register User")
                ->setFrom("tex07556@gmail.com")
                ->setTo("".$request->get('email')."")
                ->setBody($this->renderView('UsuarioBundle:Default:email_client.html.twig',array('data_client'=>$msg)),
                    'text/html');

            $id = $user->getId();
           $this->get('mailer')->send($email);



            return $this->redirect($this->generateUrl('User_index'));
        }
        return $this->render('UsuarioBundle:Usuario:create_user.html.twig', array());
    }

    public function editProfileAction(Request $request){
        $u = $this->get('security.context')->getToken()->getUser();
        $id = $u->getId();

       if(!empty($_POST)){
           $em = $this->get('doctrine')->getManager();
          //echo '<pre>';print_r($_POST);die;
           $user = $em->getRepository('UsuarioBundle:User')->find($_POST['data']['iduser']);
           $category = $em->getRepository('UsuarioBundle:CategWork')->find($_POST['data']['category']);
           $profile = new Profile();

           $user->setEmail($_POST['data']['email']);
           $user->setCategoriaWork($category);

           $birthday = explode('/', $_POST['data']['birthday']);
		   
		    //echo 'Hola '.$ciudad->getCiudad();die;
            $cadena ="".$_POST['address'].",".$_POST['data']['city'].",".$_POST['data']['country']."";
            $coordenadas = $this->getCoordinates($cadena);

           //echo '<pre>';print_r($coordenadas);die;

           $profile->setUser($user);
           $profile->setName($_POST['data']['name']);
           $profile->setLastname($_POST['data']['lastname']);
           $profile->setBirthday(new \DateTime("$birthday[2]-$birthday[1]-$birthday[0]"));
           $profile->setCountry($_POST['data']['country']);
           $profile->setCity($_POST['data']['city']);
           $profile->setPhone($_POST['data']['phone']);
           $profile->setMobile($_POST['data']['mobile']);
           $profile->setAddrees($_POST['data']['address']);
           $profile->setLatitud($coordenadas['latitud']);
           $profile->setLongitud($coordenadas['longitud']);


           /*Actualizo usuario y profile*/
           $em->persist($user);
           $em->flush();

           $em->persist($profile);
           $em->flush();
           $response = array("code" => 1, "success" => true,'mensaje'=>'Profilo aggiornò correttamente!!!');
           return new Response(json_encode($response));


       }else{
           $result =$this->user_comp->getPersonalsInfo($id);
           $projects = $this->getProjectById($id);
           $result["projects_active"] = "";
           $result['iduser'] = $id;
           $result["create_pro_activite"] = "";
           $result["create_emplo_activite"] = "";
           $result["list_emplo_activite"] = "active";
           $result["employee_active"] = "active";
           $result["team_active"] = "";
           $result["create_team_activite"] = "";
           $result["create_team_active"] = "";
           $result["list_active"] = "";
           $result["create_offer_job"] = "";
           $result["view_offer_job"] = "";
           $result["offer_job"] = "";
           $result["display"] = "";
           $messages = $this->getMsgUser();

           return $this->render('UsuarioBundle:Usuario:edit_profile.html.twig', array(
               'datos'=>$result,
               'projects'=>$projects,
               'categories'=>$this->getCategories($id),
               'messages'=>$messages
           ));

       }


    }
	
	public function getCategories($id){

        $em = $this->get('doctrine')->getManager();
        $categories = $em->getRepository('UsuarioBundle:CategWork')->findAll();

        foreach($categories as $item){
            $data['id'] = $item->getId();
            $data['name'] = $item->getName();
            $data['selected'] =($item->getId()==$id)?'selected':'';

            $opts[] = $data;
        }
        //echo '<pre>';print_r($opts);die;
        return $opts;

    }

    public function viewProfileAction(Request $request){
        $id = $request->get('id');
       // $result2 = $this->getPersonalsInfo($id);
		$em = $this->get('doctrine')->getManager();
	    $user_s = $em->getRepository('UsuarioBundle:User')->find(6123);
		//echo '<pre>';print_r($user_s);die;
		$categ_work = $em->getRepository('UsuarioBundle:CategWork')->find($user_s->getCategoriaWork());
        $result2 = $this->user_comp->getPersonalsInfo($id);
        $formation = $this->getFormationInfo($id);
        $result2["occupation"] = $formation["occupation"];
        $result2["abstract"] = $formation["abstract"];
        $result2["skills"] = $formation["skills"];
        $result2["categoria"] = $categ_work->getName();
		
        $result2["skills"] = $formation["skills"];
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();        
        $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$id));
        $result = $this->user_comp->getPersonalsInfo();
        $result["name"] = $profile->getName();
        $result["avatar"] = $profile->getAvatar();
        /*$result["profile_active"] = "";
        $result["create_pro_activite"] = "";
        $result["formation_active"] = "";
        $result["create_emplo_activite"] = "";*/
        $result["list_emplo_activite"] = "active";

        $projects = $this->getProjectById($id);
		//echo '<pre>';print_r($result2);die;
        return $this->render('UsuarioBundle:Usuario:view_profile.html.twig', array('user'=>$result2, 'datos'=>$result, 'projects'=>$projects));
    }

    /*actualizar profile*/
    public function updateProfileAction(){
        if(!empty($_POST)){
            //echo '<pre>';print_r($_POST['data']);die;
            $em = $this->get('doctrine')->getManager();
            $u = $this->get('security.context')->getToken()->getUser();
            $id = $u->getId();
            $user = $em->getRepository('UsuarioBundle:User' )->find($id);
            $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array(
                'user'=>$user->getId()
            ));
			
			$cadena ="".$_POST['data']['address'].",".$_POST['data']['city'].",".$_POST['data']['country']."";
            $coordenadas = $this->getCoordinates($cadena);
			
			 $pais =  $em->getRepository('UsuarioBundle:Paises')->findOneBy(array(
                'codigo'=>$_POST['data']['country']
            ));

            $profile->setName($_POST['data']['name']);
            $profile->setLastname($_POST['data']['lastname']);
            $profile->setBirthday($_POST['data']['birthday']);
            $profile->setCountry($pais->getCodigo());
            $profile->setCity($_POST['data']['city']);
            $profile->setPhone($_POST['data']['phone']);
            $profile->setMobile($_POST['data']['mobile']);
            $user->setEmail($_POST['data']['email']);
            $user->setCategory($_POST['data']['category']);
            $user->setLatitud($coordenadas['latitud']);
            $user->setLongitud($coordenadas['longitud']);
            $profile->setAddrees($_POST['data']['address']);
            $em->persist($user);
            $em->flush();

            $em->persist($profile);
            $em->flush();
            $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
            return new Response(json_encode($response));
        }
    }

    /*Matching Password from Database*/
    public function matchingPasswordAction(){
        if(!empty($_POST)){
            $em = $this->get('doctrine')->getManager();
            $user = $em->getRepository('UsuarioBundle:User')->findOneBy(array(
                'password'=>$_POST['data']['pawd_old']
            ));

            if(!isset($user)){
                $response = array("code" => 0, "success" => false,'mensaje'=>'Password not found');
                return new Response(json_encode($response));
            }
        }
        return new Response(json_encode(array('code'=>1)));
    }

    /*Change password*/
    public function changePasswordAction(){
        $u = $this->get('security.context')->getToken()->getUser();
        $id = $u->getId();
        if(!empty($_POST)){

            //echo '<pre>';print_r($_POST['data']);die;
            $em = $this->get('doctrine')->getManager();
            $user = $em->getRepository('UsuarioBundle:User')->find($id);

            //Codificacion del password
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $password = $encoder->encodePassword($_POST['data']['password'], $user->getSalt());
            $user->setPassword($password);
            //Persistimos en el objeto
            $em->persist($user);
            //Insertarmos en la base de datos
            $em->flush();
            $response = array("code" => 1, "success" => true,'mensaje'=>'Update!!!');
            return new Response(json_encode($response));
        }
    }

    public function editFormationAction(Request $request){
        //$result = $this->user_comp->getPersonalsInfo();
        $formation = $this->getFormationInfo();
        $u = $this->get('security.context')->getToken()->getUser();
        $id = $u->getId();
        $projects = $this->getProjectById($id);
        $result = $this->user_comp->getPersonalsInfo($id);
        $result["occupation"] = $formation["occupation"];
       // $result["education"] = $formation["education"];
       // $result["start"] = $formation["start"];
       // $result["end"] = $formation["end"];
        $result["abstract"] = $formation["abstract"];
        $result["skills"] = $formation["skills"];
        $result["file"] =(isset( $formation["file"]))?$formation["file"]:'';
        $result['idformation'] =$formation['idformation'];


       //echo '<pre>';print_r($result);die;
        return $this->render('UsuarioBundle:Usuario:edit_formation.html.twig', array('datos'=>$result, 'projects'=>$projects));
    }

    public function updateFormationAction(){
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        $em = $this->get('doctrine')->getManager();
        if(!empty($_POST)){
            if($_POST['idformation']!='undefined'){
                $formation = $em->getRepository('UsuarioBundle:Formation')->find($_POST['idformation']);
            }else{
                $formation = new Formation();
            }
                $formation->setUser($user);
                $formation->setNameOcupation($_POST['ocupation']);
                $formation->setAbstract($_POST['abtract']);
                $formation->setFile($_FILES['file']['name']);

                $em->persist($formation);
                $em->flush();

                $this->uploadFile($_FILES);

                $id_formation = $formation->getId();

                $skills =   explode(",", $_POST['skill']);

                /*Create the skills */
                foreach($skills as $value){
                    $obj_skill = new Skill();
                    $obj_skill->setNameSkill($value);
                    $em->persist($obj_skill);
                    $em->flush();

                    $user_skill = new Skillusers();
                    $user_skill->setUserId($id);
                    $user_skill->setSkillId($obj_skill->getId());

                    $em->persist($user_skill);
                    //Insertarmos en la base de datos
                    $em->flush();
                }
                $response = array("code" => 1, "success" => true,'mensaje'=>'La formazione fu aggiornata correttamente','id_formation'=>$id_formation);
                return new Response(json_encode($response));
        }
    }

    public function editAvatarAction(Request $request){
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        $em = $this->get('doctrine')->getManager();
        $projects = $this->getProjectById($id);
        $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$id));
        $result =$this->user_comp->getPersonalsInfo($id);
       // $result['name'] = $profile->getName();
        //$result["avatar"] = $profile->getAvatar();
        $result["crop"] = 0;
        $result["width"] = "";
        $result["height"] = "";
        $result["imgcrop"] = "";

        return $this->render('UsuarioBundle:Usuario:edit_avatar.html.twig', array('datos'=>$result, 'projects'=>$projects));
    }

    public function uploadAvatarAction(Request $request){
        //print_r($_FILES); die;
        $ddir = $_SERVER["DOCUMENT_ROOT"].$request->get("imgurl").'avatars/large/';
        $user = $this->get('security.context')->getToken()->getUser();
        $tmpn = $_FILES["pfile"]["tmp_name"];
        $name = $user->getSalt();
        $tmps = explode(".", $_FILES["pfile"]["name"]);
        $_ext = ".".$tmps[count($tmps)-1];
        $temp = $ddir.$name.$_ext;
        if (move_uploaded_file($tmpn, $temp)){
            $projects = $this->getProjectById($user->getId());
            list($ancho, $alto) = getimagesize($temp);
            //29x29
            $ntemp = $_SERVER["DOCUMENT_ROOT"].$request->get("imgurl").'avatars/mini/';
            $mini = $this->resize_image($temp, 29, 29);
            imagejpeg($mini, $ntemp.$name.$_ext);
            //Gardar en BD
            $em = $this->get('doctrine')->getManager();
            $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$user->getId()));
            if (!$profile){
                $profile = new Profile();
                //$profile->setId($user->getId());
            }
            $profile->setAvatar($name.$_ext);
            //$profile->setCurriculum("");
            //print_r($profile); die;
            $em->persist($profile);
            //Insertarmos en la base de datos
            $em->flush();
            $result = $this->user_comp->getPersonalsInfo();
            $result["avatar"] = $name.$_ext;
            $result["imgcrop"] = $name.$_ext;
            $result["crop"] = 0;
            $result["projects_active"] = "";
            $result["create_pro_activite"] = "";
            $result["create_emplo_activite"] = "";
            $result["list_emplo_activite"] = "active";
            $result["employee_active"] = "active";
            $result["team_active"] = "";
            $result["create_team_activite"] = "";
            $result["create_team_active"] = "";
            $result["list_active"] = "";
            $result["create_offer_job"] = "";
			 $result["view_offer_job"] = "";
            $result["offer_job"] = "";
            $result["display"] = "";

            if ($ancho != $alto && $ancho > 200){
                $result["crop"] = 1;
                $result["width"] = $ancho;
                $result["height"] = $alto;

                return $this->render('UsuarioBundle:Usuario:edit_avatar.html.twig', array('datos'=>$result, 'projects'=>$projects));
            }
            return $this->render('UsuarioBundle:Usuario:edit_profile.html.twig', array('datos'=>$result, 'projects'=>$projects));
        }
    }

    public function cropImageAction(Request $request){
        $ddir = $_SERVER["DOCUMENT_ROOT"].$_POST['data']['imgurl'].'avatars/';
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine')->getManager();
        $profile = $em->getRepository('UsuarioBundle:Profile')->find($user->getId());
        $name = $profile->getAvatar();
        $templ = $ddir.'large/'.$name;
        $tempm = $ddir.'mini/'.$name;
        $w = $_POST['data']['w'];
        $h = $_POST['data']['h'];
        $param["x1"] = $_POST['data']['x1'];
        $param["x2"] = $_POST['data']['x2'];
        $param["y1"] = $_POST['data']['y1'];
        $param["y2"] = $_POST['data']['y2'];
        $large = $this->resize_image($templ, $w, $h, $param);
        imagejpeg($large, $templ);
        $mini  = $this->resize_image($templ, 29, 29);
        imagejpeg($mini, $tempm);
        $response = array("code" => 1);
        return new Response(json_encode($response));
    }

    //list of all users
    public function listAllUserAction(){
        $result = array();
        $em = $this->get('doctrine')->getManager();
        $users = $em->getRepository('UsuarioBundle:Profile')->findAll();		
        $myuser = $this->get('security.context')->getToken()->getUser();
        $myid = $myuser->getId();
		$array_users = array();	

        foreach($users as $user){
             $id = $user->getUser()->getId();
           // echo '<pre>';print_r($user->getUser());die;
            $team = isset($user->getTeams()[0])?$user->getTeams()[0]->getName().'_'.$user->getTeams()[0]->getCode():"";

           // echo $user->getUser()->getCity();die;
            if ($myid != $id){
			//echo $user->getId();die;
                $array_users['iduser'] = $id;
                $array_users['fullname'] = $user->getName().' '.$user->getLastname();
                $array_users['email'] = $user->getUser()->getEmail();
                $array_users['rol'] = $this->getNameRol($user->getUser()->getRoles()[0]);
                $array_users['location'] = $user->getCity().','.$user->getCountry();
                $array_users['team'] = $team;
                $formation = $em->getRepository('UsuarioBundle:Formation')->find($id);
                if ($formation)
                    $array_users['occupation'] = $formation->getNameOcupation();
                else
                    $array_users['occupation'] = "N/A";
                $array_users["avatar"] = $user->getAvatar();
                $result[] = $array_users;
            }
        }
        //$profile = $em->getRepository('UsuarioBundle:Profile')->find($myid);
       //$profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$myid));
        $result2 =$this->user_comp->getPersonalsInfo($myid);
       // $result2["avatar"] = $profile->getAvatar();
       // $result2["name"] = $profile->getName();
        $projects = $this->getAllProjects();
        //echo '<pre>';print_r($result);
       // echo '<pre>';print_r($result2);die;
        return $this->render('UsuarioBundle:Usuario:list_user.html.twig', array('datos'=>$result2,'users'=>$result, 'projects'=>$projects));
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

    public function calendarAction(){
        $result = "";
        return $this->render('UsuarioBundle:Usuario:calendar.html.twig', array('datos'=>$result));
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

    private function getFormationInfo($iduser=0){
        if ($iduser)
            $id = $iduser;
        else{
            $user = $this->get('security.context')->getToken()->getUser();
            $id = $user->getId();
        }
        $skills = "";
        $em = $this->get('doctrine')->getManager();
        $formation = $em->getRepository('UsuarioBundle:Formation')->findOneBy(array('user'=>$id));
		
        $user_skills = $em->getRepository('UsuarioBundle:Skillusers')->findBy(array("userId"=>$id));
		//echo '<pre>';print_r($user_skills);die;
        foreach($user_skills as $k=>$v){
            $skill = $em->getRepository('UsuarioBundle:Skill')->find($v->getSkillId());
            $skills .= $skill->getNameSkill().", ";
        }
        if ($formation){
		
            $result["idformation"] = $formation->getId();
            $result["occupation"] = $formation->getNameOcupation();
            //$result["education"] = $formation->getEducation();
           // $result["start"] = $formation->getStartDate()->format('d/m/Y');
            //$result["end"] = $formation->getEndDate()->format('d/m/Y');
            $result["abstract"] = $formation->getAbstract();
            $result["skills"] = substr($skills, 0, strlen($skills)-2);
            $result["file"] = $formation->getFile();
        }
        else{
            $result["occupation"] = "";
            $result["education"] = "";
            $result["start"] = "";
            $result["end"] = "";
            $result["abstract"] = "";
            $result["skills"] = "";
        }
        return $result;
    }

    private function resize_image($file, $w, $h, $param=array()) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        $dx = $dy = $sx = $sy = 0;
        if ($param) {
            $dx = 0;
            $dy = 0;
            $sx = $param["x1"];
            $sy = $param["y1"];
            $width = $w;
            $height = $h;
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, $dx, $dy, $sx, $sy, $newwidth, $newheight, $width, $height);
        return $dst;
    }

    public function getProjectById($id){

        $em = $this->get('doctrine')->getManager();
        $projects = $em->getRepository('ProyectoBundle:Project')->findBy(array(
                'user'=>$id
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




    /*Upload CV*/

    function uploadFile($file = array()){

        $allowedExtensions = array('pdf','doc','docx','odt');

        $flag = $this->searchExt($allowedExtensions,$this->extension($file['file']['name']));
        if($flag){

            $dir = $_SERVER["DOCUMENT_ROOT"].'/GestionTex/web/upload/files';
           // echo $dir;die;
           /* if(!is_dir($dir)){
                echo 1;
            }*/
              //  mkdir($dir,0777);

           // echo 'Directory';die;
            if(move_uploaded_file($file['file']['tmp_name'],$dir.'/'.$file['file']['name'])){
               // echo 111;
                sleep(3);
            }

        }

    }
    function searchExt($array,$filename){
        $esta = false;
        for($i = 0;$i<count($array) && !$esta;$i++){
            if($array[$i]== $filename){
                $esta = true;
            }
        }
        return $esta;
    }

    function extension($filename){

        return substr(strrchr($filename, '.'), 1);
    }

    public function getMsgUser(){

        $em = $this->get('doctrine')->getManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();

        $messages = $em->getRepository('UsuarioBundle:Chat')->findAll();

        $array = array();

        foreach($messages as $item){

            $userdata = $em->getRepository('UsuarioBundle:Profile' )->findBy(array('user'=>$item->getUserId()));

            //echo '<pre>';print_r($userdata);die;
            $array[] = array(
                'name'=>$userdata[0]->getName().' '.$userdata[0]->getLastname(),
                'avatar'=>$userdata[0]->getAvatar(),
                'text'=>$item->getText(),
                'chatdate'=>$item->getChatdate(),

            );

        }
		
		 //echo '<pre>';print_r($array);die;

        return $array;

        //echo '<pre>';  print_r($array);die;


    }

        /* Carga el CV y
     * Envia un mensaje al Admon
     * y al user
     * */
    public function uploadFileFrontendAction(){


       // echo '<pre>';print_r($_POST);die;

        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
		
		$temp = array();
        $questions = preg_split("/[\s,]+/", $_POST['questions']);


      // echo '<pre>';print_r($questions);


        if(!empty($questions[0])){

            foreach($questions as $item){

                // echo $item;die;
                $answers = array();

                $answer = $em->getRepository('AdminBundle:Answers')->find($item);

               // echo '<pre>';print_r($answer);die;

                if(isset($answers)){

                    $answers['id_question'] = $answer->getQuestions()->getId();
                    $answers['id_anwers'] = $item;
                    $answers['iscorrect'] = ($answer->getIscorrect()==1)?true:false;

                }
                //echo '<pre>';print_r($answers);die;
                $temp[] = $answers;

            }

        }
        $array_temp['id_test'] = $_POST['id_test'];
        $array_temp['answers'] = $temp;

       // echo '<pre>';print_r($array_temp);


        $formation = $em->getRepository('UsuarioBundle:Formation' )->findOneBy(array(
            'user'=> $id
            )
        );


        if(!empty($_FILES)){

            //echo 'Akiii';die;

            $formation->setFile($_FILES['file']['name']);
            $em->persist($formation);
            $em->flush();

            $this->uploadFile($_FILES);

            $email_dato = $user->getEmail();

            $user_profile = $em->getRepository('UsuarioBundle:Profile' )->findOneBy(array(
                    'user'=> $id
                )
            );

            //create data user
            $name = $user_profile->getName().' '.$user_profile->getLastname();
            $name_offer = $_POST['name_offer'];

            $msg1 = array(
                'date'=>date('l').','.date('d').' '.date('F'),
                'name'=>$name,
                'offer'=> $name_offer,
                'test_result'=>$this->getResultTest($array_temp),
                'message'=>$_POST['message']
            );
            $msg2 = array(
                'date'=>date('l').','.date('d').' '.date('F'),
                'message'=>"Abbiamo ricevuto la sua candidatura per l’offerta:
                " .$name_offer. " a breve sarà contattato da un nostro commerciale"

            );

            //send email
		$dir = $_SERVER["DOCUMENT_ROOT"].'/web/upload/files/'.$_FILES['file']['name'];
        $email1 = \Swift_Message::newInstance()
            ->setSubject("".$name_offer."")
            ->setFrom("".$email_dato."")
            ->setTo("tex07556@gmail.com")
            ->setBody(
                $this->renderView(
                    'UsuarioBundle:Default:email_client_curriculum.html.twig',
                    array('data_client'=>$msg1)
                ),
                'text/html'
            )
			->attach(\Swift_Attachment::fromPath(''.$dir.'')
			);

        $email2 = \Swift_Message::newInstance()
            ->setSubject("".$name_offer."")
            ->setFrom('tex07556@gmail.com')
            ->setTo("".$email_dato."")            
            ->setBody($this->renderView('UsuarioBundle:Default:email_client_offer.html.twig',array('data_client'=>$msg2)),
                'text/html');

            if( $this->get('mailer')->send($email1) && $this->get('mailer')->send($email2)){
				
				$test = $em->getRepository('AdminBundle:Test' )->find($_POST['id_test']);

                $usertest = new UserTest();

                $usertest->setUser($user);
                $usertest->setTest($test);

                $em->persist($usertest);
                //Insertarmos en la base de datos
                $em->flush();
                $response = array("code" => 1);
                return new Response(json_encode($response));
            }

        }else{
		
			$test = $em->getRepository('AdminBundle:Test' )->find($_POST['id_test']);
			
            $usertest = new UserTest();

            $usertest->setUser($user);
            $usertest->setTest($test);

            $em->persist($usertest);
            //Insertarmos en la base de datos
            $em->flush();
            $response = array("code" => 0);
            return new Response(json_encode($response));
        }

    }

    public  function getResultTest($answers){


         $value = 0;
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();

        $test = $em->getRepository('AdminBundle:Test' )->find($answers['id_test']);

        if(!empty($answers)){          

            $long = count($answers['answers']);
            $cont =0;

            for($a = 0;$a<$long;$a++){

                if($answers['answers'][$a]['iscorrect']==1)
                    $cont++;
            }

            //calcular el promedio de preg acertadas
            $promedio = ($cont*100)/$long;

            $value = ($promedio >50)?5:2;

            $evaluaTest = new EvaluateTest();

            $evaluaTest->setTest($test);
            $evaluaTest->setUser($user);
            $evaluaTest->setValue($value);

            $em->persist($evaluaTest);
            //Insertarmos en la base de datos
            $em->flush();

        }

        return $value;
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
	
	 //get return city x id Country
    function getCityByCountryAction($code){
        $em = $this->get('doctrine')->getManager();
        $cities =  $em->getRepository('UsuarioBundle:Ciudades')->findBy(array('paisesCodigo'=>$code));
        foreach($cities as $item){
            $data['idciudad'] = $item->getIdciudades();
            $data['ciudad'] = $item->getCiudad();

            $result[] = $data;
        }

        if(!empty($result)){
            $response = array("code" => 1, "success" => true,'mensaje'=>'OKKK!!!','result'=>$result);
            return new Response(json_encode($response));
        }else{
            $response = array("code" => 0, "success" => false,'mensaje'=>'Not Found');
            return new Response(json_encode($response));
        }


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


	

}
