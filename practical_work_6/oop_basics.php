<?php

declare(strict_types=1);

class Person
{
    public string $name = '';
    public int $age = 0;

    public function getInfo(): string
    {
        return "Имя: {$this->name}, Возраст: {$this->age} Лет";
    }
}

class Product
{
    public string $title = '';
    protected int $stock = 0;
    private float $price = 0.0;

    public function setPrice(float $newPrice): void
    {
        $this->price = $newPrice;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}

class Greeter
{
    public function __construct(
        private string $greeting = ''
    ) {}

    public function greet(string $name): string
    {
        return "{$this->greeting}, {$name}!";
    }
}

class Book
{
    public function __construct(
        private string $title,
        private string $author,
        private int $year
    ) {}

    public function getInfo(): string
    {
        return "«{$this->title}» ({$this->author}, {$this->year})";
    }
}

class BankAccount
{
    private float $balance = 0.0;

    public function deposit(float $amount): void
    {
        if ($amount > 0) {
            $this->balance += $amount;
        }
    }

    public function withdraw(float $amount): bool
    {
        if ($amount > 0 && $this->balance >= $amount) {
            $this->balance -= $amount;
            return true;
        }
        return false;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}

class ShopProduct
{
    public function __construct(
        private string $title,
        private string $producer,
        private float $price
    ) {}

    public function getSummaryLine(): string
    {
        return "{$this->title} ({$this->producer}) — {$this->price} ₽";
    }
}

class Counter
{
    private static int $count = 0;

    public function __construct()
    {
        self::$count++;
    }

    public static function getCount(): int
    {
        return self::$count;
    }
}

class User
{
    public function __construct(
        private string $email,
        private string $name,
        private \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getInfo(): string
    {
        return "{$this->name} ({$this->email}), зарегистрирован: {$this->createdAt->format('Y-m-d')}";
    }
}

// Демонстрация работы классов и методов

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
?>
