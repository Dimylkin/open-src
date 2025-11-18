<?php

declare(strict_types=1);

/**
 * Файл с демонстрацией основ ООП в PHP: классы, свойства, методы, конструкторы
 */

/**
 * Класс Person для представления человека
 */
class Person
{
    public string $name = '';
    public int $age = 0;

    /**
     * Получить информацию о человеке
     *
     * @return string Информация о человеке
     */
    public function getInfo(): string
    {
        return "Имя: {$this->name}, Возраст: {$this->age} Лет";
    }
}

/**
 * Класс Product для представления товара
 */
class Product
{
    public string $title = '';
    protected int $stock = 0;
    private float $price = 0.0;

    /**
     * Установить цену товара
     *
     * @param float $newPrice Новая цена
     * @return void
     */
    public function setPrice(float $newPrice): void
    {
        $this->price = $newPrice;
    }

    /**
     * Получить цену товара
     *
     * @return float Цена товара
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}

/**
 * Класс Greeter для приветствий
 */
class Greeter
{
    /**
     * Конструктор класса Greeter
     *
     * @param string $greeting Текст приветствия
     */
    public function __construct(
        private string $greeting = ''
    ) {
    }

    /**
     * Поприветствовать человека
     *
     * @param string $name Имя человека
     * @return string Приветствие
     */
    public function greet(string $name): string
    {
        return "{$this->greeting}, {$name}!";
    }
}

/**
 * Класс Book для представления книги
 */
class Book
{
    /**
     * Конструктор класса Book
     *
     * @param string $title Название книги
     * @param string $author Автор книги
     * @param int $year Год издания
     */
    public function __construct(
        private string $title,
        private string $author,
        private int $year
    ) {
    }

    /**
     * Получить информацию о книге
     *
     * @return string Информация о книге
     */
    public function getInfo(): string
    {
        return "«{$this->title}» ({$this->author}, {$this->year})";
    }
}

/**
 * Класс BankAccount для представления банковского счёта
 */
class BankAccount
{
    private float $balance = 0.0;

    /**
     * Пополнить счёт
     *
     * @param float $amount Сумма пополнения
     * @return void
     */
    public function deposit(float $amount): void
    {
        if ($amount > 0) {
            $this->balance += $amount;
        }
    }

    /**
     * Снять деньги со счёта
     *
     * @param float $amount Сумма для снятия
     * @return bool true, если операция успешна
     */
    public function withdraw(float $amount): bool
    {
        if ($amount > 0 && $this->balance >= $amount) {
            $this->balance -= $amount;
            return true;
        }
        return false;
    }

    /**
     * Получить текущий баланс
     *
     * @return float Баланс счёта
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
}

/**
 * Класс ShopProduct для представления товара в магазине
 */
class ShopProduct
{
    /**
     * Конструктор класса ShopProduct
     *
     * @param string $title Название товара
     * @param string $producer Производитель
     * @param float $price Цена товара
     */
    public function __construct(
        private string $title,
        private string $producer,
        private float $price
    ) {
    }

    /**
     * Получить краткую информацию о товаре
     *
     * @return string Информация о товаре
     */
    public function getSummaryLine(): string
    {
        return "{$this->title} ({$this->producer}) — {$this->price} ₽";
    }
}

/**
 * Класс Counter для подсчёта созданных объектов
 */
class Counter
{
    private static int $count = 0;

    /**
     * Конструктор класса Counter
     */
    public function __construct()
    {
        self::$count++;
    }

    /**
     * Получить количество созданных объектов
     *
     * @return int Количество объектов
     */
    public static function getCount(): int
    {
        return self::$count;
    }
}

/**
 * Класс User для представления пользователя
 */
class User
{
    /**
     * Конструктор класса User
     *
     * @param string $email Email пользователя
     * @param string $name Имя пользователя
     * @param \DateTimeImmutable $createdAt Дата регистрации
     */
    public function __construct(
        private string $email,
        private string $name,
        private \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {
    }

    /**
     * Получить email пользователя
     *
     * @return string Email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Получить информацию о пользователе
     *
     * @return string Информация о пользователе
     */
    public function getInfo(): string
    {
        return "{$this->name} ({$this->email}), зарегистрирован: {$this->createdAt->format('Y-m-d')}";
    }
}

echo "=== Задание 1: Класс Person ===\n";
echo "Создание двух объектов класса Person и вывод их информации\n";
echo "<br>\n";

$person1 = new Person();
$person1->name = "Аня";
$person1->age = 18;
echo "Person 1: " . $person1->getInfo() . "\n";
echo "<br>\n";

$person2 = new Person();
$person2->name = "Вася";
$person2->age = 5;
echo "Person 2: " . $person2->getInfo() . "\n";
echo "<br>\n";

echo "=== Задание 2: Класс Product ===\n";
echo "Создание объекта Product, работа с методом setPrice() и getPrice()\n";
echo "<br>\n";

$milk = new Product();
$milk->title = "Молоко";
echo "Начальная цена: " . $milk->getPrice() . " ₽\n";
$milk->setPrice(99.99);
echo "Новая цена: " . $milk->getPrice() . " ₽\n";
echo "<br>\n";

echo "=== Задание 3: Класс Greeter ===\n";
echo "Создание объекта Greeter с приветствием и вывод приветствия\n";
echo "<br>\n";

$rus = new Greeter("Привет");
echo "Greeter: " . $rus->greet("Дима") . "\n";
echo "<br>\n";

echo "=== Задание 4: Класс Book ===\n";
echo "Создание объекта Book с использованием promoted properties\n";
echo "<br>\n";

$book = new Book("Мастер и Маргарита", "Булгаков", 1980);
echo "Book: " . $book->getInfo() . "\n";
echo "<br>\n";

echo "=== Задание 5: Класс BankAccount ===\n";
echo "Создание счёта, пополнение, снятие средств и проверка баланса\n";
echo "<br>\n";

$myAcc = new BankAccount();
echo "Начальный баланс: " . $myAcc->getBalance() . " ₽\n";
$myAcc->deposit(100.0);
echo "После пополнения: " . $myAcc->getBalance() . " ₽\n";
$myAcc->withdraw(10.0);
echo "После снятия: " . $myAcc->getBalance() . " ₽\n";
echo "<br>\n";

echo "=== Задание 6: Класс ShopProduct ===\n";
echo "Создание товара и вывод его описания\n";
echo "<br>\n";

$product = new ShopProduct("Кофе", "Lavazza", 399.0);
echo "ShopProduct: " . $product->getSummaryLine() . "\n";
echo "<br>\n";

echo "=== Задание 7: Класс Counter ===\n";
echo "Демонстрация работы статического счётчика объектов\n";
echo "<br>\n";

echo "Счётчик до создания объектов: " . Counter::getCount() . "\n";
$counterA = new Counter();
$counterB = new Counter();
echo "Счётчик после создания 2 объектов: " . Counter::getCount() . "\n";
echo "<br>\n";

echo "=== Задание 8: Класс User ===\n";
echo "Создание пользователей с автоматической датой регистрации\n";
echo "<br>\n";

$user1 = new User("ivan@example.com", "Иван");
echo "User 1: " . $user1->getInfo() . "\n";
echo "<br>\n";

$user2 = new User("anna@example.com", "Анна", new \DateTimeImmutable("2025-04-05"));
echo "User 2: " . $user2->getInfo() . "\n";
echo "<br>\n";