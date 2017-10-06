<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubCategory
 *
 * @ORM\Table(name="subcategory")
 * @ORM\Entity
 */
class SubCategory
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="CategOffer", inversedBy="category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id",onDelete="CASCADE")
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
     * Set description
     *
     * @param string $description
     * @return SubCategory
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param \Tex\AdminBundle\Entity\CategOffer $category
     * @return SubCategory
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
}
