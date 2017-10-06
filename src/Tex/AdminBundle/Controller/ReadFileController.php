<?php

namespace Tex\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tex\AdminBundle\Entity\Member;
use Tex\UsuarioBundle\Entity\User;
use Tex\UsuarioBundle\Entity\Profile;
use Tex\UsuarioBundle\Entity\Formation;
use Tex\UsuarioBundle\Entity\Project;
use Tex\UsuarioBundle\Entity\Skill;
use Tex\UsuarioBundle\Entity\Skillusers;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Utils\UserComponent;

class ReadFileController extends Controller
{
	var $user_comp;
	
	public function __construct(){
		$this->user_comp = new UserComponent($this);
    }
    public function loadFileAction(){

        $em = $this->get('doctrine')->getManager();
        $u = $this->get('security.context')->getToken()->getUser();
        $id = $u->getId();
        //contar numero de user
        // $projects = $em->getRepository('ProyectoBundle:Project' )->findAll();
        $result = $this->user_comp->getPersonalsInfo($id);
        $projects = $this->getAllProjects();
        //$messages =  $this->getMsgUser();
        //echo '<pre>';print_r($result['coordenadas']);die;

         //$jsonencoder = new JsonEncoder();
       //$jsonCoordenadas = $jsonencoder->encode($result['coordenadas'],$format = 'json');

        //echo '<pre>';print_r($jsonCoordenadas);
        return $this->render('AdminBundle:Admin:load_file.html.twig', array(
            'datos'=>$result,
            'projects'=>$projects,
            'coordenadas'=>""
            //'messages'=>$messages
        ));

    }
	    //get all coordenadas
    function getAllCoordenate(){

        $em = $this->get('doctrine')->getManager();
        $users = $em->getRepository('UsuarioBundle:User')->findAll();

        foreach($users as $user){
            $array[] = array('lat'=>$user->getLatitud(),'lng'=>$user->getLongitud());
        }

        return $array;
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
        $result['name'] = $profile->getName();
        $result['lastname'] = $profile->getLastname();
        $result['fullname'] = $profile->getName().' '.$profile->getLastname();
        $result['country'] =  $profile->getCountry();
        $result["avatar"] = $profile->getAvatar();
        $result['phone'] = $profile->getPhone();
        $result['mobile'] = $profile->getMobile();
        $result['address'] = $profile->getAddrees();
        $result['birthday'] = $profile->getBirthday();
		$result['coordenadas'] = $this->getAllCoordenate();
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

    function uploadFile($file = array()){

        $allowedExtensions = array('csv');

        $flag = $this->searchExt($allowedExtensions,$this->extension($file['file']['name']));
        if($flag){

            //$dir = $_SERVER["DOCUMENT_ROOT"].'/GestionTex/web/upload/files/';
            $dir = $_SERVER["DOCUMENT_ROOT"].'/web/upload/files/';
            // echo $dir;die;
            /* if(!is_dir($dir)){
                 echo 1;
             }*/
            //  mkdir($dir,0777);
            // echo 'Directory';die;
			
			if(file_exists($dir.'/'.$file['file']['name'])) {
				chmod($dir.'/'.$file['file']['name'],0755); //Change the file permissions if allowed
				unlink($dir.'/'.$file['file']['name']); //remove the file
			}
			move_uploaded_file($file['file']['tmp_name'],$dir.'/'.$file['file']['name']);
			
           /* if(move_uploaded_file($file['file']['tmp_name'],$dir.'/'.$file['file']['name'])){
                // echo 111;
                sleep(3);
            }*/

        }

        $file = $dir.''.$file['file']['name'];

        return $file;

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

    public function uploadFileDataAction(){

         // echo '<pre>';print_r($_FILES);die;
        if(!empty($_FILES)){
           $dir =  $this->uploadFile($_FILES);
            $em = $this->get('doctrine')->getManager();

           // echo $dir;die;
            $csvFile = file("".$dir."");
            $data = [];
            foreach ($csvFile as $line) {
                $data[] = str_getcsv($line);
            }

           // echo '<pre>';print_r($data);die;

            for($i =1;$i<count($data);$i++){
                $member = new Member();

                
                $obj = $em->getRepository('AdminBundle:Member')->findOneBy(array(
                    'name'=>$data[$i][0]
                ));

                if(!isset($obj)){

                    $member->setName($data[$i][0]);
                    $member->setEmail($data[$i][2]);
                    $member->setCountry($data[$i][5]);
                    $member->setProfession($data[$i][13]);

                    $em->persist($member);
                    $em->flush();
                }

            }
            //echo count($data)-1;die;

            $response = array("code" => 1);
            return new Response(json_encode($response));
        }

    }
	
	public function getFileUrl(){

       $username = "te.x@tecnolav.it";
        $password = "tecnolav2016" ;

        $output_filename = $_SERVER["DOCUMENT_ROOT"]."/web/upload/files/file_member.csv";
		
		//echo $output_filename;die;

        $host = "http://www.community.collengworld.com/main/membership/downloadMemberData?type=";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        //curl_setopt($ch, CURLOPT_REFERER, "http://www.xcontest.org");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        //curl_close($ch);

       //print_r($result); // prints the contents of the collected file before writing..


        // the following lines write the contents to a file in the same directory (provided permissions etc)
        $fp = fopen($output_filename, 'w');
        fwrite($fp, $result);
        fclose($fp);

    }

    public function insertDataUserAction(){

        // echo $dir;die;
        $em = $this->get('doctrine')->getManager();
        $u = $this->get('security.context')->getToken()->getUser();
        $id = $u->getId();

        //make curl from collengworld
        $this->getFileUrl();
        $dir = $_SERVER["DOCUMENT_ROOT"].'/web/upload/files/file_member.csv';
        $result = $this->user_comp->getPersonalsInfo($id);
        $projects = $this->getAllProjects();
        $csvFile = file("".$dir."");
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }
		
        //echo '<pre>';print_r($data);die;
		//echo 'Cont---'.count($data);die;
        if(!empty($data)){
            for($i =1;$i<count($data);$i++){
                $user = new User();
				  $profile = new Profile();

                $obj = $em->getRepository('UsuarioBundle:User')->findOneBy(array(
                    'username'=>$data[$i][2]
                ));
				
				$categ_work =  explode(':',$data[$i][13]);
				if(!empty($categ_work)){
					$name_cat = $categ_work[0];
				}else{
					$name_cat = 'Volunteer';
				}
				
                 $category = $em->getRepository('UsuarioBundle:CategWork')->findOneBy(array('name'=>$name_cat));
				// echo '<pre>';print_r($category);die;

                if(!isset($obj)){
                    /*Datos User*/
                    $user->setUsername($data[$i][2]);
                    $user->setEmail($data[$i][2]);
                    //Codificacion del password
                    $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                    $password = $encoder->encodePassword($data[$i][2], $user->getSalt());
                    $user->setPassword($password);
                    $user->setCategoriaWork($category);
                    $em->persist($user);
                    //$em->flush();

                    //Update Profile
                    $cadena ="".$data[$i][4].",".$data[$i][5]."";
                    $coordenadas = $this->getCoordinates($cadena);

                    $birthday = explode('-', $data[$i][8]);
                    $cadena_name = explode(' ',$data[$i][0]);
					
					 if(!empty( $data[$i][18]))
                        $data_sskill = explode(',', $data[$i][18]);
						
					

                    $profile->setUser($user);
                    $profile->setName($cadena_name[0]);
                    $profile->setLastname($cadena_name[1]);
                    //$profile->setBirthday(new \DateTime("$birthday[2]-$birthday[1]-$birthday[0]"));
                    $profile->setBirthday(new \DateTime('now'));
                    $profile->setCountry($data[$i][5]);
                    $profile->setCity($data[$i][4]);
                    $profile->setPhone('S/N');
                    $profile->setMobile('S/N');
                    $profile->setAddrees($data[$i][14].','.$data[$i][5]);
                    $profile->setLatitud($coordenadas['latitud']);
                    $profile->setLongitud($coordenadas['longitud']);

                    $em->persist($profile);
                    $em->flush();
					
					
					  /*Create the skills */
                  foreach($data_sskill as $value){
                        $obj_skill = new Skill();
                        $obj_skill->setNameSkill($value);
                        $em->persist($obj_skill);
                        $em->flush();

                        $user_skill = new Skillusers();
                        $user_skill->setUserId($user->getId());
                        $user_skill->setSkillId($obj_skill->getId());

                        $em->persist($user_skill);
                        //Insertarmos en la base de datos
                        $em->flush();
                    }
					
					//formation
					if(!empty($data[$i][17])){
						$formation = new Formation();						
						$formation->setUser($user);
						$formation->setNameOcupation($data[$i][17]);
						$formation->setAbstract($data[$i][21]);
						$formation->setFile('');

						$em->persist($formation);
						$em->flush();
						
						
					}
						
                }

            }
			
			 $response = array("code" => 1, "success" => true,'mensaje'=>'OK!!!');
            return new Response(json_encode($response));
        }        

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


}
