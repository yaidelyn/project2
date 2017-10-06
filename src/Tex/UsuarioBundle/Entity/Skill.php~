<?php

namespace Tex\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Skill
 *
 * @ORM\Table(name="skill")
 * @ORM\Entity
 */
class Skill
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name_skill", type="string", length=255, nullable=false)
     */
    private $nameSkill;



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
     * Set nameSkill
     *
     * @param string $nameSkill
     * @return Skill
     */
    public function setNameSkill($nameSkill)
    {
        $this->nameSkill = $nameSkill;

        return $this;
    }

    /**
     * Get nameSkill
     *
     * @return string 
     */
    public function getNameSkill()
    {
        return $this->nameSkill;
    }
}
