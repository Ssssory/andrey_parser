<?php

namespace App\Enums;

enum SendScop:string {
    case Forum = 'forum';
    case Inexpensive = 'inexpensive';
    case InexpensiveForum = 'inexpensive forum';
    case Handle = 'handle';
}