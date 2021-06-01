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
            if ($this->owner && $this->owner->canGetProperty($attribute)) {
                $metatags[$tag] = $this->owner->{$attribute};
            }
        }

        return $metatags;
    }
}
