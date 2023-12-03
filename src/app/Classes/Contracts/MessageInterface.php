<?php

namespace App\Classes\Contracts;

use Illuminate\Database\Eloquent\Model;

interface MessageInterface
{
    public Model $original;
    public function getMessage(): array;
}