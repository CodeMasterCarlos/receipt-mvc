<?php

namespace Codemastercarlos\Receipt\Controller\Authenticate\Login;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Bootstrap\SessionAuth;
use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Entity\User;
use Codemastercarlos\Receipt\Helper\ValidationHelper;
use Codemastercarlos\Receipt\Repository\UserRepository;
use DateTimeImmutable;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StoreLoginController implements RequestHandlerInterface
{
    use View, FlasherMessage, SessionAuth;

    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $bodyParams = $request->getParsedBody();

        $validation = new ValidationHelper($bodyParams, [
            "email" => ["required"],
            "password" => ["required"],
        ]);

        $email = $validation->getAttribute("email");
        $password = $validation->getAttribute("password");

        $userData = $this->repository->getFromEmail($email);

        $passwordUser = $userData['password'] ?? "";

        if (password_verify($password, $passwordUser) === false) {
            $this->flasherCreate("error", "Desculpe, não foi possível fazer login. Por favor, verifique suas credenciais e tente novamente.", 8000);
            return new Response(303, ["Location" => "/login?email=" . $email]);
        }

        $user = new User($userData['name'], $userData['email'], $userData['password'], new DateTimeImmutable($userData['date_created']));
        $user->setId($userData['id']);

        $this->createSession($user);

        return new Response(302, ["Location" => "/"]);
    }
}
