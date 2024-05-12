<?php

namespace Codemastercarlos\Receipt\Repository;

use Codemastercarlos\Receipt\Entity\User;
use PDO;

class UserRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function getFromId($id): array|bool
    {
        $sql = 'SELECT * FROM user WHERE id = ?;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getFromEmail($email): array|bool
    {
        $sql = 'SELECT * FROM user WHERE email = ?;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function create(User $user): bool
    {
        $sql = 'INSERT INTO user(name, email, password, date_created) VALUES (:name, :email, :password, :date_created);';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":name", $user->name);
        $stmt->bindValue(":email", $user->email);
        $stmt->bindValue(":password", $user->password);
        $stmt->bindValue(":date_created", $user->formartDateEUA());
        return $stmt->execute();
    }

    public function update(User $user): bool
    {
        $sql = <<<SQL
            UPDATE user
            SET name = :name, email = :email, password = :password, date_created = :date_created WHERE id = :id
        SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $user->id, PDO::PARAM_INT);
        $stmt->bindValue(":name", $user->name);
        $stmt->bindValue(":email", $user->email);
        $stmt->bindValue(":password", $user->password);
        $stmt->bindValue(":date_created", $user->formartDateEUA());
        return $stmt->execute();
    }
}