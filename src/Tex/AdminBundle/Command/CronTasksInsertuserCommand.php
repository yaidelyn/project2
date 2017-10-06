<?php
namespace Tex\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tex\UsuarioBundle\Entity\User;

class CronTasksInsertuserCommand extends ContainerAwareCommand {


    protected function configure(){

        $this->setName('crontasks:insertuser')
            ->setDescription('descripción de lo que hace el comando')
            ->addArgument('my_argument', InputArgument::OPTIONAL, 'Explicamos el significado del argumento');
    }
    protected function execute(InputInterface $input, OutputInterface $output){
		//echo 'Holaaa';die;
		$this->insertData();
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
	public function insertData(){

        // echo $dir;die;
        $em = $this->getContainer()->get('doctrine')->getManager();
        //$u = $this->get('security.context')->getToken()->getUser();
        //$id = $u->getId();

        //make curl from collengworld
        $this->getFileUrl();
        $dir = $_SERVER["DOCUMENT_ROOT"].'/web/upload/files/file_member.csv';
        //$result = $this->user_comp->getPersonalsInfo($id);
        //$projects = $this->getAllProjects();
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
			/* $response = array("code" => 1, "success" => true,'mensaje'=>'OK!!!');
            return new Response(json_encode($response));*/			
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