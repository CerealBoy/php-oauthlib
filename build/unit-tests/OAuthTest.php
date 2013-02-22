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
        ));
        $signature = $lib->signature();

        $this->assertEquals('MzJjZWVjOGE2YzJhZGYxMDdlZmFmOWU2ODlmZjVlOWQzZmNmYWE3MDBjODA5YTc4ODUzYWFiMTJjNDFhNjJmZDIwZTFkNGNhYjJmMDJiYzcxMTQ0YThhYTQwZWE0OWNmZjQ4MGY5MThlMGJjZjc0YzUzM2VmZWU0MzY3NGUwODA=', $signature);
    }
}

