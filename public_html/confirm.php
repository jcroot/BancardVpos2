<?php
require_once(__DIR__ . "/../bootstrap.php");

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(403);
    exit;
}

try {
    $post = json_decode(file_get_contents('php://input'));
} catch (Exception $e) {
    http_response_code(403);
    exit;
}

if (empty($post)) {
    http_response_code(403);
    exit;
}

$entityManager = getEntityManager();
$order = $entityManager->getRepository(Order::class)->find(['id' => $post->operation->shop_process_id]);

if (!$order){
    http_response_code(404);
    exit;
}

$security_information = json_encode($post->operation->security_information);
$transaction = new Transaction($post->operation->response,
    $post->operation->response_details,
    $post->operation->amount,
    $post->operation->currency,
    $post->operation->authorization_number,
    $post->operation->ticket_number,
    $post->operation->response_code,
    $post->operation->response_description,
    ($post->operation->extended_response_description) ? $post->operation->extended_response_description : "",
    $security_information,
    $order);

$entityManager->persist($transaction);
$entityManager->flush();

http_response_code(200);
header('Content-type: application/json');
echo json_encode([
    "status" => "success",
    "message" => "Su pago ha sido procesado",
    "developer_message" => "Operation confirmed"]);
exit;
