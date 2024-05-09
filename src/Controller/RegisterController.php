<?php

namespace Codemastercarlos\Receipt\Controller;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Entity\User;
use Codemastercarlos\Receipt\Helper\Validation;
use Codemastercarlos\Receipt\Repository\UserRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RegisterController implements RequestHandlerInterface
{
    use View, FlasherMessage;

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
        return new Response(200, body: $this->render('register'));
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $bodyParams = $request->getParsedBody();
        $location = "/register";
        [$name, $email, $password, $validation] = $this->validateParams($bodyParams);

        if ($validation->validationWasError()) {
            return new Response(303, ['Location' => $location]);
        }
        
        if ($this->validateEmailExist($email)) {
            $this->flasherCreate(
                "error",
                "Email já cadastrado. Por favor, use um endereço de e-mail diferente."
            );
            return new Response(303, ['Location' => $location]);
        }

        $hashPassword = password_hash($password, PASSWORD_ARGON2ID);

        $user = new User($name, $email, $hashPassword, new \DateTimeImmutable());

        $validationCreatedUser = $this->repository->save($user);

        if ($validationCreatedUser === false) {
            $this->flasherCreate(
                "error",
                "Desculpe, não foi possível criar o usuário no momento. Por favor, tente novamente mais tarde.",
                "5000"
            );
        } else {
            $this->flasherCreate(
                "success",
                "Usuário cadastrado com sucesso!",
            );
        }
        return new Response(303, ['Location' => $location]);
    }

    private function validateParams($params): array
    {
        $validation = new Validation($params);

        $name = $validation->validate(
            'name',
            FILTER_VALIDATE_REGEXP,
            ["options" => ["regexp" => "/^.{3}?.*/"]],
            ["message" => "O nome deve ter pelo menos 3 caracteres."]
        );

        $email = $validation->validate(
            'email',
            FILTER_VALIDATE_EMAIL,
            messageError: ["message" => "Por favor, insira um e-mail válido."]
        );

        $password = $validation->validate(
            'password',
            FILTER_VALIDATE_REGEXP,
            ["options" => ["regexp" => "/^.{8}?.*/"]],
            ['message' => "A senha deve ter pelo menos 8 caracteres."],
            false,
        );

        return [$name, $email, $password, $validation];
    }

    private function validateEmailExist($email): bool
    {
        $user = $this->repository->getFromEmail($email);
        return $user !== false;
    }
}
