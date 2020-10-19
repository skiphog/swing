<?php

namespace App\Components\Worker\Commands;

use GatewayClient\Gateway;

class Chat extends Command
{
    /**
     * @throws \Exception
     */
    public function write()
    {
        Gateway::sendToGroup($this->data, json_encode([
            'type' => 'write',
            'data' => [
                'id' => (int)Gateway::getUidByClientId($this->client_id)
            ]
        ]));
    }
}
