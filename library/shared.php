<?php

/** Check if environment is development and displiay errors **/

function setReporting()
{
    if (DEVELOPMENT_ENVIRONMENT == true) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        //设置指定配置选项的值。
        //这个选项会在脚本运行时保持新的值，
        //并在脚本结束时恢复。
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
    }
}

/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value)
{
    //array_map() : 将回调函数作用到给定数组的单元上
    //stripslashes() : 返回一个去除转义反斜线后的字符串（\' 转换为 ' 等等）。
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

function removeMagicQuotes()
{
    //get_magic_quotes_gpc() : 获取当前 magic_quotes_gpc 的配置选项设置
    //如果 magic_quotes_gpc 为关闭时返回 0，否则返回 1。
    //在 PHP 5.4.O 起将始终返回 FALSE。
    if (get_magic_quotes_gpc()) {
        $_GET    = stripSlashesDeep($_GET   );
        $_POST   = stripSlashesDeep($_POST  );
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

/** Check register globals and remove them **/

function unregisterGlobals()
{
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST',
                       '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Main Call Function **/

function callHook()
{
    global $url;

    $urlArray = array();
    $urlArray = explode("/", $url);

    $controller = $urlArray[0];
    array_shift($urlArray);//将数组开头的单元移出数组
    $action = $urlArray[0];
    array_shift($urlArray);
    $queryString = $urlArray;

    $controllerName = $controller;
    $controllerName = ucwords($controller);//将字符串中每个单词的首字母转换为大写
    $model = rtrim($controller, 's');//删除字符串末端的's'字符
    $controller .= 'Controller';
    $dispatch = new $controller($model, $controllerName, $action);

    if ((int)method_exists($controller, $action)) {
        call_user_func_array(array($dispatch, $action), $queryString);
    } else {
        /* 错误处理代码 */
    }
}

/** Autoload any classes that are required **/

function __autoload($className)
{
    if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php')
    ) {
        require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php');
    } elseif (file_exists(ROOT . DS . 'application' . DS 
                        . 'controllers' . DS .strtolower($className) . '.php')
    ) {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS 
                    . strtolower($className) . '.php');
    } elseif (file_exists(ROOT . DS . 'application' . DS 
                        . 'models' . DS . strtolower($className) . '.php')
    ) {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS 
                    . strtolower($className) . '.php');
    } else {
        /* 无法加载类，错误处理代码 */
    }
}

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();