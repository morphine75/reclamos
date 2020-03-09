<?php
function enviar_reclamo($destino, $reclamo, $idcliente, $nomcli)
{
	include ('phpmailer/PHPMailerAutoload.php');
	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'mail.logexsrl.com.ar';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'contacto@logexsrl.com.ar';                 // SMTP username
	$mail->Password = 'C0ntacto';                           // SMTP password
	$mail->SMTPSecure = '';                            // Enable encryption, 'ssl' also accepted
	$mail->Port = 587; // or 587
	$mail->isHTML(false);                           // Set email format to HTML

	//var_dump($destino);
	for ($i=0;$i<count($destino);$i++)
	{
		$mail->Subject = 'Reclamo de Cliente';
		$mail->From = 'contacto@logexsrl.com.ar';
		$mail->FromName = 'Reclamo de Cliente '.$idcliente.' - '.$nomcli;
		$mail->addAddress($destino[$i]['CORREO'], $destino[$i]['NOMBRE']);     // Add a recipient
		//$mail->addAddress('martin@logexsrl.com.ar');		
		//$mail->addAddress('carlos@logexsrl.com.ar', 'CARLOS JARA');
		//$mail->addAddress('francisco@logexsrl.com.ar');
		$mail->Body = $reclamo;

		if(!$mail->send()) {
		    return $mail->ErrorInfo;
		}
		else
		{
			$mail->ClearAllRecipients( ); 			
		}
	}
	echo " - Se han enviado los correos";	
	return 0;		    	
}