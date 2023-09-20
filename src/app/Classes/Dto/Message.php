<?php 

namespace App\Classes\Dto;

use App\Classes\Contracts\MessageInterface;
use App\Enums\SendType;
use App\Enums\Transport;

class Message 
{
    public SendType $type;
    public array $target;
    public Transport $transport;
    public MessageInterface $message;

    /**
     *
     * @param SendType $type
     * @param array{chat_id: string, topic_id: int} $target
     * @param Transport $transport
     * @param MessageInterface $message
     */
    function __construct(SendType $type, array $target, Transport $transport, MessageInterface $message)
    {
        $this->type = $type;
        $this->target = $target;
        $this->transport = $transport;
        $this->message = $message;
    }
}