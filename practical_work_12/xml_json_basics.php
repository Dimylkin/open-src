<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

function loadBooksFromXml(string $filename): array
{
    $xml = simplexml_load_file($filename);
    $books = [];
    
    foreach ($xml->book as $book) {
        $bookData = [];
        
        $bookData['isbn'] = (string)$book['isbn'];
        
        $bookData['title'] = (string)$book->title;
        
        $bookData['authors'] = [];
        foreach ($book->authors->author as $author) {
            $bookData['authors'][] = (string)$author;
        }
        
        $books[] = $bookData;
    }
    
    return $books;
}

function renderBooksAsHtmlTable(array $books): void
{
    echo '<!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Каталог книг</title>
        <style>
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
        </style>
    </head>
    <body>';
    
    echo '<table>';
    echo '<tr>
            <th>ISBN</th>
            <th>Название</th>
            <th>Авторы</th>
          </tr>';
    
    foreach ($books as $book) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($book['isbn'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') . '</td>';
        
        // Объединяем авторов через запятую
        $authors = implode(', ', array_map(function($author) {
            return htmlspecialchars($author, ENT_QUOTES, 'UTF-8');
        }, $book['authors']));
        
        echo '<td>' . $authors . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</body></html>';
}

$books = loadBooksFromXml('books.xml');
// var_dump($books);
renderBooksAsHtmlTable($books);