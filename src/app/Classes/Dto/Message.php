<?php 

namespace App\Classes\Dto;

use App\Classes\Contracts\MessageInterface;
use App\Enums\SendType;
use App\Enums\Transport;
use App\Models\Group;

class Message 
{
    public SendType $type;
    public Group $target;
    public Transport $transport;
    public MessageInterface $message;

    /**
     *
     * @param SendType $type
     * @param Group $target
     * @param Transport $transport
     * @param MessageInterface $message
     */
    function __construct(SendType $type, Group $target, Transport $transport, MessageInterface $message)
    {
        $this->type = $type;
        $this->target = $target;
        $this->transport = $transport;
        $this->message = $message;
    }
}