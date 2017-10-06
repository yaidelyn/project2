<?php

namespace Tex\TexnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Extras
 * @ORM\Table(name="extras")
 * @ORM\Entity
 */
class Extras
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nomeExtra;

    /**
     * @var float
     */
    private $valore;

    /**
     * @var integer
     */
    private $idPerson;


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
     * Set nomeExtra
     *
     * @param string $nomeExtra
     * @return Extras
     */
    public function setNomeExtra($nomeExtra)
    {
        $this->nomeExtra = $nomeExtra;

        return $this;
    }

    /**
     * Get nomeExtra
     *
     * @return string 
     */
    public function getNomeExtra()
    {
        return $this->nomeExtra;
    }

    /**
     * Set valore
     *
     * @param float $valore
     * @return Extras
     */
    public function setValore($valore)
    {
        $this->valore = $valore;

        return $this;
    }

    /**
     * Get valore
     *
     * @return float 
     */
    public function getValore()
    {
        return $this->valore;
    }

    /**
     * Set idPerson
     *
     * @param integer $idPerson
     * @return Extras
     */
    public function setIdPerson($idPerson)
    {
        $this->idPerson = $idPerson;

        return $this;
    }

    /**
     * Get idPerson
     *
     * @return integer 
     */
    public function getIdPerson()
    {
        return $this->idPerson;
    }
}
