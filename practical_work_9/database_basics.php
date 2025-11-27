<?php

declare(strict_types=1);

define('IS_DEVELOPMENT', false);

if (IS_DEVELOPMENT) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

/*
SQL для создания базы данных и таблицы:

CREATE DATABASE IF NOT EXISTS library CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE library;

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    isbn VARCHAR(20),
    pub_year INT,
    available TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/

// ============================================================================
// Задание 1: Подключение к базе данных
// ============================================================================

/**
 * Получение подключения к базе данных с обработкой ошибок
 *
 * @return PDO Объект подключения к БД
 */
function getPdoConnection(): PDO
{
    try {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=library;charset=utf8mb4",
            'uncorrect',
            '123',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        if (IS_DEVELOPMENT) {
            die("<h3>Database Connection Error</h3>" .
                "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>" .
                "<p><strong>Code:</strong> " . $e->getCode() . "</p>" .
                "<p><strong>File:</strong> " . $e->getFile() . ":" . $e->getLine() . "</p>");
        } else {
            error_log(sprintf(
                "DB Connection Error: %s in %s:%d",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
            die("<h3>Service Unavailable</h3><p>Please try again later.</p>");
        }
    }
}

// ============================================================================
// Задание 2: Добавление книги
// ============================================================================

/**
 * Добавление новой книги в базу данных
 *
 * @param PDO $pdo Объект подключения к БД
 * @param string $title Название книги
 * @param string $author Автор книги
 * @param string $isbn ISBN книги
 * @param int $year Год публикации
 * @return int ID добавленной книги
 */
function addBook(PDO $pdo, string $title, string $author, string $isbn, int $year): int
{
    $stmt = $pdo->prepare(
        "INSERT INTO books (title, author, isbn, pub_year) VALUES (:title, :author, :isbn, :year)"
    );
    $stmt->execute([
        'title' => $title,
        'author' => $author,
        'isbn' => $isbn,
        'year' => $year
    ]);
    return (int)$pdo->lastInsertId();
}

// ============================================================================
// Задание 3: Поиск книг по автору
// ============================================================================

/**
 * Поиск всех книг по автору
 *
 * @param PDO $pdo Объект подключения к БД
 * @param string $author Имя автора
 * @return array Массив найденных книг
 */
function findBooksByAuthor(PDO $pdo, string $author): array
{
    $stmt = $pdo->prepare("SELECT * FROM books WHERE author = :author");
    $stmt->execute(['author' => $author]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ============================================================================
// Задание 4: Получение всех доступных книг
// ============================================================================

/**
 * Получение всех доступных книг
 *
 * @param PDO $pdo Объект подключения к БД
 * @return array Массив доступных книг
 */
function getAllAvailableBooks(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT * FROM books WHERE available = 1");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ============================================================================
// Задание 5: Получение информации о книге по ID
// ============================================================================

/**
 * Получение информации о книге по ID
 *
 * @param PDO $pdo Объект подключения к БД
 * @param int $id ID книги
 * @return array|false Массив с данными книги или false если не найдена
 */
function getBookById(PDO $pdo, int $id): array|false
{
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

// ============================================================================
// Задание 6: Обновление доступности книги
// ============================================================================

/**
 * Обновление доступности книги
 *
 * @param PDO $pdo Объект подключения к БД
 * @param int $bookId ID книги
 * @param bool $available Доступность (true/false)
 * @return void
 */
function setBookAvailability(PDO $pdo, int $bookId, bool $available): void
{
    $stmt = $pdo->prepare("UPDATE books SET available = :available WHERE id = :id");
    $stmt->execute([
        'available' => (int)$available,
        'id' => $bookId
    ]);
}

// ============================================================================
// Задание 7: Транзакции (перенос количества)
// ============================================================================

/**
 * Перенос количества доступных экземпляров между книгами (с транзакцией)
 *
 * @param PDO $pdo Объект подключения к БД
 * @param int $fromId ID книги-источника
 * @param int $toId ID книги-назначения
 * @param int $amount Количество для переноса
 * @return void
 * @throws Exception При ошибке выполнения транзакции
 */
function transferStock(PDO $pdo, int $fromId, int $toId, int $amount): void
{
    try {
        $pdo->beginTransaction();
        
        $stmt1 = $pdo->prepare("UPDATE books SET available = available - :amount WHERE id = :id");
        $stmt1->execute(['amount' => $amount, 'id' => $fromId]);
        
        $stmt2 = $pdo->prepare("UPDATE books SET available = available + :amount WHERE id = :id");
        $stmt2->execute(['amount' => $amount, 'id' => $toId]);
        
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// ============================================================================
// Демонстрация работы
// ============================================================================

$pdo = getPdoConnection();

echo "<h2>Демонстрация работы с базой данных</h2>";

// echo "<h3>Задание 2: Добавление книг</h3>";
// $bulg = addBook($pdo, "Мастер и Маргарита", "М. Булгаков", "978-5-389-07412-7", 2020);
// $tolstoy = addBook($pdo, "Война и мир", "Л. Толстой", "978-5-17-098561-4", 2019);
// $dostoevsky = addBook($pdo, "Преступление и наказание", "Ф. Достоевский", "978-5-699-89547-3", 2018);
// $pushkin = addBook($pdo, "Евгений Онегин", "А. Пушкин", "978-5-17-095234-0", 2021);
// $gogol = addBook($pdo, "Мертвые души", "Н. Гоголь", "978-5-389-14523-8", 2022);

// echo "<p>Добавлены книги с ID: " . htmlspecialchars("$bulg, $tolstoy, $dostoevsky, $pushkin, $gogol") . "</p>";

// echo "<h3>Задание 3: Поиск книг по автору</h3>";
// $bulgakovBooks = findBooksByAuthor($pdo, "М. Булгаков");
// echo "<p>Найдено книг М. Булгакова: " . count($bulgakovBooks) . "</p>";
// echo "<ul>";
// foreach ($bulgakovBooks as $book) {
//     echo "<li>" . htmlspecialchars($book['title']) . " (ISBN: " . htmlspecialchars($book['isbn']) . ")</li>";
// }
// echo "</ul>";

// echo "<h3>Задание 4: Все доступные книги</h3>";
// $availableBooks = getAllAvailableBooks($pdo);
// echo "<p>Всего доступных книг: " . count($availableBooks) . "</p>";
// echo "<ul>";
// foreach ($availableBooks as $book) {
//     echo "<li>" . htmlspecialchars($book['title']) . " - " . htmlspecialchars($book['author']) . "</li>";
// }
// echo "</ul>";

echo "<h3>Задание 5: Информация о книге с ID 1</h3>";
$book = getBookById($pdo, 1);
if ($book) {
    echo "<p><strong>Название:</strong> " . htmlspecialchars($book['title']) . "</p>";
    echo "<p><strong>Автор:</strong> " . htmlspecialchars($book['author']) . "</p>";
    echo "<p><strong>ISBN:</strong> " . htmlspecialchars($book['isbn']) . "</p>";
    echo "<p><strong>Год:</strong> " . htmlspecialchars((string)$book['pub_year']) . "</p>";
    echo "<p><strong>Доступна:</strong> " . ($book['available'] ? 'Да' : 'Нет') . "</p>";
} else {
    echo "<p>Книга не найдена</p>";
}

// echo "<h3>Задание 6: Изменение доступности книги</h3>";
// echo "<p>Доступность книги с ID 1 до изменения: " . (getBookById($pdo, 1)['available'] ? 'Да' : 'Нет') . "</p>";
// setBookAvailability($pdo, 1, false);
// echo "<p>Доступность книги с ID 1 после изменения: " . (getBookById($pdo, 1)['available'] ? 'Да' : 'Нет') . "</p>";
// setBookAvailability($pdo, 1, true);

// echo "<h3>Задание 7: Транзакция переноса количества</h3>";
// $book1Before = getBookById($pdo, 1);
// $book2Before = getBookById($pdo, 2);
// echo "<p>Книга 1 (available) до: " . htmlspecialchars((string)$book1Before['available']) . "</p>";
// echo "<p>Книга 2 (available) до: " . htmlspecialchars((string)$book2Before['available']) . "</p>";

// try {
//     transferStock($pdo, 1, 2, 5);
//     $book1After = getBookById($pdo, 1);
//     $book2After = getBookById($pdo, 2);
//     echo "<p>Книга 1 (available) после: " . htmlspecialchars((string)$book1After['available']) . "</p>";
//     echo "<p>Книга 2 (available) после: " . htmlspecialchars((string)$book2After['available']) . "</p>";
// } catch (Exception $e) {
//     echo "<p style='color: red;'>Ошибка транзакции: " . htmlspecialchars($e->getMessage()) . "</p>";
// }

// echo "<h3>Задание 8: Защита от SQL-инъекций</h3>";
// $injectionTest = findBooksByAuthor($pdo, "' OR '1'='1");
// echo "<p>Результат поиска автора \"' OR '1'='1\": " . 
//      (empty($injectionTest) ? "Пустой массив (защита работает)" : "Найдено книг: " . count($injectionTest)) . "</p>";

// echo "<h3>Задание 9: Обработка ошибок</h3>";
// echo "<p>Режим работы: " . (IS_DEVELOPMENT ? "DEVELOPMENT (показывать ошибки)" : "PRODUCTION (логировать ошибки)") . "</p>";
// echo "<p>Для проверки обработки ошибок измените данные подключения на неверные.</p>";

// echo "<hr>";
// echo "<p><strong>Все задания выполнены успешно!</strong></p>";