<?php

namespace Tex\ProyectoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Project
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
     * @ORM\ManyToOne(targetEntity="Tex\UsuarioBundle\Entity\User", inversedBy="projects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected  $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="cant_prof", type="integer")
     */
    private $cantProf;



    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="projects")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;


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
     * Set user
     *
     * @param \Tex\UsuarioBundle\Entity\User $user
     * @return Project
     */
    public function setUser(\Tex\UsuarioBundle\Entity\User  $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \Tex\UsuarioBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Project
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Project
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set cantProf
     *
     * @param integer $cantProf
     * @return Project
     */
    public function setCantProf($cantProf)
    {
        $this->cantProf = $cantProf;

        return $this;
    }

    /**
     * Get cantProf
     *
     * @return integer 
     */
    public function getCantProf()
    {
        return $this->cantProf;
    }

    /**
     * Set category
     *
     * @param \Tex\ProyectoBundle\Entity\Category $category
     * @return Project
     */
    public function setCategory(\Tex\ProyectoBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Admin\ProyectoBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
