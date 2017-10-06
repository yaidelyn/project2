<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Opere
 *
 * @ORM\Table(name="opere")
 * @ORM\Entity
 */
class Opere
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var text
     *
     * @ORM\Column(name="identificazione", type="text")
     */
    private $identificazione;


    /**
     * @var float
     *
     * @ORM\Column(name="gradi", type="float")
     */
    private $gradi;



    /**
     * @ORM\ManyToOne(targetEntity="SubCategory", inversedBy="subcategories")
     * @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $subcategory;

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
     * Set code
     *
     * @param string $code
     * @return Opere
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set identificazione
     *
     * @param string $identificazione
     * @return Opere
     */
    public function setIdentificazione($identificazione)
    {
        $this->identificazione = $identificazione;

        return $this;
    }

    /**
     * Get identificazione
     *
     * @return string 
     */
    public function getIdentificazione()
    {
        return $this->identificazione;
    }

    /**
     * Set gradi
     *
     * @param float $gradi
     * @return Opere
     */
    public function setGradi($gradi)
    {
        $this->gradi = $gradi;

        return $this;
    }

    /**
     * Get gradi
     *
     * @return float 
     */
    public function getGradi()
    {
        return $this->gradi;
    }

    /**
     * Set subcategory
     *
     * @param \Tex\AdminBundle\Entity\SubCategory $subcategory
     * @return Opere
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
}
