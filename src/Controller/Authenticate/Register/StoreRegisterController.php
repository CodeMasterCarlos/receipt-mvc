<?php

namespace Codemastercarlos\Receipt\Controller\Authenticate\Register;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Entity\User;
use Codemastercarlos\Receipt\Exception\InvalidValidationException;
use Codemastercarlos\Receipt\Helper\HydrateHelper;
use Codemastercarlos\Receipt\Helper\ValidationHelper;
use Codemastercarlos\Receipt\Repository\UserRepository;
use Codemastercarlos\Receipt\Rules\UniqueEmailUser;
use DateTimeImmutable;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StoreRegisterController implements RequestHandlerInterface
{
    use View, FlasherMessage;

    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws InvalidValidationException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $bodyParams = $request->getParsedBody();
        $location = "/register";

        $validation = new ValidationHelper($bodyParams, [
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', new UniqueEmailUser($this->repository)],
            'password' => ['required', 'min:8', 'max:255'],
        ]);

        $name = HydrateHelper::hydrateString($validation->getAttribute('name'));
        $email = HydrateHelper::hydrateString($validation->getAttribute('email'));
        $password = $validation->getAttribute('password');

        $hashPassword = password_hash($password, PASSWORD_ARGON2ID);

        $user = new User($name, $email, $hashPassword, new DateTimeImmutable());

        $validationCreatedUser = $this->repository->create($user);

        if ($validationCreatedUser === false) {
            $this->flasherCreate("error", "Desculpe, não foi possível criar o usuário no momento. Por favor, tente novamente mais tarde.", "5000");
        } else {
            $this->flasherCreate("success", "Usuário cadastrado com sucesso!");
        }
        return new Response(303, ['Location' => $location]);
    }
}
