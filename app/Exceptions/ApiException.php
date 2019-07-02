<?php

namespace App\Exceptions;

use Throwable;

class ApiException extends \Exception{

    public function __construct($message="")
    {
        parent::__construct($message);
    }
    
    
}
