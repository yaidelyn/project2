<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EvaluateQuestion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class EvaluateQuestion
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
     * @ORM\ManyToOne(targetEntity="Questions", inversedBy="question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id",onDelete="CASCADE")
     */

    protected $question;


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
     * @return EvaluateQuestion
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
     * Set question
     *
     * @param \Tex\AdminBundle\Entity\Questions $question
     * @return EvaluateQuestion
     */
    public function setQuestion(\Tex\AdminBundle\Entity\Questions $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \Tex\AdminBundle\Entity\Questions 
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
