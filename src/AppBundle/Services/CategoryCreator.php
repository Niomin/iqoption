<?php

namespace AppBundle\Services;

use AppBundle\Entity\Category;
use AppBundle\Entity\ProductRepository;
use Doctrine\ORM\EntityManager;

class CategoryCreator
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @param EntityManager $entityManager
     * @param ProductRepository $productRepository
     */
    public function __construct(EntityManager $entityManager, ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $name
     * @param string $url
     * @param Category|null $parent
     * @param bool $default
     * @return Category
     */
    public function createCategory($name, $url, Category $parent = null, $default = false)
    {
        $em = $this->entityManager;
        $category = new Category($name, $url, $parent, $default);
        $em->beginTransaction();
        $em->persist($category);
        $em->flush($category);
        $this->productRepository->initCategory($category);
        $em->commit();

        return $category;
    }
}