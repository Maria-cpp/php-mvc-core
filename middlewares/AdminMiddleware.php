<?php


namespace zum\phpmvc\middlewares;


use zum\phpmvc\AdminApp;
use zum\phpmvc\Application;
use zum\phpmvc\exceptions\ForbiddenException;
use zum\phpmvc\exceptions\NotFoundException;

class AdminMiddleware extends BaseMiddleware
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
        if(Application::isGuest() || Application::$app->session->get('role') === 'user') {
            if (!empty($this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}