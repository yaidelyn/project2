<?php

namespace Tex\TexnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


 /**
 * Altro
 * @ORM\Table(name="altro")
 * @ORM\Entity
 */
class Altro
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $committente;

    /**
     * @var string
     */
    private $oggetto;

    /**
     * @var float
     */
    private $importo;

    /**
     * @var \DateTime
     */
    private $scadenza;

    /**
     * @var string
     */
    private $critAggiudi;

    /**
     * @var string
     */
    private $altro;

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
     * Set committente
     *
     * @param string $committente
     * @return Altro
     */
    public function setCommittente($committente)
    {
        $this->committente = $committente;

        return $this;
    }

    /**
     * Get committente
     *
     * @return string 
     */
    public function getCommittente()
    {
        return $this->committente;
    }

    /**
     * Set oggetto
     *
     * @param string $oggetto
     * @return Altro
     */
    public function setOggetto($oggetto)
    {
        $this->oggetto = $oggetto;

        return $this;
    }

    /**
     * Get oggetto
     *
     * @return string 
     */
    public function getOggetto()
    {
        return $this->oggetto;
    }

    /**
     * Set importo
     *
     * @param float $importo
     * @return Altro
     */
    public function setImporto($importo)
    {
        $this->importo = $importo;

        return $this;
    }

    /**
     * Get importo
     *
     * @return float 
     */
    public function getImporto()
    {
        return $this->importo;
    }

    /**
     * Set scadenza
     *
     * @param \DateTime $scadenza
     * @return Altro
     */
    public function setScadenza($scadenza)
    {
        $this->scadenza = $scadenza;

        return $this;
    }

    /**
     * Get scadenza
     *
     * @return \DateTime 
     */
    public function getScadenza()
    {
        return $this->scadenza;
    }

    /**
     * Set critAggiudi
     *
     * @param string $critAggiudi
     * @return Altro
     */
    public function setCritAggiudi($critAggiudi)
    {
        $this->critAggiudi = $critAggiudi;

        return $this;
    }

    /**
     * Get critAggiudi
     *
     * @return string 
     */
    public function getCritAggiudi()
    {
        return $this->critAggiudi;
    }

    /**
     * Set altro
     *
     * @param string $altro
     * @return Altro
     */
    public function setAltro($altro)
    {
        $this->altro = $altro;

        return $this;
    }

    /**
     * Get altro
     *
     * @return string 
     */
    public function getAltro()
    {
        return $this->altro;
    }

    /**
     * Set idPerson
     *
     * @param integer $idPerson
     * @return Altro
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
