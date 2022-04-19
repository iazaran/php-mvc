<?php

namespace Controllers;

use App\Helper;

/**
 * Class WebSocketController
 * @package Controllers
 */
class WebSocketController
{
    /**
     * A sample websocket ina a chat room
     *
     * @return void
     */
    public function chat(): void
    {
        Helper::render(
            'WebSocket/chat',
            [
                'page_title' => 'Chat',
                'page_subtitle' => 'Basic WebSocket Sample',
            ]
        );
    }
}
