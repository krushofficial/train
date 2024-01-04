
<?php

function custom_autoload ($className) {
    $filename = strtolower($className) . ".php";
	$file = SERVER_ROOT . "/models/" . $filename;
	if(file_exists($file)) {
        include_once($file);
    } else {
        http_response_code(404);
        die("File 'models/$filename' containing class '$className' not found.");
    }
}
spl_autoload_register("custom_autoload");

$request = "game";
if (isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] != "") {
    $request = $_SERVER["QUERY_STRING"];
}

$params = explode("&", $request);
$page = array_shift($params);
$vars = array();

foreach ($params as $p) {
	list($variable, $value) = explode("=", $p);
	$vars[$variable] = $value;
}

$filename = $page . ".php";
$target = __DIR__ . "/" . $filename;
if (file_exists($target)) {
	include_once($target);
	$class = ucfirst($page)."_Controller";
	if (class_exists($class)) {
        $controller = new $class;
    } else {
        http_response_code(404);
        die("Class '$class' not found");
    }
} else {
    http_response_code(404);
    die("File 'controllers/$filename' not found");
}
$controller->main($vars);
