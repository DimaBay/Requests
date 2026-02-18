<?php

namespace App\Enums;

enum UserRole: string
{
    case Dispatcher = 'dispatcher';
    case Master = 'master';
}
