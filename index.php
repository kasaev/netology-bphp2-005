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
$startYear = intval($startYear);

echo 'Введите месяц: ' . PHP_EOL;
$startMonth = trim(fgets(STDIN));


while (!is_numeric($startMonth) || ($startMonth < 1) || ($startMonth > 12)) {
    echo 'Вы неправильно ввели месяц. Введите еще раз: ' . PHP_EOL;
    $startMonth = trim(fgets(STDIN));
}
$startMonth = intval($startMonth);

echo 'Продолжительность работы (мес.): ' . PHP_EOL;
$duration = trim(fgets(STDIN));

while (!is_numeric($duration) || ($duration < 1)) {
    echo 'Вы неправильно ввели продолжительность работы. Введите еще раз: ' . PHP_EOL;
    $duration = trim(fgets(STDIN));
}
$duration = intval($duration);

$endMonth = ($startMonth + $duration) % 12;
$endYear = $startYear + ($startMonth + $duration - $endMonth) / 12;

$startDate = DateTimeImmutable::createFromFormat('Y-n-d H:i:s', $startYear . '-' . $startMonth . '-01 ' . '00:00:00');
$endDate = DateTimeImmutable::createFromFormat('Y-n-d H:i:s', $endYear . '-' . $endMonth . '-01' . '00:00:00');

displaySchedule($startDate, $endDate);

function displaySchedule(DateTimeImmutable $startDate, DateTimeImmutable $endDate) : void {
    $interval = DateInterval::createFromDateString('1 day');
    $workDuration = new DatePeriod($startDate, $interval, $endDate);

    $daysCounter = 0;
    $workCounter = 1;
    $dateOffset = 0;

    foreach($workDuration as $date) {

        if ($daysCounter === 0 || intval($date->format('z')) === 0) {
            echo PHP_EOL;
            echo str_pad($date->format('Y'), 20, " ", STR_PAD_BOTH) . PHP_EOL;
            echo str_pad("", 20, "-", STR_PAD_BOTH);
        }
        if (intval($date->format('j')) === 1) {
            echo PHP_EOL;
            echo PHP_EOL;
            echo mb_str_pad(printMonth($date->format('n')), 20, " ", STR_PAD_BOTH);
            echo PHP_EOL;
            echo printDayNames();
            $dateOffset = $date->format('N') - 1;
            echo str_pad("", $dateOffset * 3, " ", STR_PAD_LEFT); 
        }

        $dayString = str_pad($date->format('j'), 2, " ", STR_PAD_LEFT);

        if (intval($date->format('N')) === 6 || intval($date->format('N')) === 7) {
            echo "\033[31m{$dayString} \033[0m";
            $workCounter = 0;
        } elseif ($workCounter % 3 == 1) {
            echo "\033[32m{$dayString} \033[0m";
        } else {
            echo "\033[31m{$dayString} \033[0m";
        }

        if (intval($date->format('N')) === 7) {
            echo PHP_EOL;
        }

        $workCounter++;
        $daysCounter++;
    }

    echo PHP_EOL;

}

function printMonth(string $month = '') : string {
    $month = is_numeric($month) ? intval($month) : 0;

    $monthName = [
        'Январь', 
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь',
    ];

    if ($month > 0) {
        return $monthName[$month - 1];
    }
}

function printDayNames() : string {
    $days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
    $daysString = implode(" ", $days) . PHP_EOL;
    return $daysString;
}