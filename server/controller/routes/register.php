<?php
require_once __DIR__ . '/../../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendSmtps($mail, $email, $token, $user)
{
	try {
		// Server settings
		$mail->SMTPDebug = 4; // DEBUG_CLIENT + DEBUG_SERVER
		$mail->Debugoutput = 'error_log';
		$mail->isSMTP();                                  // Send using SMTP
		$mail->Host       = 'smtp.pacofajitas.fun';      // SMTP server
		$mail->SMTPAuth   = true;                         // Enable SMTP authentication
		$mail->Username   = 'info@pacofajitas.fun';      // SMTP username
		$mail->Password   = getenv("PASS");              // SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
		$mail->Port       = 465;                         
		$mail->SMTPOptions = [
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			]
		];
		// Recipients
		$mail->setFrom('info@pacofajitas.fun', 'PacoFajitas');
		$mail->addAddress($email);     // Add recipient
	
		// Contenido del correo
		$verifyLink = "http://localhost:8443/server/verify?token=" . urlencode($token);
	
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Verifica tu cuenta';
		$mail->Body    = "Hola $user,<br><br>
						  Por favor verifica tu cuenta haciendo clic en el siguiente enlace:<br>
						  <a href='$verifyLink'>$verifyLink</a><br><br>
						  Gracias!";
		$mail->AltBody = "Hola $user,\n\nPor favor verifica tu cuenta copiando y pegando este enlace en tu navegador:\n$verifyLink\n\nGracias!";
	
		if(!$mail->send())
		{
			echo 'Mailer Error: ' . $mail->ErrorInfo;
			return false;
		}
		return true;
	}
	catch (Exception $e)
	{
    	return false;
	}
}

return[
	'/register' => function($db, $method, $mail){
		$input = json_decode(file_get_contents('php://input'), true);
		switch ($method) {
			case 'POST':
				$user = $input["user"] ?? null;
				$email = $input["email"] ?? null;
				$pass = $input["pass"] ?? null;
				$token = bin2hex(random_bytes(16));
				if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					http_response_code(400);
					echo json_encode(["error" => "Invalid email"]);
					break ;
				}
				$stmt = $db->prepare("SELECT * FROM user WHERE user = ? OR email = ?");
				$stmt->bindValue(1, $user, SQLITE3_TEXT);
				$stmt->bindValue(2, $email, SQLITE3_TEXT); 
				$result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
				if(!empty($result))
				{
					http_response_code(401);
					echo json_encode(["error" => "User already in use"]);
					break ;
				}
				$pass = password_hash($pass, PASSWORD_DEFAULT);
				$stmt = $db->prepare("INSERT INTO user(email, user, password, authenticated, token) VALUES(?, ?, ?, 0, ?)");
				$stmt->bindValue(1, $email, SQLITE3_TEXT);
				$stmt->bindValue(2, $user, SQLITE3_TEXT); 
				$stmt->bindValue(3, $pass, SQLITE3_TEXT); 
				$stmt->bindValue(4, $token, SQLITE3_TEXT); 
				$result = $stmt->execute();
				if($result === false)
				{
					http_response_code(500);
					echo json_encode(["error" => "Internal server errora"]);
					break ;
				}
				if (!sendSmtps($mail, $email, $token, $user))
				{
					http_response_code(500);
					echo json_encode(["error" => "No se pudo enviar el correo"]);
					break;
				}
				http_response_code(201);
				echo json_encode(["success" => true]);
				break;
			
			default:
				http_response_code(500);
				echo json_encode(["error" => "Route not found"]);
				break;
		}
	}
]
?>