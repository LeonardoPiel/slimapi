<?php

namespace App\Repositories;

use App\Database;
use PDO;

class ProductRepository
{
    public function __construct(private Database $db) {}
    public function getAll(): array
    {
        $pdo = $this->db->getConnection();

        $stmt = $pdo->query('SELECT * FROM product');

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function getById(int $id): array|bool
    {
        try {
            $pdo = $this->db->getConnection();
            $sql =  'SELECT * FROM product WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!isset($data))
                return false;
            return $data;
        } catch (\Exception $exception) {
            return false;
        }
    }
    public function create(array $data): string
    {
        $sql = 'INSERT INTO product (name, description, size)
                VALUES (:name, :description, :size)';

        $pdo = $this->db->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);

        if (empty($data['description'])) {

            $stmt->bindValue(':description', null, PDO::PARAM_NULL);
        } else {

            $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        }

        $stmt->bindValue(':size', $data['size'], PDO::PARAM_INT);

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $sql = 'UPDATE product SET name = :name, description = :description, size = :size WHERE id = :id';

        $pdo = $this->db->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if (empty($data['name'])) {
            $stmt->bindValue(':name', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        }

        if (empty($data['description'])) {
            $stmt->bindValue(':description', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        }
        $stmt->bindValue(':size', $data['size'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }
    public function delete(int $id) : bool
    {
        try {
            $sql = "DELETE FROM product WHERE id = :id";
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, pdo::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
