<?php
require __DIR__ . '/vendor/autoload.php';

use \Nette\Mail\Message;
use \pointybeard\ShellArgs\Lib\ArgumentIterator;

$useMessage = "Usage：php send_content.php
               -h --help           ,show this message.
               --content           ,file as mail content to send
               \n";

$args = new ArgumentIterator();
if ($args->count() === 0 || $args->find(["h", "help"])) {
	echo $useMessage;
	exit;
}
if (!$args->find("content")) {
	echo $useMessage;
	exit;
}

$content_file = $args->find("content")->value();
$content = file_get_contents($content_file);

$mail = new Message;
$mail->setFrom('sms@qq.com', 'sms')
	->addTo('ming.zhao@qq.com')
	->setSubject('集群home磁盘使用报警邮件')
	->setBody($content);

$mailer = new \Nette\Mail\SmtpMailer([
	'host' => 'smtp.exmail.qq.com',
	'port' => 465,
	'username' => 'sms@qq.com',
	'password' => 'passwd',
	'secure' => 'ssl',
]);

$mailer->send($mail);
