<?php

spl_autoload_register(function ($class_name) {
    include './src/'.$class_name . '.php';
});

header('Content-Type: application/json');

$url = $_GET['url'];

$urlExploded = explode('/', $url);

$serviceClass = ucfirst($urlExploded[0]) . 'Service';
$service = new $serviceClass();
$id = null;

if (count($urlExploded) > 1) {
    $id = $urlExploded[1];
}

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if ($id) {
            json(($service->get($id))[0]);
        } else {
            json($service->getAll());
        }
        break;
    case 'POST':
        if (count($_POST) > 0) {
            json($service->create($_POST));
        } else {
            json($service->create(getRequestBody()));
        }
        break;
    case 'PUT':
        json($service->update(getRequestBody()));
        break;
    case 'DELETE':
        json($service->delete($id));
        break;
    default:
        break;
}

function getRequestBody() {
    return json_decode(file_get_contents("php://input"), true);
}

function json($data) {
    $response = [
        'success' => true,
        'data' => $data,
        'errors' => [],
    ];
    echo json_encode($response);
}