<?php
/**
 * OAuthProvider for dealing with Provider interactions.
 */

namespace OAuthLib;

/**
 * OAuthProvider for individual provider details.
 *
 * Sandbox the provider, for encapsulating all data and interaction
 *  with specific providers. All providers share common ground, and
 *  have a standard interface or access.
 *
 * @package php-oauthlib
 * @namespace OAuthLib
 * @author Allan Shone <allan.shone@yahoo.com>
 * @since Feb 2, 2013
 */
class OAuthProvider
{
    /**
     * Default constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Set a specific data item for this provider.
     *
     * @param string $name
     *  The name of the data item.
     * @param mixed $value
     *  The value for the param.
     * @throws RuntimeException
     * @return bool
     */
    public function set($name, $value)
    {
        if (!isset($this->data[$name])) {
            throw new \RuntimeException('Invalid OAuthProvider attribute for set()');
        }

        //
    }

    /**
     * Seed our data with some default content.
     *
     * Using the current provider, push default content into
     *  self::$data ready for updating.
     */
    private function defaults()
    {
        //
    }

    /**
     * Specific information for this provider.
     *
     * This information is used to interact with the specific provider.
     *  Defaults are contained within self::defaults()
     *
     * <pre>
     *  <b>title</b> textual identifier
     *  <b>id</b> identifier for account interaction
     *  <b>secret</b> accompanying secret for id
     *  <b>tokenurl</b> url for token generation
     *  <b>authoriseurl</b> url for redirect to authorise
     *  <b>accessurl</b> url for access acquisition
     *  <b>callbackurl</b> url for callback during process
     *  <b>apiurl</b> url for api to check credentials
     *  <b>sig</b> signature type
     *  <b>authtype</b> oauth auth type
     *  <b>acctype</b> optional access acquisition type
     *  <b>scope</b> optional scope parameter
     * </pre>
     */
    private $data = array(
        'title' => '',
        'id' => '',
        'secret' => '',
        'tokenurl' => '',
        'authoriseurl' => '',
        'accessurl' => '',
        'callbackurl' => '',
        'apiurl' => '',
        'sig' => '',
        'authtype' => '',
        'acctype' => '',
        'scope' => '',
    );
}

