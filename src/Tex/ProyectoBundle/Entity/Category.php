<?php

namespace Tex\ProyectoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
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
     * @ORM\Column(name="name_cat", type="string", length=255)
     */
    private $nameCat;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="category")
     */
    protected $pojects;


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
     * Set nameCat
     *
     * @param string $nameCat
     * @return Category
     */
    public function setNameCat($nameCat)
    {
        $this->nameCat = $nameCat;

        return $this;
    }

    /**
     * Get nameCat
     *
     * @return string 
     */
    public function getNameCat()
    {
        return $this->nameCat;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pojects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pojects
     *
     * @param \Tex\ProyectoBundle\Entity\Project $pojects
     * @return Category
     */
    public function addPoject(\Tex\ProyectoBundle\Entity\Project $pojects)
    {
        $this->pojects[] = $pojects;

        return $this;
    }

    /**
     * Remove pojects
     *
     * @param \Tex\ProyectoBundle\Entity\Project $pojects
     */
    public function removePoject(\Admin\ProyectoBundle\Entity\Project $pojects)
    {
        $this->pojects->removeElement($pojects);
    }

    /**
     * Get pojects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPojects()
    {
        return $this->pojects;
    }
}
