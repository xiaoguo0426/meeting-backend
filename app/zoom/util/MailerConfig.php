<?php


namespace app\zoom\util;


use app\zoom\model\EmailConfigModel;

class MailerConfig
{
    private $charset;
    private $host;
    private $username;
    private $password;
    private $smtp_secure;
    private $port;

    public function __construct($company_id)
    {
        $config = EmailConfigModel::mk()->where(['company_id' => $company_id])->find();
        if (empty($config)) {
            throw new \Exception('Email Configure is Empty');
        }
        $this->charset = $config['charset'];
        $this->host = $config['host'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->smtp_secure = $config['smtp_secure'];
        $this->port = $config['port'];
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSmtpSecure()
    {
        return $this->smtp_secure;
    }

    public function getPort()
    {
        return $this->port;
    }
}