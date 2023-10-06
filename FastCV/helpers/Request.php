<?php

namespace helpers;

class Request
{
    public static function isPost()
    {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }

    public static function get($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) ? $_POST[$key] : $default);
    }

    public static function post($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
}

