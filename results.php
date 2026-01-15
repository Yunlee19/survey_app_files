<?php

require_once 'db_config.php';

$results = [];
$error = '';

try {
    // 1. Установка соединения с PostgreSQL
    $pdo = new PDO("pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Выборка данных (сортируем по дате, самые новые сверху)
    $stmt = $pdo->query("SELECT id, question1_answer, question2_answer, question3_answer, submitted_at FROM results ORDER BY submitted_at DESC");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Ошибка подключения к базе данных: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты Опроса</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 900px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .header-links a { margin-right: 15px; text-decoration: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<div class="container">
    <h2>Результаты Опросов</h2>

    <div class="header-links">
        <a href="index.php">Пройти Опрос</a> | 
        <a href="results.php">Посмотреть Результаты</a>
    </div>
    
    <?php if ($error): ?>
        <p style='color: red;'><?php echo $error; ?></p>
    <?php elseif (empty($results)): ?>
        <p>Пока нет ни одного ответа.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Время</th>
                    <th>Вопрос 1 (Животное)</th>
                    <th>Вопрос 2 (Погода)</th>
                    <th>Вопрос 3 (Настрой)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['question1_answer'])); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['question2_answer'])); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['question3_answer'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>