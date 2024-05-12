<?php

namespace Codemastercarlos\Receipt\Repository;

use Codemastercarlos\Receipt\Entity\Receipt;
use PDO;

class ReceiptRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function save(Receipt $receipt): bool
    {
        return $this->create($receipt);
    }

    private function create(Receipt $receipt): bool
    {
        $sql = "INSERT INTO receipt(title, image, id_user, date) VALUES(:title, :image, :id_user, :date);";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":title", $receipt->title);
        $stmt->bindValue(":image", $receipt->image);
        $stmt->bindValue(":id_user", $receipt->idUser, PDO::PARAM_INT);
        $stmt->bindValue(":date", $receipt->formartDateEUA());
        return $stmt->execute();
    }
}