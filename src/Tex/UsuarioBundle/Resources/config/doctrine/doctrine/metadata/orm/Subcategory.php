<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Subcategory
 *
 * @ORM\Table(name="subcategory", indexes={@ORM\Index(name="IDX_723649C912469DE2", columns={"category_id"})})
 * @ORM\Entity
 */
class Subcategory
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
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \Categoffer
     *
     * @ORM\ManyToOne(targetEntity="Categoffer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;


}
