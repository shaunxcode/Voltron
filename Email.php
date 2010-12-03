<?php

class Voltron_Email
{
	public static function sendHtmlEmail($to_address, $from_address, $subject, $message, $html = null, $bcc = null, $cc=null)
	{
		$to_address = typeToPrim($to_address);
		$from_address = typeToPrim($from_address);
		$subject = typeToPrim($subject);
		$message = typeToPrim($message); 
		$html = typeToPrim($html);
		$bcc = typeToPrim($bcc);
		$cc = typeToPrim($cc);
		
		if (USE_SMTP) {
			return self::sendSMTPEmail($to_address, $from_address, $subject, $html, $message, $bcc, $cc);
		} else {
			if ($html) {
		    	$headers = array(
            		'MIME-Version' => '1.0',
		            'Content-Type' => 'text/html; charset=iso-8859-1',
		            'From' => $from_address,
		            'To' => $to_address,
		            'Subject' => $subject);

	            if ($bcc != null) {
	                $header['Bcc'] = $bcc;
	            }
	            if ($cc != null) {
	                $header['CC'] = $cc;
	            }
			} else {
				$headers = array(
	                'From' => $from_address,
	                'To' => $to_address,
	                'Subject' => $subject);
			}

	        $headerstr = '';
	        foreach ($headers as $header => $value) {
		    	if ($header == 'Subject' || $header == 'To') {
		        	continue;
				}

		        $headerstr .= "$header: $value\n";
		    }

		    if (!mail($to_address, $subject, $message, $headerstr)) {
            	EmailLog("Error sending email", array('to' => $to_address, 'subject' => $subject, 'message' => $message, 'header' => $headerstr));
				return false;
		    }
		}
	}

	public static function sendSMTPEmail($to_address, $from_address, $subject, $html_message, $text_message = null, $bcc = null, $cc = null)
	{
	    $crlf = "\n";
	    $headers = array(
	        'From' => $from_address,
	        'To' => $to_address,
	        'Subject' => $subject);

	    if ($bcc != null)
	    {
	        $header['Bcc'] = $bcc;
	    }

	    if ($cc != null)
	    {
	        $header['CC'] = $cc;
	    }

	    if (empty($text_message))
	    {
	        $text_message = strip_tags($html_message);
	        $text_message = html_entity_decode( $text_message );
	    }

	    $mime = new Mail_mime($crlf);
	    $mime->setTXTBody($text_message);
	    if (!empty($html_message))
	        $mime->setHTMLBody($html_message);

	    $body = $mime->get();
	    $headers = $mime->headers($headers);
		
		$config = newObject(APPNAME . '_Config_SMTP');
	    $mySmtp = Mail::factory('smtp', array('host' => $config::SMTP_HOST, 'auth' => true, 'username' => $config::SMTP_USER, 'password' => $config::SMTP_PASSWD));

	    EmailLog($to_address, $headers, $body);
	    $mail = $mySmtp->send($to_address, $headers, $body);

	    if (PEAR::isError($mail))
	    {
	        EmailLog("Failed to send email: ".$mail->getMessage());
	        return false;
	    }

	    return true;
	}

	public static $cellCarriers = array(
		'none' => '',
		'Alltel' => '@message.alltel.com',
		'AT&T'=> '@txt.att.net',
		'CellularOne' => '@mycellone.com',
		'Cingular' => '@cingularme.com',
		'Cricket' => '@sms.mycricket.com',
		'Metro PCS' => '@mymetropcs.com',
		'Nextel' => '@messaging.nextel.com',
		'Qwest' => '@qwestmp.com',
		'Sprint' => '@messaging.sprintpcs.com',
		'T-Mobile' => '@tmomail.net',
		'Verizon Wireless' => '@vtext.com');
    
	public static function sendText($cell_phone, $cell_carrier, $message, $from_address="", $subject="") {
		return self::sendEmail(numbersOnly($cell_phone) . self::$cellCarriers[$cell_carrier], $from_address, $subject, $message);
	}
}
