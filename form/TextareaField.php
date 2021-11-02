<?php


namespace zum\phpmvc\form;


use zum\phpmvc\Model;

class TextareaField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_TITLE = 'title';

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    public function renderInput(): string
    {
        return sprintf('<textarea name ="%s" class="form-control%s">%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->{$this->attribute}
        );
    }
}