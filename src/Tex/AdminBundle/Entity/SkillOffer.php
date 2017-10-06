<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SkillOffer
 *
 * @ORM\Table(name="skilloffer")
 * @ORM\Entity
 */
class SkillOffer
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

     /**
     * @ORM\ManyToOne(targetEntity="Offer", inversedBy="skills")
     * @ORM\JoinColumn(name="offer_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $offer;


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
     * Set name
     *
     * @param string $name
     * @return SkillOffer
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
     * Set offer
     *
     * @param \Tex\AdminBundle\Entity\Offer $offer
     * @return SkillOffer
     */
    public function setOffer(\Tex\AdminBundle\Entity\Offer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return \Tex\AdminBundle\Entity\Offer 
     */
    public function getOffer()
    {
        return $this->offer;
    }
}
