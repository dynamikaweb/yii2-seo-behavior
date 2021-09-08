<?php

namespace dynamikaweb\seo;

class SeoBehavior extends \yii\behaviors\AttributeBehavior
{   
    public $metatags = [];
    public $attribute = 'seo';

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if ($name !== $this->attribute) {
            return parent::canGetProperty($name, $checkVars);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($name !== $this->attribute) {
            return null;
        }

        $metatags = [];

        foreach($this->metatags as $tag => $attribute) {
            // closure 
            if (is_callable($attribute)){
                $metatags[$tag] = call_user_func($attribute, $this->owner);
            }
            // method
            else if (is_string($attribute) && $this->owner->canGetProperty($attribute)) {
                $metatags[$tag] = $this->owner->{$attribute};
            }
            // url
            else if (is_string($attribute)) {
                $metatags[$tag] = $attribute;
            }
        }

        return $metatags;
    }
}
