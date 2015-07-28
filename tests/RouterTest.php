<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27/07/15
 * Time: 11:17
 */

namespace tests;

use Siervo\Router;
use Siervo\Siervo;

require_once 'Siervo/Siervo.php';

class RouterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Siervo\Router
     */
    private $router;

    public function setUp(){
        Siervo::registerAutoload();
        $this->router = new Router($this->getMock('Siervo\Siervo'));
    }

    public function testRoute(){
        $this->assertInstanceOf('Siervo\Router', $this->router->route('/prueba'));
        $this->assertEquals('/prueba', $this->router->currentRoute);
    }

    public function testGet(){
        $this->assertNull($this->router->get('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->router->get(function(){return null;}));
        $this->assertFalse($this->router->get());
    }

    public function testPost(){
        $this->assertNull($this->router->post('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->router->post(function(){return null;}));
        $this->assertFalse($this->router->post());
    }

    public function testPut(){
        $this->assertNull($this->router->put('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->router->put(function(){return null;}));
        $this->assertFalse($this->router->put());
    }

    public function testDelete(){
        $this->assertNull($this->router->delete('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->router->delete(function(){return null;}));
        $this->assertFalse($this->router->delete());
    }

    public function testGetRoutes(){
        $this->router->get('/', function(){return null;});
        $this->assertArrayHasKey('/', $this->router->getRoutes('GET'));
        $this->router->post('/', function(){return null;});
        $this->assertArrayHasKey('/', $this->router->getRoutes('POST'));
        $this->router->put('/', function(){return null;});
        $this->assertArrayHasKey('/', $this->router->getRoutes('PUT'));
        $this->router->delete('/', function(){return null;});
        $this->assertArrayHasKey('/', $this->router->getRoutes('DELETE'));
        $this->assertEmpty($this->router->getRoutes('TEST'));
    }
}
