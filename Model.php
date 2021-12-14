<?php


namespace zum\phpmvc;


abstract class Model
{

    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const RULE_IMAGE_SIZE = 'size';
    public const RULE_IMAGE_TYPE = 'type';

    abstract public function rules(): array;

    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function labels(): array
    {
        return [];
    }


    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public array $errors = [];

    public function validate(): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED, $rule);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attribute, self::RULE_EMAIL, $rule);
                }
                if($ruleName === self::RULE_MIN && strlen($value)< $rule['min']){
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if($ruleName === self::RULE_MAX && strlen($value)> $rule['max']){
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if($ruleName === self::RULE_MATCH && $value!== $this->{$rule['match']}){
                    $rule['match'] =$this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if($ruleName === self::RULE_IMAGE_SIZE && $value!== $this->{$rule['size']}){
                    $rule['RULE_IMAGE_SIZE'] =$this->getLabel($rule['RULE_IMAGE_SIZE']);
                    $this->addErrorForRule($attribute, self::RULE_IMAGE_SIZE, $rule);
                }
                if($ruleName === self::RULE_IMAGE_TYPE && in_array($value,["jpeg", "jpg", "png"])){
                    $rule['RULE_IMAGE_TYPE'] =$this->getLabel($rule['RULE_IMAGE_TYPE']);
                    $this->addErrorForRule($attribute, self::RULE_IMAGE_TYPE, $rule);
                }
                if($ruleName === self::RULE_UNIQUE){
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr" , $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if($record){
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    private function addErrorForRule(string $attribute, string $rule, $params=[])
    {
        $message = $this->errorMessage()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }


    public function errorMessage()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be a valid email address',
            self::RULE_MIN => 'Min Length of this field must be {min}',
            self::RULE_MAX => 'Max Length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists',
            self::RULE_IMAGE_SIZE =>'image size is more than defined size of 2MB',
            self::RULE_IMAGE_TYPE => 'only jpeg, jpg & png image formats are acceptable'
        ];
    }
    public function hasError($attribute){
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }
}