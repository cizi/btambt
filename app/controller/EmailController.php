<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;

class EmailController {

	/**
	 * Odešle email
	 *
	 * @param $emailFrom
	 * @param $emailTo
	 * @param $subject
	 * @param $body
	 * @throws \Exception
	 */
	public static function SendPlainEmail($emailFrom, $emailTo, $subject, $body) {
		$email = new PHPMailer();
		$email->CharSet = "UTF-8";
		$email->From = $emailFrom;
		$email->FromName = $emailFrom;
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

		$email->Send();
	}

}