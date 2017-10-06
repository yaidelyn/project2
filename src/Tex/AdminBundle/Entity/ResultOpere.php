<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultOpere
 *
 * @ORM\Table(name="result_opere")
 * @ORM\Entity
 */
class ResultOpere
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
     * @ORM\ManyToOne(targetEntity="FormGara")
     * @ORM\JoinColumn(name="formgara_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $formgara;


    /**
     * @var \Gare
     *
     * @ORM\ManyToOne(targetEntity="Gare")
     * @ORM\JoinColumn(name="gare_id", referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $gare;



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
     * Set formgara
     *
     * @param \Tex\AdminBundle\Entity\FormGara $formgara
     * @return ResultOpere
     */
    public function setFormgara(\Tex\AdminBundle\Entity\FormGara $formgara = null)
    {
        $this->formgara = $formgara;

        return $this;
    }

    /**
     * Get formgara
     *
     * @return \Tex\AdminBundle\Entity\FormGara 
     */
    public function getFormgara()
    {
        return $this->formgara;
    }

    /**
     * Set gare
     *
     * @param \Tex\AdminBundle\Entity\Gare $gare
     * @return ResultOpere
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
}
