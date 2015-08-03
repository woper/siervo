<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27/07/15
 * Time: 19:21
 */

namespace tests;

use Siervo\Response;
use Siervo\Siervo;

class ResponseTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Response
     */
    private $response;

    public function setUp(){
        Siervo::registerAutoload();
        $this->response = new Response();
    }
}
