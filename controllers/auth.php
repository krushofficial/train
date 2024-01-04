<?php
class Auth_Controller {
    public function main (array $vars) {
        session_start();

        if (!array_key_exists("action", $vars)) {
            http_response_code(400);
            die();
        }

        $action = $vars["action"];

        if ($action == "validate") {
            if (array_key_exists("username", $_SESSION)) {
                http_response_code(200);
            } else {
                http_response_code(401);
            }
            exit();
        }

        if (!array_key_exists("username", $_POST) || !array_key_exists("password", $_POST)) {
            http_response_code(400);
            die();
        }

        $username = $_POST["username"];
        $password = $_POST["password"];

        if (strlen($username) < 2 || strlen($username) > 32 || !preg_match('/^[a-zA-Z0-9]+$/', $username)
        || strlen($password) < 6 || strlen($password) > 64 || !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            http_response_code(400);
            die();
        }

        if ($action == "login") {
            $stmt = Database::mysql()->prepare("SELECT password FROM train_users WHERE username=?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            if (count($result) == 0) {
                http_response_code(404);
                die();
            }

            if (password_verify($password, $result[0]["password"])) {
                $_SESSION["username"] = $username;
                http_response_code(200);
            } else {
                http_response_code(404);
            }
        } else if ($action == "register") {
            $stmt = Database::mysql()->prepare("INSERT INTO train_users (username, password) VALUES (?, ?)");
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bind_param("ss", $username, $password_hash);
            if ($stmt->execute()) {
                $_SESSION["username"] = $username;
                http_response_code(200);
            } else {
                http_response_code(409);
            }
            $stmt->close();
        } else {
            http_response_code(400);
            die();
        }
    }
}