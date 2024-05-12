<?php

namespace Codemastercarlos\Receipt\Controller;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Bootstrap\SessionAuth;
use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Entity\User;
use Codemastercarlos\Receipt\Helper\Validation;
use Codemastercarlos\Receipt\Repository\UserRepository;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class LoginController implements RequestHandlerInterface
{
    use View, FlasherMessage, SessionAuth;

    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, body: $this->render('login'));
    }

    /**
     * @throws Exception
     */
    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $bodyParams = $request->getParsedBody();
        $location = "/login";
        /** @var Validation $validation */
        [$email, $password, $validation] = $this->validateParams($bodyParams);

        if ($validation->validationWasError()) {
            return new Response(303, ["Location" => $location]);
        }

        $userData = $this->repository->getFromEmail($email);

        $passwordUser = $userData['password'] ?? "";

        if (password_verify($password, $passwordUser) === false) {
            $this->flasherCreate(
                "error",
                "Desculpe, não foi possível fazer login. Por favor, verifique suas credenciais e tente novamente.",
                8000
            );
            return new Response(303, ["Location" => "/login?email=" . $email]);
        }

        $user = new User(
            $userData['name'],
            $userData['email'],
            $userData['password'],
            new \DateTimeImmutable($userData['date_created'])
        );
        $user->setId($userData['id']);

        $this->createSession($user);

        return new Response(302, ["Location" => "/"]);
    }

    private function validateParams($params): array
    {
        $validation = new Validation($params);

        $email = $validation->validate(
            "email",
            FILTER_VALIDATE_EMAIL,
            messageError: ['message' => "Por favor, insira um e-mail válido."]
        );

        $password = $params["password"];

        return [$email, $password, $validation];
    }
}
