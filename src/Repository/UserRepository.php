<?php

namespace Codemastercarlos\Receipt\Repository;

use Codemastercarlos\Receipt\Entity\User;
use PDO;

class UserRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function getFromEmail($email): array|bool
    {
        $sql = 'SELECT * FROM user WHERE email = ?;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function save(User $user): bool
    {
        $sql = 'INSERT INTO user(name, email, password, date_created) VALUES (:name, :email, :password, :date_created);';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":name", $user->name);
        $stmt->bindValue(":email", $user->email);
        $stmt->bindValue(":password", $user->password);
        $stmt->bindValue(":date_created", $user->formartDateEUA());
        return $stmt->execute();
    }
}