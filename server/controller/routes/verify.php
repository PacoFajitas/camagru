<?php
	return [
		'/verify' => function($db, $method)
		{
			$token = $_GET['token'] ?? null;
			$stmt = $db->prepare("SELECT * FROM user WHERE token=?");
			$stmt->bindValue(1, $token, SQLITE3_TEXT);
			$result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
			if (empty($result)) 
			{
				http_response_code(401);
				echo json_encode(["error" => "User not registered"]);
				return ;
			}
			$id = $result["id"];
			$stmt = $db->prepare("UPDATE user SET authenticated=1 WHERE id = ?");
			$stmt->bindValue(1, $id, SQLITE3_TEXT);
			$result = $stmt->execute();
			if ($result) 
			{
				http_response_code(200);
				header("Location: /login.html");
				return ;
			}
			else {
				http_response_code(500);
				echo json_encode(["error" => "Internal error"]);
				header("Location: https://localhost:8443/login.html");
				return ;
			}
		}
	]
?>