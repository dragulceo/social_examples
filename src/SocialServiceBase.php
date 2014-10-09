<?php
/**
 * Created by PhpStorm.
 * User: dragulceo
 * Date: 08/10/14
 * Time: 21:09
 */

namespace Local;


use LogicException;

abstract class SocialServiceBase implements SocialServiceInterface
{
    private $defaultParams;

    protected function initDefaultParams()
    {
        $this->setDefaultParams(array());
    }

    protected function getDefaultParams()
    {
        if (!$this->defaultParams) {
            $this->initDefaultParams();
        }
        return $this->defaultParams;
    }

    protected function setDefaultParams($array)
    {
        $this->defaultParams = $array;
    }

    /**
     * @returns string
     */
    abstract protected function getSessionKey();

    protected function sessionHasKey($key)
    {
        $sessionKey = $this->getSessionKey();
        return isset($_SESSION[$sessionKey]) && isset($_SESSION[$sessionKey][$key]);
    }

    protected function sessionReset()
    {
        $_SESSION[$this->getSessionKey()] = array();
    }

    protected function sessionSave($key, $value)
    {
        $_SESSION[$this->getSessionKey()][$key] = $value;
    }

    protected function sessionLoad($key)
    {
        if ($this->sessionHasKey($key)) {
            return $_SESSION[$this->getSessionKey()][$key];
        }
        return false;
    }

    /**
     * @returns string
     */
    abstract protected function getAuthorizationURLBase();

    protected function getAuthorizationURL()
    {
        return $this->getAuthorizationURLBase() .
        http_build_query($this->getAuthorizationParams());
    }

    protected function getAuthorizationParams()
    {
        $state = uniqid('', true);
        $this->sessionSave('state', $state);
        return array_merge($this->getDefaultParams(), array(
            'response_type' => 'code',
            'state' => $state
        ));
    }

    public function getAuthorizationCode()
    {
        // Redirect user to authenticate
        header("Location: " . $this->getAuthorizationURL());
        exit;
    }

    /**
     * @returns string
     */
    abstract protected function getAccessTokenURLBase();

    protected function getAccessTokenURL()
    {
        return $this->getAccessTokenURLBase() .
        http_build_query($this->getAccessTokenParams());
    }

    protected function getAccessTokenParams()
    {
        return array_merge($this->getDefaultParams(), array(
            'code' => $_GET['code'],
            'client_secret' => '',
            'grant_type' => 'authorization_code'
        ));
    }

    protected function processAccessTokenResponse($response)
    {
        return false;
    }

    protected function getAccessTokenContext()
    {
        return stream_context_create(
            array('http' =>
                array(
                    'method' => 'POST',
                )
            )
        );
    }

    public function getAccessToken()
    {
        // Retrieve access token information
        $response = file_get_contents($this->getAccessTokenURL(), false, $this->getAccessTokenContext());
        if (!$response) {
            throw new AuthException('Could not get access token');
        }

        $this->processAccessTokenResponse($response);
        return true;
    }


    /**
     * @returns string
     */
    abstract protected function getFetchURLBase();

    protected function getFetchParams()
    {
        return array();
    }

    protected function getFetchURL($resource)
    {
        $url = $this->getFetchURLBase() . $resource;
        $params = $this->getFetchParams();
        if (count($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

    protected function getFetchContext($method)
    {
        return false;
    }

    public function fetch($method, $resource, $body = '')
    {
        $response = file_get_contents($this->getFetchURL($resource), false,
            $this->getFetchContext($method));
        if (!$response) {
            throw new AuthException('Could not get access token');
        }
        return $response;
    }

    public function checkState()
    {
        // OAuth 2 Control Flow
        if (isset($_GET['error'])) {
            throw new AuthException($_GET['error'] . ': ' . $_GET['error_description']);
        } elseif (isset($_GET['code'])) {
            // User authorized your application
            if ($this->sessionLoad('state') == $_GET['state']) {
                // Get token so you can make API calls
                $this->getAccessToken();
            } else {
                throw new AuthException('CSRF attack? Or did you mix up your states?');
            }
        } else {
            $expiresAt = $this->sessionLoad('expires_at');
            if (!$expiresAt || (time() > $expiresAt)) {
                $this->sessionReset();
            }
            if ($this->sessionLoad('access_token') === false) {
                $this->getAuthorizationCode();
            }
        }
    }

}