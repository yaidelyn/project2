<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Evaluatequestion
 *
 * @ORM\Table(name="evaluatequestion", indexes={@ORM\Index(name="IDX_F7E7C9791E27F6BF", columns={"question_id"})})
 * @ORM\Entity
 */
class Evaluatequestion
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
     * @var integer
     *
     * @ORM\Column(name="value", type="integer", nullable=false)
     */
    private $value;

    /**
     * @var \Questions
     *
     * @ORM\ManyToOne(targetEntity="Questions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * })
     */
    private $question;


}
