<?php

require_once __DIR__ . '/../../autoload.php';

class OAuthTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $lib = new OAuthLib\OAuth();
        $this->assertInstanceOf('OAuthLib\OAuth', $lib);
    }

    public function testSignatureGenerate()
    {
        $lib = new OAuthLib\OAuth();
        $lib->context(array(
            'provider' => 'twitter',
            'hash-algorithm' => 'whirlpool',
            'indexing' => 'va',
            'id' => 'something',
        ));
        $signature = $lib->signature();

        $this->assertEquals('MzJjZWVjOGE2YzJhZGYxMDdlZmFmOWU2ODlmZjVlOWQzZmNmYWE3MDBjODA5YTc4ODUzYWFiMTJjNDFhNjJmZDIwZTFkNGNhYjJmMDJiYzcxMTQ0YThhYTQwZWE0OWNmZjQ4MGY5MThlMGJjZjc0YzUzM2VmZWU0MzY3NGUwODA=', $signature);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testContextArray()
    {
        $lib = new OAuthLib\OAuth();
        $lib->context('twitter');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testContextProvider()
    {
        $lib = new OAuthLib\OAuth();
        $lib->context(array('herp' => 'derp'));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testContextNaming()
    {
        $lib = new OAuthLib\OAuth();
        $lib->context(array(
            'provider' => 'yahoo',
            'herp' => 'derp',
        ));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCookie()
    {
        $lib = new OAuthLib\OAuth();

        $lib->context(array(
            'provider' => 'facebook',
        ));
        $success = $lib->setCookie();
    }
}

