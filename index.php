<?php

declare(strict_types = 1);

echo 'Дата начала работы сотрудника' . PHP_EOL;
echo '-----------------------------' . PHP_EOL;
echo 'Введите год: ' . PHP_EOL;
$startYear = trim(fgets(STDIN));

while (!is_numeric($startYear) || ($startYear < 1970)) {
    echo 'Вы неправильно ввели год. Введите еще раз: ' . PHP_EOL;
    $startYear = trim(fgets(STDIN));
}

echo 'Введите месяц: ' . PHP_EOL;
$startMonth = trim(fgets(STDIN));

while (!is_numeric($startMonth) || ($startMonth < 1) || ($startMonth > 12)) {
    echo 'Вы неправильно ввели месяц. Введите еще раз: ' . PHP_EOL;
    $startMonth = trim(fgets(STDIN));
}


echo 'Продолжительность работы (мес.): ' . PHP_EOL;
$duration = trim(fgets(STDIN));

while (!is_numeric($duration) || ($duration < 1)) {
    echo 'Вы неправильно ввели продолжительность работы. Введите еще раз: ' . PHP_EOL;
    $duration = trim(fgets(STDIN));
}


$i = ($duration > 0) ? 1 : 0;

$endMonth = ($startMonth + $duration) % 12;
$endYear = $startYear + ($startMonth + $duration - $endMonth) / 12;

// echo 'Дата начала работы : ' . $startYear . '-' . $startMonth . PHP_EOL;
// echo 'Дата окончания работы : ' . $endYear . '-' . $endMonth . PHP_EOL;


$startDate = DateTime::createFromFormat('Y-n-d H:i:s', $startYear . '-' . $startMonth . '-01 ' . '00:00:00');
$endDate = DateTime::createFromFormat('Y-n-d H:i:s', $endYear . '-' . $endMonth . '-01' . '00:00:00');

$startDayOfWeek = $startDate->format('N');

$skipHolidays = $startDayOfWeek > 5 ? 8 - $startDayOfWeek : 0;
$startDate->add(new DateInterval('P' . $skipHolidays . 'D'));

$interval = DateInterval::createFromDateString('1 day');
$workDuration = new DatePeriod($startDate, $interval, $endDate);


foreach($workDuration as $date) {
    if ($i % 3 === 1) {
        echo "\033[32m {$date->format('d.m.Y')} \033[0m" . PHP_EOL;
    } else {
        echo "\033[31m {$date->format('d.m.Y')} \033[0m" . PHP_EOL;
    }
    $i++;
}


?>