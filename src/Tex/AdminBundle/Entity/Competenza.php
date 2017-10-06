<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Competenza
 *
 * @ORM\Table(name="competenza")
 * @ORM\Entity
 */
class Competenza
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var \Tex\UsuarioBundle\Entity\Profile
     *
     * @ORM\ManyToOne(targetEntity="\Tex\UsuarioBundle\Entity\Profile")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     * })
     */
    private $profile;

    /**
     * @var \TypePartiProgettuali
     *
     * @ORM\ManyToMany(targetEntity="\Tex\FrontendBundle\Entity\TypePartiProgettuali",inversedBy="type_parti_progettuali")
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $type_parti_progettuali;

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
     * Constructor
     */
    public function __construct()
    {
        $this->type_parti_progettuali = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set profile
     *
     * @param \Tex\UsuarioBundle\Entity\Profile $profile
     * @return Competenza
     */
    public function setProfile(\Tex\UsuarioBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Tex\UsuarioBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Add type_parti_progettuali
     *
     * @param \Tex\FrontendBundle\Entity\TypePartiProgettuali $typePartiProgettuali
     * @return Competenza
     */
    public function addTypePartiProgettuali(\Tex\FrontendBundle\Entity\TypePartiProgettuali $typePartiProgettuali)
    {
        if(!$this->type_parti_progettuali->contains($typePartiProgettuali)){
            $this->type_parti_progettuali[] = $typePartiProgettuali;
        }else{
            $this->type_parti_progettuali->remove($this->id);
        }
        return $this;
    }

    /**
     * Remove type_parti_progettuali
     *
     * @param \Tex\FrontendBundle\Entity\TypePartiProgettuali $typePartiProgettuali
     */
    public function removeTypePartiProgettuali(\Tex\FrontendBundle\Entity\TypePartiProgettuali $typePartiProgettuali)
    {
        $this->type_parti_progettuali->removeElement($typePartiProgettuali);
    }

    /**
     * Get type_parti_progettuali
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypePartiProgettuali()
    {
        return $this->type_parti_progettuali;
    }

    /**
     * Add operes
     *
     * @param \Tex\AdminBundle\Entity\Opere $operes
     * @return Competenza
     */
    public function addOpere(\Tex\AdminBundle\Entity\Opere $operes)
    {
        if(!$this->operes->contains($operes)){
            $this->operes[] = $operes;
        }else{
            $this->operes->remove($this->id);
        }
        return $this;
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
}
