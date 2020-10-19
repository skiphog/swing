<?php

namespace App\Components;

use abeautifulsite\SimpleImage;

class PhotoUploader
{
    /**
     * Допустимая минимальная ширина фотографии
     */
    protected const MIN_WIDTH = 200;

    /**
     * Допустимая минимальная вымота фотографии
     */
    protected const MIN_HEIGHT = 200;

    /**
     * @var SimpleImage
     */
    protected $simple;

    /**
     * Каталог для загрузки
     *
     * @var string
     */
    protected $dir;

    /**
     * Имя файла
     *
     * @var string
     */
    protected $file_name;

    /**
     * Абсолютный путь к паблик
     *
     * @var string
     */
    private $root;

    /**
     * PhotoUploader constructor.
     *
     * @param string $file
     * @param int    $user_id
     * @param int    $album_id
     *
     * @throws \Exception
     */
    public function __construct($file, $user_id, $album_id)
    {
        require dirname(__DIR__) . '/../include/SimpleImage.php';
        $this->simple = new SimpleImage($file);
        $this->check();
        $this->root = dirname(__DIR__, 2) . '/';
        $this->setOptions($user_id, $album_id);
    }

    /**
     * @return PhotoUploader
     * @throws \Exception
     */
    public function save()
    {
        $this->createDirectory();

        return $this
            ->saveGeneralImage()
            ->saveThumbImage()
            ->saveThumbBlurImage();
    }

    /**
     * @return string
     */
    public function dir()
    {
        return $this->dir;
    }

    /**
     * @return string
     */
    public function fileName()
    {
        return $this->file_name;
    }

    protected function check()
    {
        if ($this->simple->get_width() < static::MIN_WIDTH || $this->simple->get_height() < static::MIN_HEIGHT) {
            throw new \InvalidArgumentException('Недопустимый размер фотографии');
        }
    }

    /**
     * Сохраняет основную фотографию
     *
     * @return $this
     * @throws \Exception
     */
    protected function saveGeneralImage()
    {
        $this->simple->auto_orient();
        $this->simple->best_fit(640, 800);
        $this->simple->save($this->root . $this->dir . $this->file_name);

        return $this;
    }

    /**
     * Сохраняет миниатюрку
     *
     * @return $this
     * @throws \Exception
     */
    protected function saveThumbImage()
    {
        $this->simple->adaptive_resize(100, 100);
        $this->simple->save($this->root . $this->dir . 'thumb_' . $this->file_name);

        return $this;
    }

    /**
     * Сохраняет размытую миниатюрку
     *
     * @return $this
     * @throws \Exception
     */
    protected function saveThumbBlurImage()
    {
        $this->simple->blur('selective', 30);
        $this->simple->blur('gaussian', 60);
        $this->simple->brightness(10);
        $this->simple->save($this->root . $this->dir . md5($this->file_name) . '.jpg');

        return $this;
    }

    /**
     * Создать директорию
     */
    protected function createDirectory()
    {
        $dir = $this->root . $this->dir;

        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }

    /**
     * @param int $user_id
     * @param int $album_id
     */
    protected function setOptions($user_id, $album_id)
    {
        $this->dir = "images/album/{$user_id}/{$album_id}/";
        $this->file_name = randomString() . '.jpg';
    }
}
