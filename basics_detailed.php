<?php

function classifyAge(int $age): string 
{
    if ($age < 12) 
    {
        return "Ребёнок";
    }
    elseif ($age >= 12 && $age <= 17)
    {
        return "Подросток";
    }
    else
    {
        return "Взрослый";
    }
}

function convertCelsiusToFahrenheit(float $celsius): float
{
    return $celsius * 9 / 5 + 32;
}

function getUserName(int|string $id): string|false
{
    if (is_int($id) && $id === 1)
    {
        return "Администратор";
    }
    elseif (is_string($id) && $id === "guest")
    {
        return "Гость";
    }
    else
    {
        return false;
    }
}

function classifyAgeWithMatch(int $age): string 
{
    return match (true) {
        $age < 12 => "Ребёнок",
        $age >= 12 && $age <= 17 => "Подросток",
        $age >= 18 => "Взрослый",
    };
}

function getCities(): array
{
    return ["Москва", "Санкт-Петербург", "Екатеринбург", "Омск", "Рязань"];
}

function getFizzOrBuzz(int $i): void 
{
    if ($i % 3 == 0 && $i % 5 == 0) 
    {
        echo "FizzBuzz ";
    }
    elseif ($i % 3 == 0) 
    {
        echo "Fizz ";
    }
    elseif ($i % 5 == 0) 
    {
        echo "Buzz ";
    }
    else
    {
        echo $i . " ";
    }
}

echo "<!DOCTYPE html>\n";
echo "<html lang='ru'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>Основы PHP - Практическая работа</title>\n";
echo "    <style>\n";
echo "        body { font-family: Arial, sans-serif; max-width: 900px; margin: 20px auto; padding: 20px; }\n";
echo "        h2 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 5px; }\n";
echo "        .task { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }\n";
echo "        ul { list-style-type: disc; padding-left: 20px; }\n";
echo "        .result { background: #e8f5e9; padding: 10px; margin: 10px 0; border-left: 4px solid #4CAF50; }\n";
echo "    </style>\n";
echo "</head>\n";
echo "<body>\n";
echo "    <h1>Практическая работа: Основы PHP</h1>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 1: Классификация возраста</h2>\n";
echo "        <div class='result'>\n";
echo "            Возраст 8: " . htmlspecialchars(classifyAge(8)) . "<br>\n";
echo "            Возраст 15: " . htmlspecialchars(classifyAge(15)) . "<br>\n";
echo "            Возраст 25: " . htmlspecialchars(classifyAge(25)) . "\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 2: Список городов</h2>\n";
echo "        <ul>\n";


foreach (getCities() as $city)
{
    echo "            <li>" . htmlspecialchars($city) . "</li>\n";
}

echo "        </ul>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 3: FizzBuzz</h2>\n";
echo "        <div class='result'>\n";
echo "            ";

for ($i = 1; $i <= 100; $i++) 
{
    getFizzOrBuzz($i);
}

echo "\n        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 4: Конвертер температур</h2>\n";
echo "        <div class='result'>\n";
echo "            0°C = " . convertCelsiusToFahrenheit(0) . "°F<br>\n";
echo "            25°C = " . convertCelsiusToFahrenheit(25) . "°F<br>\n";
echo "            -10°C = " . convertCelsiusToFahrenheit(-10) . "°F<br>\n";
echo "            100°C = " . convertCelsiusToFahrenheit(100) . "°F\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 5: Union Types</h2>\n";
echo "        <div class='result'>\n";

$result1 = getUserName(1);
echo "            ID 1: " . ($result1 === false ? "false" : htmlspecialchars($result1)) . "<br>\n";

$result2 = getUserName("guest");
echo "            ID 'guest': " . ($result2 === false ? "false" : htmlspecialchars($result2)) . "<br>\n";

$result3 = getUserName(999);
echo "            ID 999: " . ($result3 === false ? "false" : htmlspecialchars($result3)) . "\n";

echo "        </div>\n";
echo "    </div>\n\n";

echo "    <div class='task'>\n";
echo "        <h2>Задание 6: Match Expression</h2>\n";
echo "        <div class='result'>\n";
echo "            Возраст 8: " . htmlspecialchars(classifyAgeWithMatch(8)) . "<br>\n";
echo "            Возраст 15: " . htmlspecialchars(classifyAgeWithMatch(15)) . "<br>\n";
echo "            Возраст 25: " . htmlspecialchars(classifyAgeWithMatch(25)) . "\n";
echo "        </div>\n";
echo "    </div>\n\n";

echo "</body>\n";
echo "</html>";
?>
