<?php

declare(strict_types=1);

// ============================================================================
// Инициализация сессии в начале файла
// ============================================================================

/**
 * Инициализация сессии с защитой от повторного вызова
 *
 * @return void
 */
function initSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

initSession();

// ============================================================================
// Задание 1: Анализ HTTP-запроса
// ============================================================================

/**
 * Вывод информации о HTTP-запросе в формате HTML
 *
 * @return void
 */
function dumpRequestInfo(): void
{
    $method = htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
    $uri = htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/', ENT_QUOTES, 'UTF-8');
    $userAgent = htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
    
    echo "<!DOCTYPE html>\n";
    echo "<html lang='ru'>\n";
    echo "<head><meta charset='UTF-8'><title>HTTP Request Info</title></head>\n";
    echo "<body>\n";
    echo "<h2>Информация о запросе</h2>\n";
    echo "<p><strong>Метод:</strong> {$method}</p>\n";
    echo "<p><strong>URI:</strong> {$uri}</p>\n";
    echo "<p><strong>User-Agent:</strong> {$userAgent}</p>\n";
    
    echo "<h3>GET параметры:</h3>\n";
    if (!empty($_GET)) {
        echo "<ul>\n";
        foreach ($_GET as $k => $v) {
            $key = htmlspecialchars((string)$k, ENT_QUOTES, 'UTF-8');
            $value = htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
            echo "<li>{$key}: {$value}</li>\n";
        }
        echo "</ul>\n";
    } else {
        echo "<p>Нет параметров</p>\n";
    }
    
    echo "<h3>POST параметры:</h3>\n";
    if (!empty($_POST)) {
        echo "<ul>\n";
        foreach ($_POST as $k => $v) {
            $key = htmlspecialchars((string)$k, ENT_QUOTES, 'UTF-8');
            $value = htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
            echo "<li>{$key}: {$value}</li>\n";
        }
        echo "</ul>\n";
    } else {
        echo "<p>Нет параметров</p>\n";
    }
    
    echo "</body>\n</html>";
}

// ============================================================================
// Задание 2: Работа с суперглобальными массивами
// ============================================================================

/**
 * Получение данных запроса
 *
 * @return array Ассоциативный массив с данными запроса
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

// ============================================================================
// Задание 3: Обработка GET- и POST-форм
// ============================================================================

/**
 * Обработка и вывод GET-формы поиска
 *
 * @return void
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

/**
 * Обработка и вывод POST-формы сообщений
 *
 * @return void
 */
function processMessageForm(): void
{
    $message = $_POST['message'] ?? '';
    
    if ($message !== '' && $_SERVER['REQUEST_METHOD'] === 'POST') {
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

// ============================================================================
// Задание 4: Cookies: установка и чтение
// ============================================================================

/**
 * Установка cookie с темой оформления
 *
 * @param string $theme Название темы
 * @return void
 */
function setThemeCookie(string $theme): void
{
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

/**
 * Получение текущей темы из cookie
 *
 * @return string Название темы
 */
function getTheme(): string
{
    return $_COOKIE['theme'] ?? 'light';
}

// ============================================================================
// Задание 5: Сессии: инициализация и использование
// ============================================================================

/**
 * Класс для работы с данными сессии
 */
class SessionBag
{
    /**
     * Установить значение в сессию
     *
     * @param string $key Ключ
     * @param mixed $value Значение
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Получить значение из сессии
     *
     * @param string $key Ключ
     * @param mixed $default Значение по умолчанию
     * @return mixed Значение из сессии или значение по умолчанию
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Проверить существование ключа в сессии
     *
     * @param string $key Ключ
     * @return bool True если ключ существует
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Удалить значение из сессии
     *
     * @param string $key Ключ
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }
}

// ============================================================================
// Задание 6: Безопасная валидация входных данных
// ============================================================================

/**
 * Валидация email адреса
 *
 * @param string $email Email адрес
 * @return bool True если email корректен
 */
function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Безопасный вывод текста с экранированием
 *
 * @param string $text Текст для вывода
 * @return string Экранированный текст
 */
function safeOutput(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ============================================================================
// Задание 8: Защита от CSRF
// ============================================================================

/**
 * Генерация CSRF токена
 *
 * @return string CSRF токен
 */
function generateCsrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Валидация CSRF токена
 *
 * @param string $token Токен для проверки
 * @return bool True если токен корректен
 */
function validateCsrfToken(string $token): bool
{
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

// ============================================================================
// Задание 9: Регенерация ID сессии
// ============================================================================

/**
 * Регенерация ID сессии для безопасности
 *
 * @return void
 */
function rotateSessionId(): void
{
    session_regenerate_id(true);
}

// ============================================================================
// Задание 7: Защита от XSS (Гостевая книга с CSRF защитой)
// ============================================================================

/**
 * Обработка и вывод гостевой книги с CSRF защитой
 *
 * @return void
 */
function guestForm(): void
{
    if (!isset($_SESSION['comments'])) {
        $_SESSION['comments'] = [];
    }
    
    $comment = $_POST['comment'] ?? '';
    $token = $_POST['csrf_token'] ?? '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $comment !== '') {
        if (validateCsrfToken($token)) {
            $_SESSION['comments'][] = $comment;
            echo '<div style="background: lightgreen; padding: 10px; margin: 10px 0;">';
            echo '<strong>Комментарий добавлен!</strong>';
            echo '</div>';
        } else {
            echo '<div style="background: lightcoral; padding: 10px; margin: 10px 0;">';
            echo '<strong>Ошибка: неверный CSRF токен!</strong>';
            echo '</div>';
        }
    }
    
    echo '<h2>Гостевая книга</h2>';
    
    if (!empty($_SESSION['comments'])) {
        echo '<h3>Комментарии:</h3>';
        echo '<ul>';
        foreach ($_SESSION['comments'] as $savedComment) {
            echo '<li>' . safeOutput($savedComment) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Комментариев пока нет.</p>';
    }
    
    echo '<h3>Добавить комментарий:</h3>';
    echo '<form method="POST">';
    echo '<label>Комментарий: ';
    echo '<input type="text" name="comment" required>';
    echo '</label>';
    echo '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
    echo '<button type="submit">Отправить</button>';
    echo '</form>';
}

// ============================================================================
// Задание 10: Корзина товаров на сессиях
// ============================================================================

/**
 * Класс для работы с корзиной товаров
 */
class ShoppingCart
{
    /**
     * Конструктор класса
     */
    public function __construct()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Добавить товар в корзину
     *
     * @param array $item Массив с данными товара (id, name, price)
     * @return void
     */
    public function addItem(array $item): void
    {
        if (isset($item['id']) && isset($item['name']) && isset($item['price'])) {
            $_SESSION['cart'][] = $item;
        }
    }

    /**
     * Получить все товары из корзины
     *
     * @return array Массив товаров
     */
    public function getItems(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Очистить корзину
     *
     * @return void
     */
    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }
}

// ============================================================================
// Задание 11: Итоговое домашнее задание (Авторизация)
// ============================================================================

/**
 * Класс для работы с авторизацией пользователей
 */
class Auth
{
    /**
     * Обработка формы авторизации
     * Выводит форму входа или приветствие авторизованного пользователя
     *
     * @return void
     */
    function authForm(): void
    {        
        if (isset($_SESSION['email']) && isset($_SESSION['user_id'])) {
            $this->welcomeForm($_SESSION['email']);
            return;
        }
        
        if (isset($_POST['exit'])) {
            session_destroy();
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $secret = "secret";
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $email !== '' && $password !== '') {
            if (validateEmail($email) && $password === $secret) {
                rotateSessionId();
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = "123";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $error = 'Неверный email или пароль';
            }
        }
        
        // Вывод формы входа
        if ($error !== '') {
            echo '<div style="background: lightcoral; padding: 10px; margin: 10px 0;">';
            echo safeOutput($error);
            echo '</div>';
        }
        
        echo '<h2>Форма входа</h2>';
        echo '<form method="POST">';
        echo '<label>Email: ';
        echo '<input type="email" name="email" value="' . safeOutput($email) . '" required>';
        echo '</label><br>';
        echo '<label>Пароль: ';
        echo '<input type="password" name="password" required>';
        echo '</label><br>';
        echo '<button type="submit">Войти</button>';
        echo '</form>';
    }
    
    /**
     * Отображение приветствия авторизованного пользователя
     *
     * @param string $email Email пользователя
     * @return void
     */
    function welcomeForm(string $email): void
    {
        echo '<div style="background: lightgreen; padding: 10px; margin: 10px 0;">';
        echo "<h2>Здравствуйте, " . safeOutput($email) . "</h2>";
        echo '<form method="POST">';
        echo '<button type="submit" name="exit">Выход</button>';
        echo '</form>';
        echo '</div>';

        if (isset($_POST['exit'])) {
            session_destroy();
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}


// ============================================================================
// Демонстрация работы (закомментированный блок)
// ============================================================================

// Задание 1: Анализ HTTP-запроса
// dumpRequestInfo();

// Задание 2: Работа с суперглобальными массивами
// print_r(getRequestData());

// Задание 3: Обработка GET- и POST-форм
// processSearchForm();
// processMessageForm();

// Задание 4: Cookies
// setThemeCookie('dark');
// echo "Текущая тема: " . getTheme();

// Задание 5: Сессии
// $session = new SessionBag();
// $session->set('username', 'Ivan');
// echo $session->get('username');
// $session->remove('username');

// Задание 6: Валидация
// var_dump(validateEmail('test@example.com'));
// echo safeOutput('<script>alert("XSS")</script>');

// Задание 7: Гостевая книга с CSRF
// guestForm();

// Задание 10: Корзина товаров
// $cart = new ShoppingCart();
// $cart->addItem(['id' => 1, 'name' => 'Товар 1', 'price' => 100]);
// print_r($cart->getItems());
// $cart->clear();

// Задание 11: Авторизация
$auth = new Auth();
$auth->authForm();