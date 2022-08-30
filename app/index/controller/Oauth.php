<?php


namespace app\index\controller;


use think\admin\Controller;

class Oauth extends Controller
{
    public function zparcelOauth2()
    {
        echo 'oauth2';
    }

    public function zparcelRedirect()
    {
        echo 'success';
    }
}