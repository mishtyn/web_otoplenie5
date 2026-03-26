<?php
require_once __DIR__ . '/config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    $data = $_POST;
}

$name = htmlspecialchars($data['name'] ?? '');
$phone = htmlspecialchars($data['phone'] ?? '');
$message = htmlspecialchars($data['message'] ?? '');

if (!$phone) {
    echo json_encode(["status" => "error", "message" => "Телефон обязателен"]);
    exit;
}

$text = "📩 Новая заявка (Отопление):\n";
$text .= "👤 Имя: $name\n";
$text .= "📞 Телефон: $phone\n";
$text .= "💬 Сообщение: $message";

$url = "https://api.telegram.org/bot$token/sendMessage";

$params = [
    "chat_id" => $chat_id,
    "text" => $text,
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

$response = curl_exec($ch);
curl_close($ch);

echo json_encode([
    "status" => "ok"
]);
?>