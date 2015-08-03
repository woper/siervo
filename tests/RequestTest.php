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

class RequestTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Request
     */
    private $request;

    public function setUp(){
        Siervo::registerAutoload();
        $this->request = new Request();
    }
}
