<?php

use Pux\Controller\ExpandableController;
use Pux\Builder\ControllerRouteBuilder;
use Pux\Mux;

class RESTfulControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testRESTfulDispatch()
    {
        $con = new ProductResourceController;

        $routes = ControllerRouteBuilder::build($con);
        $this->assertNotEmpty($routes);
        $this->assertTrue(is_array($routes));

        $methods = ControllerRouteBuilder::parseActionMethods($con);
        $this->assertNotEmpty($methods);
        $productMux = $con->expand();  // there is a sorting bug (fixed), this tests it.
        $this->assertNotEmpty($productMux);

        $root = new Mux;
        $root->mount('/product', $con->expand());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertNotNull($root->dispatch('/product/10'));

        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertNotNull( $root->dispatch('/product/10') );

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertNotNull( $root->dispatch('/product') ); // create

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertNotNull( $root->dispatch('/product/10') ); // update
        
    }
}

