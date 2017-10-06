<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * FormGara
 *
 * @ORM\Table(name="form_gara")
 * @ORM\Entity
 */
class FormGara
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
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="createBy", type="string", length=255)
     */
    private $createBy;


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
     * @ORM\ManyToOne(targetEntity="GareTeam")
     * @ORM\JoinColumn(name="gareteam_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $gareteam;

    /**
     * @ORM\ManyToOne(targetEntity="Gare")
     * @ORM\JoinColumn(name="gare_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $gare;


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
        $this->type_parti_progettuali = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return FormGara
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set createBy
     *
     * @param string $createBy
     * @return FormGara
     */
    public function setCreateBy($createBy)
    {
        $this->createBy = $createBy;

        return $this;
    }

    /**
     * Get createBy
     *
     * @return string 
     */
    public function getCreateBy()
    {
        return $this->createBy;
    }

    /**
     * Set gareteam
     *
     * @param \Tex\AdminBundle\Entity\GareTeam $gareteam
     * @return FormGara
     */
    public function setGareteam(\Tex\AdminBundle\Entity\GareTeam $gareteam = null)
    {
        $this->gareteam = $gareteam;

        return $this;
    }

    /**
     * Get gareteam
     *
     * @return \Tex\AdminBundle\Entity\GareTeam 
     */
    public function getGareteam()
    {
        return $this->gareteam;
    }

    /**
     * Set gare
     *
     * @param \Tex\AdminBundle\Entity\Gare $gare
     * @return FormGara
     */
    public function setGare(\Tex\AdminBundle\Entity\Gare $gare = null)
    {
        $this->gare = $gare;

        return $this;
    }

    /**
     * Get gare
     *
     * @return \Tex\AdminBundle\Entity\Gare 
     */
    public function getGare()
    {
        return $this->gare;
    }

    /**
     * Add type_parti_progettuali
     *
     * @param \Tex\FrontendBundle\Entity\TypePartiProgettuali $typePartiProgettuali
     * @return FormGara
     */
    public function addTypePartiProgettuali(\Tex\FrontendBundle\Entity\TypePartiProgettuali $typePartiProgettuali)
    {
        if(!$this->type_parti_progettuali->contains($typePartiProgettuali)){
       $this->type_parti_progettuali[] = $typePartiProgettuali;
        }else{
            $this->type_parti_progettuali->remove($this->id);
        }
        return $this;

       /* $this->type_parti_progettuali[] = $typePartiProgettuali;

        return $this;*/
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
     * @return FormGara
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
