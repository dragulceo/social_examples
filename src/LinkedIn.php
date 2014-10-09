<?php
/**
 * Created by PhpStorm.
 * User: dragulceo
 * Date: 07/10/14
 * Time: 20:17
 */

namespace Local;

class LinkedIn extends SocialServiceBase
{
    protected function initDefaultParams()
    {
        $this->setDefaultParams(array(
            'client_id' => Config::$LINKEDIN_APP_ID,
            'redirect_uri' => Config::getLinkedInRedirectURL(),
        ));
    }

    protected function getAuthorizationParams()
    {
        return array_merge(parent::getAuthorizationParams(), array(
            'scope' => Config::$LINKEDIN_SCOPE
        ));
    }

    protected function getAuthorizationURLBase()
    {
        return 'https://www.linkedin.com/uas/oauth2/authorization?';
    }

    protected function getAccessTokenURLBase()
    {
        return 'https://www.linkedin.com/uas/oauth2/accessToken?';
    }

    protected function getAccessTokenParams()
    {
        return array_merge(parent::getAccessTokenParams(), array(
            'client_secret' => Config::$LINKEDIN_APP_SECRET
        ));
    }

    protected function processAccessTokenResponse($response)
    {
        // Native PHP object, please
        $token = json_decode($response);

        // Store access token and expiration time
        $this->sessionSave('access_token', $token->access_token);
        $this->sessionSave('expires_in', $token->expires_in);
        $this->sessionSave('expires_at', time() + $token->expires_in);

    }

    protected function getFetchURLBase()
    {
        return 'https://api.linkedin.com';
    }

    protected function getFetchContext($method)
    {
        $headers = array(
            'Authorization: Bearer ' . $this->sessionLoad('access_token'),
            'x-li-format: json'
        );

        $context = stream_context_create(
            array('http' =>
                array(
                    'method' => $method,
                    'header' => implode("\r\n", $headers),
                )
            )
        );

        return $context;
    }

    protected function getSessionKey()
    {
        return 'ln';
    }
}