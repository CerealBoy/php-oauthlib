<?php
/**
 * Shared parts for OAuthLib.
 */

namespace OAuthLib;

/**
 * Shared functionality across OAuthLib classes.
 *
 * @package php-oauthlib
 * @namespace OAuthLib
 * @author Allan Shone <allan.shone@yahoo.com>
 * @since Feb 6, 2013
 */
trait OAuthShared
{
    /**
     * Complete a curl request.
     *
     * @param array $options
     *  All setopt params for curl.
     * @return string
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    private function request($options)
    {
        if (!is_array($options)) {
            throw new \InvalidArgumentException('request() expects an array');
        }

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
    }
}

