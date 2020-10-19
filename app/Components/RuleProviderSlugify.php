<?php

namespace App\Components;

use Cocur\Slugify\RuleProvider\RuleProviderInterface;

class RuleProviderSlugify implements RuleProviderInterface
{
    protected static array $rulesets = [
        'russian' => [
            'Ъ' => '',
            'Ь' => '',
            'А' => 'A',
            'Б' => 'B',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Э' => 'E',
            'Ф' => 'F',
            'Г' => 'G',
            'Х' => 'H',
            'И' => 'I',
            'Й' => 'Y',
            'Я' => 'Ya',
            'Ю' => 'Yu',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Ш' => 'Sh',
            'Щ' => 'Shch',
            'Т' => 'T',
            'У' => 'U',
            'В' => 'V',
            'Ы' => 'Y',
            'З' => 'Z',
            'Ж' => 'Zh',
            'ъ' => '',
            'ь' => '',
            'а' => 'a',
            'б' => 'b',
            'ц' => 'c',
            'ч' => 'ch',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'э' => 'e',
            'ф' => 'f',
            'г' => 'g',
            'х' => 'h',
            'и' => 'i',
            'й' => 'y',
            'я' => 'ya',
            'ю' => 'yu',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'ш' => 'sh',
            'щ' => 'shch',
            'т' => 't',
            'у' => 'u',
            'в' => 'v',
            'ы' => 'y',
            'з' => 'z',
            'ж' => 'zh',
        ],
    ];

    public function getRules($ruleset)
    {
        return static::$rulesets[$ruleset];
    }
}