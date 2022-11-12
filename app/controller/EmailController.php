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
	    $emailFromCount = explode(';', $emailFrom);
        $emailFrom = (count($emailFromCount) > 1 ? trim($emailFromCount[0]) : $emailFrom);
		$email = new PHPMailer();
		$email->CharSet = "UTF-8";
		$email->From = $emailFrom;
		$email->FromName = empty(trim($fromName)) ? $emailFrom : $fromName;
		$email->isHTML(true);
		$email->Subject = $subject;
        $email->Body = $body;

        // SNAS config
        //$email->isSMTP();
        //$email->Host = 'localhost';
        //$email->Port = 25;
		// bullterierconfig
		// $email->SMTPDebug = SMTP::DEBUG_SERVER;                         //Enable verbose debug output
		// $email->isSMTP();                                           		 //Send using SMTP
		// $email->Host       = 'smtp.seznam.cz';                     		 //Set the SMTP server to send through
		// $email->SMTPAuth   = true;                                  		 //Enable SMTP authentication
		// $email->Username   = 'bulterieraminiaturnibulterier@seznam.cz';   //SMTP username
		// $email->Password   = 'iVESimeTchaNdsmE';                          //SMTP password
		// $email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           		 //Enable implicit TLS encryption
		// $email->Port       = 465;                                    	 //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

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
