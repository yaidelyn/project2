<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Usertest
 *
 * @ORM\Table(name="usertest", indexes={@ORM\Index(name="IDX_2746CB151E5D0459", columns={"test_id"}), @ORM\Index(name="IDX_2746CB15A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class Usertest
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
