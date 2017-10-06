<?php

namespace Tex\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypePartiProgettuali
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TypePartiProgettuali
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
     * @ORM\ManyToOne(targetEntity="PartiProgettuali", inversedBy="parti_progettali")
     * @ORM\JoinColumn(name="parti_progettali_id", referencedColumnName="id",onDelete="CASCADE")
     */

    protected $parti_progettali;



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
     * @return TypePartiProgettuali
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
     * Set parti_progettali
     *
     * @param \Tex\FrontendBundle\Entity\PartiProgettuali $partiProgettali
     * @return TypePartiProgettuali
     */
    public function setPartiProgettali(\Tex\FrontendBundle\Entity\PartiProgettuali $partiProgettali = null)
    {
        $this->parti_progettali = $partiProgettali;

        return $this;
    }

    /**
     * Get parti_progettali
     *
     * @return \Tex\FrontendBundle\Entity\PartiProgettuali 
     */
    public function getPartiProgettali()
    {
        return $this->parti_progettali;
    }
}
