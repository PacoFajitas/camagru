<?php
return [
	'/auth' => function($db, $method){
		$input = json_decode(file_get_contents('php://input'), true);
		switch ($method) {
			case 'POST':
				$user = $input["user"] ?? null;
				$pass = $input["pass"] ?? null;

				$stmt = $db->prepare("SELECT * FROM user WHERE user = ?");
				$stmt->bindValue(1, $user, SQLITE3_TEXT); // 1 = primer ?
				$result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
	
				if (empty($result)) {
					http_response_code(404);
					echo json_encode(["error" => "User not registered"]);
					break ;
				}
				if(!password_verify($pass,$result["password"] ))
				{
					http_response_code(401);
					echo json_encode(["error" => "Wrong password"]);
					break ;
				}
				if($result["authenticated"] == 0)
				{
					http_response_code(401);
					echo json_encode(["error" => "User not authenticated"]);
					break ;
				}
				http_response_code(200);
				echo json_encode($result["id"]);
				break;

			case 'PUT':
				$id = $input["id"] ?? null;
				$field = $input["field"] ?? null;
				$value = $input["value"] ?? null;
				$stmt = $db->prepare("SELECT user FROM user WHERE id = ?");
				$stmt->bindValue(1, $id, SQLITE3_TEXT); // 1 = primer ?
				$result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
				if(empty($result))
				{
					http_response_code(401);
					echo json_encode(["error" => "User not found"]);
					break ;
				}
				if($field != "email" && $field != "user" && $field != "password")
				{
					http_response_code(401);
					echo json_encode(["error" => "Value not valid"]);
					break ;
				}
				if ($field == "email" && !filter_var($value, FILTER_VALIDATE_EMAIL))
				{
					http_response_code(401);
					echo json_encode(["error" => "Email not valid"]);
					break ;
				}
				if ($field == "password")
					$value = password_hash($value, PASSWORD_DEFAULT);
				$stmt = $db->prepare("UPDATE user SET $field = ? WHERE id = ?");
				$stmt->bindValue(1, $value, SQLITE3_TEXT); // 1 = primer ?
				$stmt->bindValue(2, $id);
				$result = $stmt->execute();
				if ($result === false) {
					http_response_code(500);
					echo json_encode(["error" => $db->lastErrorMsg()]);
					break ;
				}
				return $context->json(["message" => "Update successfull"]);
				break;
			default:
				http_response_code(500);
				echo json_encode(["error" => "Route not found"]);
				break;
		}
	}
]



?>

