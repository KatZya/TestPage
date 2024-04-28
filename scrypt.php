<?php
// Параметры подключения к базе данных MySQL
$servername = "localhost"; // Имя сервера
$username = "root"; // Имя пользователя
$password = ""; // Пароль
$dbname = "form"; // Имя базы данных

// Создание соединения с базой данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Обработка аргументов командной строки
$options = getopt(null, ["email:"]);

// Получаем значение опции --email, если она была передана
$email = isset($options['email']) ? $options['email'] : null;

// Открываем CSV файл для записи
$csvFile = fopen('feedback.csv', 'w');

// Заголовки CSV файла
$headers = array('Name', 'Email', 'Message');
fputcsv($csvFile, $headers);

// Подготовка SQL запроса в зависимости от наличия параметра email
if ($email) {
    $sql = "SELECT name, email, message FROM test WHERE email = '$email'";
} else {
    $sql = "SELECT name, email, message FROM test";
}

// Выполнение запроса
$result = $conn->query($sql);

// Запись данных в CSV файл
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($csvFile, $row);
    }
    echo "Экспорт завершен. Результаты сохранены в feedback.csv\n";
} else {
    echo "Нет данных для экспорта.\n";
}

// Закрываем соединение с базой данных и файлом CSV
$conn->close();
fclose($csvFile);
?>