<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function handle(Request $request)
    {
        $update = Telegram::commandsHandler(true);

        $message = $update->getMessage();

        return $message;

        //$chat_id = $message->getChat()->getId();

        Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Hello!'
        ]);
    }
}
