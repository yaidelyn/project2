<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EvaluateTest
 *
 * @ORM\Table(name="evaluatetest")
 * @ORM\Entity
 */
class EvaluateTest
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
     * @var integer
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Test", inversedBy="test")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id",onDelete="CASCADE")
     */

    protected $test;

    /**
     * @ORM\ManyToOne(targetEntity="Tex\UsuarioBundle\Entity\User", inversedBy="user")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",onDelete="CASCADE")
     */

    protected $user;


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
     * Set value
     *
     * @param integer $value
     * @return EvaluateTest
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set test
     *
     * @param \Tex\AdminBundle\Entity\Test $test
     * @return EvaluateTest
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
     * @return EvaluateTest
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
