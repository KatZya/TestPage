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

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Проверяем валидность email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Ошибка: Некорректный email.";
        exit;
    }

    // SQL запрос для вставки данных в базу
    $sql = "INSERT INTO test (name, email, message) VALUES ('$name', '$email', '$message')";

    // Проверяем успешность выполнения запроса
    if ($conn->query($sql) === TRUE) {
        echo "Данные успешно отправлены!";
    } else {
        echo "Ошибка при обработке данных: " . $conn->error;
    }
}

// Закрываем соединение с базой данных
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Форма обратной связи</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Форма обратной связи</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-group">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="message">Ваш вопрос:</label>
        <textarea id="message" name="message" rows="4" required></textarea>
      </div>
      <button type="submit">Отправить</button>
    </form>
    <form action="/test/page/index.php">
    <button type="submit">Назад</button>
  </div>
</body>
</html>
