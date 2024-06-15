<?php

namespace Codemastercarlos\Receipt\Controller\User;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Bootstrap\SessionAuth;
use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Entity\User;
use Codemastercarlos\Receipt\Helper\HydrateHelper;
use Codemastercarlos\Receipt\Helper\ValidationHelper;
use Codemastercarlos\Receipt\Repository\UserRepository;
use Codemastercarlos\Receipt\Rules\NullableMinRule;
use Codemastercarlos\Receipt\Rules\UpdateUserEmailRule;
use DateTimeImmutable;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StoreUserController implements RequestHandlerInterface
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
        $userAuthSession = $_SESSION['receipt']['user']['value'];
        $location = "/user";

        $validation = new ValidationHelper($bodyParams, [
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', new UpdateUserEmailRule($this->repository, $userAuthSession['email'])],
            'password' => [new NullableMinRule(8), 'max:255'],
        ]);

        $name = HydrateHelper::hydrateString($validation->getAttribute('name'));
        $email = HydrateHelper::hydrateString($validation->getAttribute('email'));
        $password = $validation->getAttribute('password');

        $userData = $this->repository->getFromId($userAuthSession['id']);

        $hashPassword = empty($password) ? $userData['password'] : password_hash($password, PASSWORD_ARGON2ID);
        $user = new User($name, $email, $hashPassword, new DateTimeImmutable($userData['date_created']));
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
}
