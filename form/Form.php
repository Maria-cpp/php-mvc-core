<?php


namespace zum\phpmvc\form;


use zum\phpmvc\Model;

class Form
{
    public static function begin($action, $method, $enctype="application/x-www-form-urlencoded"){
        echo sprintf('<form action="%s" method="%s" enctype="%s">', $action, $method, $enctype);
        return new Form();
    }

    public static function end(){
            echo '</form>';
    }

    public function field (Model $model, $attribute){
        return new InputField($model, $attribute);
    }
    public function textarea (Model $model, $attribute){
        return new TextareaField($model, $attribute);
    }
}