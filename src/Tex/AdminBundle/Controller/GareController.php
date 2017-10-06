<?php


namespace Tex\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Utils\UserComponent;
use Tex\AdminBundle\Entity\Competenza;
use Tex\AdminBundle\Entity\FormGara;
use Tex\AdminBundle\Entity\Gare;
use Symfony\Component\HttpFoundation\Response;
use Tex\AdminBundle\Entity\GareTeam;
use Tex\AdminBundle\Entity\ResultGara;
use Tex\AdminBundle\Entity\UserSelectGara;
use Tex\AdminBundle\Entity\OpereStar;
use Tex\UsuarioBundle\Entity\Team;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


class GareController  extends Controller
{

    var $user_comp;


    public function __construct(){
        $this->user_comp = new UserComponent($this);

    }
    public function indexAction(){

    }

    public function createGareAction(){
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        if(!empty($_POST)){
            // echo '<pre>';print_r($_POST);die;
            $gare = new Gare();

            $scadenza_gara = $_POST['data']['scadenza_gare'];
            $scadenza_candi = $_POST['data']['scadenza_candi'];

            $tipologia = $em->getRepository('AdminBundle:TipologiaGara')->find($_POST['data']['tipologia']);

            // $gare->setCategory($category);
            $gare->setTitle($_POST['data']['title']);
            $gare->setRifBando($_POST['data']['rifbando']);
            $gare->setScadenzaGara(new \DateTime($scadenza_gara));
            $gare->setScadenzaCanditura(new \DateTime($scadenza_candi));
            $gare->setCodGara($_POST['data']['cod_gara']);
            $gare->setImporte($_POST['data']['importe']);
            $gare->setTipologia($tipologia);
            $gare->setObjective($_POST['data']['objetive']);
            $gare->setCapTeam($_POST['data']['tipologia']);


            foreach($_POST['data']['idopere'] as $val){
                $opere = $em->getRepository('AdminBundle:Opere')->findOneBy(array(
                    'code'=>$val
                ));

                $gare->addOpere($opere);
            }


            $id_gare = $gare->getId();
            //add gare Team

            foreach($_POST['data']['name_rol'] as $rol){
                $gareteam = new GareTeam();
                //$gareteam->setGare($gare);
                $gareteam->setNameRol($rol['name']);
                $gareteam->setCantidad($rol['cantidad']);
                //$gareteam->addGare($gare);

                $em->persist($gareteam);
                $em->flush();

                $gare->addGareteam($gareteam);
            }

            $em->persist($gare);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'La gara è stata aggiornata correttamente');
            return new Response(json_encode($response));

        }else{



            $gares = $em->getRepository('AdminBundle:Gare')->findBy(
                array(),
                array('id' => 'DESC') ,
                1,
                0
            ) ;
            $cod_gara = "";

            if(!empty($gares)){
                $cod_gara =  $this->getCodegara($gares[0]->getCodGara());
            }else{
                $cod_gara = '001-'.date('m').'-'.date('y');
            }

            $result = $this->user_comp->getPersonalsInfo($id);
            $result['active_gare'] = 'active';
            $result['create_gare'] = 'active';
            $result['cod_gara'] =$cod_gara;
            $projects = $this->user_comp->getAllProjects();
            $categories = $this->getAllCategory();

            return $this->render('AdminBundle:Admin:create_gare.html.twig', array(
                'datos'=>$result,
                'projects'=>$projects,
                'categories'=>$categories,
                'operes'=>$this->getAllOpere(),
                'subcategoperes'=>$this->allSubcategoryOpere(),
                'tipologias'=>$this->getTipologiasGara()
            ));


        }
    }


    public function generateCodegara($start, $count, $digits){

        $result = array();
        for ($n = $start; $n < $start + $count; $n++) {
            $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);
        }
        return $result;
    }

    public function getCodegara($cadena){

        $temp = explode('-', $cadena);
        $cod_temp = $this->generateCodegara(substr($temp[0],-1)+1,1,strlen($temp[0]))[0];
        $codigo = $cod_temp.'-'.date('m').'-'.date('y');

        return $codigo;

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


    //get all oper
    public function getAllOpere(){
        $em = $this->get('doctrine')->getManager();
        $operes = $em->getRepository('AdminBundle:Opere' )->findAll();

        foreach($operes as $item){
            $data['id'] = $item->getId();
            $data['code'] = $item->getCode();

            $result[] = $data;

        }

        return $result;
    }


    public function allSubcategoryOpere(){

        $em = $this->get('doctrine')->getManager();
        $subcateg = $em->getRepository('AdminBundle:SubCategory' )->findAll();

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
        //echo '<pre>';print_r($result);die;
        return $result;
        // $response = array("code" => 1, "success" => true,'mensaje'=>'OKKK!!!','result'=>$result);
        //return new Response(json_encode($response));

    }

    public function viewGaraAction(){

        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        $em = $this->get('doctrine')->getManager();
        $garas = $em->getRepository('AdminBundle:Gare' )->findAll();


        foreach($garas as $item){
            $data['id'] = $item->getId();
            $data['codigo'] = $item->getCodGara();
            $data['title'] = $item->getTitle();
            $data['importe'] = $item->getImporte();
            $data['capteam'] = $item->getCapTeam();
            $data['scadenzagrara'] = $item->getScadenzaGara()->format('d/m/y h:m:s');
            $data['scadenzacanditura'] = $item->getScadenzaCanditura()->format('d/m/y h:m:s');

            $result_gara[] = $data;
        }
        $result = $this->user_comp->getPersonalsInfo($id);
        $result['active_gare'] = 'active';
        $result['view_create'] = 'active';
        $projects = $this->user_comp->getAllProjects();


        return $this->render('AdminBundle:Admin:view_gara.html.twig', array(
            'datos'=>$result,
            'projects'=>$projects,
            'garas'=>(!empty($result_gara))?$result_gara:''
        ));

    }
    //delete gara
    public function deleteGaraAction($id){
        if(isset($id)){
            $em = $this->get('doctrine')->getManager();
            $gara = $em->getRepository('AdminBundle:Gare')->find($id);
            $em->remove($gara);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'La Gara eliminò correttamente');
            return new Response(json_encode($response));

        }

    }

    public function getIdOperes($array){
        foreach($array as $item){
            $result[] = $item->getId();
        }

        return $result;
    }

    //calcular coincidencia x user
    public function calculateCoincidencia(){

        $em = $this->get('doctrine')->getManager();
        $resultoperes = $em->getRepository('AdminBundle:ResultOpere')->findAll();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();

        foreach($resultoperes as $item){
            //$operes = $this->getIdOperes($item->getOperes());
            //echo '<pre>';print_r($item->getFormgara());
            if(!empty($item->getFormgara())){
                $idformgara = $item->getFormgara()->getId();
                $formgara = $em->getRepository('AdminBundle:FormGara')->find($idformgara);


                $createBy =  $formgara->getCreateBy();

                //echo 'Username '.$createBy;die;

                $userformgara =  $em->getRepository('UsuarioBundle:User')->findOneBy(array(
                        'username'=>$createBy
                    )
                );

                //echo '<pre>';print_r($userformgara);die;
                $data['id'] = $item->getId();
                $data['fullname'] = $formgara->getCreateBy();
                $data['namerol'] = $formgara->getGareteam()->getNameRol();
                $data['namegara'] = $item->getGare()->getTitle();
                $data['idformgara'] = $idformgara;

                $operesForm = $item->getFormgara()->getOperes();
                $cantoperes = count($operesForm);
                $idoperesFormGara = array();
                foreach($operesForm as $val){
                    $idoperesFormGara[] = $val->getId();
                }

                //get operes gara
                $gara = $em->getRepository('AdminBundle:Gare')->find($item->getGare()->getId());
                // $user= $em->getRepository('UsuarioBundle:Gare')->find(array());
                $operes_gara = $gara->getOperes();

                $data['idgara'] = $gara->getId();

                $idoperesGara = array();

                foreach($operes_gara as $value){
                    $idoperesGara[] = $value->getId();
                }

                $cont = 0;
                foreach($idoperesFormGara as $elem){
                    if(in_array($elem,$idoperesGara))
                        $cont++;
                }

                $percent = floatval(($cantoperes/count($idoperesGara))*100);
                $data['perecent'] = $percent;
                $data['iduser'] = $userformgara->getId();

                //crear Result Gara
                $resultGara = new ResultGara();

                //setear atributos
                $resultGara->setNameGara($item->getGare()->getTitle());
                $resultGara->setNameUser($formgara->getCreateBy());
                $resultGara->setNameFigure($formgara->getGareteam()->getNameRol());
                $resultGara->setPercent($percent);

                $em->persist($resultGara);
                $em->flush();

                $result[] = $data;
            }
        }
        if(!empty($result)){
            return $result;
        }else{

        }
        //echo '<pre>';print_r($result);die;

    }

    public function viewUserCoincidenciaAction(){

        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();

        $result = $this->user_comp->getPersonalsInfo($id);
        $result['active_gare'] = 'active';
        $result['view_result'] = 'active';
        $projects = $this->user_comp->getAllProjects();




        $garas = $this->porcentGara();
		//echo '<pre>';print_r($garas);die;

        $jsonencoder = new JsonEncoder();
        $result_gara = $jsonencoder->encode($this->porcentGara(),$format = 'json');


        return $this->render('AdminBundle:Admin:result_user_gara.html.twig', array(
            'datos'=>$result,
            'projects'=>$projects,
            'acertions'=>$this->calculateCoincidencia(),
            'result_gara'=> $result_gara,
            'namegaras'=>$garas,
            'teams'=>$this->getTeam()
        ));

    }

    public function porcentGara(){
        $em = $this->get('doctrine')->getManager();
        $userSelectGara = $em->getRepository('AdminBundle:UserSelectGara')->findAll();
        $garas= $em->getRepository('AdminBundle:Gare')->findAll();
        foreach($garas as $item){
            $data['id'] = $item->getId();
            $data['name'] = $item->getTitle();
            $data['cap'] = $item->getCapTeam();

            $result[] = $data;
        }
        $arr_id = array();

        foreach($userSelectGara as $ele){
            $arr_id[] = $ele->getGare()->getId();
        }
        // echo '<pre>';print_r($arr_id);die;
        $cont = 0;
        $arr_temp = array();
        $result_data = array();
        for($i = 0;$i<count($result);$i++){
            $id = $result[$i]['id'];
            $name= $result[$i]['name'];
            $cap= $result[$i]['cap'];
            if(in_array($id,$arr_id)){
                //$arr_temp[] = $id ;
                for($j = $i;$j<count($result);$j++){
                    if($result[$j]['id']===$id){
                        $cont++;
                    }
                }
                //echo $cont;die;
                if($cont > 0){
                    $resto = $cap - $cont;
                    $percent_resto = ($resto*100)/$cap;
                    $percent_aprov = ($cont*100)/$cap;
                    $arr_2[] = array('label'=>$name);
                    $arr_percent = array(0=>array('label'=>'COMPLETATO','data'=>$percent_aprov),1=>array('label'=>'NO COMPLETATO','data'=>$percent_resto));
                    $result_data[] = array('garas'=>$arr_2,'percents'=>$arr_percent);
                    $cont = 0;

                }


            }else{
                $arr_2[] = array('label'=>$name);
                $arr_percent = array(0=>array('label'=>'COMPLETATO','data'=>0),1=>array('label'=>'NO COMPLETATO','data'=>100));
                $result_data[] = array('garas'=>$arr_2,'percents'=>$arr_percent);
            }
            unset($arr_2);
        }
        //echo '<pre>';print_r($result_data);die;
        return $result_data;

    }

    public function selectPersonGaraAction($id){

        //echo 'Hola '.$id;die;
        $em = $this->get('doctrine')->getManager();

        $user = $em->getRepository('UsuarioBundle:User')->find($id);
        $resultopere = $em->getRepository('AdminBundle:ResultGara')->findOneBy(array(
            'nameUser'=>$user->getUsername()
        ));

        //$figure = $em->getRepository('AdminBundle:Figure')->find($resultopere->getFigure()->getId());
        $name_figure = $resultopere->getNameFigure();

        $gara = $em->getRepository('AdminBundle:Gare')->find($_POST['id']);

        /*$result_opere = $em->getRepository('AdminBundle:ResultOpere')->findOneBy(array(
            'user'=>$id,
            'gares'=>$gara->getId(),
        ));*/

        $gareTeam = $em->getRepository('AdminBundle:GareTeam')->findOneBy(array('name_rol'=>$name_figure));

        //echo '<pre>';print_r($gareTeam);die;
        // echo 'Hola'.$result_opere->getGarateam()->getActive();die;

        if($gareTeam->getCantidad() == 0){
            $response = array("code" => 0, "success" => true,'mensaje'=>'This role has been assigned previously');
            return new Response(json_encode($response));
        }else{
            //actualizo value active gareTeam
            $cantidad = $gareTeam->getCantidad() - 1;
            $gareTeam->setCantidad($cantidad);
            $em->persist($gareTeam);
            $em->flush();

            //actualizo value cap_team de la tabla gare
            // $gare = $em->getRepository('AdminBundle:Gare')->find($resultopere->getGares()->getId());
            //$cant = $gara->getCapTeam() - 1;
            //$gara->setCapTeam($cant);
            //$em->persist($gara);
            //$em->flush();

            $userselectGara = new UserSelectGara();

            $userselectGara->setUser($user);
            $userselectGara->setGare($gara);

            $em->persist($userselectGara);
            $em->flush();

            //delete result
            /*  $em->remove($resultopere);
              $em->flush();*/

            //send email
            $response = array("code" => 1, "success" => true,'mensaje'=>'OKKK!!!');
            return new Response(json_encode($response));
        }

    }
    public function aceptGaraAction($id){
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $iduser = $user->getId();

        $gare = $em->getRepository('AdminBundle:Gare')->find($id);
        $userSelectGara = $em->getRepository('AdminBundle:UserSelectGara')->findOneBy(array('gare'=>$gare->getId()));

        $iduserGara = $userSelectGara->getUser()->getId();
        if($gare->getCapTeam()== 0){

            //buscar todos los gare  team
            $gareteams = $gare->getGareteams();

            foreach($gareteams as $item)
            {
                $temp = $this->getDataUser($iduserGara);
                if(!empty($temp)){
                    //echo $temp['id'];die;
                    $data['id'] =  $temp['id'];
                    $data['fullname'] =  $temp['fullname'];
                    $data['name_rol'] = $item->getNameRol();

                    $result[] = $data;
                }

            }

            //echo '<pre>';print_r($result);die;
            $team = $em->getRepository('UsuarioBundle:Team')->findBy(
                array(),
                array('id' => 'ASC') ,
                1,
                0
            ) ;
            $code = $this->user_comp->generate_numbers(substr($team[count($team)-1]->getCode(),-1)+1,1,3)[0];
            $name = 'Team_'.$code;
            $createby = $iduser;
            $active = 1;
            //$user = $em->getRepository('UsuarioBundle:User' )->find($iduser);

            //create Team
            $team = new Team();
            $team->setName($name);
            $team->setCode($code);
            $team->setDescription("Team from Gara");
            $team->setCreateBy($createby);
            $team->setActivate($active);
            $team->setUser($user);

            foreach($result as $user){
                $profile = $em->getRepository('UsuarioBundle:Profile' )->findOneBy(array(
                    'user'=>$user['id']
                ));
                $team->addProfile($profile);
            }
            $em->persist($team);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'OKKK!!!');
            return new Response(json_encode($response));

        }
        else{
            $response = array("code" => 0, "success" => true,'mensaje'=>'Gara incompleta!!!');
            return new Response(json_encode($response));
        }

    }

    public function getDataUser($id){
        $em = $this->get('doctrine')->getManager();
        $user = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$id));
        $data = array();
        if(isset($user)){
            $data['id'] = $id;
            $data['fullname'] = $user->getName().' '.$user->getLastname();
        };

        //echo '<pre>';print_r($data);die;

        return $data;

    }
    //Update gare
    public function updateGareAction($id){
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $iduser = $user->getId();
        $result = $this->user_comp->getPersonalsInfo($iduser);
        $result['active_gare'] = 'active';
        $result['view_result'] = 'active';
        $projects = $this->user_comp->getAllProjects();

        if(!empty($_POST)){

            //echo '<pre>';print_r($_POST);die;
            $gare = $em->getRepository('AdminBundle:Gare')->find($id);

            $scadenza_gara = $_POST['data']['scadenza_gare'];
            $scadenza_candi = $_POST['data']['scadenza_candi'];

            $tipologia = $em->getRepository('AdminBundle:TipologiaGara')->find($_POST['data']['tipologia']);


            // $gare->setCategory($category);
            $gare->setTitle($_POST['data']['title']);
            $gare->setRifBando($_POST['data']['rifbando']);
            $gare->setScadenzaGara(new \DateTime($scadenza_gara));
            $gare->setScadenzaCanditura(new \DateTime($scadenza_candi));
            $gare->setCodGara($_POST['data']['cod_gara']);
            $gare->setImporte($_POST['data']['importe']);
            $gare->setTipologia($tipologia);
            $gare->setObjective($_POST['data']['objetive']);
            $gare->setCapTeam($_POST['data']['tipologia']);


            foreach($_POST['data']['idopere'] as $val){
                $opere = $em->getRepository('AdminBundle:Opere')->findOneBy(array(
                    'code'=>$val
                ));

                $gare->addOpere($opere);
            }
            $id_gare = $gare->getId();
            //add gare Team

            foreach($_POST['data']['name_rol'] as $rol){
                $gareteam = new GareTeam();
                //$gareteam->setGare($gare);
                $gareTeam = $em->getRepository('AdminBundle:GareTeam')->findOneBy(array(
                    'name_rol'=>$rol['name']
                ));
                // echo '<pre>';print_r($gareTeam);die;
                if(empty($gareTeam)){
                    $gareteam->setNameRol($rol['name']);
                    $gareteam->setCantidad($rol['cantidad']);

                    $em->persist($gareteam);
                    $em->flush();
                    $gare->addGareteam($gareteam);
                }elseif($gareTeam->getCantidad()!=$rol['cantidad']){
                    //$gareTeam->setNameRol($rol['name']);
                    $gareTeam->setCantidad($rol['cantidad']);
                    $em->persist($gareTeam);
                    $em->flush();
                }
                //$gareteam->addGare($gare);
            }

            $em->persist($gare);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'La competizione aggiornò correttamente');
            return new Response(json_encode($response));

        }else{
            $gare = $em->getRepository('AdminBundle:Gare')->find($id);
            $tipologia = $em->getRepository('AdminBundle:TipologiaGara')->find($gare->getTipologia());

            $id_tipologia = $tipologia->getId();

            //echo '<pre>';$gare->getScadenzaGara()->getTimestamp();
            //echo '<pre>';$gare->getScadenzaGara()->format('Y-m-d h:i:s');

            $data['id'] = $gare->getId();
            $data['title'] = $gare->getTitle();
            $data['rifbando'] = $gare->getRifBando();
            $data['scadenzagara'] = $gare->getScadenzaGara()->format('Y-m-d h:m:s');
            $data['ecadenzacandi'] = $gare->getScadenzaCanditura()->format('Y-m-d h:m:s');
            $data['objetive'] = $gare->getObjective();
            $data['importe'] = $gare->getImporte();
            $data['codgara'] = $gare->getCodGara();
            $data['tipologia'] =  $id_tipologia;
            $data['cantteam'] = $gare->getCapTeam();


            foreach($gare->getOperes() as $opere){

                $temp['idopere'] = $opere->getId();
                $temp['code'] = $opere->getCode();
                $temp['identificazione'] = $opere->getIdentificazione();
                /* $temp['subcategory'] = array(
                     'idsubc'=>$opere->getSubcategory()->getid(),
                     'namecateg'=>$opere->getSubcategory()->getDescription()
                 );*/

                $temp_result[] = $temp;
            }
            $data['operes'] = $temp_result;

            //get all Name Team
            $gare_teams = $gare->getGareteams();

            foreach($gare_teams as $item){
                $gareTeam[] = array(
                    'id'=>$item->getId(),
                    'name'=>$item->getNameRol(),
                    'cantidad'=>$item->getCantidad()
                );
            }

            $data['gareteam'] = $gareTeam;
            $jsonencoder = new JsonEncoder();
            $gareteam = $jsonencoder->encode($data['gareteam'],$format = 'json');


            $result['gara'] = $data;
            $categories = $this->getAllCategory();

            $operes = $gare->getOperes();

            foreach($operes as $value){
                //echo $value->getSubcategory()->getId();die;
                $oper['idsubcateg'] = is_object($value->getSubcategory())?$value->getSubcategory()->getId():null;
                $oper['name_subcateg'] = is_object($value->getSubcategory())?$value->getSubcategory()->getDescription():null;
                $oper['id'] = $value->getId();
                $oper['code'] = $value->getCode();

                $arr_operes [] = $oper;
            }
            return $this->render('AdminBundle:Admin:update_gare.html.twig', array(
                'idgare'=>$id,
                'datos'=>$result,
                'projects'=>$projects,
                'gareteam'=>$gareteam,
                'subcategoperes'=>$this->allSubcategoryOpere(),
                'tipologias'=>$this->getTipologiasGara()
                //'jsonsubcateg'=>$result_subcateg
            ));

        }
    }

    //all subcategory by id
    public function getAllSubCategoryId($id){

        $em = $this->get('doctrine')->getManager();
        $subcateg = $em->getRepository('AdminBundle:SubCategory' )->findBy(array(
            'category'=>$id
        ));

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

        //echo '<pre>';print_r($result);die;
        return $result;

    }

    public function deleteResultGaraAction($id){

        if(isset($id)){
            $em = $this->get('doctrine')->getManager();
            $resultGare = $em->getRepository('AdminBundle:FormGara')->find($id);
            $resultOpere = $em->getRepository('AdminBundle:ResultOpere')->findOneBy(array('formgara'=>$id));
            $em->remove($resultOpere);
            $em->remove($resultGare);
            $em->flush();


            //$em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'La Gara eliminò correttamente');
            return new Response(json_encode($response));

        }

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

    //function all team
    public function getTeam(){

        $em = $this->get('doctrine')->getManager();
        $teams = $em->getRepository('UsuarioBundle:Team')->findAll();

        foreach($teams as $item){
            $data['id'] = $item->getId();
            $data['name'] = $item->getName();

            $result[] = $data;
        }
        if(!empty($result))
             return $result;
    }

    public function addUserTeamAction(){
        $em = $this->get('doctrine')->getManager();
        if(!empty($_POST)){

            $team = $em->getRepository('UsuarioBundle:Team')->find($_POST['idteam']);

            foreach($_POST['users'] as $item){
                $profile =  $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$item));
                $team->addProfile($profile);
            }
            $em->persist($team);
            $em->flush();
            $response = array("code" => 1, "success" => true,'mensaje'=>'OKKK!!!');
            return new Response(json_encode($response));
        }
    }

    public function gareForUserAction(){

        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $id = $user->getId();
        $idcompetenza = 0;
        $result = $this->user_comp->getPersonalsInfo($id);
        $formation = $em->getRepository('UsuarioBundle:Formation')->findOneBy(array('user'=>$id));
        $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=>$id));

        //get Competenza
        $result2 = array();
        $competenza = $em->getRepository('AdminBundle:Competenza')->findOneBy(array('profile'=>$profile->getId()));
        if(!empty($competenza)){
            $idcompetenza = $competenza->getId();
            $operes = $competenza->getOperes();
            $proggettualis = $competenza->getTypePartiProgettuali();
            $array_categ = $arr_idoperes = $arr_oper =$arr_idcateg = array();

            foreach($operes as $opere){
                //'subcategoria'=>$opere->getSubcategory()->getDescription()
                $idcategoria = $opere->getSubcategory()->getCategory()->getId();
                $name_catego = $opere->getSubcategory()->getCategory()->getName();
                $pos =0;
                if (in_array($idcategoria, $array_categ)) {
                    $pos = array_search($idcategoria, $array_categ);
                    $result2[$pos][] = array(
                        'id' => $opere->getId(),
                        'indetificazion' => $opere->getIdentificazione(),
                        'categoria'=>$name_catego
                    );
                }else{
                    unset($data);
                    $data[] = array(
                        'id' => $opere->getId(),
                        'indetificazion' => $opere->getIdentificazione(),
                        'categoria'=>$name_catego
                    );
                    $result2[] = $data;
                    $array_categ[] = $idcategoria;
                }
            }
        }

       // $result2['idcompetenza'] =  $competenza->getId();


        //echo '<pre>';print_r($result2);die;
        //echo '<pre>';print_r($this->arrayNotRepeat($data));die;
        return $this->render('AdminBundle:Admin:competenza.html.twig', array(
            'datos'=>$result,
            'projects'=>$projects = $this->user_comp->getAllProjects(),
            'formation'=>$formation->getNameOcupation(),
            'competenzas'=>$result2,
            'idcompetenza'=>$idcompetenza
        ));

    }

    //new gara
    public function newGaraAction(){

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
        //$result['idgara']= $id;

        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;

        // $gara =  $em->getRepository('AdminBundle:Gare')->find($id);
        $userGaraSelect =  $em->getRepository('AdminBundle:UserSelectGara')->findOneBy(array(
            'user'=>$iduser,
            //'gare'=>$id
        ));



        if(isset($user)){
            $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array("user"=>$iduser));
            $fullname = $profile->getName().' '.$profile->getLastname();
            $result['user']= array('user'=>$user->getUsername(),'name'=>$fullname);
        }
        //echo '<pre>';print_r($result);die;
        //$jsonencoder = new JsonEncode();
        //$result_gara = $jsonencoder->encode($this->resultGara($id),$format = 'json');

        return $this->render('AdminBundle:Admin:new_gara.html.twig',array(
                'datos'=>$result,
                'projects'=>$projects = $this->user_comp->getAllProjects()
            )
        );
    }

    public function adminsubCategoryByCategoryAction(){
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

                $data = $this->render('AdminBundle:Admin:admin_questions.html.twig', array(
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

    public function createDataOpereAction(){
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $iduser = $user->getId();
        //echo 'POST---->>>';print_r($_POST);die;
        if(!empty($_POST)){

            $competenza = new Competenza();
            //profile user
            $profile = $em->getRepository('UsuarioBundle:Profile')->findOneBy(array('user'=> $iduser));
            $arr_operes = $_POST['operes'];
            $competenza->setProfile($profile);

            foreach($arr_operes as $item_oper){
                $opere = $em->getRepository('AdminBundle:Opere')->find($item_oper['idopere']);
                $competenza->addOpere($opere);
                foreach($item_oper['progettuali'] as $proge){
                    $type_proggetuali = $em->getRepository('FrontendBundle:TypePartiProgettuali')->find($proge);
                    $competenza->addTypePartiProgettuali($type_proggetuali);
                }
            }
            //add competenza
            $em->persist($competenza);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'Ha inserito correttamente  competenza!!');
            return new Response(json_encode($response));
        }

    }

    public function admincreateCompetenciaAction(){
        $em = $this->get('doctrine')->getManager();
        if(!empty($_POST)){

            $user = $this->get('security.context')->getToken()->getUser();
            if(is_object($user)){
                $iduser = $user->getId();
                $result = $this->user_comp->getPersonalsInfo($iduser);
            }

            //echo 'admincreateCompetencia----->>>>';print_r($_POST);
            //echo 'Id Form Gara '.$_COOKIE['idformgara'];die;


            $gare  =  $em->getRepository('AdminBundle:Gare')->findOneBy(array('id'=>$_POST['idgara']));
            //echo '<pre>';print_r($gare);die;
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
                $response = array("code" => 1, "success" => true,'mensaje'=>'Il tuo curriculum è stato caricato, grazie per la tua candidatura');
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

    //insert gara opere
    public function admininsertOpereStartAction(){
        if(!empty($_POST)){
            $em = $this->get('doctrine')->getManager();

            // echo 'admininsertOpereStart ';print_r($_POST);die;
            //echo 'Id Form Gara '.$_COOKIE["idform"];die;
            $idformgara = $_POST['idformgara'];
            $formGara = $em->getRepository('AdminBundle:FormGara')->find($idformgara);
            $opereStar = new OpereStar();
            //echo 'Form Gare';print_r($formGara);die;

            $idgara = $formGara->getGare()->getId();

            $opereStar->addFormgara($formGara);
            foreach($_POST['idoperes'] as $k=>$v){
                $opere = $em->getRepository('AdminBundle:Opere')->find($v);
                $opereStar->addOpere($opere);
            }
            //add form gara
            $em->persist($opereStar);
            $em->flush();

            $response = array("code" => 1, "success" => true,'mensaje'=>'Okk','idformgara'=>$idformgara,'idgara'=> $idgara);
            return new Response(json_encode($response));

        }

    }

} 