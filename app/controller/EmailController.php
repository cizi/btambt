<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailController {

	private const SMTP_HOST = 'smtp.seznam.cz';
	private const SMTP_USERNAME = 'bulterierclub@email.cz';
	private const SMTP_PASSWORD = 'xxxxxhledej v mejluxxx';
	private const SMTP_PORT = 465;

	/**
	 * Odešle email
	 *
	 * @param $emailFrom
	 * @param $emailTo
	 * @param $subject
	 * @param $body
     * @param array $attachements - string
     * @param string $fromName
	 * @throws \Exception
	 */
	public static function SendPlainEmail($emailFrom, $emailTo, $subject, $body, array $attachements = [], $fromName = '', $overSmtp = true) {
        $emailFromCount = explode(';', $emailFrom);
		// SNAS config
        //$email->isSMTP();
        //$email->Host = 'localhost';
        //$email->Port = 25;

		// bullterierconfig
		$email = new PHPMailer();
		if ($overSmtp) {
			$email->isSMTP();
			$email->From = self::SMTP_USERNAME;
			//$email->SMTPDebug = SMTP::DEBUG_SERVER;				//Enable verbose debug output
			$email->Host       = self::SMTP_HOST;				//Set the SMTP server to send through
			$email->SMTPAuth   = true;							//Enable SMTP authentication
			$email->Username   = self::SMTP_USERNAME;			//SMTP username
			$email->Password   = self::SMTP_PASSWORD;			//SMTP password
			$email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;	//Enable implicit TLS encryption
			$email->Port       = self::SMTP_PORT;				//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			$email->clearReplyTos();
			$email->addReplyTo(count($emailFromCount) > 1 ? trim($emailFromCount[0]) : $emailFrom);
			// foreach (explode(';', $emailFrom) as $emailReplyTo) {
			// 	$email->addReplyTo($emailReplyTo);
			// }
		} else {
        	$email->From = (count($emailFromCount) > 1 ? trim($emailFromCount[0]) : $emailFrom);
		}

		$email->CharSet = "UTF-8";
		$email->FromName = empty(trim($fromName)) ? $emailFrom : $fromName;
		$email->isHTML(true);
		$email->Subject = $subject;
        $email->Body = $body;

		if (strpos($emailTo, ";") !== false) {	// více příjemců
			$addresses = explode(";", $emailTo);
			foreach ($addresses as $address) {
				if (trim($address) != "") {
					$email->AddAddress($address);
				}
			}
		} else {	// jeden příjemnce
			$email->AddAddress($emailTo);
        }
        
        foreach ($attachements as $attachement) {
            $email->addAttachment($attachement);
        }

		// Send mail
		if (substr($_SERVER['HTTP_HOST'], 0, 9) != 'localhost') {
			$email->send();
		}

		if ($overSmtp) {
			$email->smtpClose();
		}
	}
}
