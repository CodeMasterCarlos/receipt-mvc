<?php

namespace Codemastercarlos\Receipt\Tests\Unit\Bootstrap\Route;

use Codemastercarlos\Receipt\Bootstrap\Route\Route;
use Codemastercarlos\Receipt\Bootstrap\Route\RouteFile;
use Codemastercarlos\Receipt\Controller\Receipt\ReceiptController;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class RouteTest extends TestCase
{
    protected function setUp(): void
    {
        $this->resetRoutes();
    }

    #[DataProvider('dataProviderForRouteCreation')]
    public function testCreateRouteWithGetMethodSuccessfully(string $controller, string $route): void
    {
        Route::get($route, $controller);
        $routes = Route::allRoutes();

        static::assertArrayHasKey('get', $routes);
        static::assertCount(1, $routes['get']);
        static::assertArrayHasKey($route, $routes['get']);
    }

    #[DataProvider('dataProviderForRouteCreation')]
    public function testCreateRouteWithPostMethodSuccessfully(string $controller, string $route): void
    {
        Route::post($route, $controller);
        $routes = Route::allRoutes();

        static::assertArrayHasKey('post', $routes);
        static::assertCount(1, $routes['post']);
        static::assertArrayHasKey($route, $routes['post']);
    }

    public function testAddAllRoutesWhenTwoRouteFiles(): void
    {
        $fileRouteWeb = __DIR__ . '/stubs/web.php';
        $fileRouteWeb2 = __DIR__ . '/stubs/web2.php';

        Route::requiredFileRoutes(
            new RouteFile($fileRouteWeb, true),
            new RouteFile($fileRouteWeb2, true),
        );
        $routes = Route::allRoutes();

        static::assertArrayHasKey('get', $routes);
        static::assertCount(2, $routes['get']);
        static::assertArrayHasKey('/web', $routes['get']);
        static::assertArrayHasKey('/web2', $routes['get']);
    }

    public static function dataProviderForRouteCreation(): array
    {
        return [
            [ReceiptController::class, '/']
        ];
    }

    private function resetRoutes(): void
    {
        $reflection = new ReflectionClass(Route::class);
        $routesProperty = $reflection->getProperty('routes');
        $routesProperty->setValue(null, []);
    }
}
