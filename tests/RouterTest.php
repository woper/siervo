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

require_once 'src/Siervo/Siervo.php';

class RouterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Siervo\Router
     */
    private $router;

    public function setUp(){
        Siervo::registerAutoload();
        $this->router = Router::getInstance($this->getMockBuilder('Siervo\Siervo')->disableOriginalConstructor()->getMock());
    }

    public function testRoute(){
        $this->assertInstanceOf('Siervo\Router', $this->router->route('/prueba'));
        $this->assertEquals('/prueba', $this->router->currentRoute);
    }

    public function testGet(){
        $this->assertTrue($this->router->get('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->router->get(function(){return null;}));
        $this->assertFalse($this->router->get());
    }

    public function testPost(){
        $this->assertTrue($this->router->post('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->router->post(function(){return null;}));
        $this->assertFalse($this->router->post());
    }

    public function testPut(){
        $this->assertTrue($this->router->put('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->router->put(function(){return null;}));
        $this->assertFalse($this->router->put());
    }

    public function testDelete(){
        $this->assertTrue($this->router->delete('/', function(){return null;}));
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

    public function testProcess(){
        $this->router->get('/', function(){return true;});
        $request = $this->getMockBuilder('Siervo\Request')->disableOriginalConstructor()->getMock();
        $this->assertNull($this->router->process($request));

    }

    public function testMatch(){
        $this->assertNull($this->router->match(array('', 'maxi', 'maxi'), array('', 'maxi', 'tarara')));
        $this->assertNotNull($this->router->match(array('', 'maxi', 'maxi'), array('', 'maxi', 'maxi')));
        $this->assertNotNull($this->router->match(array('', 'maxi', 'maxi'), array('', 'maxi', ':name')));
    }
}
