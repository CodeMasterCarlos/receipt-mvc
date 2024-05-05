<?php

namespace Codemastercarlos\Receipt\bootstrap;

use Codemastercarlos\Receipt\Views\Utils;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
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
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        $twig->addGlobal('utils', new Utils());
        return $twig->load($name . '.twig')->render($data);
    }
}