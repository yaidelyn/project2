<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Questions
 *
 * @ORM\Table(name="questions")
 * @ORM\Entity
 */
class Questions
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
     * @ORM\Column(name="title", type="text")
     */
    private $title;


    /**
     * @ORM\ManyToOne(targetEntity="Test", inversedBy="test")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id",onDelete="CASCADE")
     */

    protected $test;


    /**
     * @ORM\ManyToOne(targetEntity="TypeQuestions")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id",onDelete="CASCADE")
     */

    protected $type;


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
     * @return Questions
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
     * Set test
     *
     * @param \Tex\AdminBundle\Entity\Test $test
     * @return Questions
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
     * Set type
     *
     * @param \Tex\AdminBundle\Entity\TypeQuestions $type
     * @return Questions
     */
    public function setType(\Tex\AdminBundle\Entity\TypeQuestions $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Tex\AdminBundle\Entity\TypeQuestions 
     */
    public function getType()
    {
        return $this->type;
    }
}
