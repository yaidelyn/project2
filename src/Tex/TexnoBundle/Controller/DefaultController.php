<?php

namespace Tex\TexnoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Date;
use Tex\TexnoBundle\Entity\Altro;
use Tex\TexnoBundle\Entity\Extras;
use Tex\TexnoBundle\Entity\Mansion;
use Tex\TexnoBundle\Entity\Person;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $data = array(
            'cant_junior'=>2,
            'cant_senior'=>3,
            'cant_service'=>2,
            'importo'=>25000,
            'date'=>new \DateTime('now')
        );

        return $this->render('TexnoBundle:Default:index.html.twig', array());
    }

    public function calcoloAction(){

        return  $this->render('TexnoBundle:Default:preventivo.html.twig', array());
    }

    public function insertDataAction(){

        if(!empty($_POST['data'])){

            $em = $this->getDoctrine()->getManager();

            $nome = $_POST['data']['nome'];
            $totale = $_POST['data']['txt_totale'];

            $addressTo = array("" . $_POST['data']['email'] . "", 'tex07556@gmail.com');

            $person = new Person();
            $region = $_POST['data']['regione'];
            $iva = $_POST['data']['iva'];
            $phone = $_POST['data']['phone'];
            $cell = $_POST['data']['cell'];
            $email_dato = $_POST['data']['email'];
            $web = $_POST['data']['web'];

            $person->setNome( $nome);
            $person->setRegione($region);
            $person->setIva($iva);
            $person->setPhone($phone);
            $person->setCell($cell);
            $person->setEmail($email_dato);
            $person->setWeb($web);


            $em->persist($person);
            //Insertarmos en la base de datos
            $flush = $em->flush();

            $id_person = $person->getId();

            //Add Altro
            $committente = $_POST['data']['committente'];
            $oggetto = $_POST['data']['oggetto'];
            $importo = $_POST['data']['importo'];
            $scadenza = $_POST['data']['scadenza'];
            $criteria = $_POST['data']['criteria'];
            $altro = $_POST['data']['altro'];

            $altro = new Altro();

            $altro->setCommittente($committente);
            $altro->setOggetto($oggetto);
            $altro->setImporto($importo);
            $altro->setScadenza(new \DateTime('now'));
            $altro->setCritAggiudi($criteria);
            $altro->setAltro('');
            $altro->setIdPerson($id_person);

            $em->persist($altro);
            //Insertarmos en la base de datos
            $flush = $em->flush();


            // insert mansiones junior


            if (!empty($_POST['data']['list_j'])) {
                $list_j = $_POST['data']['list_j'];
                foreach ($list_j as $item_j) {
                    $mansion = new Mansion();
                    $mansion->setNomeMansion($item_j['name_prop']);
                    $mansion->setValue($item_j['value_prop']);
                    $mansion->setJuniorSenior(0);
                    $mansion->setIdPerson($id_person);

                    $em->persist($mansion);
                    //Insertarmos en la base de datos
                    $flush = $em->flush();
                   // $obj->insertMansion($item_j['name_prop'], $item_j['value_prop'], 0, $insertId);
                }
            }

            // insert mansiones senior
            if (!empty($_POST['data']['list_s'])) {
                $list_s = $_POST['data']['list_j'];
                foreach ($list_s as $item_s) {
                    $mansion = new Mansion();
                    $mansion->setNomeMansion($item_s['name_prop']);
                    $mansion->setValue($item_s['value_prop']);
                    $mansion->setJuniorSenior(1);
                    $mansion->setIdPerson($id_person);

                    $em->persist($mansion);
                    //Insertarmos en la base de datos
                    $flush = $em->flush();
                   // $obj->insertMansion($item_s['name_prop'], $item_s['value_prop'], 1, $insertId);
                }
            }

            //insert extras
            if (!empty($_POST['data']['list_extras'])) {
                $list_e = $_POST['data']['list_extras'];
                foreach ($list_e as $item_e) {
                    $extra = new Extras();
                    $extra->setNomeExtra($item_e['name_prop']);
                    $extra->setValore($item_e['value_prop']);
                    $extra->setIdPerson($id_person);

                    $em->persist($extra);
                    //Insertarmos en la base de datos
                    $flush = $em->flush();
                    //$obj->insertExtra($item_e['name_prop'], $item_e['value_prop'], $insertId);
                }

            }


            $msg1 =array(
				'date'=>date('l').','.date('d').' '.date('F'),
                'cant_junior'=>2,
                'cant_senior'=>4,
                'cant_service'=>2,
                'totale'=>$totale

            );

            $msg2 = array(
				'date'=>date('l').','.date('d').' '.date('F'),
                'nome'=>$nome,
                'phone'=>$phone,
                'email'=>$email_dato

            );

           /* if ($_POST['data']['txt-check'] == 1) {


                //$msg1 = 'Grazie per aver inviato il tuo preventivo. A breve riceverai un nostro un nostro contatto e un preventivo ad hoc.';
                //$msg2 = "Dati Personali\n Nome: " . $nome . "\nTelefono: " . $phone . "\nE-mail: " . $email_dato . "</strong>";

            } else {
                $msg1 =array(
                    'msg'=>"Grazie per aver inviato il tuo preventivo A breve riceverai un nostro contatto Il totale del tuo preventivo è <strong>€" . $totale . "</strong>",

                );

                $msg2 = array(
                    'msg'=>'Gentile cliente abbiamo ricevuto la sua gentile richiesta, a breve sarà contattato da un nostro commerciale.',
                    'nome'=>$nome,
                    'telefono'=>$phone,
                    'email'=>$email_dato

                );
                //$msg1 = "Grazie per aver inviato il tuo preventivo A breve riceverai un nostro contatto Il totale del tuo preventivo è <strong>€" . $totale . "</strong>";
                //$msg2 = "Dati Personali\n Nome: " . $nome . "\nTelefono: " . $phone . "\nE-mail: " . $email_dato . "\nPreventivo:<strong>€" . $totale . "</strong>";
            }*/

            $email1 = \Swift_Message::newInstance()
                ->setSubject('Test')
                ->setFrom('tex07556@gmail.com')
                ->setTo("" . $_POST['data']['email'] . "")
                ->setBody(
                    $this->renderView(
                        'TexnoBundle:Default:email_client.html.twig',
                        array('data_client'=>$msg2)
                    ),
                    'text/html'
                );

            $email2 = \Swift_Message::newInstance()
                ->setSubject('Test')
                ->setFrom('tex07556@gmail.com')
                ->setTo("tex07556@gmail.com")
                ->setBody($this->renderView('TexnoBundle:Default:email_tex.html.twig',array('data_tex'=>$msg1)),
                    'text/html');

            if( $this->get('mailer')->send($email1) &&  $this->get('mailer')->send($email2))
                echo json_encode(array('error' => 0, 'message' => 'OK'));
                die;
        }
    }

}
