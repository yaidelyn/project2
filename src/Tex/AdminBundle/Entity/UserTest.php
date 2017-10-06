<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserTest
 *
 * @ORM\Table(name="usertest")
 * @ORM\Entity
 */
class UserTest
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
     * @ORM\ManyToOne(targetEntity="Test", inversedBy="test")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id",onDelete="CASCADE")
     */

    protected $test;

     /**
     * @ORM\ManyToOne(targetEntity="Tex\UsuarioBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected  $user;


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
     * Set test
     *
     * @param \Tex\AdminBundle\Entity\Test $test
     * @return UserTest
     */
    public function setTest(\Tex\AdminBundle\Entity\Test $test = null)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get test
     *
     * @return \Tex\AdminBundle\Entity\Test 
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Set user
     *
     * @param \Tex\UsuarioBundle\Entity\User $user
     * @return UserTest
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
}
