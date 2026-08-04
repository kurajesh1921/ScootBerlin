<?php

declare(strict_types=1);

namespace App\Exceptions\Scooter;

use Exception;

class ScooterNotAvailableException extends Exception
{
    protected $message = 'Scooter is not available.';
}