<?php
return[
	'/register' => function($db, $method){
		$input = json_decode(file_get_contents('php://input'), true);
		switch ($method) {
			case 'POST':
				$user = $input["user"] ?? null;
				$email = $input["email"] ?? null;
				$pass = $input["pass"] ?? null;
				if(!filter_var($value, FILTER_VALIDATE_EMAIL))
				{
					http_response_code(400);
					echo json_encode(["error" => "Invalid email"]);
					break ;
				}
				$stmt = $db->prepare("SELECT * FROM user WHERE user = ?");
				$stmt->bindValue(1, $user, SQLITE3_TEXT); // 1 = primer ?
				$result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
				if(!empty($result))
				{
					http_response_code(401);
					echo json_encode(["error" => "User already in use"]);
					break ;
				}
				$pass = password_hash($pass, PASSWORD_DEFAULT);
				$stmt = $db->prepare("INSERT INTO user(email, user, password, authenticated) VALUES(?, ?, ?, 0)");
				$stmt->bindValue(1, $email, SQLITE3_TEXT); // 1 = primer ?
				$stmt->bindValue(2, $user, SQLITE3_TEXT); // 1 = primer ?
				$stmt->bindValue(3, $pass, SQLITE3_TEXT); // 1 = primer ?
				$result = $stmt->execute();
				if($result === false)
				{
					http_response_code(500);
					echo json_encode(["error" => "Internal server error"]);
				}
				$result->finalize();
				break;
			
			default:
				http_response_code(500);
				echo json_encode(["error" => "Route not found"]);
				break;
		}
	}
]
?>