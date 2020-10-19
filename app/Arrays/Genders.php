<?php

namespace App\Arrays;

/**
 * Class Gender
 *
 * @package App\Arrays
 */
class Genders
{
    public static array $gender = [
        1 => 'Мужчина',
        2 => 'Девушка',
        3 => 'Пара M+Ж',
        4 => 'Транс',
    ];

    public static $sgender = [
        1 => 'Мужчину',
        2 => 'Девушку',
        3 => 'Пару М+Ж',
        4 => 'Транса',
    ];

    public static $old = [
        1 => 'Был',
        2 => 'Была',
        3 => 'Были',
        4 => 'Был',
    ];

    public static $search = [
        1 => 'Я ищу',
        2 => 'Я ищу',
        3 => 'Мы ищем',
        4 => 'Я ищу',
    ];

    public static $icons = [
        1 => '/img/icons/man.svg',
        2 => '/img/icons/woman.svg',
        3 => '/img/icons/couple.svg',
        4 => '/img/icons/shemale.svg',
    ];

    public static $icons_year = [
        1 => '/img/NewYear/g1.png',
        2 => '/img/NewYear/g2.png',
        3 => '/img/NewYear/g3.png',
        4 => '/img/NewYear/g4.png',
    ];
}
