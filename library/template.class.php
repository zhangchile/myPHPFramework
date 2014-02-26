<?php

class Template
{
    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($_controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    function set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    //渲染视图
    function render()
    {
        extract($this->variables);//从数组中将变量导入到当前的符号表
        //加载视图模板
        //默认头部 ./application/views/header.php
        //默认底部 ./application/views/footer.php
        //控制器对应的头部 ./application/views/控制器名字/header.php
        //控制器对应的底部 ./application/views/控制器名字/footer.php
        if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
            include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
        } else {
            include (ROOT . DS . 'application' . DS . 'views' . DS . 'header.php');//加载模板头部
        }
        //加载控制器所在的视图
        //i.e ./application/views/controller_name/action_name.php
        include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');

        if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
            include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
        } else {
            include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');//加载模板底部
        }
    }
}