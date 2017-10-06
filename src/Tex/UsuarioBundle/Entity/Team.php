<?php

namespace Tex\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="team")
 * @ORM\Entity
 */
class Team
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="createBy", type="string", length=255)
     */
    private $createBy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activate", type="boolean")
     */
    private $activate;

    /**
     * @ORM\ManyToMany(targetEntity="Tex\UsuarioBundle\Entity\Profile", inversedBy="teams")
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    protected $profiles;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


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
     * Set name
     *
     * @param string $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Team
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Team
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createBy
     *
     * @param string $createBy
     * @return Team
     */
    public function setCreateBy($createBy)
    {
        $this->createBy = $createBy;

        return $this;
    }

    /**
     * Get createBy
     *
     * @return string 
     */
    public function getCreateBy()
    {
        return $this->createBy;
    }

    /**
     * Set activate
     *
     * @param boolean $activate
     * @return Team
     */
    public function setActivate($activate)
    {
        $this->activate = $activate;

        return $this;
    }

    /**
     * Get activate
     *
     * @return boolean 
     */
    public function getActivate()
    {
        return $this->activate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->profiles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add profiles
     *
     * @param \Tex\UsuarioBundle\Entity\Profile $profiles
     * @return Team
     */
    public function addProfile(\Tex\UsuarioBundle\Entity\Profile $profiles)
    {
        if(!$this->profiles->contains($profiles)){
            $this->profiles[] = $profiles;
        }else{
            $this->profiles->remove($this->id);
        }
        return $this;
    }


    /**
     * Remove profiles
     *
     * @param \Tex\UsuarioBundle\Entity\Profile $profiles
     */
    public function removeProfile(\Tex\UsuarioBundle\Entity\Profile $profiles)
    {
        $this->profiles->removeElement($profiles);
    }


  /*  function setProfiles($profiles) {
        foreach($this->profiles as $id => $profile) {
            if(!isset($profiles[$id])) {
                //remove from old because it doesn't exist in new
                $this->profiles->remove($id);
            }
            else {
                //the product already exists do not overwrite
                unset($profiles[$id]);
            }
        }

        //add products that exist in new but not in old
        foreach($profiles as $id => $profile) {
            $this->profiles[$id] = $profile;
        }

    }*/

    /**
     * Get profiles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * Set user
     *
     * @param \Tex\UsuarioBundle\Entity\User $user
     * @return Team
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
