<?php

namespace Admin\UsuarioBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType  extends  AbstractType
{
    public  function buildForm(FormBuilderInterface $builder,array $options){

        $builder
            ->add('name','text',array('attr'=>array('class'=>'form-control','required'=>'required')))
            ->add('lastname','text',array('required'=>'required','attr'=>array('class'=>'form-control')))
            ->add('email','	email',array('required'=>'required','attr'=>array('class'=>'form-control')))
            ->add('name_usuario','text',array('required'=>'required','attr'=>array('class'=>'form-control')))
            ->add('phone','text',array('required'=>'required','attr'=>array('class'=>'form-control')))
            ->add('mobile','text',array('required'=>'required','attr'=>array('class'=>'form-control')))
            ->add('address','textarea',array('required'=>'required','attr'=>array('class'=>'form-control')))
            ->add('date_created','date',array('required'=>'required','attr'=>array('class'=>'form-control')))
        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){

        $resolver->setDefaults(array(
            'data_class' => 'Admin\UsuarioBundle\Entity\Usuario'
        ));
    }


    public function getName(){

        return '';
    }


} 