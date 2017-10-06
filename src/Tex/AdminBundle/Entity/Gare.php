<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gare
 *
 * @ORM\Table(name="gare")
 * @ORM\Entity
 */
class Gare
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
     * @var \Opere
     *
     * @ORM\ManyToMany(targetEntity="Opere",inversedBy="operes")
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $operes;


    /**
     * @var \GareTeam
     *
     * @ORM\ManyToMany(targetEntity="GareTeam",inversedBy="gares_team")
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $gareteams;

    /**
     * @var string
     *
     * @ORM\Column(name="rif_bando", type="string", length=255)
     */
    private $rifBando;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="scadenza_gara", type="datetime")
     */
    private $scadenzaGara;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="scadenza_canditura", type="datetime")
     */
    private $scadenzaCanditura;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_gara", type="string", length=255)
     */
    private $cod_gara;


    /**
     * @var integer
     *
     * @ORM\Column(name="cap_team", type="integer")
     */
    private $capTeam;


    /**
     * @var float
     *
     * @ORM\Column(name="importe", type="float")
     */
    private $importe;


    /**
     * @var text
     *
     * @ORM\Column(name="objective", type="text")
     */
    private $objective;

    /**
     * @ORM\ManyToOne(targetEntity="TipologiaGara", inversedBy="tipologiagara")
     * @ORM\JoinColumn(name="tipologia_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $tipologia;


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
     * @return Gare
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
     * Set rifBando
     *
     * @param string $rifBando
     * @return Gare
     */
    public function setRifBando($rifBando)
    {
        $this->rifBando = $rifBando;

        return $this;
    }

    /**
     * Get rifBando
     *
     * @return string 
     */
    public function getRifBando()
    {
        return $this->rifBando;
    }

    /**
     * Set scadenzaGara
     *
     * @param \DateTime $scadenzaGara
     * @return Gare
     */
    public function setScadenzaGara($scadenzaGara)
    {
        $this->scadenzaGara = $scadenzaGara;

        return $this;
    }

    /**
     * Get scadenzaGara
     *
     * @return \DateTime 
     */
    public function getScadenzaGara()
    {
        return $this->scadenzaGara;
    }

    /**
     * Set scadenzaCanditura
     *
     * @param \DateTime $scadenzaCanditura
     * @return Gare
     */
    public function setScadenzaCanditura($scadenzaCanditura)
    {
        $this->scadenzaCanditura = $scadenzaCanditura;

        return $this;
    }

    /**
     * Get scadenzaCanditura
     *
     * @return \DateTime 
     */
    public function getScadenzaCanditura()
    {
        return $this->scadenzaCanditura;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->operes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add operes
     *
     * @param \Tex\AdminBundle\Entity\Opere $operes
     * @return Gare
     */
    public function addOpere(\Tex\AdminBundle\Entity\Opere $operes)
    {
        if(!$this->operes->contains($operes)){
            $this->operes[] = $operes;
        }else{
            $this->operes->remove($this->id);
        }
        return $this;
    }

    /**
     * Remove operes
     *
     * @param \Tex\AdminBundle\Entity\Opere $operes
     */
    public function removeOpere(\Tex\AdminBundle\Entity\Opere $operes)
    {
        $this->operes->removeElement($operes);
    }

    /**
     * Get operes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperes()
    {
        return $this->operes;
    }

    /**
     * Add gareteams
     *
     * @param \Tex\AdminBundle\Entity\GareTeam $gareteams
     * @return Gare
     */
    public function addGareteam(\Tex\AdminBundle\Entity\GareTeam $gareteams)
    {
        $this->gareteams[] = $gareteams;

        return $this;
    }

    /**
     * Remove gareteams
     *
     * @param \Tex\AdminBundle\Entity\GareTeam $gareteams
     */
    public function removeGareteam(\Tex\AdminBundle\Entity\GareTeam $gareteams)
    {
        $this->gareteams->removeElement($gareteams);
    }

    /**
     * Get gareteams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGareteams()
    {
        return $this->gareteams;
    }

    /**
     * Set cod_gara
     *
     * @param string $codGara
     * @return Gare
     */
    public function setCodGara($codGara)
    {
        $this->cod_gara = $codGara;

        return $this;
    }

    /**
     * Get cod_gara
     *
     * @return string 
     */
    public function getCodGara()
    {
        return $this->cod_gara;
    }

    /**
     * Set capTeam
     *
     * @param integer $capTeam
     * @return Gare
     */
    public function setCapTeam($capTeam)
    {
        $this->capTeam = $capTeam;

        return $this;
    }

    /**
     * Get capTeam
     *
     * @return integer 
     */
    public function getCapTeam()
    {
        return $this->capTeam;
    }

    /**
     * Set objective
     *
     * @param string $objective
     * @return Gare
     */
    public function setObjective($objective)
    {
        $this->objective = $objective;

        return $this;
    }

    /**
     * Get objective
     *
     * @return string 
     */
    public function getObjective()
    {
        return $this->objective;
    }

    /**
     * Set tipologia
     *
     * @param \Tex\AdminBundle\Entity\TipologiaGara $tipologia
     * @return Gare
     */
    public function setTipologia(\Tex\AdminBundle\Entity\TipologiaGara $tipologia = null)
    {
        $this->tipologia = $tipologia;

        return $this;
    }

    /**
     * Get tipologia
     *
     * @return \Tex\AdminBundle\Entity\TipologiaGara 
     */
    public function getTipologia()
    {
        return $this->tipologia;
    }

    /**
     * Set importe
     *
     * @param float $importe
     * @return Gare
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return float 
     */
    public function getImporte()
    {
        return $this->importe;
    }
}
