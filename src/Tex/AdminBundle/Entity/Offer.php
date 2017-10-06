<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offer
 *
 * @ORM\Table(name="offer")
 * @ORM\Entity
 */
class Offer
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="CategOffer", inversedBy="category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $category;


    /**
     * @ORM\ManyToOne(targetEntity="SubCategory", inversedBy="subcategory")
     * @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $subcategory;

    /**
     * @var integer
     *  @ORM\Column(name="active", type="integer")
     */
    private $active;

    /**
     * @var float
     *
     * @ORM\Column(name="budget", type="float")
     */
    private $budget;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Tex\UsuarioBundle\Entity\User", inversedBy="offer")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected  $user;


    /**
     * @ORM\OneToMany(targetEntity="SkillOffer", mappedBy="offer",cascade={"remove"}, orphanRemoval=true)
     */
    protected $skills;

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
     * Set title
     *
     * @param string $title
     * @return Offer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set budget
     *
     * @param float $budget
     * @return Offer
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return float 
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Offer
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skills = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \Tex\UsuarioBundle\Entity\User $user
     * @return Offer
     */
    public function setUser(\Tex\UsuarioBundle\Entity\User $user = null)
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
     * Add skills
     *
     * @param \Tex\AdminBundle\Entity\SkillOffer $skills
     * @return Offer
     */
    public function addSkill(\Tex\AdminBundle\Entity\SkillOffer $skills)
    {
        $this->skills[] = $skills;

        return $this;
    }

    /**
     * Remove skills
     *
     * @param \Tex\AdminBundle\Entity\SkillOffer $skills
     */
    public function removeSkill(\Tex\AdminBundle\Entity\SkillOffer $skills)
    {
        $this->skills->removeElement($skills);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set category
     *
     * @param \Tex\AdminBundle\Entity\CategOffer $category
     * @return Offer
     */
    public function setCategory(\Tex\AdminBundle\Entity\CategOffer $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tex\AdminBundle\Entity\CategOffer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set subcategory
     *
     * @param \Tex\AdminBundle\Entity\SubCategory $subcategory
     * @return Offer
     */
    public function setSubcategory(\Tex\AdminBundle\Entity\SubCategory $subcategory = null)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * Get subcategory
     *
     * @return \Tex\AdminBundle\Entity\SubCategory 
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Set active
     *
     * @param integer $active
     * @return Offer
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return integer 
     */
    public function getActive()
    {
        return $this->active;
    }
}
