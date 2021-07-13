<?php


namespace zum\phpmvc\middlewares;


use zum\phpmvc\Application;
use zum\phpmvc\exceptions\ForbiddenException;
use zum\phpmvc\exceptions\NotFoundException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $action =[])
    {
        $this->actions = $action;
    }

    /**
     * @throws ForbiddenException
     */
    public function execute()
    {
        if(Application::isGuest()){
            if(empty($this->actions) || in_array(Application::$app->controller->action, $this->actions))
            throw new ForbiddenException();
        }
    }
}