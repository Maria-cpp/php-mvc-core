<?php


namespace zum\phpmvc;


class Response
{
    public function setStatusCode(int $code){
         http_response_code($code);
    }

    public function redirect(string $url)
    {
        header('location:http://localhost:7800'.$url);
    }

}