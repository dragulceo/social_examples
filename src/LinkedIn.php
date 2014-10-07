<?php
/**
 * Created by PhpStorm.
 * User: dragulceo
 * Date: 07/10/14
 * Time: 20:17
 */

namespace Local;

class LinkedIn
{

    private static $__defaultParams;

    private static function getDefaultParams()
    {
        if (!self::$__defaultParams) {
            self::$__defaultParams = array(
                'client_id' => Config::$LINKEDIN_APP_ID,
                'redirect_uri' => Config::getLinkedInRedirectURL(),
            );
        }
        return self::$__defaultParams;
    }

    public static function getAuthorizationCode()
    {
        $params = array_merge(self::getDefaultParams(), array(
            'response_type' => 'code',
            'scope' => Config::$LINKEDIN_SCOPE,
            'state' => uniqid('', true), // unique long string
        ));

        // Authentication request
        $url = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($params);

        // Needed to identify request when it returns to us
        $_SESSION['state'] = $params['state'];

        // Redirect user to authenticate
        header("Location: $url");
        exit;
    }

    public static function getAccessToken()
    {
        $params = array_merge(self::getDefaultParams(), array(
            'code' => $_GET['code'],
            'client_secret' => Config::$LINKEDIN_APP_SECRET,
            'grant_type' => 'authorization_code'
        ));

        // Access Token request
        $url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);

        // Tell streams to make a POST request
        $context = stream_context_create(
            array('http' =>
                array('method' => 'POST',
                )
            )
        );

        // Retrieve access token information
        $response = file_get_contents($url, false, $context);

        // Native PHP object, please
        $token = json_decode($response);

        // Store access token and expiration time
        $_SESSION['access_token'] = $token->access_token; // guard this!
        $_SESSION['expires_in'] = $token->expires_in; // relative time (in seconds)
        $_SESSION['expires_at'] = time() + $_SESSION['expires_in']; // absolute time

        return true;
    }

    public static function fetch($method, $resource, $body = '')
    {
        $headers = array(
            'Authorization: Bearer ' . $_SESSION['access_token'],
            'x-li-format: json', // Comment out to use XML
        );

        $params = array(//      'param1' => 'value1',
        );

        // Need to use HTTPS
        $url = 'https://api.linkedin.com' . $resource;

        // Append query parameters (if there are any)
        if (count($params)) {
            $url .= '?' . http_build_query($params);
        }

        // Tell streams to make a (GET, POST, PUT, or DELETE) request
        // And use OAuth 2 access token as Authorization
        $context = stream_context_create(
            array('http' =>
                array(
                    'method' => $method,
                    'header' => implode("\r\n", $headers),
                )
            )
        );

        // Hocus Pocus
        $response = file_get_contents($url, false, $context);

        // Native PHP object, please
        return $response;
    }
}