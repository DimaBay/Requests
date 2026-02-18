<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class RequestConflictException extends HttpException
{
    public function __construct(string $message = 'Заявка уже взята в работу. Обновите страницу.')
    {
        parent::__construct(409, $message);
    }
}
