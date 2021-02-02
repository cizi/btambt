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
     * @param array $attachements - string
     * @param string $fromName
	 * @throws \Exception
	 */
	public static function SendPlainEmail($emailFrom, $emailTo, $subject, $body, array $attachements = [], $fromName = '') {
		$email = new PHPMailer();
		$email->CharSet = "UTF-8";
		$email->From = $emailFrom;
		$email->FromName = empty(trim($fromName)) ? $emailFrom : $fromName;
		$email->isHTML(true);
		$email->Subject = $subject;
        $email->Body = $body;

        // SNAS config
        /* $email->isSMTP();
        $email->Host = 'localhost';
        $email->Port = 25; */

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

		$email->Send();
	}

}
