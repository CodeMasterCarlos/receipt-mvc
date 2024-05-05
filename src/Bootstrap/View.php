<?php

namespace Codemastercarlos\Receipt\Bootstrap;

use Codemastercarlos\Receipt\Views\Utils;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

trait View
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function render(string $name, array $data = []): string
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../views');
        $twig = new Environment($loader, ['debug' => true]);
        $twig->addExtension(new DebugExtension());
        $twig->addGlobal('utils', new Utils());
        return $twig->load($name . '.twig')->render($data);
    }
}