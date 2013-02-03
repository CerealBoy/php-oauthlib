<?php
/**
 * Main OAuth class.
 *
 * @namespace OAuthLib
 * @package php-oauthlib
 * @author Allan Shone <allan.shone@yahoo.com>
 */

namespace OAuthLib;

/**
 * The OAuth class.
 *
 * This class provides all access and usage for OAuth.
 *   Whilst there are other classes provided within the OAuthLib namespace,
 *   this is the gateway and interfacing class.
 */
class OAuth
{
    /**
     * Constructor for OAuth.
     *
     * Setup an OAuth instance and prepare ofr authentication.
     */
    public function __construct()
    {
        //
    }

    /**
     * Data for state and setting.
     *
     * <pre>
     *   - <b>hash-algorithm</b> algorithm used for hashing
     *   - <b>indexing</b> main cookie index
     *   - <b>indexer</b> temp cookie index
     *   - <b>salt</b> salt for use with hashing
     *   - <b>cookie</b> current content of the cookie
     * </pre>
     *
     * @var array
     */
    private $data = array(
        'hash-algorithm' => 'whirlpool',
        'indexing' => 'va',
        'indexer' => 'vb',
        'salt' => 'This1s5@It,8008135',
        'cookie' => array(),
    );
}

