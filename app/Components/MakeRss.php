<?php

namespace App\Components;

class MakeRss
{
    /**
     * Массив с данными для формирования RSS
     *
     * @var array
     */
    protected $data = [];

    /**
     * Форматированный вывод данных
     *
     * @var bool
     */
    protected $format = true;

    /**
     * Обрезка текста в заголовке для RSS
     *
     * @var int
     */
    protected $sub = 100;

    /**
     * Заголовок RSS
     *
     * @var string
     */
    protected $title = 'swing-kiska';

    /**
     * Описание RSS
     *
     * @var string
     */
    protected $description;

    /**
     * Ссылка на Фид
     *
     * @var string
     */
    protected $link = 'https://swing-kiska.ru';

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Установить формат вывода RSS
     *
     * @param bool $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Устанавливает обрезание
     *
     * @param int $sub
     *
     * @return $this
     */
    public function setSub($sub)
    {
        $this->sub = $sub;

        return $this;
    }

    /**
     * Устанавливает заголовок
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title .= ' - ' . $title;

        return $this;
    }

    /**
     * Устанавливает описание RSS
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Устанавливает link RSS
     *
     * @param string $link
     *
     * @return $this
     */
    public function setLink($link)
    {
        $this->link .= '/' . ltrim($link, '/');

        return $this;
    }

    /**
     * Выводит RSS в поток
     *
     * @return void
     */
    public function display()
    {
        echo $this->makeRss();
    }

    /**
     * Возвращает сформированный RSS
     *
     * @return string
     */
    public function makeRss()
    {
        [$dom, $root, $channel] = $this->getRootRss();

        $this->addChannelInfo($dom, $channel, $this->getInfoAsArray());

        foreach ($this->data as $value) {
            $item = $dom->createElement('item');
            $this->addChannelInfo($dom, $item, $this->getValueInfoAsArray($value));
            $channel->appendChild($item);
        }

        $root->appendChild($channel);
        $dom->appendChild($root);

        return $dom->saveXML();
    }

    /**
     * Возвращает root документа
     *
     * @return array
     */
    private function getRootRss()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = $this->format;
        $root = $dom->createElement('rss');
        $root->setAttribute('version', '2.0');

        return [$dom, $root, $dom->createElement('channel')];
    }


    /**
     * Возвращает подготовленный массив
     *
     * @return array
     */
    private function getInfoAsArray()
    {
        return [
            'title'       => $this->title,
            'description' => $this->description,
            'link'        => $this->link,
            'language'    => 'ru',
        ];
    }


    /**
     * Возвращает подготовленный массив
     *
     * @param array $data
     *
     * @return array
     */
    private function getValueInfoAsArray(array $data)
    {
        return [
            'title'   => sprintf('%s: %s', $data['author'], $this->getText($data['content'])),
            'author'  => $data['author'],
            'link'    => !empty($data['link']) ? $data['link'] : $this->link,
            'guid'    => $data['guid'],
            'pubDate' => (new \DateTime($data['created_at']))->format(DATE_RSS),
        ];
    }

    /**
     * Обрезает текст
     *
     * @param $text
     *
     * @return string
     */
    private function getText($text)
    {
        if (isset($text[$this->sub * 2 + 2])) {
            $text = mb_substr($text, 0, $this->sub);
            $text = mb_substr($text, 0, mb_strrpos($text, ' '));
            $text .= ' ...';
        }

        return $text;
    }

    /**
     * @param \DOMDocument $dom
     * @param \DOMElement  $element
     * @param array        $data
     *
     * @return void
     */
    private function addChannelInfo(\DOMDocument $dom, \DOMElement $element, array $data)
    {
        foreach ($data as $key => $value) {
            $element->appendChild(
                $dom->createElement($key, htmlspecialchars($value, ENT_QUOTES))
            );
        }
    }
}
