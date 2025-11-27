<?php

declare(strict_types=1);

session_start();

/**
 * Валидация email из POST-запроса
 *
 * @param string $fieldName Имя поля в POST
 * @return string|null Валидный email или null
 */
function validateEmailFromPost(string $fieldName): ?string
{
    $email = filter_input(INPUT_POST, $fieldName, FILTER_VALIDATE_EMAIL);
    return ($email === null || $email === false) ? null : $email;
}

/**
 * Валидация имени пользователя
 *
 * @param string $name Имя пользователя
 * @return string|null Возвращает имя, если оно корректно, иначе null
 */
function validateName(string $name): ?string
{
    if (preg_match('/^[A-Za-zА-Яа-я\s]{2,50}$/u', $name)) {
        return $name;
    }
    return null;
}

/**
 * Валидация возраста 1..120
 *
 * @param int $age Возраст для проверки
 * @return int|null Валидный возраст или null
 */
function validateAge(int $age): ?int
{
    $options = ['options' => ['min_range' => 1, 'max_range' => 120]];
    return filter_var($age, FILTER_VALIDATE_INT, $options) ?: null;
}

/**
 * Экранирование текста для безопасного вывода в HTML
 *
 * @param string $text Текст для экранирования
 * @return string Экранированный текст
 */
function escapeHtml(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Генерация CSRF токена для защиты формы
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
 * Проверка CSRF токена
 *
 * @param string $token Токен из запроса
 * @return bool true, если токен совпадает с сохраненным
 */
function validateCsrfToken(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Проверка, что файл является валидным изображением JPEG или PNG
 *
 * @param string $tmpPath Путь к временному файлу
 * @return bool true, если MIME-тип изображения разрешен
 */
function isValidImageFile(string $tmpPath): bool
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $tmpPath);
    finfo_close($finfo);
    return in_array($mimeType, ['image/jpeg', 'image/png'], true);
}

/**
 * Генерация безопасного имени файла с расширением
 *
 * @param string $originalName Оригинальное имя файла
 * @return string Новое безопасное имя с расширением
 */
function generateSafeFileName(string $originalName): string
{
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    return bin2hex(random_bytes(16)) . '.' . $extension;
}

/**
 * Проверка валидности размера файла (по умолчанию не более 1Мб)
 *
 * @param int $size Размер файла в байтах
 * @param int $maxBytes Максимально допустимый размер файла
 * @return bool true, если размер валиден
 */
function isFileSizeValid(int $size, int $maxBytes = 1048576): bool
{
    return $size > 0 && $size <= $maxBytes;
}

/**
 * Проверка, что директория для загрузок находится вне публичных папок
 *
 * @param string $uploadDir Путь к директории загрузок
 * @return bool true, если директория безопасна
 */
function isUploadDirSafe(string $uploadDir): bool
{
    $realPath = realpath($uploadDir);
    if ($realPath === false) {
        return false;
    }
    $publicDirs = ['public', 'htdocs', 'www', 'html', 'public_html'];
    foreach ($publicDirs as $dir) {
        if (strpos($realPath, DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR) !== false) {
            return false;
        }
    }
    return true;
}

/**
 * Сохраняет загруженный файл в безопасной директории и возвращает относительный путь
 *
 * @param array $file Массив с данными загруженного файла из $_FILES
 * @param string $uploadDir Директория для сохранения файла
 * @return string|null Относительный путь или null если ошибка
 */
function saveUploadedFile(array $file, string $uploadDir): ?string
{
    if (!isset($file['tmp_name'], $file['name'], $file['size'])) {
        return null;
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        return null;
    }

    if (!isValidImageFile($file['tmp_name'])) {
        return null;
    }

    if (!isFileSizeValid($file['size'])) {
        return null;
    }

    if (!isUploadDirSafe($uploadDir)) {
        return null;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!is_writable($uploadDir)) {
        return null;
    }

    $safeFileName = generateSafeFileName($file['name']);
    $destination = $uploadDir . DIRECTORY_SEPARATOR . $safeFileName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return null;
    }

    return ltrim(str_replace(dirname(__DIR__), '', $destination), DIRECTORY_SEPARATOR);
}

/**
 * Инициализация сессии с безопасными параметрами куки
 *
 * @return void
 */
function secureSessionStart(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_secure', '1');
    ini_set('session.cookie_samesite', 'Strict');
}

/**
 * Класс формы регистрации с обработкой и выводом
 */
class Form
{
    /**
     * Отображение формы с валидацией и обработкой POST
     *
     * @return void
     */
    public function inputForm(): void
    {
        secureSessionStart();

        $email = $_POST['email'] ?? $_SESSION['email'] ?? '';
        $name = $_POST['name'] ?? $_SESSION['name'] ?? '';
        $age = $_POST['age'] ?? $_SESSION['age'] ?? '';
        $csrfToken = generateCsrfToken();

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $avatar = $_FILES['avatar'] ?? null;
            $tokenFromForm = $_POST['csrf_token'] ?? '';

            if (!validateCsrfToken($tokenFromForm)) {
                $errors[] = 'Ошибка CSRF токена.';
            }
            if (validateEmailFromPost('email') === null) {
                $errors[] = 'Некорректный email.';
            }
            if (validateName($name) === null) {
                $errors[] = 'Некорректное имя.';
            }
            if (validateAge((int) $age) === null) {
                $errors[] = 'Некорректный возраст.';
            }
            if ($avatar === null || $avatar['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Ошибка загрузки файла аватара.';
            } else {
                if (!isValidImageFile($avatar['tmp_name'])) {
                    $errors[] = 'Недопустимый тип файла аватара.';
                }
                if (!isFileSizeValid($avatar['size'])) {
                    $errors[] = 'Размер файла аватара превышает 1 Мб.';
                }
            }

            if (empty($errors)) {
                $uploadDir = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'uploads';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $savedPath = saveUploadedFile($avatar, $uploadDir);
                if ($savedPath === null) {
                    $errors[] = 'Не удалось сохранить файл аватара.';
                } else {
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $name;
                    $_SESSION['age'] = $age;
                    $_SESSION['avatar'] = $savedPath;
                    $this->infoForm();
                    return;
                }
            }
        }

        echo '<!DOCTYPE html>
            <html lang="ru">
            <head><meta charset="UTF-8" /><title>Форма регистрации</title></head><body><h2>Регистрация пользователя</h2>';
        if (!empty($errors)) {
            echo '<div style="color:red;"><ul>';
            foreach ($errors as $error) {
                echo '<li>' . escapeHtml($error) . '</li>';
            }
            echo '</ul></div>';
        }
        echo '<form action="" method="POST" enctype="multipart/form-data">
            <label for="email">Email:</label><br />
            <input type="email" id="email" name="email" required value="' . escapeHtml($email) . '" /><br /><br />
            <label for="name">Имя:</label><br />
            <input type="text" id="name" name="name" required minlength="2" maxlength="50" value="' . escapeHtml($name) . '" /><br /><br />
            <label for="age">Возраст:</label><br />
            <input type="number" id="age" name="age" min="1" max="120" required value="' . escapeHtml((string)$age) . '" /><br /><br />
            <label for="avatar">Аватар (JPEG, PNG):</label><br />
            <input type="file" id="avatar" name="avatar" accept="image/jpeg, image/png" required /><br /><br />
            <input type="hidden" name="csrf_token" value="' . escapeHtml($csrfToken) . '" />
            <button type="submit">Отправить</button>
            </form></body></html>';
    }

    /**
     * Вывод информации о пользователе после успешной регистрации
     *
     * @return void
     */
    public function infoForm(): void
    {
        echo '<!DOCTYPE html>
            <html lang="ru">
            <head><meta charset="UTF-8" /><title>Данные пользователя</title></head><body>
            <h2>Данные пользователя</h2>
            <p><strong>Email:</strong> ' . escapeHtml($_SESSION['email']) . '</p>
            <p><strong>Имя:</strong> ' . escapeHtml($_SESSION['name']) . '</p>
            <p><strong>Возраст:</strong> ' . escapeHtml($_SESSION['age']) . '</p>
            <p><strong>Аватар:</strong><br />
            <img src="/uploads/' . escapeHtml(basename($_SESSION['avatar'])) . '" alt="Аватар" style="max-width: 200px;" />
            </p></body></html>';
    }
}


//================== Демонстрация и тестирование ==================

secureSessionStart();

$form = new Form();
$form->inputForm();