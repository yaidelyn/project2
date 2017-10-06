<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answers
 *
 * @ORM\Table(name="answers")
 * @ORM\Entity
 */
class Answers
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
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var boolean
     *
     * @ORM\Column(name="iscorrect", type="boolean")
     */
    private $iscorrect;

    /**
     * @ORM\ManyToOne(targetEntity="Questions", inversedBy="question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id",onDelete="CASCADE")
     */

    protected $questions;


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
     * @param string $value
     * @return Answers
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set iscorrect
     *
     * @param boolean $iscorrect
     * @return Answers
     */
    public function setIscorrect($iscorrect)
    {
        $this->iscorrect = $iscorrect;

        return $this;
    }

    /**
     * Get iscorrect
     *
     * @return boolean 
     */
    public function getIscorrect()
    {
        return $this->iscorrect;
    }

    /**
     * Set questions
     *
     * @param \Tex\AdminBundle\Entity\Questions $questions
     * @return Answers
     */
    public function setQuestions(\Tex\AdminBundle\Entity\Questions $questions = null)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * Get questions
     *
     * @return \Tex\AdminBundle\Entity\Questions 
     */
    public function getQuestions()
    {
        return $this->questions;
    }
}
