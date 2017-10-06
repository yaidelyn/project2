<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Typequestions
 *
 * @ORM\Table(name="typequestions")
 * @ORM\Entity
 */
class Typequestions
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
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;


}
