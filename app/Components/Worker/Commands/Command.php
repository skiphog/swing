<?php

namespace App\Components\Worker\Commands;

abstract class Command
{
    /**
     * @var string
     */
    protected $client_id;

    /**
     * @var mixed
     */
    protected $data;

    public function __construct(string $client_id, $data)
    {
        $this->data = $data;
        $this->client_id = $client_id;
    }

    public function action(string $method)
    {
        if (!method_exists($this, $method)) {
            throw new \BadMethodCallException("Метод {$method} в контроллере " . static::class . ' не найден');
        }

        return $this->$method();
    }
}
