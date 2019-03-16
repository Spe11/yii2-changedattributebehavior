<?php 

namespace spe11\changedattribute;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\base\UnknownMethodException;

class ChangedAttributeBehavior extends Behavior
{
     /**
     * @var array configuring arrays of attributes and actions as key and values
     */
    public $attributes = [];

    /**
     * @var array attributes to be cheched before model saving
     */
    private $beforeAttributes = [];
    
    /**
     * @var array  attributes to be cheched after model saving
     */
    private $afterAttributes = [];

     /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->beforeAttributes = $this->attributes['before'];
        $this->afterAttributes = $this->attributes['after'];
    }

    /**
     * {@inheritdoc}
     */
    public function events()
    {
       return [
           ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
           ActiveRecord::EVENT_AFTER_UPDATE => 'onAfterSave',
       ];
    }

    /**
     * Execute actions for every changed atrribute before model save
     *
     * @return void|yii\base\UnknownMethodException
     */
    public function onBeforeSave()
    {
        $this->execute($this->beforeAttributes);
    }

    /**
     * Execute actions for every changed atrribute after model save
     *
     * @return void|yii\base\UnknownMethodException
     */
    public function onAfterSave()
    {
        $this->execute($this->afterAttributes);
    }

    /**
     * Execute actions fore every appropriate attibute that has been changed
     *
     * @param array $attributes array of attributes that should be checked for being changed
     * @return void|yii\base\UnknownMethodException
     */
    private function execute($attributes)
    {
        foreach($attributes as $key => $value) {
            if(in_array($key, array_keys($this->owner->getDirtyAttributes()))) {
                if(is_array($value)) {
                    if (method_exists($value[0], $value[1])) {
                        call_user_func($value);
                    } else {
                        throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$value[1]()");
                    }
                } else {
                    if ($this->owner->hasMethod($value)) {
                        $this->owner->$value();
                    } else {
                        throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$value()");
                    }
                }
            }
        }
    }
}