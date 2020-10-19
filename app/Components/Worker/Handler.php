<?php

namespace App\Components\Worker;

use GatewayWorker\Lib\Gateway;
use App\Components\Worker\Commands\Command;

class Handler
{
    /**
     * @var string
     */
    private $client_id;

    /**
     * @var string
     */
    private $message;

    public static function resolve(string $client_id, string $message)
    {
        return (new static($client_id, $message))
            ->handle();
    }

    public function __construct(string $client_id, string $message)
    {
        $this->client_id = $client_id;
        $this->message = $message;
    }

    public function handle()
    {
        try {
            $data = json_decode($this->message, true, 512, JSON_THROW_ON_ERROR);

            if (!isset($data['type'], $data['act'], $data['data'])) {
                throw new \InvalidArgumentException('Не переданы параметры для обработки запроса');
            }

            $class = 'App\\Components\\Worker\\Commands\\' . ucfirst($data['type']);

            if (!class_exists($class)) {
                throw new \InvalidArgumentException("Class [{$class}] not found");
            }

            /** @noinspection PhpUndefinedMethodInspection */
            return (new $class($this->client_id, $data['data']))
                ->action($data['act']);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
