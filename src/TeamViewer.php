<?php
/**
 * Created by PhpStorm.
 * User: dragulceo
 * Date: 08/10/14
 * Time: 20:58
 */

namespace Local;


class TeamViewer extends SocialServiceBase
{

    static private $TEAMVIEWER_URL_API_BASE = 'https://webapi.teamviewer.com';

    protected function initDefaultParams()
    {
        self::setDefaultParams(array(
            'client_id' => Config::$TEAMVIEWER_APP_ID,
            'redirect_uri' => Config::getTeamViewerRedirectURL()
        ));
    }

    protected function getAuthorizationURLBase()
    {
        return self::$TEAMVIEWER_URL_API_BASE . '/api/v1/oauth2/authorize?';
    }

    protected function getAccessTokenURLBase()
    {
        return self::$TEAMVIEWER_URL_API_BASE . '/api/v1/oauth2/token?';
    }

    protected function getAccessTokenParams()
    {
        return array();
    }

    protected function getAccessTokenContext()
    {
        return stream_context_create(
            array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query(array_merge(parent::getAccessTokenParams(), array(
                        'client_secret' => Config::$TEAMVIEWER_APP_SECRET
                    )))
                )
            )
        );
    }

    protected function getFetchURLBase()
    {
        return self::$TEAMVIEWER_URL_API_BASE;
    }

    protected function getFetchContext($method)
    {
        $headers = array(
            'Authorization: Bearer ' . $this->sessionLoad('access_token'),
            'Content-type: application/x-www-form-urlencoded'
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
        return 'tw';
    }


}
