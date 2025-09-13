<?php
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

$app->post("/auth", function ($context) use ($client) {
    $user = $context->req->param("user");
	$pass = $context->req->param("pass");

    $result = $client->query("SELECT * FROM user WHERE user = ?", [$user])->fetchArray(LibSQL::LIBSQL_ASSOC);

    if (empty($result)) {
        return $context->json(["error" => "User not registered"], 404);
    }
	if(!password_verify($pass,$result["password"] ))
	{
		return $context->json(["error" => "Wrong password"], 401);
	}
    return $context->json($result["id"], ["message" => "Login successfull"]);
});

$app->put("/auth", function ($context) use ($client) {
    $id = $context->req->param("id");
	$field = $context->req->param("field");
	$value = $context->req->param("value");
	if(!($client->query("SELECT * FROM user WHERE id = ?", [$id])->fetchArray(LibSQL::LIBSQL_ASSOC)))
		return $context->json(["error" => "User not found"], 401);
	if($field != "email" && $field != "user" && $field != "password")
		return $context->json(["error" => "Value not valid"], 401);
	if ($field == "email" && !filter_var($value, FILTER_VALIDATE_EMAIL))
		return $context->json(["error" => "Email not valid"], 401);
	if ($field == "password")
		$value = password_hash($value, PASSWORD_DEFAULT);
	$result = $client->query("UPDATE user SET $field = ? WHERE id = ?", [$value, $id]);

    return $context->json(["message" => "Update successfull"]);
});
?>

