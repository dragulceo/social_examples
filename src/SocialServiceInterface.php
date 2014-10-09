<?php
/**
 * Created by PhpStorm.
 * User: dragulceo
 * Date: 08/10/14
 * Time: 21:34
 */

namespace Local;


interface SocialServiceInterface
{
    function getAuthorizationCode();

    function getAccessToken();

    function fetch($method, $resource, $body);
}