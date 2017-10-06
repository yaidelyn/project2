<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpereStar
 *
 * @ORM\Table(name="opere_star")
 * @ORM\Entity
 */
class OpereStar
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var \Opere
     *
     * @ORM\ManyToMany(targetEntity="Opere",inversedBy="operes")
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $operes;


    /**
     * @var \FormGara
     *
     * @ORM\ManyToMany(targetEntity="FormGara",inversedBy="formgaras")
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $formgaras;


    /**
     * @var \Tex\FrontendBundle\Entity\TypePartiProgettuali
     *
     * @ORM\ManyToMany(targetEntity="Tex\FrontendBundle\Entity\TypePartiProgettuali",inversedBy="typeprogettualis")
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $typeprogettualis;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formgaras = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->typeprogettualis = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add formgaras
     *
     * @param \Tex\AdminBundle\Entity\FormGara $formgaras
     * @return OpereStar
     */
    public function addFormgara(\Tex\AdminBundle\Entity\FormGara $formgaras)
    {
        if(! $this->formgaras->contains($formgaras)){
            $this->formgaras[] = $formgaras;
        }else{
            $this->formgaras->remove($this->id);
        }
        return $this;

        /*$this->formgaras[] = $formgaras;

        return $this;*/
    }

    /**
     * Remove formgaras
     *
     * @param \Tex\AdminBundle\Entity\FormGara $formgaras
     */
    public function removeFormgara(\Tex\AdminBundle\Entity\FormGara $formgaras)
    {
        $this->formgaras->removeElement($formgaras);
    }

    /**
     * Get formgaras
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormgaras()
    {
        return $this->formgaras;
    }

    /**
     * Add operes
     *
     * @param \Tex\AdminBundle\Entity\Opere $operes
     * @return OpereStar
     */
    public function addOpere(\Tex\AdminBundle\Entity\Opere $operes)
    {
        if(!$this->operes->contains($operes)){
            $this->operes[] = $operes;
        }else{
            $this->operes->remove($this->id);
        }
        return $this;
       // return $this;
    }

    /**
     * Remove operes
     *
     * @param \Tex\AdminBundle\Entity\Opere $operes
     */
    public function removeOpere(\Tex\AdminBundle\Entity\Opere $operes)
    {
        $this->operes->removeElement($operes);
    }

    /**
     * Get operes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperes()
    {
        return $this->operes;
    }

    /**
     * Add typeprogettualis
     *
     * @param \Tex\FrontendBundle\Entity\TypePartiProgettuali $typeprogettualis
     * @return OpereStar
     */
    public function addTypeprogettuali(\Tex\FrontendBundle\Entity\TypePartiProgettuali $typeprogettualis)
    {
        if(!$this->typeprogettualis->contains($typeprogettualis)){
            $this->typeprogettualis[] = $typeprogettualis;
        }else{
            $this->typeprogettualis->remove($this->id);
        }
        return $this;


    }

    /**
     * Remove typeprogettualis
     *
     * @param \Tex\FrontendBundle\Entity\TypePartiProgettuali $typeprogettualis
     */
    public function removeTypeprogettuali(\Tex\FrontendBundle\Entity\TypePartiProgettuali $typeprogettualis)
    {
        $this->typeprogettualis->removeElement($typeprogettualis);
    }

    /**
     * Get typeprogettualis
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypeprogettualis()
    {
        return $this->typeprogettualis;
    }
}
