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

    public function getAll(string|int $id, $offset = 0, $limit = 100): array
    {
        $sql = "SELECT * FROM receipt WHERE id_user = :id LIMIT :offset, :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        $receiptList = $stmt->fetchAll();

        return array_map(
            $this->hydrateReceipt(...),
            $receiptList
        );
    }

    /**
     * @throws Exception
     */
    private function hydrateReceipt(array $receiptData): Receipt
    {
        $receipt = new Receipt($receiptData['id_user'], $receiptData['title'], $receiptData['image'], new DateTimeImmutable($receiptData['date']));
        $receipt->setId($receiptData['id']);
        return $receipt;
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
}