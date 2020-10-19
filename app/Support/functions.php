<?php

use App\Arrays\Smiles;
use App\Arrays\Genders;
use App\Components\Guard;
use App\Components\SxGeo;
use Cocur\Slugify\Slugify;
use App\Models\Users\Auth;
use App\Components\Parse\All;
use App\Components\Parse\Clear;
use App\Components\Parse\NoSession;

/**
 * @return Auth
 */
function auth()
{
    return app(Guard::class);
}

/**
 * @param string $string
 *
 * @return string
 */
function hyperlink($string)
{
    /** @noinspection HtmlUnknownTarget */
    return preg_replace(
        '!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;/=]+)!iu',
        '<a href="$1" target="_blank" rel="noopener noreferrer">Ссылка</a>',
        $string
    );
}

/**
 * @param $text
 *
 * @return string
 */
function smile($text)
{
    return strtr($text, Smiles::$smiles);
}

/**
 * @param string $city1
 * @param string $city2
 *
 * @return int
 */
function cityCompare($city1, $city2)
{
    return (int)(mb_strtolower($city1) === mb_strtolower($city2));
}

/**
 * Если регионы равны, то возвращается 1, иначе 0
 *
 * @param int $region_id
 * @param int $region_id_2
 *
 * @return int
 */
function regionComp($region_id, $region_id_2): int
{
    return (int)((int)$region_id === (int)$region_id_2);
}

/**
 * @param int $number
 *
 * @return string
 */
function formatNumber($number)
{
    return number_format($number, 0, '', ' ');
}

/**
 * Получить иконку юзера на основании gender
 *
 * @param int $gender
 * @param int $width
 * @param int $height
 *
 * @return string
 */
function genderIcon($gender, $width = 20, $height = 20)
{
    return '<img class="user-icon" src="' . Genders::$icons[$gender] . '" width="' . $width . '" height="' . $height . '" alt="gender">';
}

/**
 * Получить количество прошедших лет
 *
 * @param string $date SQL format Date [2020-10-03 16:00:00]
 *
 * @return string [39 лет]
 */
function dateAge(string $date): string
{
    return swDate($date)->age();
}

/**
 * @param string $date SQL format Date [2020-10-05 14:08:00]
 *
 * @return string [2 минуты назад]
 */
function dateHumans(string $date): string
{
    return swDate($date)->humans();
}

/**
 * Parsing BBcode в зависимости от зарегистрированный пользователь или нет
 *
 * @param string $string
 *
 * @return string
 */
function parseBB(string $string): string
{
    static $parser;

    if (null === $parser) {
        $parser = app(auth()->isUser() ? All::class : NoSession::class);
    }

    return $parser->parse($string);
}

/**
 * Clear BBcode для вывода текста без html tags
 *
 * @param string $string
 *
 * @return string
 */
function clearBB(string $string): string
{
    return app(Clear::class)->parse($string);
}

/**
 * @param string $text
 *
 * @return string
 */
function imgart($text)
{
    return preg_replace_callback('~{{(.+?)}}~', static function ($matches) {
        return '<img class="ug-imgart" src="/imgart/' . e($matches[1]) . '">';
    }, $text);
}

/**
 * @param $text
 *
 * @return mixed|null|string
 */
function nickart($text)
{
    $login = auth()->login;

    return preg_replace_callback('#\|\|(.+?)\|\|#', static function ($value) use (&$login) {
        $color = $login === $value[1] ? '#F00' : '#747474';

        return '<b style="color:' . $color . '">' . e($value[1]) . '</b>';
    }, $text);
}

/**
 * @param $string
 *
 * @return string
 */
function html($string)
{
    return e($string);
}

/**
 * Доступ к avatar пользователя
 *
 * @param Auth   $auth
 * @param string $pic
 * @param int    $uVis
 *
 * @return string
 */
function avatar(Auth $auth, string $pic, int $uVis)
{
    if (0 === $uVis || ((2 === $uVis || 1 === $uVis) && $auth->isUser()) || (3 === $uVis && $auth->isReal())) {
        return '/avatars/user_thumb/' . $pic;
    }

    if (2 === $uVis) {
        return '/img/avatars/user.jpg';
    }

    return '/img/avatars/real.jpg';
}

function slugify(string $string)
{
    /** @var Slugify $slugify */
    $slugify = app(Slugify::class);

    return $slugify->slugify($string);
}

/**
 * @return SxGeo;
 */
function geo(): SxGeo
{
    return app(SxGeo::class);
}

/**
 * @param string $ip
 *
 * @return array|false
 */
function geoCity(string $ip)
{
    return geo()->getCityFull($ip);
}

/**
 * @param     $extension
 * @param int $length
 *
 * @return string
 */
function randomFileName($extension, int $length = 32): string
{
    return randomString($length) . ".{$extension}";
}

function generatorFromFile(string $path)
{
    try {
        if (!is_readable($path)) {
            throw new InvalidArgumentException('Файла не существует');
        }

        if (!$resource = @fopen($path, 'rb')) {
            throw new InvalidArgumentException('Невозможно прочитать файл');
        }

        while (false !== $line = fgets($resource)) {
            yield $line;
        }
    } catch (Exception $e) {
        return;
    } finally {
        if (!empty($resource) && is_resource($resource)) {
            fclose($resource);
        }
    }
}
