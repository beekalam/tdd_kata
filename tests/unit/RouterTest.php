<?php

namespace Tests\Unit;

use App\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private $router;

    protected  function setUp(): void
    {
        parent::setUp();
        $this->router = new Router(__DIR__ . DIRECTORY_SEPARATOR. "../stubs");
    }

    /** @test */
    function check_router_can_find_all_controller_files()
    {
        $this->assertEquals(['StudentController','TeacherController'], $this->router->controllers());
    }

    /** @test */
    function can_get_routes_from_method_docs()
    {
        $routes = array_keys($this->router->getRoutes());
        $this->assertContains('/student/home', $routes);
        $this->assertContains('/student/about', $routes);
    }

    /** @test */
    function given_class_with_routes_in_comments_can_get_a_map_of_routes_to_controller_classes()
    {
        $routes = $this->router->getRoutes();
        $this->assertEquals(['controller' => 'StudentController', 'method' => 'homeAction'], $routes['/student/home']);
        $this->assertEquals(['controller' => 'StudentController', 'method' => 'aboutAction'], $routes['/student/about']);
    }

    /** @test */
    function given_a_route_should_returns_the_output_of_the_function()
    {
        $this->assertEquals('home action', $this->router->getRouteResult('/student/home'));
        $this->assertEquals('about action', $this->router->getRouteResult('/student/about'));
    }



}