<?php

namespace App\Exceptions;

class APIMessagesErrors
{

    private array $message = [];

    public function __construct(string $message, array $data = [])
    {
        $this->message['message'] = $message;
        $this->message['errors']  = $data;
    }

    public function getMessage()
    {
        return $this->message;
    }

}
