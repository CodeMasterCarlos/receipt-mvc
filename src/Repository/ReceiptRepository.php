<?php

namespace Codemastercarlos\Receipt\Repository;

use Codemastercarlos\Receipt\Entity\Receipt;
use DateTimeImmutable;
use Exception;
use PDO;

class ReceiptRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @throws Exception
     */
    public function find(string|int $id, string|int $idUser): Receipt|bool
    {
        $sql = "SELECT * FROM receipt WHERE id_user = :id_user AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id_user", $idUser, PDO::PARAM_INT);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $receipt = $stmt->fetch();

        return $this->hydrateReceipt($receipt);
    }

    /**
     * @throws Exception
     */
    private function hydrateReceipt(array|bool $receiptData): Receipt|bool
    {
        if (!$receiptData) {
            return false;
        }

        $receipt = new Receipt($receiptData['id_user'], $receiptData['title'], $receiptData['image'], new DateTimeImmutable($receiptData['date']));
        $receipt->setId($receiptData['id']);
        return $receipt;
    }

    public function getAll(string|int $id, $offset = 0, $limit = 100): array
    {
        $sql = "SELECT * FROM receipt WHERE id_user = :id LIMIT :offset, :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        $receiptList = $stmt->fetchAll();

        return array_map($this->hydrateReceipt(...), $receiptList);
    }

    public function create(Receipt $receipt): bool
    {
        $sql = "INSERT INTO receipt(title, image, id_user, date) VALUES(:title, :image, :id_user, :date);";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":title", $receipt->title);
        $stmt->bindValue(":image", $receipt->image);
        $stmt->bindValue(":id_user", $receipt->idUser, PDO::PARAM_INT);
        $stmt->bindValue(":date", $receipt->formartDateEUA());
        return $stmt->execute();
    }

    public function destroy(string|int $idUser, string|int $idReceipt): bool
    {
        $sql = "DELETE FROM receipt WHERE id_user = :id_user AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id_user", $idUser, PDO::PARAM_INT);
        $stmt->bindValue(":id", $idReceipt, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update(Receipt $receipt): bool
    {
        $sql = "UPDATE receipt SET title = :title, image = :image, id_user = :id_user, date = :date WHERE id = :id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":title", $receipt->title);
        $stmt->bindValue(":image", $receipt->image);
        $stmt->bindValue(":id_user", $receipt->idUser, PDO::PARAM_INT);
        $stmt->bindValue(":id", $receipt->id, PDO::PARAM_INT);
        $stmt->bindValue(":date", $receipt->formartDateEUA());
        return $stmt->execute();
    }

    public function searchFor(mixed $search): array
    {
        $sql = "SELECT * FROM receipt WHERE MATCH(title) AGAINST(? IN NATURAL LANGUAGE MODE)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $search);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}