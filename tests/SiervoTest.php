<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 23/07/15
 * Time: 14:40
 */

namespace tests;

use PHPUnit_Framework_TestCase;
use \Siervo\Siervo;

require_once 'src/Siervo/Siervo.php';

class SiervoTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \Siervo\Siervo
     */
    private $app;

    public function setUp(){
        Siervo::registerAutoload();
        $this->app = new Siervo();
    }

    public function testSetEnv(){
        $this->assertTrue($this->app->setEnv());
        $this->assertEquals('development', Siervo::$_ENV);
        $this->app->setEnv('production');
        $this->assertEquals('production', Siervo::$_ENV);
        $this->app->environment('lalala', function(){return null;});
        $this->app->setEnv('lalala');
        $this->assertEquals('lalala', Siervo::$_ENV);
        $this->assertFalse($this->app->setEnv('lololo'));
    }

    public function testSetPath(){
        $this->app->setPath();
        $this->assertEquals('/', substr(Siervo::$_PATH, 0, 1));
        $this->app->setPath('/');
        $this->assertEquals('/', Siervo::$_PATH);
    }

    public function testSetRPath(){
        $this->app->setRPath();
        $this->assertEquals('/', substr(Siervo::$_rPATH, 0, 1));
        $this->app->setRPath('/');
        $this->assertEquals('/', Siervo::$_rPATH);
    }

    public function testRoute(){
        $this->assertInstanceOf('Siervo\Router', $this->app->route('/prueba'));
    }

    public function testGet(){
        $this->assertNull($this->app->get('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->app->get(function(){return null;}));
        $this->assertFalse($this->app->get());
    }

    public function testPost(){
        $this->assertNull($this->app->post('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->app->post(function(){return null;}));
        $this->assertFalse($this->app->post());
    }

    public function testPut(){
        $this->assertNull($this->app->put('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->app->put(function(){return null;}));
        $this->assertFalse($this->app->put());
    }

    public function testDelete(){
        $this->assertNull($this->app->delete('/', function(){return null;}));
        $this->assertInstanceOf('Siervo\Router', $this->app->delete(function(){return null;}));
        $this->assertFalse($this->app->delete());
    }

    public function testNotFoud(){
        $this->app->notFound('hola');
        $this->assertFalse($this->app->notFoundCallback);
        $this->app->notFound(function(){return null;});
        $this->assertNull(call_user_func($this->app->notFoundCallback));
    }

    public function testDispatch(){
        $this->assertTrue($this->app->dispatch(function(){return true;}));
        $app = $this->app;
        $this->assertInstanceOf('Siervo\Siervo', $this->app->dispatch(function() use ($app) {return $app;}));
    }

    public function testDispatchNotFound(){
        $callback = function(){return true;};
        $this->app->notFound($callback);
        $this->assertTrue($this->app->dispatch($callback));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDispatchException(){
        $this->app->dispatch('jojojo');
        $this->app->dispatch([function($rq, $rs, $next){$next();}, function($rq, $rs, $next){$next();}]);
    }

    public function testDispatchArray(){
        $this->assertTrue($this->app->dispatch([function($rq, $rs, $next){return $next();}, function(){return true;}]));
    }

    public function testUso(){
        $this->app->uso(function(){
            return true;
        });
        $this->assertTrue($this->app->dispatch());
    }

    public function testUsoMultiple(){
        $this->app->uso(function($rq, $rs, $next){return $next();});
        $this->app->uso(function(){return true;});
        $this->assertTrue($this->app->dispatch());
    }

    public function testUsoBreak(){
        $this->app->uso(function($rq, $rs, $next){return true;});
        $this->app->uso(function($rq, $rs, $next){$next();});
        $this->assertTrue($this->app->dispatch());
    }

    public function testUsoReturn(){
        $this->assertInstanceOf('Siervo\Siervo', $this->app->uso(function(){return true;}));
    }
}
