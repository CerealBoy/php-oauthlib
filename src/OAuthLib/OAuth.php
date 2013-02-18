<?php
/**
 * Main OAuth class.
 */

namespace OAuthLib;

/**
 * The OAuth class.
 *
 * This class provides all access and usage for OAuth.
 *   Whilst there are other classes provided within the OAuthLib namespace,
 *   this is the gateway and interfacing class.
 *
 * @package php-oauthlib
 * @namespace OAuthLib
 * @author Allan Shone <allan.shone@yahoo.com>
 * @since Feb 2, 2013
 */
class OAuth
{
    use OAuthShared;

    /**
     * Constructor for OAuth.
     *
     * Setup an OAuth instance and prepare for authentication.
     */
    public function __construct()
    {
        //
    }

    /**
     * Take the available data and determine context.
     *
     * @param array $details
     *  All contextual information.
     * @return string
     * @throws RuntimeException
     */
    public function context($details)
    {
        if (!is_array($details)) {
            throw new \InvalidArgumentException('Context should be an array');
        }

        if (!isset($details['provider'])) {
            throw new \InvalidArgumentException('Provider is required in context');
        }

        $this->provider = new OAuthProvider($details['provider']);

        foreach ($details as $name => $value) {
            switch ($name) {
                case 'hash-algorithm':
                case 'indexing':
                case 'indexer':
                case 'salt':
                case 'host':
                    $this->data[$name] = $value;
                    break;

                default:
                    $this->giveProvider($name, $value);
                    break;
            }
        }
    }

    /**
     * Pass through data to provider.
     *
     * From the initial context ingestion, ensure
     *  the provider has enough data to complete any
     *  necessary actions when required.
     *
     * @param string $name
     *  The name of the data attribute.
     * @param mixed $value
     *  The accompanying data value.
     * @throws \RuntimeException
     * @return bool
     */
    private function giveProvider($name, $value)
    {
        try {
            $this->provider->set($name, $value);
        } catch (\RuntimeException $e) {
            // could be silent here, let's revisit
            error_log("Error encountered sending to provider: {$e}");
        }
    }

    /**
     * Sign data details.
     *
     * Generate the signature for all known data. This is used for client-side
     *  state management and request details, i.e. having a session to be stored
     *  in a cookie for easy use between requests.
     *
     * @throws RuntimeException
     * @return string
     */
    public function signature()
    {
        $flatten = http_build_query($this->data['cookie']);

        $hash = hash_hmac(
            $this->data['hash-algorithm'],
            'salt' . $flatten . $this->data['salt'],
            $this->data['salt']
        );

        return base64_encode($hash);
    }

    /**
     * Sets the cookie with current auth details.
     *
     * Ensure the current status is valid, and then use the current settings
     *  to store all auth information in cookie.
     *
     * @return bool
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @see OAuthLib\OAuth::signature()
     */
    public function setCookie()
    {
        if (empty($this->data['cookie']) || count($this->data['cookie']) < 1) {
            throw new \InvalidArgumentException('Invalid content for setting cookie');
        }

        $signature = $this->signature();

        $details = array_merge($this->data['cookie'], array('sig' => $signature));
        $cookie = setrawcookie(
            $this->data['indexing'],
            rawurlencode(base64_encode(http_build_query($details))),
            time() + (3600 * 24),
            '/',
            $this->data['host'],
            false,
            true
        );

        if (!$cookie) {
            throw new \RuntimeException('Unable to set cookie');
        }
    }

    /**
     * Data for state and setting.
     *
     * <pre>
     *  <b>hash-algorithm</b> algorithm used for hashing
     *  <b>indexing</b> main cookie index
     *  <b>indexer</b> temp cookie index
     *  <b>salt</b> salt for use with hashing
     *  <b>cookie</b> current content of the cookie
     *  <b>host</b> host for cookie being set in
     *  <b>authed</b> if full authentication is achieved
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
        'host' => '',
        'authed' => false,
    );

    /**
     * Current provider object.
     *
     * @var OAuthLib\OAuthProvider
     */
    private $provider;
}

