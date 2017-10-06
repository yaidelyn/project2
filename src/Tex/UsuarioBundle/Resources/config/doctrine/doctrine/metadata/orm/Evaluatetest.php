<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Evaluatetest
 *
 * @ORM\Table(name="evaluatetest", indexes={@ORM\Index(name="IDX_10E9E2A01E5D0459", columns={"test_id"}), @ORM\Index(name="IDX_10E9E2A0A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class Evaluatetest
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
     * @var \Test
     *
     * @ORM\ManyToOne(targetEntity="Test")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="test_id", referencedColumnName="id")
     * })
     */
    private $test;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


}
