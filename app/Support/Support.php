<?php

namespace App\Support;

/**
 * Вспомогательный класс
 */
class Support
{

    public static function getCityCompare($myCity, $city)
    {
        return strcmp(mb_strtolower($myCity), mb_strtolower($city));
    }

    public static function getUserBoldChat($myLogin, $text)
    {
        return preg_replace_callback('#{{(.+?)}}#', function ($item) use ($myLogin) {
            return '<span class="u-bold n-' . self::getCityCompare($myLogin, $item[1]) . '">' . $item[1] . '</span>';
        }, $text);
    }

    public static function getUserBold($text)
    {
        return preg_replace_callback('#\|\|(.+?)\|\|#', function ($item) {
            return '<span class="uk-text-bold">' . $item[1] . '</span>';
        }, $text);
    }

    public static function getUserBoldMy($myLogin, $text)
    {
        return preg_replace_callback('#\|\|(.+?)\|\|#', function ($item) use ($myLogin) {
            return '<span class="u-bold n-' . self::getCityCompare($myLogin, $item[1]) . '">' . $item[1] . '</span>';
        }, $text);
    }

    public static function subText($text, $sub, $end = '...')
    {
        if (isset($text[$sub * 2 + 2])) {
            $text = mb_substr($text, 0, (int)$sub);
            $text = mb_substr($text, 0, mb_strrpos($text, ' '));
            $text .= $end;
        }
        return $text;
    }

    public static function getAccessAvatar($real, $pic, $uVis)
    {
        if (!$uVis) {
            return '/avatars/user_thumb/' . $pic;
        }
        if(((int)$uVis === 3 && !$real) || ($uVis && empty($_SESSION['id'])))  {
            return '/avatars/net-dostupa.jpg';
        }
        return '/avatars/user_thumb/' . $pic;
    }


    /**
     * Проверяет фолайн/нлайн юзера
     *
     * @param $time string Время из ластВиев
     *
     * @return bool Онлайн/офлайн
     */
    public static function getOff($time)
    {
        return (int)((strtotime($time) + 600) > $_SERVER['REQUEST_TIME']);
    }


    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Проверяет, есть ли у юзера вип
     *
     * @param string $vipTime время вип
     *
     * @return int 1 или 0
     */
    public static function getVipStatus($vipTime)
    {
        return (int)(strtotime($vipTime) - $_SERVER['REQUEST_TIME'] >= 0);
    }

    /**
     *
     * @param $txt
     * @return string
     */
    public static function getImagePrivat($txt)
    {
        return preg_replace_callback('#{{(.+?)}}#', function ($item){
            if(is_file(__DIR__ . '/../../' .$item[1])) {
                return '<br><a href="/'.$item[1].'" target="_blank"><img class="img-responsive" style="max-width:200px;max-height:200px" src="/'.$item[1].'" alt="image"></a><br>';
            }
            return '<br><img class="img-responsive" src="/img/noImage.jpg" width="88" height="20" alt="noImage">';
        }, $txt);
    }

    public static function hyperlink($txt) {
        return preg_replace('~(([a-zA-Z]+://)([a-z][a-z0-9_.-]*[a-z]{2,6})([a-zA-Z0-9/*?.!;@_\-+=&%]*[a-zA-Z0-9/]))~i', " <a target='_blank' href='$0'>Ссылка</a> ", $txt);
    }

    public static function getIpGeoBase($ip, array $params = [])
    {
        $result = file_get_contents('http://ipgeobase.ru:7020/geo?json=1&ip=' . $ip);

        if (!$result = mb_convert_encoding($result, 'utf-8', 'windows-1251')) {
            return 'ipgeobase.ru вернул ошибку';
        }

        if ((!$obj = json_decode($result, true)) || json_last_error() !== JSON_ERROR_NONE) {
            return json_last_error_msg();
        }

        if(empty($obj[$ip]) || !empty($obj[$ip]['message'])) {
            return 'Адрес не определен';
        }

        return empty($params) ? $obj[$ip] :  array_intersect_key($obj[$ip], array_flip($params));
    }

    public static function plural($number, $words)
    {
        $tmp = explode('|', $words);

        if (\count($tmp) < 3) {
            return '';
        }

        return $tmp[(($number % 10 === 1) && ($number % 100 !== 11)) ? 0 : ((($number % 10 >= 2) && ($number % 10 <= 4) && (($number % 100 < 10) || ($number % 100 >= 20))) ? 1 : 2)];
    }
}
