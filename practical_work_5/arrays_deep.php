<?php

declare(strict_types=1);

/**
 * Извлекает названия всех книг из массива.
 * 
 * @param array $books Массив книг
 * @return array Массив названий книг
 */
function getBookTitles(array $books): array
{
    $titles = [];
    foreach ($books as $book) {
        if (isset($book["title"])) {
            $titles[] = $book["title"];
        }
    }
    return $titles;
}

/**
 * Проверяет наличие книги автора в списке.
 * 
 * @param array $books Массив книг
 * @param string $author Имя автора
 * @return bool true, если автор есть в списке
 */
function hasBookByAuthor(array $books, string $author): bool
{
    foreach ($books as $book) {
        if (isset($book["author"]) && $book["author"] === $author) {
            return true;
        }
    }
    return false;
}

/**
 * Добавляет год по умолчанию к книгам без года.
 * 
 * @param array $books Массив книг
 * @param int $defaultYear Год по умолчанию
 * @return array Массив книг с добавленными годами
 */
function addDefaultYear(array $books, int $defaultYear = 2025): array
{
    foreach ($books as &$book) {
        if (!isset($book["year"])) {
            $book["year"] = (string)$defaultYear;
        }
    }
    unset($book);
    return $books;
}

/**
 * Фильтрует книги по году (старше указанного).
 * 
 * @param array $books Массив книг
 * @param int $minYear Минимальный год
 * @return array Отфильтрованные книги
 */
function filterBooksByYear(array $books, int $minYear): array
{
    $filtered = [];
    foreach ($books as $book) {
        if (isset($book["year"])) {
            $year = (int)$book["year"];
            if ($year > $minYear) {
                $filtered[] = $book;
            }
        }
    }
    return $filtered;
}

/**
 * Преобразует книги в строки вида "Название (Автор, Год)".
 * 
 * @param array $books Массив книг
 * @return array Массив строк с информацией о книгах
 */
function mapBooksToPairs(array $books): array
{
    $result = [];
    foreach ($books as $book) {
        $year = isset($book["year"]) ? $book["year"] : "неизвестен";
        $result[] = $book["title"] . " (" . $book["author"] . ", " . $year . ")";
    }
    return $result;
}

/**
 * Сортирует книги по году (возрастание), затем по названию (алфавит).
 * 
 * @param array $books Массив книг
 * @return array Отсортированный массив книг
 */
function sortBooks(array $books): array
{
    usort($books, function (array $book1, array $book2) {
        $year1 = isset($book1["year"]) ? (int)$book1["year"] : 0;
        $year2 = isset($book2["year"]) ? (int)$book2["year"] : 0;
        
        if ($year1 !== $year2) {
            return $year1 - $year2;
        }
        
        return strcmp($book1["title"], $book2["title"]);
    });
    
    return $books;
}

/**
 * Группирует элементы по ключу.
 * 
 * @param array $items Массив элементов
 * @param string $key Ключ для группировки
 * @return array Группированный массив
 */
function groupBy(array $items, string $key): array
{
    $result = [];
    foreach ($items as $item) {
        if (isset($item[$key])) {
            $value = $item[$key];
            if (!isset($result[$value])) {
                $result[$value] = [];
            }
            $result[$value][] = $item;
        }
    }
    return $result;
}

/**
 * Добавляет элемент в стек.
 * 
 * @param array &$stack Стек
 * @param mixed $value Элемент для добавления
 * @return void
 */
function stackPush(array &$stack, mixed $value): void
{
    if (!empty($stack)) {
        if (gettype($stack[0]) !== gettype($value)) {
            return;
        }
    }
    array_push($stack, $value);
}

/**
 * Извлекает элемент из стека.
 * 
 * @param array &$stack Стек
 * @return mixed|null Извлечённый элемент или null
 */
function stackPop(array &$stack): mixed
{
    if (empty($stack)) {
        return null;
    }
    return array_pop($stack);
}

/**
 * Добавляет элемент в очередь.
 * 
 * @param array &$queue Очередь
 * @param mixed $value Элемент для добавления
 * @return void
 */
function queueEnqueue(array &$queue, mixed $value): void
{
    if (!empty($queue)) {
        if (gettype($queue[0]) !== gettype($value)) {
            return;
        }
    }
    array_push($queue, $value);
}

/**
 * Извлекает элемент из очереди.
 * 
 * @param array &$queue Очередь
 * @return mixed|null Извлечённый элемент или null
 */
function queueDequeue(array &$queue): mixed
{
    if (empty($queue)) {
        return null;
    }
    return array_shift($queue);
}

/**
 * Безопасно получает значение по ключу.
 * 
 * @param array $array Массив
 * @param string|int $key Ключ
 * @param mixed $default Значение по умолчанию
 * @return mixed Значение или значение по умолчанию
 */
function safeGet(array $array, string|int $key, mixed $default = null): mixed
{
    return $array[$key] ?? $default;
}

/**
 * Проверяет, является ли массив ассоциативным.
 * 
 * @param array $array Массив для проверки
 * @return bool true, если ассоциативный
 */
function isAssociative(array $array): bool
{
    $expectedKey = 0;
    foreach ($array as $key => $value) {
        if (!is_int($key)) {
            return true;
        }
        if ($key !== $expectedKey) {
            return true;
        }
        $expectedKey++;
    }
    return false;
}

$testBooks = [
    [
        "title" => "1984",
        "author" => "Джордж Оруэлл",
        "year" => "1949"
    ],
    [
        "title" => "Мастер и Маргарита",
        "author" => "Михаил Булгаков",
        "year" => "1967"
    ],
    [
        "title" => "Преступление и наказание",
        "author" => "Фёдор Достоевский",
        "year" => "1866"
    ],
    [
        "title" => "Война и мир",
        "author" => "Лев Толстой"
    ],
    [
        "title" => "Гарри Поттер и философский камень",
        "author" => "Джоан Роулинг",
        "year" => "1997"
    ]
];


echo "=== 1. getBookTitles ===\n";
echo "<br>\n";
print_r(getBookTitles($testBooks));
echo "<br>\n";

$testBooks = addDefaultYear($testBooks);

echo "\n=== 2. hasBookByAuthor (Оруэлл) ===\n";
echo "<br>\n";
echo hasBookByAuthor($testBooks, "Джордж Оруэлл") ? "Да" : "Нет";
echo "<br>\n";

echo "\n=== 3. addDefaultYear ===\n";
echo "<br>\n";
print_r($testBooks);
echo "<br>\n";

echo "\n=== 4. filterBooksByYear (после 1900) ===\n";
echo "<br>\n";
print_r(filterBooksByYear($testBooks, 1900));
echo "<br>\n";

echo "\n=== 5. mapBooksToPairs ===\n";
echo "<br>\n";
print_r(mapBooksToPairs($testBooks));
echo "<br>\n";

echo "\n=== 6. sortBooks ===\n";
echo "<br>\n";
print_r(sortBooks($testBooks));
echo "<br>\n";

echo "\n=== 7. groupBy (по автору) ===\n";
echo "<br>\n";
print_r(groupBy($testBooks, "author"));
echo "<br>\n";

echo "\n=== 8. Стек ===\n";
echo "<br>\n";
$stack = [];
stackPush($stack, "A");
stackPush($stack, "B");
echo "Стек: ";
echo "<br>\n";
print_r($stack);
echo "<br>\n";
echo "Pop: " . stackPop($stack) . "\n";
echo "<br>\n";
print_r($stack);
echo "<br>\n";

echo "\n=== 9. Очередь ===\n";
echo "<br>\n";
$queue = [];
queueEnqueue($queue, "X");
queueEnqueue($queue, "Y");
echo "Очередь: ";
echo "<br>\n";
print_r($queue);
echo "<br>\n";
echo "Dequeue: " . queueDequeue($queue) . "\n";
echo "<br>\n";
print_r($queue);
echo "<br>\n";

echo "\n=== 10. safeGet ===\n";
echo "<br>\n";
echo safeGet($testBooks[0], "title", "нет ключа") . "\n";
echo "<br>\n";

echo "\n=== 11. isAssociative ===\n";
echo "<br>\n";
echo "Индексированный: " . (isAssociative($testBooks) ? "true" : "false") . "\n";
echo "<br>\n";
$assoc = ["name" => "John", 0 => "first"];
echo "Ассоциативный: " . (isAssociative($assoc) ? "true" : "false") . "\n";
echo "<br>\n";
$assoc2 = [0 => "a", 2 => "b"];
echo "С пробелами: " . (isAssociative($assoc2) ? "true" : "false") . "\n";
echo "<br>\n";
?>
