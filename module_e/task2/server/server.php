<?php
// Устанавливаем заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
http_response_code(200);

// --- 1. Чтение и подготовка данных ---

$dataFilePath = __DIR__ . '/data.json';
if (!file_exists($dataFilePath)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Server data.json not found."]);
    exit;
}
$dataContent = file_get_contents($dataFilePath);
$data = json_decode($dataContent, true);
$news = $data['news'] ?? [];

// --- 2. Анализ URI для роутинга ---

// $_SERVER['REQUEST_URI'] содержит полный путь, например, "/api/news/"
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$response = ["status" => "success", "data" => []];


// --- 3. Обработка роутов ---

// Роут: /api/news/latest
if ($uri === 'api/news/latest') {
    if (!empty($news)) {
        // Требуется ID последней статьи. Ваш JS-скрипт ожидает data.latestId
        // Берем ID первой (самой свежей) статьи из массива.
        $latestId = $news[0]['id'];

        $response = [
            "status" => "success",
            "latestId" => $latestId // Имя поля, которое ожидает JS-скрипт
        ];
    } else {
        $response = ["status" => "success", "latestId" => 0];
    }
}
// Роут: /api/news/ (получение всех новостей)
else if ($uri === 'api/news/check_new') {
    // Возвращаем полный массив новостей
    $response = [
        "status" => "success",
        "data" => $news
    ];
}
// 404: Роут не найден
else {
    http_response_code(404);
    $response = ["status" => "error", "message" => "Endpoint not found: /{$uri}"];
}

// 4. Вывод результата
echo json_encode($response, JSON_PRETTY_PRINT);

?>