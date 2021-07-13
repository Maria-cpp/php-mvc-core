<?php

namespace zum\phpmvc\exceptions;

use http\Exception;

class NotFoundException extends \Exception
{
    protected  $message = 'Page not Found';
    protected $code = 404;

}