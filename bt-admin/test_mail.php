<?php
require($_SERVER['DOCUMENT_ROOT']."/bt-admin/config/include.php");
//require(DIR_LIBRERIAS."PHPMailerAutoload.php");

$mail = new PHPMailer ();
$mail->SetLanguage( 'es', 'phpmailer/language/' );
$mail -> SMTPDebug=2;
$mail -> FromName = EMAIL_FROMNAME;
$mail -> From = EMAIL_FROM;
$mail -> Subject = "prueba";
$mail -> AddAddress("jmendez@bigtree.com.ar");
/**
 * Genera el cuerpo del mail
 */
$htmlmail = "Este es un mail para probar el phpmailer";

$mail -> Body = $htmlmail;
$mail -> IsHTML (true);
/**
 * Establacemos que utilzaremos SMTP 
 * y habilitamos la autenticación.
 */
$mail->IsSMTP();
$mail->SMTPAuth   = true;

/**
 * La siguiente parte debería estar comentada en la mayoría de los casos
 * ya que desactiva la verificación de certificados, solo se debe usar cuando 
 * la verificacion de certificados falla constantemente (geralmente pasa en windows)
 */
//----------------------------------------------------------------------------
//	$mail -> SMTPOptions = array(                                           //
//		'ssl' => array(                                                     //
//			'verify_peer' => false,                                         //
//			'verify_peer_name' => false,                                    //
//			'allow_self_signed' => true                                     //
//		)                                                                   //
//	);                                                                      //
//----------------------------------------------------------------------------

/**
 * Seleccióna el tipo de autenticación dependiendo de los parametros
 */
if (SMTP_SSL==1)
	$mail->SMTPSecure = "ssl";
if (SMTP_TLS==1)
	$mail->SMTPSecure = "tls";
$mail->SMTPKeepAlive = true;
$mail->Host       = SMTP_HOST;
$mail->Port       = SMTP_PORT;
$mail->Username   = SMTP_USER;
$mail->Password   = SMTP_PASSW;
$mail->SetFrom(EMAIL_FROM, EMAIL_FROMNAME);
/**
 * Envía e mail.
 */
if(!$mail->Send()) 
{
	echo "Error al enviar mail: ".$mail->ErrorInfo;
	return false;
}
else
{
	echo "Mail enviado";
}


?>