<? 
 
/* functions.inc.php
 * - Fonctions utilitaires
 * Copyright (c) 2003 Fr�d�ric Jaqcuot
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
 
	function makeRandomPassword()
	{
		$pass = "";
  		$salt = "abcdefghjkmnpqrstuvwxyz0123456789";
    		srand((double)microtime()*1000000);
          	$i = 0;
	        while ($i <= 6) 
		{
	        	$num = rand() % 33;
	        	$tmp = substr($salt, $num, 1);
	        	$pass = $pass . $tmp;
	        	$i++;
	      	}
	     	return $pass;
	}

	function isSelected($champ1, $champ2) { 
	  if ($champ1 == $champ2) { 
	    echo " selected"; 
	  } 
	} 
 
	function isChecked($champ1, $champ2) { 
	  if ($champ1 == $champ2) { 
	    echo " checked"; 
	  } 
	} 

	function txt_sqls($champ) { 
		return "'".str_replace("'", "\'", str_replace('\\', '', $champ))."'"; 
	}
	
	function is_valid_web_url($url) {
	  return (preg_match(
	  		'/^(http|https):\/\/'.
	  		'.*/i', $url, $m
	  		));
	}
	
/*
 *
 * is_valid_email(): an e-mail validation utility routine
 * Version 1.1.1 -- September 10, 2000
 *
 * Written by Michael A. Alderete
 * Please send bug reports and improvements to: <michael@aldosoft.com>
 *
 * This function matches a proposed e-mail address against a validating
 * regular expression. It's intended for use in web registration systems
 * and other places where the user is inputting their e-mail address and
 * you want to check that it's OK.
 *
 */

	function is_valid_email ($address) {
    return (preg_match(
        '/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+'.   // the user name
        '@'.                                     // the ubiquitous at-sign
        '([-0-9A-Z]+\.)+' .                      // host, sub-, and domain names
        '([0-9A-Z]){2,4}$/i',                    // top-level domain (TLD)
        trim($address)));
	}
	
	function dblog($text, $query="")
	{
		if (PREF_LOG=="2")
		{
			$requete = "INSERT INTO ".PREFIX_DB."logs (date_log, ip_log, adh_log, text_log) VALUES (" . $GLOBALS["DB"]->DBTimeStamp(time()) . ", " . $GLOBALS["DB"]->qstr($_SERVER["REMOTE_ADDR"]) . ", " . $GLOBALS["DB"]->qstr($_SESSION["logged_nom_adh"]) . ", " . $GLOBALS["DB"]->qstr($text."\n".$query) . ");";
			$GLOBALS["DB"]->Execute($requete);
		}
		elseif (PREF_LOG=="1")
		{
			$requete = "INSERT INTO ".PREFIX_DB."logs (date_log, ip_log, adh_log, text_log) VALUES (" . $GLOBALS["DB"]->DBTimeStamp(time()) . ", " . $GLOBALS["DB"]->qstr($_SERVER["REMOTE_ADDR"]) . ", " . $GLOBALS["DB"]->qstr($_SESSION["logged_nom_adh"]) . ", " . $GLOBALS["DB"]->qstr($text) . ");";
			$GLOBALS["DB"]->Execute($requete);
		}
	}
	
	
	
	
	function resizeimage($img,$img2,$w,$h)
	{
		if (function_exists("imagecreate"))
		{
			$ext = substr($img,-4);
			$imagedata = getimagesize($img);
			$ratio = $imagedata[0]/$imagedata[1];
			if ($imagedata[0]>$imagedata[1])
				$h = $w/$ratio;
			else
				$w = $h*$ratio;
			$thumb = imagecreate ($w, $h);
			switch($ext)
			{
				case ".jpg":
					$image = ImageCreateFromJpeg($img);
					imagecopyresized ($thumb, $image, 0, 0, 0, 0, $w, $h, $imagedata[0], $imagedata[1]);
					imagejpeg($thumb, $img2);
					break;
				case ".png":
					$image = ImageCreateFromPng($img);
					imagecopyresized ($thumb, $image, 0, 0, 0, 0, $w, $h, $imagedata[0], $imagedata[1]);
					imagepng($thumb, $img2);
					break;
				case ".gif":
					if (function_exists("imagegif"))
					{
						$image = ImageCreateFromGif($img);
						imagecopyresized ($thumb, $image, 0, 0, 0, 0, $w, $h, $imagedata[0], $imagedata[1]);
						imagegif($thumb, $img2);
					}
					break;					
			}
		}
	}
	
	function custom_html_entity_decode( $given_html, $quote_style = ENT_QUOTES )
	{
   	$trans_table = array_flip(get_html_translation_table( HTML_ENTITIES, $quote_style ));
   	$trans_table['&#39;'] = "'";
   	return ( strtr( $given_html, $trans_table ) );
	}

	function custom_mail($email_adh,$mail_subject,$mail_text)
	{
		// codes retour :
		//  0 - mail envoye
		//  1 - erreur mail()
		//  2 - mail desactive
		//  3 - mauvaise configuration
		//  4 - SMTP injoignable 
		$result = 0;

		// Headers :
		$headers = array("Subject: $mail_subject",
				"From: ".PREF_EMAIL_NOM." <".PREF_EMAIL.">",
				"To: <".$email_adh.">",
				"X-Sender: <".PREF_EMAIL.">",
				"Return-Path: <".PREF_EMAIL.">",
				"Errors-To: <".PREF_EMAIL.">",
				"X-Mailer: PHP",
				"X-Priority: 3",
				"Content-Type: text/plain; charset=iso-8859-15");
		
		switch (PREF_MAIL_METHOD)
		{
			case 0:
				$result = 2;
				break;
			case 1:
				$mail_headers = "";
				foreach($headers as $oneheader)
					$mail_headers .= $oneheader."\n";
				if (!mail($email_adh,$mail_subject,$mail_text, $mail_headers))
					$result = 1;
				break;
			case 2:
			  // $toArray format --> array("Name1" => "address1", "Name2" => "address2", ...)

			    ini_set(sendmail_from, "myemail@address.com");
				$errno = "";
				$errstr = "";
				if (!$connect = fsockopen (PREF_MAIL_SMTP, 25, $errno, $errstr, 30))
					$result = 4;
				else
				{
			        	$rcv = fgets($connect, 1024);
					fputs($connect, "HELO {$_SERVER['SERVER_NAME']}\r\n");
					$rcv = fgets($connect, 1024);
					fputs($connect, "MAIL FROM:".PREF_EMAIL."\r\n");
					$rcv = fgets($connect, 1024);
					fputs($connect, "RCPT TO:".$email_adh."\r\n");
					$rcv = fgets($connect, 1024);
					fputs($connect, "DATA\r\n");
					$rcv = fgets($connect, 1024);
					foreach($headers as $oneheader)
						fputs($connect, $oneheader."\r\n");
					fputs($connect, "\r\n");
					fputs($connect, stripslashes($mail_text)." \r\n");
					fputs($connect, ".\r\n");
					$rcv = fgets($connect, 1024);
					fputs($connect, "RSET\r\n");
					$rcv = fgets($connect, 1024);
					fputs ($connect, "QUIT\r\n");
					$rcv = fgets ($connect, 1024);
					fclose($connect);
				}
				break;
			default:
				$result = 3;
		}
		return $result;
	}
	
	
?>
