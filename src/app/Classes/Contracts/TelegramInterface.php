<?php 

namespace App\Classes\Contracts;


interface TelegramInterface 
{
    function sendTextMesage(string $text,string $chatId,int $thread): void;
    function getChat(string $chatId);
    function sendOnePhotoMesage(string $urlPhoto): void;
    function sendMediaMessage(MessageInterface $message, string $chatId=null, int $topic=null): void;
}