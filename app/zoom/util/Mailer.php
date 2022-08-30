<?php


namespace app\zoom\util;


use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $mailer;

    public function __construct(MailerConfig $config)
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->SMTPDebug = 0; // 是否开启smtp的debug进行调试 ，0关闭，1开启
        $this->mailer->isSMTP(); // 启用SMTP
        $this->mailer->CharSet = $config->getCharset(); //设置字符编码
        $this->mailer->Host = $config->getHost();// SMTP服务器
        $this->mailer->SMTPAuth = true;// 启用SMTP认证
        $this->mailer->Username = $config->getUserName();// 发送邮件的邮箱，即自己的邮箱
        $this->mailer->Password = $config->getPassword();// 授权码
        $this->mailer->SMTPSecure = $config->getSmtpSecure();// 加密方式为tls或者ssl，根据需求自己改
        $this->mailer->Port = $config->getPort();// 端口号
        $this->mailer->isHTML(true);
    }

    /**
     * 发送邮件
     * @param $subject  string 邮件标题
     * @param $to       string 邮件接收人
     * @param $body     string 邮件内容
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send(string $subject, string $to, string $body)
    {

        $this->mailer->Subject = $subject;
        $this->mailer->addAddress($to);
        $this->mailer->Body = $body;

        return $this->mailer->send();//发送邮件
    }


}