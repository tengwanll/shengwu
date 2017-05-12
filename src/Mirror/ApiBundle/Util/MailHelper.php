<?php

namespace Mirror\ApiBundle\Util;

class MailHelper {
	
	public function sendMail($toUser, $ccUser = array(), $title, $content, $fileList = array()) {
		$message = \Swift_Message::newInstance ();
		$message->setFrom ( 'jiqiren@canguanwuyou.cn' );
		$message->setTo ( $toUser );
		foreach ( $ccUser as $address ) {
			$message->setBcc ( $address );
		}
		$message->setSubject ( $title );
		$message->setBody ( $content, 'text/html', 'utf-8' );
		$transport = \Swift_SmtpTransport::newInstance ( 'smtp.mxhichina.com', 25 );
		$transport->setUsername ( 'jiqiren@canguanwuyou.cn' );
		$transport->setPassword ( 'Canguanwuyou1902' );
	
		$mailer = \Swift_Mailer::newInstance ( $transport );
		foreach ( $fileList as $file ) {
			$message->attach ( \Swift_Attachment::fromPath ( $file ) );
		}
		try {
			$mailer->send ( $message );
		} catch ( \Exception $e ) {
			echo $e->getMessage ();
			return false;
		}
		return true;
	}
}

?>