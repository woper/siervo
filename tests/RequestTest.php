<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27/07/15
 * Time: 17:43
 */

namespace tests;

use Siervo\Request;
use Siervo\Siervo;

/**
 * Class RequestTest
 *
 * Estos tests estan fallando porque debo crear globales
 * que representen a $_SERVER['REQUEST_METHOD'],
 * $_SERVER['HTTP_X_HTTP_METHOD'], $_SERVER['REQUEST_URI'] y creo
 * que apache_request_headers(), de esta última no estoy muy seguro.
 *
 * @package tests
 */
class RequestTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Request
     */
    private $request;

    public function setUp(){
        Siervo::registerAutoload();
        $this->request = new Request();
    }

    public function testAddArgs(){
        $this->request->addArgs(array('jojojo' => true));
        $this->assertTrue($this->request->jojojo);
    }
}
