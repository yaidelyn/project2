<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSelectGara
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UserSelectGara
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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="Tex\UsuarioBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


    /**
     * @var \Gare
     *
     * @ORM\ManyToOne(targetEntity="Gare")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gare_id", referencedColumnName="id")
     * })
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
     * Set user
     *
     * @param \Tex\UsuarioBundle\Entity\User $user
     * @return UserSelectGara
     */
    public function setUser(\Tex\UsuarioBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tex\UsuarioBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set gare
     *
     * @param \Tex\AdminBundle\Entity\Gare $gare
     * @return UserSelectGara
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
