<?php
namespace Tex\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

class FrontendController extends Controller
{
    public function aboutAction(){

        $locale = $this->get('request')->getLocale();
        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;
        $result['menu_home']= "";
        $result['menu_about']= "active";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_opportunita']= "";
        return $this->render('UsuarioBundle:Default:about.html.twig',array('datos'=>$result));
    }

    public function contactAction(){


        $locale = $this->get('request')->getLocale();
        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;
        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "active";
        $result['menu_opportunita']= "";

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

        $msg2 = array(
            'date'=>date('l').','.date('d').' '.date('F'),
            'email'=>$message['email']

        );

        $email = $message['email'];

        //send email
          $email1 = \Swift_Message::newInstance()
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
		$this->get('mailer')->send($email1);	  

    }

    public function showPageOfferAction($id){

        $em = $this->get('doctrine')->getManager();

      $categoria =  $em->getRepository('AdminBundle:CategOffer')->find($id);



        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;

       $offers = $em->getRepository('AdminBundle:Offer')->findBy(
            array('category'=>$id),
            array('id' => 'DESC')
            );


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
        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_opportunita']= "active";
      //  $result['menu_opportunita']= "active";

        return $this->render('UsuarioBundle:Default:page_show.html.twig',array('datos'=>$result,'category'=>$categoria->getName()));

    }

    //get all questions and answers x test
    public function getQuestionsAnswers($id){

        $em = $this->get('doctrine')->getManager();

        $questions = $em->getRepository('AdminBundle:Questions')->findBy(array(
            'test'=>$id
        ));

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

        return $result;

    }

  
    public function loginExternoAction(Request $request){


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

        if(isset($test))
            $result['name_test'] = $test->getName();
        $result['test'] = $this->getQuestionsAnswers($id);
       // $result['offer'][] = $array;

        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "";
        $result['menu_opportunita']= "active";
       //$result['menu_opportunita']= "active";

      //echo '<pre>';print_r($result);die;

        return $this->render('UsuarioBundle:Default:show_offer.html.twig',array('datos'=>$result));
    }

    public function aboutPageAction(){



        $result["login"] = ($this->get('security.context')->isGranted('ROLE_USER')||
            $this->get('security.context')->isGranted('ROLE_ADMIN')) ? 1 : 0;
        $result['menu_home']= "";
        $result['menu_about']= "";
        $result['menu_project']= "";
        $result['menu_contact']= "active";
        $result['menu_opportunita']= "";


        return $this->render('UsuarioBundle:Frontend:page_about.html.twig',array('datos'=>$result));
    }


}