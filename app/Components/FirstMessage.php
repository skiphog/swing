<?php

namespace App\Components;

class FirstMessage
{
    protected static string $text = 'Привет. Вы %1$s интересны. Посмотрите, пожалуйста, %2$s анкету и, если %3$s под ваши параметры поиска, то дайте знать :)';

    protected static array $arr = [
        0 => ['мне', 'мою', 'я подхожу'],
        1 => ['мне', 'мою', 'я подхожу'],
        2 => ['мне', 'мою', 'я подхожу'],
        3 => ['нам', 'нашу', 'мы подходим'],
        4 => ['мне', 'мою', 'я подхожу'],
    ];

    public static function getMessage($gender)
    {
        return vsprintf(self::$text, self::$arr[$gender]);
    }
}
