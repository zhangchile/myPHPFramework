<?php 

if ( ! function_exists('site_url'))
{
    function site_url($uri = '')
    {
        $url = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $url .= $uri;
        return rtrim($url, "/");;   
    }
}

if ( ! function_exists('base_url'))
{
    function base_url($uri = '')
    {
        $url = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $url .= $uri;
        return rtrim($url, "/");
    }
}

if ( ! function_exists('redirect'))
{
    function redirect($uri)
    {
        if($uri == '') return null;
        $url = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $url .= $uri;
        
        header('Location: ' . rtrim($url, "/"));
    }
}