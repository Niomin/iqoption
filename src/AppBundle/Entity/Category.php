<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     * Безусловно, название категории уместнее хранить в шаблоне, для удобства перевода и так далее.
     * Но так проще, а KISS никто не отменял :) По крайней мере, в ТЗ не было уточнения, что
     * ожидается мультиязычность.
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $url;

    /**
     * @ORM\Column(type="boolean", options={"default"=false}, name="is_default")
     * @var boolean
     */
    private $default = false;

    /**
     * @param Category $parentCategory
     * @return $this
     */
    public function setParentCategory(Category $parentCategory)
    {
        $this->parentCategory = $parentCategory;
        return $this;
    }

    /**
     * @return Category[]|PersistentCollection
     */
    public function getChildrenCategories()
    {
        return $this->childrenCategories;
    }

    /**
     * @return Category
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="childrenCategories")
     * @var Category
     */
    private $parentCategory;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Category", mappedBy="parentCategory")
     * @var PersistentCollection|Category[]
     */
    private $childrenCategories;

    /**
     * @param string $name
     * @param string $url
     * @param Category $parent
     * @param bool $default
     */
    public function __construct($name, $url, Category $parent = null, $default = false)
    {
        $this->name = $name;
        $this->url = $url;
        $this->default = $default;
        $this->parentCategory = $parent;
        $this->childrenCategories = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }
}