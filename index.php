<?php

require_once 'db_config.php';

$message = '';

// 1. Обработка POST-запроса (отправка опроса)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_survey'])) {
    $q1 = trim($_POST['q1'] ?? '');
    $q2 = trim($_POST['q2'] ?? '');
    $q3 = trim($_POST['q3'] ?? '');

    if (!empty($q1) && !empty($q2) && !empty($q3)) {
        try {
            // Установка соединения с PostgreSQL
            $pdo = new PDO("pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Подготовка и выполнение запроса INSERT
            $sql = "INSERT INTO results (question1_answer, question2_answer, question3_answer) VALUES (:q1, :q2, :q3)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':q1' => $q1,
                ':q2' => $q2,
                ':q3' => $q3
            ]);

            $message = "<p style='color: green;'>Спасибо за участие в опросе!</p>";
            
        } catch (PDOException $e) {
            $message = "<p style='color: red;'>Ошибка базы данных: " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p style='color: orange;'>Пожалуйста, заполните все поля.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Простой Опрос</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        textarea { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        input[type="submit"] { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px; }
        .header-links a { margin-right: 15px; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>Простой Опрос</h2>
    
    <div class="header-links">
        <a href="index.php">Пройти Опрос</a> | 
        <a href="results.php">Посмотреть Результаты</a>
    </div>
    
    <?php echo $message; ?>

    <form method="POST" action="index.php">
        <!-- Вопрос 1 -->
        <label for="q1">1. Какое ваше любимое животное?</label>
        <textarea id="q1" name="q1" rows="3" required></textarea>

        <!-- Вопрос 2 -->
        <label for="q2">2. Какое ваше любимое время года?</label>
        <textarea id="q2" name="q2" rows="3" required></textarea>

        <!-- Вопрос 3 -->
        <label for="q3">3. Опишите ваше настроение сегодня.</label>
        <textarea id="q3" name="q3" rows="3" required></textarea>

        <input type="submit" name="submit_survey" value="Отправить Ответ">
    </form>
</div>

</body>
</html>