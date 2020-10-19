<?php

namespace App\Components;

class SiteMap
{
    public const URL = 'https://swing-kiska.ru';

    public const PRETTY_FILE = false;

    protected static $static_url = [
        '',
        '/findlist',
        '/hotmeet',
        '/travel',
        '/group_result_1',
        '/all_events',
    ];

    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function makeSiteMap()
    {
        $data = array_merge(static::$static_url, $this->data);

        return $this->makeXml($data);
    }

    protected function makeXml(array $data)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');

        if (true === self::PRETTY_FILE) {
            $dom->formatOutput = true;
        }

        $root = $this->getRoot($dom);

        foreach ($data as $item) {
            $url = $dom->createElement('url');
            $loc = $dom->createElement('loc', self::URL . $item);
            $url->appendChild($loc);
            $root->appendChild($url);
        }
        $dom->appendChild($root);

        return (int)(bool)$dom->save(__DIR__ . '/../../sitemap.xml');
    }

    private function getRoot(\DOMDocument $dom)
    {
        $root = $dom->createElement('urlset');
        $root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:schemaLocation',
            'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        return $root;
    }
}
