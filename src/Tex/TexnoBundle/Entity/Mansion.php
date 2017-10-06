<?php

namespace Tex\TexnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


 /**
 * Mansion
 * @ORM\Table(name="mansion")
 * @ORM\Entity
 */
class Mansion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nomeMansion;

    /**
     * @var float
     */
    private $value;

    /**
     * @var integer
     */
    private $juniorSenior;

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
     * Set nomeMansion
     *
     * @param string $nomeMansion
     * @return Mansion
     */
    public function setNomeMansion($nomeMansion)
    {
        $this->nomeMansion = $nomeMansion;

        return $this;
    }

    /**
     * Get nomeMansion
     *
     * @return string 
     */
    public function getNomeMansion()
    {
        return $this->nomeMansion;
    }

    /**
     * Set value
     *
     * @param float $value
     * @return Mansion
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set juniorSenior
     *
     * @param integer $juniorSenior
     * @return Mansion
     */
    public function setJuniorSenior($juniorSenior)
    {
        $this->juniorSenior = $juniorSenior;

        return $this;
    }

    /**
     * Get juniorSenior
     *
     * @return integer 
     */
    public function getJuniorSenior()
    {
        return $this->juniorSenior;
    }

    /**
     * Set idPerson
     *
     * @param integer $idPerson
     * @return Mansion
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
