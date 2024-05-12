<?php

namespace Codemastercarlos\Receipt\Bootstrap;

use Codemastercarlos\Receipt\Entity\User;
use Firebase\JWT\JWT;

trait SessionAuth
{
    private function createSession(User $user): void
    {
        $privateKey = file_get_contents($_ENV['JWT_KEY_PRIVATE']);

        $payload = [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
        ];
        $jwt = JWT::encode($payload, $privateKey, $_ENV['JWT_ALGORITHM']);

        $_SESSION['receipt']['user']['authorization'] = $jwt;
    }
}