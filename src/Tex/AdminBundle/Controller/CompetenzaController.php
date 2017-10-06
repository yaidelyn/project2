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


class CompetenzaController  extends Controller
{

    var $user_comp;


    public function __construct(){
        $this->user_comp = new UserComponent($this);

    }

    public function deleteCompetenzaAction($id){

        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if(isset($id)){
            $competenza = $em->getRepository('AdminBundle:Competenza')->find($id);

            $em->remove($competenza);
            $em->flush();

            return $this->redirect(
                $this->generateUrl('user_result_gara')
            );

        }

    }
	public function updateCompetenzaAction($id){
        $em = $this->get('doctrine')->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if(isset($id)){
            $competenza = $em->getRepository('AdminBundle:Competenza')->find($id);
            $em->remove($competenza);
            $em->flush();

            return $this->redirect(
                $this->generateUrl('new_gara')
            );

        }
    }


} 