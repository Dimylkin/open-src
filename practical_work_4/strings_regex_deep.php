<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/**
 * Генерирует HTML-шаблон письма с использованием heredoc.
 * 
 * Использует синтаксис heredoc для создания HTML-шаблона
 * с интерполяцией переменных.
 *
 * @param string $name Имя получателя
 * @param string $product Название продукта
 * @return string HTML-шаблон письма
 */
function generateEmailTemplateHeredoc(string $name, string $product): string
{
    if (empty($name) || empty($product)) {
        return '';
    }
    
    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safeProduct = htmlspecialchars($product, ENT_QUOTES, 'UTF-8');
    
    return <<<HEREDOC
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ваш заказ</title>
</head>
<body>
    <p>Добрый день, $safeName!</p>
    <p>Благодарим вас за заказ $safeProduct!</p>
</body>
</html>
HEREDOC;
}

/**
 * Генерирует HTML-шаблон письма с использованием nowdoc.
 * 
 * Использует синтаксис nowdoc для создания статичного HTML-шаблона
 * без интерполяции переменных.
 *
 * @param string $name Имя получателя (не используется в nowdoc)
 * @param string $product Название продукта (не используется в nowdoc)
 * @return string HTML-шаблон письма
 */
function generateEmailTemplateNowdoc(string $name, string $product): string
{
    return <<<'HEREDOC'
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ваш заказ</title>
</head>
<body>
    <p>Добрый день, пользователь!</p>
    <p>Благодарим вас за заказ!</p>
</body>
</html>
HEREDOC;
}

/**
 * Извлекает первый и последний символ строки.
 * 
 * Использует многобайтовые функции для корректной работы
 * с Unicode-символами (кириллица, иероглифы и т.д.).
 *
 * @param string $str Исходная строка
 * @return array Ассоциативный массив с ключами 'first' и 'last'
 */
function getFirstAndLastChar(string $str): array
{
    if (mb_strlen($str) === 0) {
        return ['first' => '', 'last' => ''];
    }
    
    return [
        'first' => mb_substr($str, 0, 1),
        'last' => mb_substr($str, -1, 1)
    ];
}

/**
 * Объединяет имя и фамилию в полное имя.
 * 
 * Убирает лишние пробелы в начале и конце результата.
 *
 * @param string $first Имя
 * @param string $last Фамилия
 * @return string Полное имя
 */
function buildFullName(string $first, string $last): string
{
    return trim($first . ' ' . $last);
}

/**
 * Преобразует каждое слово строки к виду "С Заглавной Буквы".
 * 
 * Поддерживает Unicode (кириллицу, латиницу и другие алфавиты).
 * Сохраняет знаки препинания и разделители.
 *
 * @param string $phrase Исходная фраза
 * @return string Фраза с заглавными первыми буквами
 */
function toTitleCase(string $phrase): string
{
    if (empty($phrase)) {
        return '';
    }
    
    $words = preg_split('/(\s+|[,.!?])/u', $phrase, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    $result = '';
    
    foreach ($words as $word) {
        if (preg_match('/^\p{L}+$/u', $word) === 1) {
            $result .= mb_strtoupper(mb_substr($word, 0, 1)) . mb_substr($word, 1);
        } else {
            $result .= $word;
        }
    }
    
    return $result;
}

/**
 * Извлекает имя файла из полного пути.
 * 
 * Корректно работает как с полными путями, так и с именами файлов.
 *
 * @param string $path Полный путь к файлу
 * @return string Имя файла
 */
function extractFileName(string $path): string
{
    if (empty($path)) {
        return '';
    }
    
    $lastSlash = strrpos($path, '/');
    
    if ($lastSlash === false) {
        return $path;
    }
    
    return substr($path, $lastSlash + 1);
}

/**
 * Объединяет массив тегов в строку CSV.
 * 
 * Теги разделяются запятой с пробелом.
 *
 * @param array $tags Массив тегов
 * @return string Строка тегов в формате CSV
 */
function tagListToCSV(array $tags): string
{
    return implode(', ', $tags);
}

/**
 * Преобразует строку CSV в массив тегов.
 * 
 * Убирает лишние пробелы вокруг каждого тега.
 *
 * @param string $csv Строка в формате CSV
 * @return array Массив тегов
 */
function csvToTagList(string $csv): array
{
    if (empty($csv)) {
        return [];
    }
    
    $items = explode(',', $csv);
    
    foreach ($items as &$item) {
        $item = trim($item);
    }
    unset($item);
    
    return array_filter($items, fn($item) => $item !== '');
}

/**
 * Безопасно экранирует строку для вывода в HTML.
 * 
 * Предотвращает XSS-атаки путём преобразования специальных символов
 * в HTML-сущности.
 *
 * @param string $userInput Пользовательский ввод
 * @return string Экранированная строка
 */
function safeEcho(string $userInput): string
{
    return htmlspecialchars($userInput, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Формирует URL для поискового запроса.
 * 
 * Корректно кодирует запрос для использования в URL.
 *
 * @param string $query Поисковый запрос
 * @return string Полный URL с закодированным запросом
 */
function buildSearchUrl(string $query): string
{
    if (empty($query)) {
        return 'https://example.com/search?q=';
    }
    
    $encodedQuery = rawurlencode($query);
    return "https://example.com/search?q=$encodedQuery";
}

/**
 * Проверяет надёжность пароля.
 * 
 * Пароль должен содержать минимум 8 символов,
 * хотя бы одну заглавную букву и одну цифру.
 *
 * @param string $pass Пароль для проверки
 * @return bool true, если пароль надёжный
 */
function validatePassword(string $pass): bool
{
    return preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $pass) === 1;
}

/**
 * Извлекает все email-адреса из текста.
 * 
 * Использует регулярное выражение для поиска email-адресов.
 *
 * @param string $text Исходный текст
 * @return array Массив найденных email-адресов
 */
function extractEmails(string $text): array
{
    if (empty($text)) {
        return [];
    }
    
    preg_match_all('/[\w.+-]+@[\w.-]+\.[a-z]{2,}/i', $text, $matches);
    return $matches[0] ?? [];
}

/**
 * Заменяет все числа в тексте на многоточие.
 * 
 * Используется для маскировки числовых данных.
 *
 * @param string $text Исходный текст
 * @return string Текст с замаскированными числами
 */
function highlightNumbers(string $text): string
{
    return preg_replace('/\d+/', '...', $text);
}

// Демонстрация вызова функций с входными данными в титуле

$name = "Дима";
$product = "Компьютер";
// Задание 1
echo "=== Задание 1: Heredoc. Входные данные: name = $name, product = $product ===" . PHP_EOL;
echo "<br>\n";
echo generateEmailTemplateHeredoc($name, $product) . PHP_EOL;
echo "<br>\n";

$nameNowdoc = "Евгений";
$productNowdoc = "Смартфон";
echo "=== Задание 1 (nowdoc): Входные данные: name = $nameNowdoc, product = $productNowdoc ===" . PHP_EOL;
echo "<br>\n";
echo generateEmailTemplateNowdoc($nameNowdoc, $productNowdoc) . PHP_EOL;
echo "<br>\n";

// Задание 2
$word = "Слово";
echo "=== Задание 2: Первый и последний символ. Входные данные: word = $word ===" . PHP_EOL;
echo "<br>\n";
$chars = getFirstAndLastChar($word);
echo "При слове \"$word\". Первый символ: {$chars['first']}, а последний: {$chars['last']}." . PHP_EOL;
echo "<br>\n";

// Задание 3
$first = "Дима";
$last = "Суставов";
echo "=== Задание 3: Полное имя. Входные данные: first = $first, last = $last ===" . PHP_EOL;
echo "<br>\n";
echo buildFullName($first, $last) . PHP_EOL;
echo "<br>\n";

// Задание 4
$phrase = "привет, мир! это тест.";
echo "=== Задание 4: Title Case. Входные данные: phrase = \"$phrase\" ===" . PHP_EOL;
echo "<br>\n";
echo toTitleCase($phrase) . PHP_EOL;
echo "<br>\n";

// Задание 5
$path1 = "/var/www/index.php";
$path2 = "index.php";
echo "=== Задание 5: Имя файла. Входные данные: path = $path1 ===" . PHP_EOL;
echo "<br>\n";
echo extractFileName($path1) . PHP_EOL;
echo "<br>\n";
echo "=== Задание 5: Имя файла. Входные данные: path = $path2 ===" . PHP_EOL;
echo "<br>\n";
echo extractFileName($path2) . PHP_EOL;
echo "<br>\n";

// Задание 6
$tags = ["php", "regex", "web"];
echo "=== Задание 6: CSV теги. Входные данные: tags = [" . implode(", ", $tags) . "] ===" . PHP_EOL;
echo "<br>\n";
$csv = tagListToCSV($tags);
echo "CSV: $csv" . PHP_EOL;
echo "Теги: ";
print_r(csvToTagList($csv));
echo "<br>\n";

// Задание 7
$userInput = "<script>alert('XSS')</script>";
echo "=== Задание 7: Безопасный вывод. Входные данные: userInput = $userInput ===" . PHP_EOL;
echo "<br>\n";
echo safeEcho($userInput) . PHP_EOL;
echo "<br>\n";

// Задание 8
$query = "привет мир";
echo "=== Задание 8: URL. Входные данные: query = \"$query\" ===" . PHP_EOL;
echo "<br>\n";
echo buildSearchUrl($query) . PHP_EOL;
echo "<br>\n";

// Задание 9
$passwordStrong = "Password1";
$passwordWeak = "weak";
echo "=== Задание 9: Валидация пароля. Входные данные: pass = \"$passwordStrong\" ===" . PHP_EOL;
echo "<br>\n";
var_dump(validatePassword($passwordStrong));
echo "<br>\n";
echo "=== Задание 9: Валидация пароля. Входные данные: pass = \"$passwordWeak\" ===" . PHP_EOL;
echo "<br>\n";
var_dump(validatePassword($passwordWeak));
echo "<br>\n";

// Задание 10
$emailText = "Контакты: test@example.com и admin@site.org";
echo "=== Задание 10: Email. Входные данные: text = \"$emailText\" ===" . PHP_EOL;
echo "<br>\n";
print_r(extractEmails($emailText));
echo "<br>\n";

// Задание 11
$numText = "Мой номер 123 и код 456";
echo "=== Задание 11: Маскировка чисел. Входные данные: text = \"$numText\" ===" . PHP_EOL;
echo "<br>\n";
echo highlightNumbers($numText) . PHP_EOL;
echo "<br>\n";