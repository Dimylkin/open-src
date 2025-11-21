<?php
declare(strict_types=1);

/*
CREATE DATABASE library
CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255),
  isbn VARCHAR(20),
  pub_year INT,
  available TINYINT DEFAULT 1
);
*/

function getPdoConnection(): PDO {
    try {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=library;charset=utf8mb4",
            'bd',
            '123',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Ошибка подключения: " . $e->getMessage());
    }
}

function addBook(PDO $pdo, string $title, string $author, string $isbn, int $year): int {
    $stmt = $pdo->prepare("INSERT INTO books (title, author, isbn, pub_year) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $author, $isbn, $year]);
    return (int)$pdo->lastInsertId();
}

function findBooksByAuthor(PDO $pdo, string $author): array {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE author = :author");
    $stmt->execute(['author' => $author]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAvailableBooks(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM books WHERE available = 1");
    while ($row = $stmt->fetch()) {
        echo htmlspecialchars($row['title']) . "\n";
    }
}

function setBookAvailability(PDO $pdo, int $bookId, bool $available): void {
    $stmt = $pdo->prepare("UPDATE books SET available = :available WHERE id = :id");
    $stmt->execute(['available' => $available, 'id' => $bookId]);
}

function transferStock(PDO $pdo, int $fromId, int $toId, int $amount): void {
    try {
    $pdo->beginTransaction();
    $pdo->exec("UPDATE books SET available = available - $amount WHERE user_id = $fromId");
    $pdo->exec("UPDATE books SET available = available + $amount WHERE user_id = $toId");
    $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollback();
        throw $e;
    }
}
// $pdo = getPdoConnection();
// $id = addBook($pdo, "Мастер и Маргарита", "М. Булгаков", "978-5-389-07412-7", 2020);
// echo "Добавлена книга с ID: " . $id;
// echo "<br>\n";
// $authorBook = findBooksByAuthor($pdo, "М. Булгаков");
// getAllAvailableBooks($pdo);