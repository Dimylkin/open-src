<?php

declare(strict_types=1);

// Проверка наличия расширения GD
if (!extension_loaded('gd')) {
    die('Расширение GD не установлено');
}

// Подключение библиотеки FPDF
define('FPDF_FONTPATH', 'font/');
require('fpdf.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

// ============================================================================
// Задание 1: Чёрный квадрат на белом фоне
// ============================================================================

/**
 * Отрисовка чёрного квадрата на белом фоне
 *
 * @return void
 */
function renderBlackSquare(): void
{
    $image = imagecreatetruecolor(200, 200);

    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);

    imagefill($image, 0, 0, $white);
    imagefilledrectangle($image, 50, 50, 150, 150, $black);

    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
}

// ============================================================================
// Задание 2: Текст с встроенным шрифтом
// ============================================================================

/**
 * Отрисовка текста с встроенным шрифтом
 *
 * @param string $text Текст для вывода (не более 50 символов)
 * @return void
 */
function renderTextImage(string $text): void
{
    if (mb_strlen($text) > 50) {
        die("Текст не должен превышать 50 символов!");
    }

    $image = imagecreatetruecolor(300, 100);

    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    
    imagefill($image, 0, 0, $white);
    imagestring($image, 5, 10, 10, $text, $black);
    
    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
}

// ============================================================================
// Задание 3: TrueType-шрифты
// ============================================================================

/**
 * Отрисовка текста с использованием TrueType-шрифта
 *
 * @param string $text Текст для вывода
 * @param string $fontPath Путь к файлу шрифта
 * @return void
 */
function renderTtfText(string $text, string $fontPath): void
{
    $image = imagecreatetruecolor(400, 200);

    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    
    imagefill($image, 0, 0, $white);

    if (is_readable($fontPath)) {
        imagettftext($image, 20, 0, 50, 100, $black, $fontPath, $text);
    } else {
        imagestring($image, 5, 10, 10, "File does not exist!", $black);
    }

    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
}

// ============================================================================
// Задание 4: Динамическая кнопка
// ============================================================================

/**
 * Отрисовка кнопки с текстом на фоновом изображении
 *
 * @param string $text Текст на кнопке (только буквы, цифры и пробелы)
 * @param string $bgImagePath Путь к фоновому изображению
 * @return void
 */
function renderButton(string $text, string $bgImagePath): void
{
    if (!preg_match('/^[A-Za-zА-Яа-яЁё0-9\s]+$/u', $text)) {
        die("Текст должен содержать только буквы, цифры и пробелы!");
    }

    if (!file_exists($bgImagePath)) {
        die("Файл не найден: " . htmlspecialchars($bgImagePath));
    }
    
    $image = imagecreatefrompng($bgImagePath);
    imagealphablending($image, true);
    imagesavealpha($image, true);
    
    $white = imagecolorallocate($image, 255, 255, 255);
    $fontPath = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
    
    $width = imagesx($image);
    $height = imagesy($image);
    
    imagettftext($image, 20, 0, 50, (int)($height / 2), $white, $fontPath, $text);
    
    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
}

// ============================================================================
// Задание 5: Кэширование изображений
// ============================================================================

/**
 * Получение изображения из кэша или генерация нового
 *
 * @param string $cacheDir Директория для хранения кэша
 * @param string $key Уникальный ключ для кэша
 * @param callable $generator Функция-генератор изображения
 * @return void
 */
function getCachedImageOrGenerate(string $cacheDir, string $key, callable $generator): void
{
    $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . md5($key) . '.png';

    if (file_exists($cacheFile)) {
        header('Content-Type: image/png');
        readfile($cacheFile);
        exit;
    }
    
    $image = $generator();
    
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    
    imagepng($image, $cacheFile);
    
    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
}

// ============================================================================
// Задание 6: Простой PDF-документ
// ============================================================================

/**
 * Генерация простого PDF-документа с сообщением
 *
 * @param string $message Сообщение для вывода
 * @return void
 */
function renderSimplePdf(string $message): void
{
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(40, 10, $message);
    $pdf->Output();
}

// ============================================================================
// Задание 7-9: Класс InvoicePdf с колонтитулами, таблицей и ссылками
// ============================================================================

/**
 * Класс для генерации PDF-счёта
 */
class InvoicePdf extends FPDF
{
    /**
     * Верхний колонтитул с логотипом и заголовком
     *
     * @return void
     */
    public function Header(): void
    {
        $logoPath = __DIR__ . '/image/user_ivan.png';
        
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 10, 8, 15);
        }
        
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Score', 0, 1, 'C');
        $this->Ln(10);
    }

    /**
     * Нижний колонтитул с номером страницы и гиперссылкой
     *
     * @return void
     */
    public function Footer(): void
    {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 5, 'Page ' . $this->PageNo() . ' from {nb}', 0, 1, 'C');

        $this->Ln(3);
        $this->SetTextColor(0, 0, 255);
        $this->SetFont('Arial', 'U', 10);
        $this->Cell(0, 5, 'Visit GitHub', 0, 1, 'C', false, 'https://github.com/Dimylkin/open-src/blob/master/practical_work_11/graphics_pdf_basics.php');
        $this->SetTextColor(0, 0, 0);
    }

    /**
     * Построение таблицы с заголовками и данными
     *
     * @param array $header Массив заголовков таблицы
     * @param array $data Двумерный массив данных таблицы
     * @return void
     */
    public function buildTable(array $header, array $data): void
    {
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(200, 220, 255);

        foreach ($header as $col) {
            $this->Cell(60, 10, $col, 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFont('Arial', '', 12);

        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell(60, 10, $col, 1, 0, 'L');
            }
            $this->Ln();
        }
        
        $this->Output();
    }
}

// ============================================================================
// Задание 10: Роутинг и функции для badge и invoice
// ============================================================================

/**
 * Генерация бейджа с именем пользователя
 *
 * @param string $name Имя пользователя
 * @return void
 */
function renderBadge(string $name): void
{
    if (!preg_match('/^[A-Za-zА-Яа-яЁё\s]{2,50}$/u', $name)) {
        die("Имя должно содержать только буквы и пробелы, длина 2-50 символов!");
    }

    getCachedImageOrGenerate(
        __DIR__ . '/cache',
        'badge_' . $name,
        function () use ($name) {
            $bgPath = __DIR__ . '/image/user_ivan.png';
            
            if (!file_exists($bgPath)) {
                die("Фон не найден: user_ivan.png");
            }
            
            $image = imagecreatefrompng($bgPath);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            
            $black = imagecolorallocate($image, 0, 0, 0);
            $fontPath = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
            
            $width = imagesx($image);
            $height = imagesy($image);
            
            imagettftext($image, 20, 0, (int)($width / 2 - 50), (int)($height / 2), $black, $fontPath, $name);
            
            return $image;
        }
    );
}

/**
 * Генерация PDF-счёта с таблицей товаров
 *
 * @return void
 */
function renderInvoicePdf(): void
{
    $pdf = new InvoicePdf();
    $header = ["Name", "Count", "Price"];
    $products = [
        ['Banana', '20', '2000'],
        ['Apple', '150', '100'],
        ['Milk', '200', '50'],
        ['Chips', '1', '1000'],
        ['Chocolate', '1000', '1']
    ];

    $pdf->buildTable($header, $products);
}

// ============================================================================
// Роутинг по параметру type
// ============================================================================

// $type = htmlspecialchars($_GET['type'] ?? 'invoice', ENT_QUOTES, 'UTF-8');
$type = 'badge';
// $name = htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES, 'UTF-8');
$name = 'Яблоко';

if ($type === 'badge') {
    renderBadge($name);
} else {
    renderInvoicePdf();
}

// ============================================================================
// Демонстрация всех функций (закомментировано)
// ============================================================================

// Задание 1: Чёрный квадрат
// renderBlackSquare();

// Задание 2: Текст с встроенным шрифтом
// renderTextImage("Hello World!");

// Задание 3: TrueType-шрифты
// renderTtfText("Привет!", "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf");

// Задание 4: Динамическая кнопка
// renderButton("Яблоко", __DIR__ . "/image/user_ivan.png");

// Задание 5: Кэширование изображений
// getCachedImageOrGenerate(
//     __DIR__ . '/cache',
//     'user_ivan',
//     function() {
//         $image = imagecreatetruecolor(200, 200);
//         $white = imagecolorallocate($image, 255, 255, 255);
//         imagefill($image, 0, 0, $white);
//         $black = imagecolorallocate($image, 0, 0, 0);
//         imagefilledrectangle($image, 50, 50, 150, 150, $black);
//         return $image;
//     }
// );

// Задание 6: Простой PDF
// renderSimplePdf("Hello World!");

// Задание 7-9: PDF-счёт с таблицей
// $pdf = new InvoicePdf();
// $header = ["Name", "Count", "Price"];
// $products = [
//     ['Banana', '20', '2000'],
//     ['Apple', '150', '100'],
//     ['Milk', '200', '50']
// ];
// $pdf->buildTable($header, $products);

// Задание 10: Тестирование роутинга
// Для тестирования badge: ?type=badge&name=Иван
// Для тестирования invoice: ?type=invoice (или без параметров)