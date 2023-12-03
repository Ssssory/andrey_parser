<?php 

namespace App\Classes\Contracts;

use SergiX44\Nutgram\Telegram\Types\Chat\Chat;

interface TelegramInterface 
{
    function sendTextMesage(string $text,string $chatId,int $thread): void;
    function getChat(string $chatId): Chat|null;
    function sendOnePhotoMesage(string $urlPhoto): void;
    function sendMediaMessage(MessageInterface $message, string $chatId=null, int $topic=null): void;
}