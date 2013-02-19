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
    use OAuthShared;

    /**
     * Default constructor.
     *
     * @param string $provider
     *  The provider for this instance.
     * @throws RuntimeException
     */
    public function __construct($provider)
    {
        $this->defaults($provider);
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

        $this->data[$name] = $value;
    }

    /**
     * Acquire an attribute of the provider.
     *
     * @param string $name
     *  The attribute to acquire.
     * @throws RuntimeException
     * @return mixed
     */
    public function get($name)
    {
        if (!isset($this->data[$name])) {
            throw new \RuntimeException('Invalid OAuthProvider attribute for get()');
        }

        return $this->data[$name];
    }

    /**
     * Run the necessary requests and details for acquiring the token.
     *
     * @throws RuntimeException
     * @return string
     */
    public function token()
    {
        $this->load();

        $token_url = $this->data['tokenurl'];
        if (strlen($token_url) < 1) {
            // FB will fall through here
            if ($this->data['title'] === 'facebook') {
                return '';
            }

            throw new \RuntimeException('Invalid URL for Token acquisition');
        }
    }

    /**
     * Fire up the global oauth object as required.
     *
     * @see \oauth
     * @throws \RuntimeException
     */
    private function load()
    {
        if (!is_a($this->oauth, "\oauth")) {
            $this->oauth = new \oauth($this->data['id'], $this->data['secret'], $this->data['sig'], $this->data['authtype']);

            if (!is_a($this->oauth, "\oauth")) {
                throw new \RuntimeException('Unable to load global oauth object');
            }
        }
    }

    /**
     * Seed our data with some default content.
     *
     * Using the current provider, push default content into
     *  self::$data ready for updating.
     *
     * @param string $provider
     *  The name of the provider to set the defaults for.
     * @throws RuntimeException
     * @return null
     */
    private function defaults($provider)
    {
        $provider = strtolower(trim($provider));
        $this->data['title'] = $provider;

        switch ($provider) {
            case 'twitter':
                $this->data['tokenurl'] = 'https://api.twitter.com/oauth/request_token';
                $this->data['authoriseurl'] = 'https://api.twitter.com/oauth/authorize';
                $this->data['accessurl'] = 'https://api.twitter.com/oauth/access_token';
                $this->data['apiurl'] = 'https://api.twitter.com/1/account/verify_credentials.json';

                $this->data['sig'] = OAUTH_SIG_METHOD_HMACSHA1;
                $this->data['authtype'] = OAUTH_AUTH_TYPE_AUTHORIZATION;

                break;

            case 'facebook':
                $this->data['authoriseurl'] = 'https://www.facebook.com/dialog/oauth';
                $this->data['accessurl'] = 'https://graph.facebook.com/oauth/access_token';
                $this->data['apiurl'] = 'https://graph.facebook.com/me';

                $this->data['sig'] = OAUTH_SIG_METHOD_HMACSHA1;
                $this->data['authtype'] = OAUTH_AUTH_TYPE_URI;

                break;

            case 'yahoo':
                $this->data['tokenurl'] = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
                $this->data['authoriseurl'] = 'https://api.login.yahoo.com/oauth/v2/request_auth';
                $this->data['accessurl'] = 'https://api.login.yahoo.com/oauth/v2/get_token';
                $this->data['apiurl'] = 'http://social.yahooapis.com/v1/user/{xoauth_yahoo_guid}/profile';

                $this->data['sig'] = OAUTH_SIG_METHOD_HMACSHA1;
                $this->data['acctype'] = 'PLAINTEXT';
                $this->data['authtype'] = OAUTH_AUTH_TYPE_URI;

                break;

            case 'google':
                $this->data['tokenurl'] = 'https://www.google.com/accounts/OAuthGetRequestToken';
                $this->data['authoriseurl'] = 'https://www.google.com/accounts/OAuthAuthorizeToken';
                $this->data['accessurl'] = 'https://www.google.com/accounts/OAuthGetAccessToken';
                $this->data['apiurl'] = 'http://www-opensocial.googleusercontent.com/api/people/@me/@self';

                $this->data['sig'] = OAUTH_SIG_METHOD_HMACSHA1;
                $this->data['authtype'] = OAUTH_AUTH_TYPE_URI;
                $this->data['scope'] = 'http://www-opensocial.googleusercontent.com/api/people/';

                break;

            case 'myspace':
                $this->data['tokenurl'] = 'http://api.myspace.com/request_token';
                $this->data['authoriseurl'] = 'http://api.myspace.com/authorize';
                $this->data['accessurl'] = 'http://api.myspace.com/access_token';
                $this->data['apiurl'] = 'http://api.myspace.com/1.0/people/@me/@self?format=json';

                $this->data['sig'] = OAUTH_SIG_METHOD_HMACSHA1;
                $this->data['authtype'] = OAUTH_AUTH_TYPE_URI;

                break;

            case 'linkedin':
                $this->data['tokenurl'] = 'https://api.linkedin.com/uas/oauth/requestToken';
                $this->data['authoriseurl'] = 'https://www.linkedin.com/uas/oauth/authorize';
                $this->data['accessurl'] = 'https://api.linkedin.com/uas/oauth/accessToken';
                $this->data['apiurl'] = 'http://api.linkedin.com/v1/people/~';

                $this->data['sig'] = OAUTH_SIG_METHOD_HMACSHA1;
                $this->data['authtype'] = OAUTH_AUTH_TYPE_URI;

                break;

            default:
                throw new \RuntimeException('Invalid provider selected');
        }
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

    /**
     * Keep the global oauth object.
     *
     * @var \oauth
     */
    private $oauth;
}

