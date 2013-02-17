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

        $this->assertEquals('replace-this-string', $signature);
    }
}

