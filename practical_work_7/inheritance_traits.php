<?php

declare(strict_types=1);

/**
 * Файл с демонстрацией наследования, абстрактных классов, интерфейсов и трейтов в PHP
 */

// Задание 1. Наследование

/**
 * Класс товара с базовыми свойствами
 */
class Product
{
    protected string $title = "Продукт";
    protected float $price;

    /**
     * Конструктор класса Product
     *
     * @param string $title Название товара
     * @param float $price Цена товара
     */
    public function __construct(string $title, float $price)
    {
        $this->title = $title;
        $this->price = $price;
    }

    /**
     * Получить название товара
     *
     * @return string Название товара
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}

/**
 * Класс книги, наследующий Product
 */
class Book extends Product
{
    /**
     * Конструктор класса Book
     *
     * @param string $title Название книги
     * @param float $price Цена книги
     * @param string $author Автор книги
     */
    public function __construct(string $title, float $price, private string $author)
    {
        parent::__construct($title, $price);
    }

    /**
     * Получить автора книги
     *
     * @return string Автор книги
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
}

// Задание 2. Абстрактные классы и методы

/**
 * Абстрактный класс урока
 */
abstract class Lesson
{
    /**
     * Получить стоимость урока
     *
     * @return int Стоимость урока
     */
    abstract public function cost(): int;

    /**
     * Получить тип оплаты
     *
     * @return string Описание типа оплаты
     */
    abstract public function chargeType(): string;
}

/**
 * Класс лекции
 */
class Lecture extends Lesson
{
    /**
     * Получить стоимость лекции
     *
     * @return int Стоимость лекции
     */
    public function cost(): int
    {
        return 30;
    }

    /**
     * Получить тип оплаты лекции
     *
     * @return string Описание типа оплаты
     */
    public function chargeType(): string
    {
        return "Фиксированная ставка";
    }
}

/**
 * Класс семинара
 */
class Seminar extends Lesson
{
    /**
     * Получить стоимость семинара
     *
     * @return int Стоимость семинара
     */
    public function cost(): int
    {
        return 100;
    }

    /**
     * Получить тип оплаты семинара
     *
     * @return string Описание типа оплаты
     */
    public function chargeType(): string
    {
        return "Ставка зависит от количества слушателей";
    }
}

// Задание 3 и 4. Интерфейсы

/**
 * Интерфейс для объектов, которые можно бронировать
 */
interface Bookable
{
    /**
     * Забронировать объект
     *
     * @return void
     */
    public function book(): void;
}

/**
 * Интерфейс для объектов с расчетом стоимости
 */
interface Chargeable
{
    /**
     * Рассчитать стоимость
     *
     * @return float Стоимость
     */
    public function calculateFee(): float;
}

/**
 * Класс мастер-класса, реализующий интерфейсы Bookable и Chargeable
 */
class Workshop implements Bookable, Chargeable
{
    /**
     * Забронировать мастер-класс
     *
     * @return void
     */
    public function book(): void
    {
        echo "Это книга";
    }

    /**
     * Рассчитать стоимость мастер-класса
     *
     * @return float Стоимость
     */
    public function calculateFee(): float
    {
        return 999.9;
    }
}

/**
 * Обработать бронирование
 *
 * @param Bookable $item Объект для бронирования
 * @return void
 */
function processBooking(Bookable $item): void
{
    $item->book();
}

// Задание 5 и 6. Трейты

/**
 * Трейт для расчета налогов
 */
trait PriceUtilities
{
    /**
     * Рассчитать налог
     *
     * @param float $price Цена товара
     * @return float Сумма налога
     */
    public function calculateTax(float $price): float
    {
        return $price * 0.2;
    }
}

/**
 * Трейт для генерации идентификаторов
 */
trait IdentityTrait
{
    /**
     * Сгенерировать уникальный идентификатор
     *
     * @return string Уникальный идентификатор
     */
    public function generateId(): string
    {
        return uniqid();
    }
}

/**
 * Класс магазинного товара с использованием трейтов
 */
class ShopProduct
{
    use IdentityTrait;
    use PriceUtilities;

    /**
     * Конструктор класса ShopProduct
     *
     * @param string $title Название товара
     * @param float $price Цена товара
     */
    public function __construct(private string $title, private float $price)
    {
    }

    /**
     * Получить цену с налогом
     *
     * @return float Цена с налогом
     */
    public function getPriceWithTax(): float
    {
        return $this->price + $this->calculateTax($this->price);
    }

    /**
     * Получить идентификатор товара
     *
     * @return string Идентификатор товара
     */
    public function getId(): string
    {
        return $this->generateId();
    }
}

// Задание 7. Конфликт трейтов

/**
 * Трейт для вывода информации (принтер)
 */
trait Printer
{
    /**
     * Вывести информацию (режим принтера)
     *
     * @return void
     */
    public function output(): void
    {
        echo "Printer";
    }
}

/**
 * Трейт для логирования
 */
trait Logger
{
    /**
     * Вывести информацию (режим логгера)
     *
     * @return void
     */
    public function output(): void
    {
        echo "Logger";
    }
}

/**
 * Класс отчета с разрешением конфликта трейтов
 */
class Report
{
    use Printer;
    use Logger {
        Logger::output insteadof Printer;
        Printer::output as print;
    }
}

// Задание 8

/**
 * Трейт для описания объектов
 */
trait Describable
{
    /**
     * Получить описание объекта
     *
     * @return string Описание объекта
     */
    public function describe(): string
    {
        return "Объект: {$this->name}";
    }
}

/**
 * Класс элемента с описанием
 */
class Item
{
    use Describable;

    public string $name;
}

// Задание 9

/**
 * Трейт для валидации с абстрактным методом
 */
trait Validatable
{
    /**
     * Получить правила валидации
     *
     * @return array<string, string> Правила валидации
     */
    abstract public function getRules(): array;

    /**
     * Выполнить валидацию
     *
     * @return bool Результат валидации
     */
    public function validate(): bool
    {
        return true;
    }
}

/**
 * Класс формы пользователя с валидацией
 */
class UserForm
{
    use Validatable;

    /**
     * Получить правила валидации формы
     *
     * @return array<string, string> Правила валидации
     */
    public function getRules(): array
    {
        return ['name' => 'required', 'email' => 'email'];
    }
}

// Задание 10

/**
 * Интерфейс для объектов с медиа-контентом
 */
interface HasMedia
{
    /**
     * Получить длину медиа
     *
     * @return int Длина медиа
     */
    public function getMediaLength(): int;
}

/**
 * Трейт для расчета налогов
 */
trait TaxCalculation
{
    /**
     * Получить сумму налога
     *
     * @return float Сумма налога
     */
    public function getTax(): float
    {
        return $this->price * 0.2;
    }
}

/**
 * Класс книжного товара с медиа
 */
class BookProduct implements HasMedia
{
    use TaxCalculation;

    /**
     * Конструктор класса BookProduct
     *
     * @param string $title Название книги
     * @param float $price Цена книги
     */
    public function __construct(private string $title, private float $price)
    {
    }

    /**
     * Получить количество страниц книги
     *
     * @return int Количество страниц
     */
    public function getMediaLength(): int
    {
        return 300;
    }

    /**
     * Вывести налог на экран
     *
     * @return void
     */
    public function printTax(): void
    {
        echo "Новая цена: {$this->getTax()}";
    }
}

/**
 * Класс CD-диска
 */
class CDProduct implements HasMedia
{
    use TaxCalculation;

    /**
     * Конструктор класса CDProduct
     *
     * @param string $title Название CD
     * @param float $price Цена CD
     */
    public function __construct(private string $title, private float $price)
    {
    }

    /**
     * Получить продолжительность CD в минутах
     *
     * @return int Продолжительность в минутах
     */
    public function getMediaLength(): int
    {
        return 74;
    }

    /**
     * Вывести налог на экран
     *
     * @return void
     */
    public function printTax(): void
    {
        echo "Новая цена: {$this->getTax()}";
    }
}

// Задание 11

/**
 * Абстрактный класс сервиса
 */
abstract class Service
{
    /**
     * Получить длительность сервиса
     *
     * @return int Длительность в минутах
     */
    abstract public function getDuration(): int;

    /**
     * Получить описание сервиса
     *
     * @return string Описание сервиса
     */
    abstract public function getDescription(): string;
}

/**
 * Интерфейс для объектов с расписанием
 */
interface Schedulable
{
    /**
     * Получить расписание
     *
     * @return string Расписание
     */
    public function schedule(): string;
}

/**
 * Трейт для логирования действий
 */
trait Loggers
{
    /**
     * Записать сообщение в лог
     *
     * @param string $msg Сообщение для логирования
     * @return string Отформатированное сообщение
     */
    public function log(string $msg): string
    {
        return "Лог: $msg";
    }
}

/**
 * Класс консультирования
 */
class Consulting extends Service implements Schedulable
{
    use Loggers;

    private bool $isCheckSchedule = false;

    /**
     * Получить длительность консультации
     *
     * @return int Длительность в минутах
     */
    public function getDuration(): int
    {
        return 300;
    }

    /**
     * Получить описание консультации
     *
     * @return string Описание
     */
    public function getDescription(): string
    {
        return "Это консультирование";
    }

    /**
     * Получить расписание консультаций
     *
     * @return string Расписание
     */
    public function schedule(): string
    {
        $this->isCheckSchedule = true;
        return "Пн-Пт: 8:00-20:00 \n Сб-Вс: 10:00-18:00";
    }

    /**
     * Получить лог действий
     *
     * @return string Лог
     */
    public function getLog(): string
    {
        if ($this->isCheckSchedule === true) {
            return $this->log("Просмотр расписания консультирования");
        }
        return $this->log("Пусто");
    }
}

/**
 * Класс обучения
 */
class Training extends Service implements Schedulable
{
    use Loggers;

    private bool $isCheckSchedule = false;

    /**
     * Получить длительность обучения
     *
     * @return int Длительность в минутах
     */
    public function getDuration(): int
    {
        return 180;
    }

    /**
     * Получить описание обучения
     *
     * @return string Описание
     */
    public function getDescription(): string
    {
        return "Это обучение";
    }

    /**
     * Получить расписание обучения
     *
     * @return string Расписание
     */
    public function schedule(): string
    {
        $this->isCheckSchedule = true;
        return "Пн-Пт: 14:00-18:00 \n Сб-Вс: Выходной";
    }

    /**
     * Получить лог действий
     *
     * @return string Лог
     */
    public function getLog(): string
    {
        if ($this->isCheckSchedule === true) {
            return $this->log("Просмотр расписания обучения");
        }
        return $this->log("Пусто");
    }
}

// Задание 1. Простейшее наследование
echo "=== Задание 1. Простейшее наследование ===\n";
$product = new Product("Общий товар", 0.0);
echo $product->getTitle() . "\n";

$bulgakov = new Book("Мастер и Маргарита", 100.5, "Булгаков");
echo "Автор: " . $bulgakov->getAuthor() . "\n\n";

// Задание 2. Абстрактные классы
echo "=== Задание 2. Абстрактные классы ===\n";
$lec = new Lecture();
echo $lec->chargeType() . "\n";
echo "Стоимость: " . $lec->cost() . "\n";

$sem = new Seminar();
echo $sem->chargeType() . "\n";
echo "Стоимость: " . $sem->cost() . "\n\n";

// Задание 3 и 4. Интерфейсы
echo "=== Задание 3 и 4. Интерфейсы ===\n";
$workshop = new Workshop();
echo "Стоимость мастер-класса: " . $workshop->calculateFee() . "\n";
processBooking($workshop);
echo "\n\n";

// Задание 5 и 6. Трейты
echo "=== Задание 5 и 6. Трейты ===\n";
$prod = new ShopProduct("Молоко", 100.0);
echo "Цена с налогом: " . $prod->getPriceWithTax() . "\n";
echo "ID товара: " . $prod->getId() . "\n\n";

// Задание 7. Конфликт трейтов
echo "=== Задание 7. Конфликт трейтов ===\n";
$rep = new Report();
$rep->output();
echo "\n";
$rep->print();
echo "\n\n";

// Задание 8. Трейт с доступом к свойствам хост-класса
echo "=== Задание 8. Трейт с доступом к свойствам хост-класса ===\n";
$item = new Item();
$item->name = "Итем";
echo $item->describe() . "\n\n";

// Задание 9. Абстрактные методы в трейтах
echo "=== Задание 9. Абстрактные методы в трейтах ===\n";
$form = new UserForm();
print_r($form->getRules());
var_dump($form->validate());
echo "\n";

// Задание 10. Комплексное задание: расширение ShopProduct
echo "=== Задание 10. Комплексное задание: расширение ShopProduct ===\n";
$book = new BookProduct("Булгаков", 50.4);
echo "Количество страниц: " . $book->getMediaLength() . "\n";
$book->printTax();
echo "\n";

$cd = new CDProduct("Альбом", 30.0);
echo "Продолжительность CD: " . $cd->getMediaLength() . " минут\n";
$cd->printTax();
echo "\n\n";

// Задание 11. Итоговое домашнее задание
echo "=== Задание 11. Итоговое домашнее задание ===\n";
$cons = new Consulting();
echo "Длительность: " . $cons->getDuration() . " минут\n";
echo $cons->getDescription() . "\n";
echo $cons->schedule() . "\n";
echo $cons->getLog() . "\n\n";

$tran = new Training();
echo "Длительность: " . $tran->getDuration() . " минут\n";
echo $tran->getDescription() . "\n";
echo $tran->getLog() . "\n";
echo $tran->schedule() . "\n";
echo $tran->getLog() . "\n";