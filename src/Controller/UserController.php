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

class UserController implements RequestHandlerInterface
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
        $userData = $_SESSION['receipt']['user']['value'];

        return new Response(200, body: $this->render('user', [
            "name" => $userData['name'],
            "email" => $userData['email'],
        ]));
    }

    /**
     * @throws Exception
     */
    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $bodyParams = $request->getParsedBody();
        $userAuthSession = $_SESSION['receipt']['user']['value'];
        $location = "/user";
        /** @var Validation $validation */
        [$name, $email, $password, $validation] = $this->validateParams($bodyParams);

        if ($validation->validationWasError()) {
            return new Response(303, ["Location" => $location]);
        }

        $userData = $this->repository->getFromId($userAuthSession['id']);

        if (($email !== $userAuthSession['email']) && $this->validateEmailExist($email)) {
            $this->flasherCreate(
                "error",
                "Email já cadastrado. Por favor, use um endereço de e-mail diferente."
            );
            return new Response(303, ['Location' => $location]);
        }

        $hashPassword = empty($password) ? $userData['password'] : password_hash($password, PASSWORD_ARGON2ID);
        $user = new User($name, $email, $hashPassword, new \DateTimeImmutable($userData['date_created']));
        $user->setId($userData['id']);

        $validationCreatedUser = $this->repository->update($user);

        if ($validationCreatedUser === false) {
            $this->flasherCreate(
                "error",
                "Desculpe, não foi possível atualizar o usuário no momento. Por favor, tente novamente mais tarde.",
                "5000"
            );
        } else {
            $this->flasherCreate(
                "success",
                "Usuário atualizado com sucesso!",
                "5000"
            );
            $this->createSession($user);
        }

        return new Response(302, ['Location' => $location]);
    }

    public function destroy(ServerRequestInterface $request): ResponseInterface
    {
        unset($_SESSION['receipt']['user']);
        return new Response(302, ['Location' => '/login']);
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

        $password = $params['password'];
        if (empty($password) === false) {
            $password = $validation->validate(
                'password',
                FILTER_VALIDATE_REGEXP,
                ["options" => ["regexp" => "/^.{8}?.*/"]],
                ['message' => "A senha deve ter pelo menos 8 caracteres."],
                false,
            );
        }

        return [$name, $email, $password, $validation];
    }

    private function validateEmailExist($email): bool
    {
        $user = $this->repository->getFromEmail($email);
        return $user !== false;
    }
}
