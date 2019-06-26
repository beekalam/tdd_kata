<?php

namespace Tests\Unit;

use App\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private $router;

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = new Router(__DIR__ . DIRECTORY_SEPARATOR . "../stubs");
    }

    /** @test */
    function check_router_can_find_all_controller_files()
    {
        $this->assertEquals(['StudentController', 'TeacherController'], $this->router->controllers());
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
        $this->assertEquals('StudentController', $routes['/student/home']['controller']);
        $this->assertEquals('homeAction', $routes['/student/home']['method']);

        $this->assertEquals('StudentController', $routes['/student/about']['controller']);
        $this->assertEquals('aboutAction', $routes['/student/about']['method']);
    }

    /** @test */
    function given_a_route_should_returns_the_output_of_the_function()
    {
        $this->assertEquals('home action', $this->router->getRouteResult('/student/home'));
        $this->assertEquals('about action', $this->router->getRouteResult('/student/about'));
        $this->assertEquals('info action', $this->router->getRouteResult('/student/1/2'));
        $this->assertEquals('teacher info', $this->router->getRouteResult('/teacher/1/2'));
    }

    /** @test */
    function a_route_can_have_placeholder_values()
    {
        $key = "/student/{id}/{page}";
        $routes = $this->router->getRoutes();
        $this->assertEquals(2, count($routes[$key]['params']));
        $this->assertEquals("id", $routes[$key]['params'][0]);
        $this->assertEquals("page", $routes[$key]['params'][1]);
    }

    /** @test */
    function a_route_should_have_a_parts_section_that_is_the_number_of_placeholde_and_noneplaceholder_parts()
    {
        $key = "/student/{id}/{page}";
        $routes = $this->router->getRoutes();
        $this->assertEquals(3, count($routes[$key]['parts']));
    }


}