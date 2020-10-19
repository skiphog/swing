<?php

namespace App\Arrays;

class Profile
{
    /**
     * Анкета доступна
     */
    public static $visibility = [
        0 => 'Для всех',
        2 => 'Для пользователей с анкетами',
        3 => 'Для владельцев статуса реальности ',
    ];

    /**
     * Семейное положение
     */
    public static $marstat = [
        0 => ' Не выбрано',
        1 => 'Холост(Не замужем)',
        2 => 'Разведённый(ая)',
        3 => 'В разлуке',
        4 => 'Вдовец(Вдова)',
        5 => 'Женат(Замужем)',
    ];

    /**
     * Наличие детей
     */
    public static $child = [
        0 => ' Не выбрано',
        1 => 'Нет',
        2 => '1',
        3 => '2',
        4 => '3 или более',
        5 => 'Взрослые, живут отдельно',
    ];

    /**
     * Цвет волос
     */
    public static $hcolor = [
        0  => ' Не выбрано',
        1  => 'Чёрный',
        2  => 'Блондин',
        3  => 'Коричневый',
        4  => 'Брюнет',
        5  => 'Каштан',
        6  => 'Тёмнорыжий',
        7  => 'Крашенный блондин',
        8  => 'Золотой',
        9  => 'Красный',
        10 => 'Серый',
        11 => 'Белый',
        12 => 'Седой',
        13 => 'Лысый',
        14 => 'Другое',
    ];

    /**
     * Предохранение
     */
    public static $ecolor = [
        0 => ' Не выбрано',
        1 => 'Всегда с презервативом',
        2 => 'Без презерватива с проверенными партнёрами',
        3 => 'Предпочитаем без презерватива',
        4 => 'Как получится',
        5 => 'Не важно',
    ];

    /**
     * Ориентация
     */
    public static $etnicity = [
        0 => ' Не выбрано',
        1 => 'Гетеро',
        2 => 'Би',
        3 => 'Она-би, он-гетеро ',
        4 => 'Она-гетеро, Он-би',
    ];

    /**
     * Место для встреч
     */
    public static $religion = [
        0 => ' Не выбрано',
        1 => 'Нет',
        2 => 'Иногда бывает',
        3 => 'Есть своя квартира',
        4 => 'Организую встречу на нейтральной территории',
        5 => 'Не важно',
    ];

    /**
     * Отношение к курению
     */
    public static $smoke = [
        0 => ' Не выбрано',
        1 => 'Не курю',
        2 => 'Курю',
        3 => 'Курю иногда',
        4 => 'Категорично против',
    ];

    /**
     * Отношение к алкоголю
     */
    public static $drink = [
        0 => ' Не выбрано',
        1 => 'Выпиваю регулярно',
        2 => 'Очень редко',
        3 => 'Совсем не пью',
        4 => 'Категорично против',
        5 => 'Поддержу компанию',
    ];

    /**
     * Образование
     */
    public static $education = [
        0 => ' Не выбрано',
        1 => 'Среднее',
        2 => 'Среднее специальное',
        3 => 'Незаконченное высшее',
        4 => 'Высшее',
        5 => 'Другое',
    ];
}
