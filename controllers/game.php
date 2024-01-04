<?php
class Game_Controller {
    public $baseName = "game";

    public function main (array $vars) {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!array_key_exists("score", $_POST) || !array_key_exists("username", $_SESSION)) {
                http_response_code(400);
                die();
            }

            header("Content-type:application/json");

            $username = $_SESSION["username"];
            $score = (int)$_POST["score"];

            $stmt = Database::mysql()->prepare("SELECT top_score FROM train_users WHERE username=?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            if ($result[0]["top_score"] < $score) {
                $stmt = Database::mysql()->prepare("UPDATE train_users SET top_score=? WHERE username=?");
                $stmt->bind_param("is", $score, $username);
                $stmt->execute();
                echo json_encode(array("is_highscore" => true, "highscore" => $score, "score" => $score));
            } else {
                echo json_encode(array("is_highscore" => false, "highscore" => $result[0]["top_score"], "score" => $score));
            }
        } else {
            new View_Loader($this->baseName);
        }
    }
}
