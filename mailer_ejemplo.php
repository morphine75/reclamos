<?php
require ('phpmailer/PHPMailerAutoload.php');

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.logexsrl.com.ar';  // Specify main and backup SMTP servers
//$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'contacto@logexsrl.com.ar';                 // SMTP username
$mail->Password = 'C0ntacto';                           // SMTP password
$mail->SMTPSecure = '';                            // Enable encryption, 'ssl' also accepted
$mail->Port = 587; // or 587

$mail->From = 'contacto@logexsrl.com.ar';
$mail->FromName = 'Mailer';
$mail->addAddress('martin@logexsrl.com.ar', 'Joe User');     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('martined.dominguez@gmail.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

/*$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/
$mail->isHTML(false);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
/*$mail->Body    = '<table class="table" id="detalle_Exporta_Excel">
		<tr class="style1">
			<th>COD.RUTA</th>
			<th>RUTA</th>
			<th></th>
			<th></th>
			<th></th>			
		</tr>
</table>';*/
$mail->Body = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}