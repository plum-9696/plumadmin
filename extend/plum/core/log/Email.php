<?php

namespace plum\core\log;

use PHPMailer\PHPMailer\PHPMailer;
use think\App;
use think\contract\LogHandlerInterface;

class Email implements LogHandlerInterface
{
    /**
     * 配置参数
     * @var array
     */
    protected $config = [
        //项目名称,用于邮件标题区分项目
        'title'       => "项目名称",
        //是否关闭,调试模式下关闭,生产模式下打开
        'close'       => false,
        //调试模式,只有需要校验邮箱是否连同,才开启
        //只要trace('错误日志','error'),写入了内存,就会调用,邮件成功与否都会打印出来
        'debug'       => false,
        //SMTP服务器地址
        'host'        => '',
        //发件人昵称
        'sender_name' => '',
        //SMTP用户名,一般指邮箱用户名
        'username'    => '',
        //SMTP密码
        'password'    => '',
        //SMTP端口
        'port'        => 25,
        //收件邮箱地址
        'address'     => ['']
    ];


    // 实例化并传入参数
    public function __construct(App $app, $config = [])
    {
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * 日志写入接口
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月24日 13:04
     */
    public function save(array $log): bool
    {
        if (!$this->ableEmail() || empty($log['error'])) {
            return false;
        }
        //美化消息
        $message = $this->prettyTips($log['error']);
        return $this->sendEmail($message);
    }

    /**
     * 发送邮件
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月24日 02:15
     */
    public function sendEmail($message): bool
    {
        $mail = new PHPMailer(true);
        try {
            //设定邮件编码
            $mail->CharSet = "UTF-8";
            // 调试模式输出
            $mail->SMTPDebug = $this->config['debug'];
            // 使用SMTP
            $mail->isSMTP();
            // SMTP服务器
            $mail->Host = $this->config['host'];
            // 允许 SMTP 认证
            $mail->SMTPAuth = true;
            // SMTP 用户名  即邮箱的用户名
            $mail->Username = $this->config['username'];
            // SMTP 密码  部分邮箱是授权码(例如163邮箱)
            $mail->Password = $this->config['password'];
            // 服务器端口 25 或者465 具体要看邮箱服务器支持
            $mail->Port = $this->config['port'];
            //发件人
            $mail->setFrom($this->config['username'], $this->config['sender_name']);
            foreach ($this->config['address'] as $v) {
                // 收件人
                $mail->addAddress($v);
            }
            //回复的时候回复给哪个邮箱,建议和发件人一致
            $mail->addReplyTo($this->config['username'], $this->config['sender_name']);
            // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            $mail->isHTML(true);
            $mail->Subject = $this->config['title'] . '服务异常 ' . date('Y-m-d H:i:s');
            $mail->Body = $message;
            $mail->send();
        } catch (\Throwable $e) {
            return false;
        }
        return true;
    }

    /**
     * 美化邮件消息
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月24日 12:58
     */
    public function prettyTips($message): string
    {
        foreach ($message as &$v) {
            if ($v instanceof \Throwable) {
                $v = "{$v->getMessage()}  {$v->getfile()}[{$v->getLine()}]";
            } elseif (!is_string($v)) {
                $v = var_export($v, true);
            }
            $v = "<div class='message'>{$v}</div>";
        }
        $message = implode('', $message);
        $content = "<style>.container{margin:30px auto;width:60%%;background:#fafafa;color:#333;font-size:12px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:1.666;padding:30px 10%%;border-radius:10px}.title{font-size:20px;font-weight:300;color:#888;text-align:center}.message{padding-bottom:5px;border-bottom:1px solid #eee;margin-bottom:10px}</style><div class='container'><div class='title'>%s</div>%s</div>";
        $title = $this->config['title'] . '服务异常 ' . date('Y-m-d H:i:s');
        return sprintf($content, $title, $message);
    }

    /**
     * 邮箱通道是否可用
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月24日 02:15
     */
    public function ableEmail(): bool
    {
        if ($this->config['debug'] || !$this->config['close']) {
            if (empty($this->config['title']) || empty($this->config['host']) || empty($this->config['sender_name']) || empty($this->config['username']) || empty($this->config['password']) || empty($this->config['address'])) {
                return false;
            }
            return true;
        }
        return false;
    }

}
