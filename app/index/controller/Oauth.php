<?php


namespace app\index\controller;


use think\admin\Controller;
use think\facade\Request;

class Oauth extends Controller
{
    public function zparcelOauth2()
    {
        echo 'oauth2';
    }

    public function zparcelRedirect()
    {
        echo 'success';
        var_dump(Request::post());
        var_dump(Request::get());
    }
}