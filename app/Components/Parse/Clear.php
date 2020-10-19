<?php

namespace App\Components\Parse;

/**
 * Class Clear
 */
class Clear extends GParse
{
    /**
     * @var string[]
     */
    protected array $replace = [
        '[b]'      => '',
        '[/b]'     => '',
        '[u]'      => '',
        '[/u]'     => '',
        '[i]'      => '',
        '[/i]'     => '',
        '[s]'      => '',
        '[/s]'     => '',
        '[*]'      => '',
        '[/*]'     => '',
        '[list]'   => '',
        '[/list]'  => '',
        '[listo]'  => '',
        '[/listo]' => '',
        '[/color]' => '',
        '[/size]'  => '',
        '[/font]'  => '',
        '[quote]'  => '',
        '[/quote]' => '',
        '[/url]'   => '',
        '[table]'  => '',
        '[/table]' => '',
    ];

    protected array $pattern = [
        '~\[quote=[^]]*]~i'                  => '',
        '~\[size=[^]]*]~i'                   => '',
        '~\[color=[^]]*]~i'                  => '',
        '~\[spoiler=%.*?%].*?\[/spoiler]~is' => '',
        '~:s\d{2,3}:~'                       => '',
        '~…~'                                => '...',
        '~\.{4,}~'                           => '...',
        '~!{2,}~'                            => '!',
        '~\){2,}~'                            => ')',
        '~ ~'                             => ' ',
        '~﻿~'                           => ' ',
        '~&nbsp;~'                           => ' ',
        '~\s{2,}~'                           => ' '
    ];

    /**
     * @param $txt
     *
     * @return mixed
     */
    protected function parseBb($txt)
    {
        return preg_replace(array_keys($this->pattern), array_values($this->pattern), $txt);
    }
}
