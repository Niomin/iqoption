<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    const FILENAME = __DIR__ . '/.product-inited';

    /**
     * @param Category $category
     * @param int $limit
     * @param int $offset
     * @param string $search
     * @return array
     */
    public function findByCategory(Category $category, $limit, $offset, $search)
    {
        $stmt = $this->search($category, $limit, $offset, $search);
        $result = $stmt->fetchAll();
        foreach ($result as $key => $res) {
            $result[$key]['photos'] = json_decode($res['photos'], true);
        }
        return $result;
    }

    private function search(Category $category, $limit, $offset, $search)
    {
        $tableName = $this->getTableName($category);
        $search = trim($search);
        $search = preg_replace('/([|&])/', '\\$1', $search);
        $search = preg_replace('/\s+/', '|', $search);
        if ($search) {
            $sql = <<<sql
SELECT  id, name, photos
  FROM $tableName, to_tsquery(:search) query
 WHERE fts @@ query 
 ORDER BY ts_rank_cd(fts, query) DESC
 LIMIT $limit
OFFSET $offset
sql;
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
            $stmt->bindValue('search', $search);
        } else {
            $sql = <<<sql
SELECT id, name, photos 
  FROM $tableName
 ORDER BY id DESC
 LIMIT $limit
OFFSET $offset
sql;
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        }
        $stmt->execute();
        return $stmt;
    }

    public function init()
    {
        $queries = [
            'CREATE TABLE product (id BIGSERIAL NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, photos JSON NOT NULL, PRIMARY KEY(id))',
            'ALTER TABLE product ADD CONSTRAINT product_category_fk FOREIGN KEY (category_id) REFERENCES category (id)',
            'ALTER TABLE product ADD COLUMN fts TSVECTOR',
            'UPDATE product SET fts = to_tsvector(name)',
            'CREATE INDEX product_fts_index ON product USING GIN (fts)'
        ];

        $this->runQueries($queries);
    }

    public function initCategory(Category $category)
    {
        $baseName = $this->getBaseName();
        $tableName = $this->getTableName($category);
        $queries = [
            "CREATE TABLE $tableName (LIKE $baseName INCLUDING ALL)",
            "ALTER TABLE $tableName INHERIT $baseName;"
        ];
        $this->runQueries($queries);
    }

    public function addProduct(Product $product)
    {
        $tableName = $this->getTableName($product->getCategory());
        $sql = "INSERT INTO $tableName (category_id, name, photos, fts) VALUES (:categoryId, :name, :photos, to_tsvector(:name_))";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('categoryId', $product->getCategory()->getId());
        $stmt->bindValue('name', $product->getName());
        $stmt->bindValue('name_', $product->getName());
        $stmt->bindValue('photos', json_encode($product->getPhotos()));
        $stmt->execute();
    }

    private function getBaseName()
    {
        return $this->getClassMetadata()->getTableName();
    }

    private function getTableName(Category $category)
    {
        /*
         * В целом, можно было обойтись без извращений с именами таблиц, а просто задать check у
         * соответствующего partition. Но, если захочется разнести таблицы по разным коннектам,
         * то сейчас достаточно будет переопределить правило создания (вместо наследования
         * создавать по образу и подобию), ну и правило раздачи коннектов.
         * То есть это будет актуально, если какие-то несколько категорий окажутся слишком
         * популярными у пользователей. Хотя тогда, возможно, стоит просто перенести это из
         * Postgres в Mongo, в котором из коробки есть горизонтальный шардинг.
         * Короче, я не знаю очевидного и сразу правильного решения, так что выбрал такое,
         * серединка на половинку.
         */
        return $this->getBaseName() . '_' . $category->getId();
    }

    /**
     * @param string[] $queries
     */
    private function runQueries(array $queries)
    {
        $conn = $this->getEntityManager()->getConnection();
        $conn->beginTransaction();
        foreach ($queries as $query) {
            $stmt = $conn->prepare($query);
            $stmt->execute();
        }
        $conn->commit();
    }
}