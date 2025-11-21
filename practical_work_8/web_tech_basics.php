<?php


declare(strict_types=1);


/*
Задание 1. Анализ HTTP-запроса
На основе раздела «2. Структура HTTP-запроса и ответа».

Напишите функцию dumpRequestInfo(): void, которая выводит в формате HTML:

Метод запроса ($_SERVER['REQUEST_METHOD'])
URI ($_SERVER['REQUEST_URI'])
Все параметры из $_GET и $_POST (если есть)
Информацию о браузере ($_SERVER['HTTP_USER_AGENT'])
Данные должны быть экранированы.
*/
function dumpRequestInfo(): void
{
    $method = htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
    $uri = htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/', ENT_QUOTES, 'UTF-8');
    $protocol = htmlspecialchars($_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1', ENT_QUOTES, 'UTF-8');
    $host = htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
    $userAgent = htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
    
    echo "<!DOCTYPE html><html lang='ru'><head><meta charset='UTF-8'><title>{$method}</title></head><body>";
    echo "<p>{$method} {$uri} {$protocol}</p>";
    echo "<p>Host: {$host}</p>";
    echo "<p>User-Agent: {$userAgent}</p>";
    
    echo "<p>GET:</p>";
    if ($_GET) {
        foreach ($_GET as $k => $v) {
            echo htmlspecialchars($k, ENT_QUOTES, 'UTF-8') . ': ' . htmlspecialchars($v, ENT_QUOTES, 'UTF-8') . '<br>';
        }
    } else {
        echo "Нет параметров<br>";
    }
    
    echo "<p>POST:</p>";
    if ($_POST) {
        foreach ($_POST as $k => $v) {
            echo htmlspecialchars($k, ENT_QUOTES, 'UTF-8') . ': ' . htmlspecialchars($v, ENT_QUOTES, 'UTF-8') . '<br>';
        }
    } else {
        echo "Нет параметров<br>";
    }
    
    echo "</body></html>";
}
// dumpRequestInfo();


/*
Задание 2. Работа с суперглобальными массивами
На основе раздела «3. Глобальные суперглобальные массивы PHP».

Реализуйте функцию getRequestData(): array, которая возвращает ассоциативный массив с полями:

'method' — метод запроса
'get' — копия $_GET
'post' — копия $_POST
'server_info' — массив с HTTP_HOST, SERVER_NAME, HTTPS (если задано)
*/
function getRequestData(): array
{
    return [
        'method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
        'get' => $_GET,
        'post' => $_POST,
        'server_info' => [
            'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'Unknown',
            'SERVER_NAME' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
            'HTTPS' => $_SERVER['HTTPS'] ?? 'off'
        ]
    ];
}
// print_r(getRequestData());

/*
Задание 3. Обработка GET- и POST-форм
На основе раздела «4. Обработка HTML-форм».

Создайте две простые формы на одной странице:

GET-форма с полем «поиск»
POST-форма с полем «сообщение»
Реализуйте обработку: если данные есть — выведите их (экран). Иначе — покажите формы.

Используйте sticky-формы — сохраняйте введённые значения при ошибке (здесь ошибки нет, но поведение должно быть).
*/
function processSearchForm(): void
{
    $search = $_GET['search'] ?? '';
    
    if ($search !== '') {
        echo '<div style="background: lightgreen; padding: 10px; margin: 10px 0;">';
        echo '<strong>Результат поиска:</strong> ' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8');
        echo '</div>';
    }
    
    echo '<h2>GET-форма (поиск)</h2>';
    echo '<form method="GET">';
    echo '<label>Поиск: ';
    echo '<input type="text" name="search" value="' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '">';
    echo '</label>';
    echo '<button type="submit">Найти</button>';
    echo '</form>';
}

function processMessageForm(): void
{
    $message = $_POST['message'] ?? '';
    
    if ($message !== '') {
        echo '<div style="background: lightblue; padding: 10px; margin: 10px 0;">';
        echo '<strong>Получено сообщение:</strong> ' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        echo '</div>';
    }
    
    echo '<h2>POST-форма (сообщение)</h2>';
    echo '<form method="POST">';
    echo '<label>Сообщение: ';
    echo '<input type="text" name="message" value="' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '">';
    echo '</label>';
    echo '<button type="submit">Отправить</button>';
    echo '</form>';
}
// processSearchForm();
// processMessageForm();

/*
Задание 4. Cookies: установка и чтение
На основе раздела «5. Cookies».

Реализуйте функцию setThemeCookie(string $theme): void, которая устанавливает cookie theme со сроком 1 час, флагами secure, httponly, samesite=Lax.

Реализуйте функцию getTheme(): string, которая возвращает значение cookie или 'light' по умолчанию.
*/

function setThemeCookie(string $theme): void {
    setcookie(
        'theme',
        $theme,
        [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );
}

function getTheme(): string {
    return $_COOKIE['theme'] ?? 'light';
}
// setThemeCookie('dark');
// $currentTheme = getTheme();
// echo "Текущая тема: " . htmlspecialchars($currentTheme, ENT_QUOTES, 'UTF-8');

/*
Задание 5. Сессии: инициализация и использование
На основе раздела «6. Сессии».

Напишите функцию initSession(): void, которая вызывает session_start() только один раз (защита от повторного вызова).

Создайте класс SessionBag с методами:

set(string $key, mixed $value): void
get(string $key, mixed $default = null): mixed
has(string $key): bool
remove(string $key): void
Все операции должны работать с $_SESSION.
*/

function initSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

class SessionBag {
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    function remove(string $key): void {
        unset($_SESSION[$key]);
    }
}
// initSession();
// initSession();
 
// $session = new SessionBag();
 
// $session->set('username', 'Ivan');
// $session->set('age', 25);
// $session->set('theme', 'dark'); 
// echo $session->get('username');
// echo $session->get('email', 'not@set.com');
// if ($session->has('username')) {
//      echo "Пользователь залогинен: " . $session->get('username');
// } 
// $session->remove('age');
 
// var_dump($session->has('age'));

/*
Задание 6. Безопасная валидация входных данных
На основе раздела «7.1. Валидация и экранирование».

Реализуйте функцию validateEmail(string $email): bool, использующую filter_var().

Реализуйте функцию safeOutput(string $text): string, возвращающую htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').
*/

function validateEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function safeOutput(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/*
Задание 7. Защита от XSS
На основе раздела «7.2. Защита от атак».

Создайте форму «Гостевая книга» с полем comment (POST).

При отправке:

Сохраняйте комментарий в сессии ($_SESSION['comments'][])
При выводе — обязательно экранируйте каждый комментарий
Убедитесь, что ввод не выполняется.
*/

function guestForm(): void
{
    initSession();
    
    if (!isset($_SESSION['comments'])) {
        $_SESSION['comments'] = [];
    }
    
    $message = $_POST['message'] ?? '';
    
    if ($message !== '') {
        $_SESSION['comments'][] = $message;
        echo '<div style="background: lightgreen; padding: 10px; margin: 10px 0;">';
        echo '<strong>Комментарий добавлен!</strong>';
        echo '</div>';
    }
    
    echo '<h2>Гостевая книга</h2>';
    
    if (!empty($_SESSION['comments'])) {
        echo '<h3>Комментарии:</h3>';
        echo '<ul>';
        foreach ($_SESSION['comments'] as $comment) {
            echo '<li>' . htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Комментариев пока нет.</p>';
    }
    
    echo '<h3>Добавить комментарий:</h3>';
    echo '<form method="POST">';
    echo '<label>Комментарий: ';
    echo '<input type="text" name="message" required>';
    echo '</label>';
    echo '<button type="submit">Отправить</button>';
    echo '</form>';
}

/*
Задание 8. Защита от CSRF
На основе раздела «7.2. Защита от атак».

Реализуйте функцию generateCsrfToken(): string (через bin2hex(random_bytes(32))).

Реализуйте функцию validateCsrfToken(string $token): bool, использующую hash_equals().

Добавьте CSRF-токен в форму из задания 7 и проверяйте его при отправке.
*/

function generateCsrfToken(): string
{
    initSession();
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}


function validateCsrfToken(string $token): bool
{
    initSession();
    
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/*
Задание 9. Регенерация ID сессии
На основе раздела «7.2. Защита от атак».

Создайте функцию rotateSessionId(): void, которая вызывает session_regenerate_id(true).

Примените её при «входе» (в имитации авторизации из итогового задания).
*/
function rotateSessionId(): void {
    session_regenerate_id(true);
}

/*
Задание 10. Корзина товаров на сессиях
На основе раздела «8.1. Корзина товаров на сессиях».

Реализуйте класс ShoppingCart с методами:

addItem(array $item): void — $item должен содержать id, name, price
getItems(): array
clear(): void
Все данные храните в $_SESSION['cart'].
*/
class ShoppingCart {
    function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    function addItem(array $item): void {
        initSession();
        if (isset($item['id']) && isset($item['name']) && isset($item['price'])) {
            $_SESSION['cart'][] = $item;
        }
    }

    function getItems(): array {
        initSession();
        return  $_SESSION['cart'];
    }

    function clear(): void {
        unset($_SESSION['cart']);
    }
}

/*
Задание 11. Итоговое домашнее задание
Реализуйте полноценный сценарий авторизации:

Форма входа с полями email и password (POST).
Валидация email через filter_var().
Имитация проверки пароля: допустим, правильный пароль — "secret".
При успешном входе:
Вызовите rotateSessionId()
Сохраните email и user_id (например, 123) в сессию
На той же странице:
Если пользователь авторизован — покажите «Здравствуйте, {email}» и кнопку «Выход»
Иначе — покажите форму входа
Кнопка «Выход» должна вызывать session_destroy() и перенаправлять на ту же страницу.
Все выводы — через safeOutput().
*/

class Auth {
    function authForm(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $secret = "secret";
        
        if (validateEmail($email) && $password == $secret) {
            rotateSessionId();
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = "123";
            echo '<div style="background: lightblue; padding: 10px; margin: 10px 0;">';
            wellcomeForm();
            echo '</div>';
        }
        
        echo '<h2>POST-форма (сообщение)</h2>';
        echo '<form method="POST">';
        echo '<label>Сообщение: ';
        echo '<input type="text" name="message" value="' . safeOutput($message) . '">';
        echo '</label>';
        echo '<button type="submit">Отправить</button>';
        echo '</form>';
    }

    function wellcomeForm($email): void {
        echo "<h2>Здравствуйте, {$email}</h2>";
        echo '<button type="submit", name="exit">Выход</button>';

        if (isset($_POST['exit'])) {
            exitFromWellcom();
        }
    }

    function exitFromWellcom(): void {
        session_destroy();
        authForm();
    }
}

$auth = new Auth();
$auth -> authForm();

