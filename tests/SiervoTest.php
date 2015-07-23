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

require '../Siervo/Siervo.php';

class SiervoTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \Siervo\Siervo
     */
    private $app;

    public function setUp(){
        $this->app = new Siervo();
    }

    public function testSetEnv(){
        $this->app->setEnv();
        $this->assertEquals('development', Siervo::$_ENV);
        $this->app->setEnv('production');
        $this->assertEquals('production', Siervo::$_ENV);
        $this->app->environment('lalala', function(){return null;});
        $this->app->setEnv('lalala');
        $this->assertEquals('lalala', Siervo::$_ENV);
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
}
