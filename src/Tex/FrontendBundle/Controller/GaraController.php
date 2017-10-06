<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 30/12/2016
 * Time: 0:42
 */

namespace Tex\FrontendBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Utils\UserComponent;
use Tex\AdminBundle\Entity\FormGara;
use Tex\AdminBundle\Entity\OpereStar;
use Tex\AdminBundle\Entity\ResultOpere;

class GaraController  extends Controller
{
    var $user_comp;
    var $arr_garas;

    public function __construct(){
        $this->user_comp = new UserComponent($this);
        $this->arr_garas = array();

    }

    public function getAllGaraAction(){

        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $iduser = $user->getId();
			 $result = $this->user_comp->getPersonalsInfo($iduser);
        }
		
        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;


       // $garas = $em->getRepository('AdminBundle:Gare')->findAll();
        $garas = $em->getRepository('AdminBundle:Gare')->findBy(
            array(),
            array('id' => 'DESC') ,
            10,
            0
        ) ;

        foreach($garas as $gara){
            $data['id'] = $gara->getId();
            $data['title'] = $gara->getTitle();
            $data['scadenzagara'] = $gara->getScadenzaGara()->format('d/m/Y');
            $data['scadenzacandidatura'] = $gara->getScadenzaCanditura()->format('d/m/Y');
            $data['importe'] = $gara->getImporte();
            $data['objetive'] = $gara->getObjective();

            $figures = $gara->getGareteams();
            $arr_temp = array();

            foreach($figures as $item){
                $temp['idfigure'] = $item->getId();
                $temp['name_figure'] = $item->getNameRol();
                $temp['active'] = ($item->getCantidad()>0)?1:0;

                $arr_temp[] = $temp;
               // echo '<pre>';print_r($arr_temp);die;
            }

            $data['figures'] = $arr_temp;
            unset($arr_temp);

            $arr_result[] = $data;

        }

        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_opportunita']= "";
        $result['menu_gara']= "active";
        $result['menu_work']= "";
        $result['categories'] = $this->getAllCategories();
        $result['garas'] = $arr_result;

        //echo '<pre>';print_r($result);die;

        return $this->render('FrontendBundle:Gara:all_gara.html.twig',array(
            'datos'=>$result,
            'category'=>'Gara',
            'tipologias'=>$this->getTipologiasGara()
        ));
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

    public function executeGaraAction($id){

        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $iduser = $user->getId();
		$result = $this->user_comp->getPersonalsInfo($iduser);
       

        $result['categories'] = $this->getAllCategories();
        $result['partipogettuali'] = $this->getPartiProgettuali();
        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_gara']= "active";
        $result['menu_work']= "";
        $result['menu_opportunita']= "";
        $result['idgara']= $id;

        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;

        $gara =  $em->getRepository('AdminBundle:Gare')->find($id);
        $userGaraSelect =  $em->getRepository('AdminBundle:UserSelectGara')->findOneBy(array(
            'user'=>$iduser,
            'gare'=>$id
        ));

        $figures = $gara->getGareteams();

        foreach($figures as $item){
            $temp['idfigure'] = $item->getId();
            $temp['name_figure'] = $item->getNameRol();
            $temp['active'] = ($item->getCantidad()>0)?1:0;

            $arr_temp[] = $temp;
        }

        $result['figures'] = $arr_temp;


        //echo '<pre>';print_r($userGaraSelect);die;

        if(empty($userGaraSelect)){

            $data['id'] = $gara->getId();
            $data['title'] = $gara->getTitle();
            $data['scadenzagara'] = $gara->getScadenzaGara()->format('d/m/Y');
            $data['scadenzacandidatura'] = $gara->getScadenzaCanditura()->format('d/m/Y');
            $data['importe'] = $gara->getImporte();
            $data['objetive'] = $gara->getObjective();

            if(isset($user)){
                $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array("user"=>$iduser));
                $result['user']= array('user'=>$user->getUsername(),'name'=>$profile->getName().' '.$profile->getLastname());
            }
          
			$garas = $this->resultGara($id);		
			$data = $garas[0]['percents'];
			//echo '<pre>';print_r($data);die;			
			//$arrGaras[0] = array('y'=>($data[0]['data']==0)?5:(int)$data[0]['data'],'name'=>$data[0]['label']);
			//$arrGaras[1] = array('y'=>($data[1]['data']==0)?5:(int)($data[1]['data']-5),'name'=>$data[1]['label']);
			$arrGaras[0] = array($data[0]['label'],$data[0]['data']);
			$arrGaras[1] = array($data[1]['label'],$data[1]['data']);
			
			
            $jsonencoder = new JsonEncode();
			//echo '<pre>';print_r($arrGaras);die;
            $result_gara = $jsonencoder->encode($arrGaras,$format = 'json');
			

            return $this->render('FrontendBundle:Gara:gara.html.twig',array('datos'=>$result, 'gara'=>$garas,'result_gara'=> $result_gara));

        }else{
           // $message = 'Spiacenti, lei ha già inoltrato la sua candidatura per questa Gara';
            $this->get('session')->getFlashBag()->add(
                'error',
                'Spiacenti, lei ha già inoltrato la sua candidatura per questa Gara'
            );
            return $this->redirect($this->generateUrl('all_gara'));
           /* return $this->render('FrontendBundle:Gara:all_gara.html.twig',array(
                'datos'=>$result,
                'message'=>$message,
                'category'=>'Gara',
                'tipologias'=>$this->getTipologiasGara()
            ));*/

        }
    }


    //devuelve todas las partes progettuali
    public function getPartiProgettuali(){
        $em = $this->get('doctrine')->getManager();

        $partiprogettualis =  $em->getRepository('FrontendBundle:PartiProgettuali')->findAll();

        foreach($partiprogettualis as $parti){
            $data['id'] = $parti->getId();
            $data['name'] = $parti-> getName();

            $result[] = $data;

        }
        return $result;
    }

    public function getTypePartiProgettualiAction(){
        if(!empty($_POST)){

            $em = $this->get('doctrine')->getManager();
            $typeparti = $em->getRepository('FrontendBundle:TypePartiProgettuali')->findBy(array("parti_progettali"=>$_POST['id']));

            if(!empty($typeparti)){
                foreach($typeparti as $item){
                    $data['id'] = $item->getId();
                    $data['name'] = $item->getName();

                    $result[] = $data;
                }
                //echo '<pre>';print_r($result);die;

                $response = array("code" => 1, "success" => true,'mensaje'=>'OKkk','result'=>$result);
                return new Response(json_encode($response));
            }else{
                $response = array("code" => 0, "success" => false,'mensaje'=>'');
                return new Response(json_encode($response));

            }
        }
    }



    public function makeGaraAction($id,$cat){
        //echo $cat;die;
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $iduser = $user->getId();
		 $result = $this->user_comp->getPersonalsInfo($iduser);

        $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array("user"=>$iduser));
        // echo '<pre>';print_r($profile);die;
        $gara =  $em->getRepository('AdminBundle:Gare')->find($id);
        $categoria =  $em->getRepository('AdminBundle:CategOffer')->find($cat);
        $subcategorias =  $em->getRepository('AdminBundle:SubCategory')->findBy(array(
            'category'=>$cat
        ));

            $result = array();

        $gareteams = $gara->getGareteams();

           /* $gareteams =  $em->getRepository('AdminBundle:GareTeam')->findBy(array(
                'gare'=>$gara->getId()
            ));*/
            if(!empty($gareteams)){

               // echo 'Aki Gara!!!';die;

                foreach($gareteams as $item){
                    $temp['id'] = $item->getId();
                    $temp['name_rol'] = $item->getNameRol();
                    $temp['active'] = $item->getActive();
					
                    $array_result[] = $temp;
                }
            }

           // echo '<pre>';print_r($array_result);die;
            foreach($subcategorias as $sub){
                $subcate['id'] = $sub->getId();
                $subcate['description'] = $sub->getDescription();

                $result_sub[] = $subcate;
            }

            $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
                $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;

            $result['menu_home']= "";
            $result['menu_about']= "";
            $result['menu_project']= "";
            $result['menu_contact']= "";
            $result['menu_opportunita']= "active";
            $result['menu_work']= "";
            $result['menu_gara']= "";
            $result['user']= array('user'=>$user->getUsername(),'name'=>$profile->getName().' '.$profile->getLastname());

            return $this->render('FrontendBundle:Default:make_gara.html.twig',array(
                'datos'=>$result,
                'category'=>$categoria->getName(),
                'subcategories'=>$result_sub,
                'role_team'=>$array_result,
                'idgara'=>$gara->getId(),
                'categories'=>$this->getAllCategory()

            ));

    }

    public function getOpereByIdSubCateg($id){

        $em = $this->get('doctrine')->getManager();

        $operes =  $em->getRepository('AdminBundle:Opere')->findBy(array(
            'subcategory'=>$id
        ));

        foreach($operes as $item){
            $data['id'] = $item->getId();
            $data['identificazione'] = $item->getIdentificazione();

            $result[] = $data;

        }
        //echo '<pre>';print_r($result);die;
        return $result;
    }

    public function createCompetenciaAction(){
        $em = $this->get('doctrine')->getManager();		
        if(!empty($_POST)){
		
		$user = $this->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $iduser = $user->getId();
			 $result = $this->user_comp->getPersonalsInfo($iduser);
        }

            //echo '<pre>';print_r($_POST);die;           

            $gare  =  $em->getRepository('AdminBundle:Gare')->findOneBy(array('id'=>$_POST['idgara']));
			// echo '<pre>';print_r($gare);die;
			$name_gara = $gare->getTitle();
            $formgara  =  $em->getRepository('AdminBundle:FormGara')->findOneBy(array('id'=>$_POST['idformgara']));

            $resultOpere = new ResultOpere();
            $resultOpere->setGare($gare);
            $resultOpere->setFormgara($formgara);

            $em->persist($resultOpere);
            $em->flush();

          // echo $resultOpere->getId();die;
             //enviar CV via email
            $this->uploadFile($_FILES);

            //send email
			$usermail= $user->getUsername();
            $msg1 = array(
                'date'=>date('l').','.date('d').' '.date('F'),
                'name'=>'Hola',
                'offer'=> 'Garaa',
                'message'=>'Holaaa',
                'result'=>$this->getTableHtml($this->arr_garas)
            );
            $msg2 = array(
                'date'=>date('l').','.date('d').' '.date('F'),
                'message'=>"Abbiamo ricevuto la sua candidatura per l’gara:".$name_gara."
                a breve sarà contattato da un nostro commerciale",

            );

            //send email
            $dir = $_SERVER["DOCUMENT_ROOT"].'/web/upload/files/'.$_FILES['file']['name'];
            $email1 = \Swift_Message::newInstance()
                ->setSubject("CV!!")
                ->setFrom("".$usermail."")
                ->setTo("tex07556@gmail.com")
                ->setBody(
                    $this->renderView(
                        'FrontendBundle:Gara:email_client.html.twig',
                        array('data_client'=>$msg1)
                    ),
                    'text/html'
                )
                ->attach(\Swift_Attachment::fromPath(''.$dir.'')
                )->attach(\Swift_Attachment::fromPath(''.$dir.'')
                );

            $email2 = \Swift_Message::newInstance()
                ->setSubject("CV!!!")
                ->setFrom("tex07556@gmail.com")
                ->setTo("".$usermail."")
                ->setBody($this->renderView('UsuarioBundle:Default:email_client_offer.html.twig',array('data_client'=>$msg2)),
                    'text/html');

            if( $this->get('mailer')->send($email1) && $this->get('mailer')->send($email2)){
                $response = array("code" => 1, "success" => true,'mensaje'=>'L´gare correttamente efettuata!!');
                return new Response(json_encode($response));
            }
            // $this->user_comp->sendEmailComp('yosveni.ee@sanna.net','test@sanna.net','AdminBundle:Default:email_client.html.twig');
        }
    }

    public function getTableHtml($array){
        $html = "";
        foreach($array as $item):
            $html.="<tr>".$item['name_opere']."";
                foreach($item['progettuali'] as $elem):
                    $html.="<table align='left' border='0' cellpadding='0' cellspacing='0'><thead>".$elem['name']."</thead>";
                    $html.="<tbody>";
                    foreach($elem['type'] as $value):
                        $html.="<tr>".$value['nametype']."</tr>";
                    $html.="</tbody>";
                    endforeach;
                    $html.="</table>";
               endforeach;
               $html.="</tr>";
            endforeach;

        return $html;
    }

    //all categories
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

    public function subCategoryByIdAction($id){

        $em = $this->get('doctrine')->getManager();
        $subcateg = $em->getRepository('AdminBundle:SubCategory' )->findBy(array(
            'category'=>$id
        ));
		 if(is_object($user)){
            $iduser = $user->getId();
			 $result = $this->user_comp->getPersonalsInfo($iduser);
        }

        $categoria = $em->getRepository('AdminBundle:CategOffer')->find($id);
        $name_catg = $categoria->getName();

        foreach($subcateg as $sub){

            $subcategory['id'] = $sub->getId();
            $subcategory['name'] = $sub->getDescription();
            $operes = $em->getRepository('AdminBundle:Opere')->findBy(array(
                'subcategory'=>$sub->getId()
            ));
            // echo count($operes);
            if(!empty($operes)){

                foreach($operes as $oper){
                    $temp['id'] = $oper->getId();
                    $temp['code'] = $oper->getCode();
                    $temp['identificazione'] = $oper->getIdentificazione();

                    $result_oper[] = $temp;
                }

                $result[] = array(
                    'subcategory'=>$subcategory,
                    'operes'=>$result_oper
                );
                unset($result_oper);
            }
        }
        $html = '';
        //$result['name_categ'] = $name_catg;
        //echo '<pre>';print_r($result);die;
        if(!empty($result)){

            $data = $this->render('FrontendBundle:Gara:questions_gara.html.twig', array(
                'result'=>array('data'=>$result)
            ));
            $status = 'success';
            $html = $data->getContent();
        }

        $jsonArray = array(
            'status' => $status,
            'data' => $html,
            'name_catg'=>$name_catg

        );


        $response = new Response(json_encode($jsonArray));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $response;
       // return $result;
    }

    function uploadFile($file = array()){
        $allowedExtensions = array('docx','doc','pdf','xlsx');
        $flag = (in_array($this->extension($file['file']['name']),$allowedExtensions))?true:false;
        if($flag){
            $dir = $_SERVER["DOCUMENT_ROOT"].'/web/upload/files/';
            //$path = $dir.'/'.$name;
           /* if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }*/
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

    //get operes x ide
    public function getDataOpereAction(){
        $em = $this->get('doctrine')->getManager();
		
        if(!empty($_POST)){

            //echo '<pre>';print_r($_POST);die;

            $arrOperes = $_POST['operes'];

            $gareteam = $em->getRepository('AdminBundle:GareTeam')->find($_POST['idfigure']);
            $gare = $em->getRepository('AdminBundle:Gare')->find($_POST['idgara']);

            $formgara = new FormGara();

            $formgara->setCreated(new \DateTime('now'));
            $formgara->setCreateBy($_POST['createby']);
            $formgara->setGareteam($gareteam);
            $formgara->setGare($gare);

            foreach($arrOperes as $key=>$value){

               $objoper = $em->getRepository('AdminBundle:Opere')->find($value['idopere']);
                $data['id'] = $objoper->getId();
                $data['name'] = $objoper->getIdentificazione();
                $formgara->addOpere($objoper);

                foreach($value['progettuali'] as $k=>$v){
                    $typeprogettuali = $em->getRepository('FrontendBundle:TypePartiProgettuali')->find($v);
                    $formgara->addTypePartiProgettuali($typeprogettuali);

                    $data_t['id'] = $v;
                    $data_t['nametype'] = $typeprogettuali->getName();

                    $arr[] = $data_t;

                    $arr_result[] = array(
                        'idparti'=>$typeprogettuali->getPartiProgettali()->getId(),
                        'name'=>$typeprogettuali->getPartiProgettali()->getName(),
                        'type'=>$arr
                    );
                    unset($arr);
                }

               $result_opere[] = $data;
               $result_opere2[] = array(
                   'idopere'=>$objoper->getId(),
                   'name_opere'=>$objoper->getIdentificazione(),
                   'progettuali'=>$arr_result
               );
                unset($arr_result);
            }

         // echo '<pre>';print_r($result_opere2);die;
            //add form gara
            $em->persist($formgara);
            $em->flush();

            $idformgara = $formgara->getId();


            $html = '';
            if(!empty($result_opere2)){

                $this->setArray($result_opere2);

                //echo '<pre>';print_r($this->arr_garas);die;

                $data = $this->render('FrontendBundle:Gara:progettuali_star.html.twig', array(
                    'result'=>array('data'=>$result_opere2,'idformgara'=>$idformgara)
                ));
                $status = 'success';
                $html = $data->getContent();
            }

            $jsonArray = array(
                'status' => $status,
                'data' => $html,
            );

            $response = new Response(json_encode($jsonArray));
            $response->headers->set('Content-Type', 'application/json; charset=utf-8');

            return $response;



        }
        if(!empty($result_opere)){
            $response = array("code" => 1, "success" => true,'mensaje'=>'Ha inserito correttamente  l´gare!!','result'=>$result_opere,'idformgara'=>$idformgara);
            return new Response(json_encode($response));
        }

    }

    public function categoriesAll(){

        $em = $this->get('doctrine')->getManager();
        $categories = $em->getRepository('AdminBundle:CategOffer' )->findAll();

        foreach($categories as $categ){
            $data['id'] = $categ->getId();
            $data['name'] = $categ->getName();

            $result[] = $data;
        }

        return $result;
    }

    public function  garaCategoryAction(){
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $iduser = $user->getId();
		 $result = $this->user_comp->getPersonalsInfo($iduser);

        //echo 'Hola';die;
        $figures = $em->getRepository('AdminBundle:Figure')->findAll();

        foreach($figures as $fig){
            $temp_fig['id'] = $fig->getId();
            $temp_fig['name'] = $fig->getName();

            $arr_figure[] = $temp_fig;
        }

        $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array("user"=>$iduser));
        $result['categories'] = $this->categoriesAll();
        $result['figures'] = $arr_figure;
        $result['user']= array('user'=>$user->getUsername(),'name'=>$profile->getName().' '.$profile->getLastname());




        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;
        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_gara']= "active";
        $result['menu_opportunita']= "";
        $result['menu_work']= "";

       //echo '<pre>';print_r($result);die;

        return $this->render('FrontendBundle:Gara:gara.html.twig',array('datos'=>$result));



    }

    public function subCategoryByCategoryAction(){
        if(!empty($_POST)){
            $em = $this->get('doctrine')->getManager();
            $categories = $_POST['idcateg'];
            foreach($categories as $cat){

                $categoria = $em->getRepository('AdminBundle:CategOffer')->find($cat);
                $name_catg = $categoria->getName();

                $subcateg = $em->getRepository('AdminBundle:SubCategory' )->findBy(array(
                    'category'=>$cat
                ));
                foreach($subcateg as $sub) {

                    $subcategory['id'] = $sub->getId();
                    $subcategory['name'] = $sub->getDescription();
                    $operes = $em->getRepository('AdminBundle:Opere')->findBy(array(
                        'subcategory' => $sub->getId()
                    ));
                    // echo count($operes);
                    if(!empty($operes)){

                        foreach($operes as $oper){
                            $temp['id'] = $oper->getId();
                            $temp['code'] = $oper->getCode();
                            $temp['identificazione'] = $oper->getIdentificazione();

                            $result_oper[] = $temp;
                        }

                        $result[] = array(
                            'subcategory'=>$subcategory,
                            'operes'=>$result_oper
                        );
                        unset($result_oper);
                    }
                }

                $arr_categ_result[] = array(
                    'name_categ'=>$name_catg,
                    'subcategories'=>(!empty($result))?$result:null,

                );
                unset($result);

            }

            //echo '<pre>';print_r($arr_categ_result);die;

            $html = '';
            if(!empty($arr_categ_result)){

                $data = $this->render('FrontendBundle:Gara:questions.html.twig', array(
                    'result'=>array('data'=>$arr_categ_result)
                ));
                $status = 'success';
                $html = $data->getContent();
            }

            $jsonArray = array(
                'status' => $status,
                'data' => $html,
            );

            $response = new Response(json_encode($jsonArray));
            $response->headers->set('Content-Type', 'application/json; charset=utf-8');

            return $response;



        }
    }

    public function insertOpereStarAction(){
        if(!empty($_POST)){
            $em = $this->get('doctrine')->getManager();

           // echo '<pre>';print_r($_POST);die;
            $formGara = $em->getRepository('AdminBundle:FormGara')->find($_POST['idformgara']);
            $opereStar = new OpereStar();

            $opereStar->addFormgara($formGara);
            foreach($_POST['idoperes'] as $k=>$v){
                $opere = $em->getRepository('AdminBundle:Opere')->find($v);
                $opereStar->addOpere($opere);
            }

            //add form gara
            $em->persist($opereStar);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'Okk');
            return new Response(json_encode($response));

        }

    }

    public function showProgettualiAction()
    {
        if (!empty($_POST)) {

            $em = $this->get('doctrine')->getManager();
            $arrProgettuali = $_POST['progettualli'];

           // echo '<pre>';print_r($arrProgettuali);die;

            $partisprogettuali = $this->getPartiProgettuali();
          // echo '<pre>'; print_r($arrProgettuali);die;
            foreach ($partisprogettuali as $parti) {
                $typeparti = $em->getRepository('FrontendBundle:TypePartiProgettuali')->findBy(array("parti_progettali" => $parti['id']));

                if (!empty($typeparti)) {
                    foreach ($typeparti as $item) {
                        $data['id'] = $item->getId();
                        $data['name'] = $item->getName();

                        $result[] = $data;
                    }
                    //echo '<pre>'; print_r($result);die;
                    $result_total[] = array('progettuali'=>$parti,'type_progettuali'=>$result);
                    unset($result);
                }
            }


            foreach($arrProgettuali as $oper){
                //foreach($oper['operes'] as $k=>$v)
                $objOper = $em->getRepository('AdminBundle:Opere')->find($oper['operes']);
                //$objOper = $em->getRepository('AdminBundle:Opere')->find($v);

                $id = $oper['operes'];
                $name_opere = $objOper->getIdentificazione();

                $arr_res_operes[] = array('id'=>$id,'name'=>$name_opere);
            }

            $html = '';
            if(!empty($result_total) && !empty($arr_res_operes)){

                $data = $this->render('FrontendBundle:Gara:progettuali.html.twig', array(
                    'result'=>array('data'=>$result_total,'operes'=>$arr_res_operes)
                ));
                $status = 'success';
                $html = $data->getContent();
            }
            $jsonArray = array(
                'status' => $status,
                'data' => $html,
            );

            $response = new Response(json_encode($jsonArray));
            $response->headers->set('Content-Type', 'application/json; charset=utf-8');

            return $response;
        }
    }

    public function setArray($array){
        array_push($this->arr_garas,$array);
    }

    public  function getTipologiasGara(){
        $em = $this->get('doctrine')->getManager();
        $tipologias = $em->getRepository('AdminBundle:TipologiaGara')->findAll();

        foreach($tipologias as $tipo){
            $data['id'] = $tipo->getId();
            $data['name'] = $tipo->getName();

            $result[] = $data;

        }
        return $result;
    }

    public function SendFormGaraAction(Request $request){

        if($request->isMethod('POST')){
           // echo '<pre>';print_r($_POST);die;
            $em = $this->get('doctrine')->getManager();
            $tipologia = $em->getRepository('AdminBundle:TipologiaGara')->find($_POST['tipologia']);

            $name = $_POST['name'];
            $email = $_POST['email'];
            $cell = $_POST['cell'];
            $title = $_POST['title'];
            $riferimento = $_POST['riferimento'];
            $tipologia =$tipologia->getName();
            $scadenza_gara = $_POST['scadenza_gara'];


            $msg = array(
                'date'=>date('l').','.date('d').' '.date('F'),
                'name'=>$name,
                'cell'=>$cell,
                'title'=>$title,
                'riferimento'=>$riferimento,
                'tipologoia'=>$tipologia,
                'scadenzza'=>$scadenza_gara
            );
           $data_email = \Swift_Message::newInstance()
                ->setSubject("NUOVE SEGNALAZIONI GARA:".$name)
                ->setFrom("".$email."")
                ->setTo("test@sanna.net")
                ->setBody($this->renderView('FrontendBundle:Gara:email_gara.html.twig',array('data'=>$msg)),
                    'text/html');

            if( $this->get('mailer')->send($data_email)){

                return $this->redirect($this->generateUrl('User_index'));
            }

        }

    }
	 //completamiento de las garas
    public function resultGara($id){

        $em = $this->get('doctrine')->getManager();
        $userSelectGara = $em->getRepository('AdminBundle:UserSelectGara')->findBy(array('gare'=>$id));
        $gara= $em->getRepository('AdminBundle:Gare')->find($id);

        $resto = $gara->getCapTeam() - count($userSelectGara);
        $percent_resto = ($resto*100)/$gara->getCapTeam();
        $percent_aprov = (count($userSelectGara)*100)/$gara->getCapTeam();

        $arr_percent = array(0=>array('label'=>'COMPLETATO','data'=> $percent_aprov),1=>array('label'=>'NO COMPLETATO','data'=> $percent_resto));
        $result_data[] = array('label'=>'Gara X','percents'=>$arr_percent);

        return $result_data;
    }




} 