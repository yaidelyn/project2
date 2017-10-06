<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Formation
 *
 * @ORM\Table(name="formation", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_404021BFA76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class Formation
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
     * @ORM\Column(name="name_ocupation", type="string", length=255, nullable=false)
     */
    private $nameOcupation;

    /**
     * @var string
     *
     * @ORM\Column(name="education", type="string", length=255, nullable=false)
     */
    private $education;

    /**
     * @var string
     *
     * @ORM\Column(name="start_date", type="string", length=255, nullable=false)
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="end_date", type="string", length=255, nullable=false)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="string", length=255, nullable=false)
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

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
