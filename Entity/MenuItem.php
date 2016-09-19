<?php

namespace WH\SuperAdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use WH\LibBundle\Entity\Tree;
use WH\SuperAdminBundle\Entity\MenuItem as Entity;

/**
 * MenuItem
 *
 * @ORM\Table(name="menu_item")
 * @ORM\Entity(repositoryClass="WH\SuperAdminBundle\Repository\MenuItemRepository")
 *
 * @Gedmo\Tree(type="nested")
 */
class MenuItem
{

    use Tree;

    /**
     * MenuItem constructor.
     */
    public function __construct()
    {

        $this->children = new ArrayCollection();
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
     * @ORM\Column(name="route", type="string", length=255, nullable=true)
     */
    private $route;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="MenuItem")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="menuItems")
     */
    private $menu;

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
     *
     * @return MenuItem
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
     * Get name indented
     *
     * @return string
     */
    public function getIndentedName()
    {

        return str_repeat(" > ", $this->lvl).$this->name;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return MenuItem
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set root
     *
     * @param \WH\SuperAdminBundle\Entity\MenuItem $root
     *
     * @return MenuItem
     */
    public function setRoot(\WH\SuperAdminBundle\Entity\MenuItem $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return \WH\SuperAdminBundle\Entity\MenuItem
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set parent
     *
     * @param \WH\SuperAdminBundle\Entity\MenuItem $parent
     *
     * @return MenuItem
     */
    public function setParent(\WH\SuperAdminBundle\Entity\MenuItem $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \WH\SuperAdminBundle\Entity\MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \WH\SuperAdminBundle\Entity\MenuItem $child
     *
     * @return MenuItem
     */
    public function addChild(\WH\SuperAdminBundle\Entity\MenuItem $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \WH\SuperAdminBundle\Entity\MenuItem $child
     */
    public function removeChild(\WH\SuperAdminBundle\Entity\MenuItem $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set menu
     *
     * @param \WH\SuperAdminBundle\Entity\Menu $menu
     *
     * @return MenuItem
     */
    public function setMenu(\WH\SuperAdminBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \WH\SuperAdminBundle\Entity\Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
