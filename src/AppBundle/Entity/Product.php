<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass(repositoryClass="ProductRepository")
 * @ ORM\Entity
 */
class Product
{
    /**
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     */
    private $category;

    /**
     * @var string[]
     * @ORM\Column(type="json_array")
     */
    private $photos;

    /**
     * @param string $name
     * @param Category $category
     * @param string[] $photos
     */
    public function __construct($name, Category $category, array $photos)
    {
        $this->name = $name;
        $this->category = $category;
        $this->photos = $photos;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Category $category
     * @return $this
     * @throws \Exception
     */
    public function setCategory(Category $category)
    {
        throw new \Exception('Здесь нужно организовать перенос из одного партишена в другой :)');
        $this->category = $category;
        return $this;
    }

    /**
     * @param string[] $photos
     * @return $this
     */
    public function setPhotos(array $photos)
    {
        $this->photos = $photos;
        return $this;
    }

    public static function create($id, $name, Category $category, $photos)
    {
        $product = new self($name, $category, $photos);
        $product->id = $id;
        return $product;
    }
}