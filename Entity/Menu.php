<?php

namespace WH\SuperAdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="WH\SuperAdminBundle\Repository\MenuRepository")
 */
class Menu
{

    /**
     * Menu constructor.
     */
    public function __construct()
    {

        $this->menuItems = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var int
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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="menu")
     */
    private $menuItems;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Menu
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Menu
     */
    public function setSlug($slug)
    {

        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {

        return $this->slug;
    }

    /**
     * Add menuItem
     *
     * @param \WH\SuperAdminBundle\Entity\MenuItem $menuItem
     *
     * @return Menu
     */
    public function addMenuItem(\WH\SuperAdminBundle\Entity\MenuItem $menuItem)
    {

        $this->menuItems[] = $menuItem;

        return $this;
    }

    /**
     * Remove menuItem
     *
     * @param \WH\SuperAdminBundle\Entity\MenuItem $menuItem
     */
    public function removeMenuItem(\WH\SuperAdminBundle\Entity\MenuItem $menuItem)
    {

        $this->menuItems->removeElement($menuItem);
    }

    /**
     * Get menuItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenuItems()
    {

        return $this->menuItems;
    }
}
