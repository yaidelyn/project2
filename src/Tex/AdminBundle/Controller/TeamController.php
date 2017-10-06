<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 29/11/2016
 * Time: 21:39
 */

namespace Tex\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tex\UsuarioBundle\Entity\Team;
use Tex\UsuarioBundle\Entity\User;
use Tex\UsuarioBundle\Entity\Formation;
use Tex\UsuarioBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller{

    public function listTeamAction(){

        $em = $this->get('doctrine')->getManager();
        $teams = $em->getRepository('UsuarioBundle:Team')->findAll();

        foreach($teams as $item){
            $data['name'] = $item->getName();
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
            $data['leader'] = $user_profile->getName().' '.$user_profile->getLastname() ;


            $result[] = $data;
        }
        //echo '<pre>';print_r($result);die;

    }

} 