<?php

namespace AppBundle\Services;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRepository;
use Doctrine\ORM\EntityRepository;

class GeneratorService
{
    /**
     * @var string[]
     */
    private $words = null;

    /**
     * @var CategoryCreator
     */
    private $categoryCreator;

    /**
     * @var EntityRepository
     */
    private $categoryRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @param CategoryCreator $categoryCreator
     * @param EntityRepository $categoryRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        CategoryCreator $categoryCreator,
        EntityRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        $this->categoryCreator = $categoryCreator;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    public function generateCategories($cnt = 50)
    {
        $categories = [];
        $default = true;

        for ($i = 0; $i < $cnt; $i++) {
            $parent = rand(0, 100);

            $parentCategory = isset($categories[$parent]) ?  $categories[$parent] : null;

            $categories[] = $this->categoryCreator->createCategory(
                $this->generateWord(),
                'category' . $i,
                $parentCategory,
                $default
            );

            $default = false;
        }
    }

    public function generateProducts($cnt = 500)
    {
        /** @var Category[] $categories */
        $categories = $this->categoryRepository->findAll();
        foreach ($categories as $category) {
            for ($i = 0; $i < $cnt; $i++) {
                $product = new Product($this->generateProductName(), $category, $this->generateImages());
                $this->productRepository->addProduct($product);
            }
        }
    }

    private function generateWords($cnt = 300)
    {
        for ($i = 0; $i < $cnt; $i++) {
            $this->words[] = $this->generateWord();
        }
    }

    private function generateProductName()
    {
        $cnt = rand(1, 5);
        $result = '';
        for ($i = 0; $i < $cnt; $i++) {
            $result .= ' ' . $this->getWord();
        }
        return substr($result, 1);
    }

    /**
     * @return string
     */
    private function getWord()
    {
        if ($this->words === null) {
            $this->generateWords();
        }
        return $this->words[rand(0, count($this->words) - 1)];
    }

    private function generateWord()
    {
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        $lettersLength = strlen($letters);
        $length = rand(3, 8);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($letters, rand(0, $lettersLength - 1), 1);
        }
        return $string;
    }

    private function generateImages()
    {
        $images = [
            "http://img1.wbstatic.net/c246x328/new/3110000/3114951-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114940-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114944-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114961-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114957-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3230000/3232133-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114933-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3170000/3170587-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3390000/3393834-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114925-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114942-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114946-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114950-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114932-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114948-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3420000/3422034-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3440000/3446540-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114959-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3230000/3232853-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114945-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114927-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3410000/3412017-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/2120000/2123876-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3310000/3314797-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114923-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/1900000/1900493-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3270000/3272345-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114941-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3430000/3438418-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3260000/3264507-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3440000/3446541-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114930-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3430000/3438443-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3440000/3446539-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3114953-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3410000/3412019-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114952-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3400000/3402097-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3170000/3170586-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3230000/3234896-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3111353-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3170000/3177516-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3390000/3394547-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3130000/3137022-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3440000/3441937-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/2910000/2914637-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3130000/3137024-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3111354-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3110000/3111357-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/2850000/2850088-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3350000/3355087-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3150000/3159452-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3440000/3442078-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3190000/3197755-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/2470000/2479065-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3440000/3442073-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/2570000/2570617-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/2820000/2827578-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114960-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3350000/3355348-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114954-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3440000/3442874-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/2890000/2890075-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3420000/3422105-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3140000/3140020-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3170000/3178265-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3340000/3345434-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/2920000/2927009-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3420000/3422054-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/1270000/1274896-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3440000/3442873-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3350000/3355350-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3170000/3176492-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/1270000/1274895-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/1250000/1256875-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3340000/3345675-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114936-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3270000/3278244-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3440000/3442911-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3440000/3442875-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/2490000/2496518-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/1260000/1268604-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/2620000/2621384-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3440000/3441934-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3420000/3422052-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3420000/3422109-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3350000/3355088-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3110000/3114926-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3400000/3403818-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3390000/3394779-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3040000/3045356-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3310000/3318278-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/2620000/2621383-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3420000/3422036-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3140000/3140016-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3420000/3422103-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/2590000/2597128-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3270000/3270066-1.jpg",
            "http://img1.wbstatic.net/c246x328/new/3440000/3442075-1.jpg",
            "http://img2.wbstatic.net/c246x328/new/3440000/3442878-1.jpg",
        ];
        $cnt = count($images);
        $img = $images[rand(0, $cnt - 1)];
        $img2 = str_replace('-1', '-2', $img);
        return [$img, $img2];
    }
}