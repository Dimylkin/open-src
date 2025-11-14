<?php

/**
 * Проверяет, является ли число простым
 * 
 * @param int $n Число для проверки
 * @return bool Возвращает true, если число простое, иначе false
 */
function isPrime(int $n): bool
{
    if ($n <= 1) {
        return false;
    }
    
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i == 0) {
            return false;
        }
    }
    
    return true;
}

/**
 * Возвращает текстовое представление результата проверки числа на простоту
 * 
 * @param int $n Число для проверки
 * @return string "Простое число" или "Не простое число"
 */
function getOutput(int $n): string
{
    $answer = isPrime($n);

    if ($answer == true) {
        return "Простое число";
    } else {
        return "Не простое число";
    }
}

/**
 * Вычисляет n-е число последовательности Фибоначчи
 * 
 * @param int $n Порядковый номер числа в последовательности
 * @return int n-е число Фибоначчи
 */
function fibonacci(int $n): int
{
    if ($n == 1) {
        return 1;
    } elseif ($n == 0) {
        return 0;
    }

    return fibonacci($n - 1) + fibonacci($n - 2);
}

/**
 * Форматирует номер телефона в читаемый вид
 * 
 * @param string $phone Номер телефона (11 цифр)
 * @return string Отформатированный номер или сообщение об ошибке
 */
function formatPhone(string $phone): string
{
    if (strlen($phone) == 11) {
        $first_three = substr($phone, 1, 3);
        $second_three = substr($phone, 4, 3);
        
        $first_twice = substr($phone, 7, 2);
        $second_twice = substr($phone, 9, 2);
        
        $correct_number = "+7 ($first_three) $second_three-$first_twice-$second_twice";
        return $correct_number;
    }
    
    return "Неверный формат номера";
}

/**
 * Фильтрует массив чисел, оставляя только чётные элементы
 * 
 * @param array $numbers Массив целых чисел
 * @return string Строка с чётными числами через запятую или сообщение об отсутствии
 */
$filter = function (array $numbers): string {
    $array = array_filter($numbers, function ($n) {
        return $n % 2 == 0;
    });
    
    if (strlen(implode(",", $array)) != 0) {
        return implode(",", $array);
    } else {
        return "Четных чисел нет";
    }
};

/**
 * Вычисляет факториал числа с кэшированием результатов
 * 
 * Использует статическую переменную для хранения
 * ранее вычисленных значений, что ускоряет повторные вызовы
 * 
 * @param int $n Число для вычисления факториала
 * @return int Факториал числа n
 */
function memoizedFactorial(int $n): int 
{
    static $cache = [];
    
    if (isset($cache[$n])) {
        return $cache[$n];
    }
    
    if ($n <= 1) {
        return $cache[$n] = 1;
    }
    
    return $cache[$n] = $n * memoizedFactorial($n - 1);
}

/**
 * Создаёт строковое представление пользователя
 * 
 * Поддерживает именованные аргументы для гибкого вызова
 * 
 * @param string $name Имя пользователя
 * @param string $email Электронная почта
 * @param int $age Возраст пользователя
 * @param bool $isActive Статус активности (по умолчанию true)
 * @return string Строка с данными пользователя
 */
function createUser(string $name, string $email, int $age, bool $isActive = true): string
{
    if ($isActive == true)
    {
        return "Имя: $name, Почта: $email, Возраст: $age, Активен: Да";
    }
    else
    {
        return "Имя: $name, Почта: $email, Возраст: $age, Активен: Нет";
    }
}

/**
 * Создаёт генератор уникального счётчика
 * 
 * Возвращает замыкание, которое при каждом вызове
 * увеличивает внутренний счётчик на 1 и возвращает его значение.
 * Использует передачу переменной по ссылке
 * 
 * @return callable Функция-счётчик
 */
function makeCounter(): callable
{
    $number = 0;
    $ret = function() use (&$number): int
    {
        $number++;
        return $number;
    };

    return $ret;
}

$counter = makeCounter();


echo "<!DOCTYPE html>\n";
echo "<html lang='ru'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>PHP - Практическая работа </title>\n";
echo "    <style>\n";
echo "        body { font-family: Arial, sans-serif; max-width: 900px; margin: 20px auto; padding: 20px; }\n";
echo "        h2 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 5px; }\n";
echo "        .task { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }\n";
echo "        ul { list-style-type: disc; padding-left: 20px; }\n";
echo "        .result { background: #e8f5e9; padding: 10px; margin: 10px 0; border-left: 4px solid #4CAF50; }\n";
echo "    </style>\n";
echo "</head>\n";
echo "<body>\n";
echo "    <h1>Практическая работа: Функции и область видимости</h1>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 1: Простые числа</h2>\n";
echo "        <div class='result'>\n";
echo "            Число 1: " . htmlspecialchars(getOutput(1)) . "<br>\n";
echo "            Число 7: " . htmlspecialchars(getOutput(7)) . "<br>\n";
echo "            Число 5: " . htmlspecialchars(getOutput(5)) . "\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 2: Числа Фибоначчи</h2>\n";
echo "        <div class='result'>\n";
echo "            2 Последовательности: " . htmlspecialchars(fibonacci(2)) . "<br>\n";
echo "            11 Последовательности: " . htmlspecialchars(fibonacci(11)) . "<br>\n";
echo "            23 Последовательности: " . htmlspecialchars(fibonacci(23)) . "\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 3: Форматирование номера телефона</h2>\n";
echo "        <div class='result'>\n";
echo "            Номер: 89998887766: " . htmlspecialchars(formatPhone("89998887766")) . "<br>\n";
echo "            Номер: 89123456789: " . htmlspecialchars(formatPhone("89123456789")) . "<br>\n";
echo "            Номер: 911: " . htmlspecialchars(formatPhone("911")) . "\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 4: Фильтрация с анонимной функцией</h2>\n"; 
echo "        <div class='result'>\n";
echo "            Массив: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]: " . htmlspecialchars($filter([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])) . "<br>\n";
echo "            Массив: [1]: " . htmlspecialchars($filter([1])) . "<br>\n";
echo "            Массив: [1, 3, 5, 7, 9]: " . htmlspecialchars($filter([1, 3, 5, 7, 9])) . "\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 5: Кэширование с помощью статической переменной</h2>\n";
echo "        <div class='result'>\n";
echo "            Факториал 1: " . htmlspecialchars(memoizedFactorial(1)) . "<br>\n";
echo "            Факториал 5: " . htmlspecialchars(memoizedFactorial(5)) . "<br>\n";
echo "            Факториал 15: " . htmlspecialchars(memoizedFactorial(10)) . "\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 6: Гибкий вызов с именованными аргументами</h2>\n";
echo "        <div class='result'>\n";
echo "            1 человек: " . htmlspecialchars(createUser(isActive: false, email: "vas@mail.ru", name: "Вася", age: 99)) . "<br>\n";
echo "            2 человек: " . htmlspecialchars(createUser(isActive: true, name: "Аня", email: "an@gmail.com", age: 8)) . "<br>\n";
echo "            3 человек: " . htmlspecialchars(createUser(isActive: false, age: 18, email: "gen@icloud.com", name: "Геннадий")) . "\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 7: Замыкание и передача по ссылке</h2>\n";
echo "        <div class='result'>\n";
echo "            1 вызов: " . htmlspecialchars($counter()) . "<br>\n";
echo "            2 вызов: " . htmlspecialchars($counter()) . "<br>\n";
echo "            3 вызов: " . htmlspecialchars($counter()) . "\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "</body>\n";
echo "</html>";
?>