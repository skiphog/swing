<?php
/**
 * @noinspection PhpUnused
 */

namespace App\Components\Worker;

use GatewayWorker\Lib\Gateway;

class Events
{
    /**
     * @param string $client_id
     */
    public static function onConnect(string $client_id)
    {
        Gateway::sendToCurrentClient(json_encode([
            'type' => 'init',
            'data' => ['client_id' => $client_id],
        ]));
    }

    /**
     * @param string $client_id
     * @param array  $data
     */
    public static function onWebSocketConnect(string $client_id, array $data)
    {
    }

    /**
     * @param $client_id
     * @param $message
     *
     * @return mixed
     * @throws \Exception
     */
    public static function onMessage($client_id, $message)
    {
        return Handler::resolve($client_id, $message);
    }

    /**
     * @param $client_id
     *
     * @throws \Exception
     */
    public static function onClose($client_id)
    {
        if (!isset($_SESSION['id'], $_SESSION['room'])) {
            return;
        }

        $user_id = $_SESSION['id'];
        $room = $_SESSION['room'];

        if (!array_key_exists($user_id, Gateway::getUidListByGroup($room))) {
            Gateway::sendToGroup($room, json_encode([
                'type' => 'leave',
                'room' => $room,
                'data' => [
                    'id' => $user_id,
                ],
            ]));
        }
    }
}
