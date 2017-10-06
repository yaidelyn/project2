<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultGara
 *
 * @ORM\Table(name="result_gara")
 * @ORM\Entity
 */
class ResultGara
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
     * @ORM\Column(name="name_gara", type="string", length=255)
     */
    private $nameGara;

    /**
     * @var string
     *
     * @ORM\Column(name="name_user", type="string", length=255)
     */
    private $nameUser;

    /**
     * @var string
     *
     * @ORM\Column(name="name_figure", type="string", length=255)
     */
    private $nameFigure;

    /**
     * @var float
     *
     * @ORM\Column(name="percent", type="float")
     */
    private $percent;


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
     * Set nameGara
     *
     * @param string $nameGara
     * @return ResultGara
     */
    public function setNameGara($nameGara)
    {
        $this->nameGara = $nameGara;

        return $this;
    }

    /**
     * Get nameGara
     *
     * @return string 
     */
    public function getNameGara()
    {
        return $this->nameGara;
    }

    /**
     * Set nameUser
     *
     * @param string $nameUser
     * @return ResultGara
     */
    public function setNameUser($nameUser)
    {
        $this->nameUser = $nameUser;

        return $this;
    }

    /**
     * Get nameUser
     *
     * @return string 
     */
    public function getNameUser()
    {
        return $this->nameUser;
    }

    /**
     * Set nameFigure
     *
     * @param string $nameFigure
     * @return ResultGara
     */
    public function setNameFigure($nameFigure)
    {
        $this->nameFigure = $nameFigure;

        return $this;
    }

    /**
     * Get nameFigure
     *
     * @return string 
     */
    public function getNameFigure()
    {
        return $this->nameFigure;
    }

    /**
     * Set percent
     *
     * @param float $percent
     * @return ResultGara
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return float 
     */
    public function getPercent()
    {
        return $this->percent;
    }
}
