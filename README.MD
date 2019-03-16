Yii2 behaviour for changed attributes
===============================================
This behaviour let you to set methods that should be executed if some attribute has been changed
------------
Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist spe11/changedattribute dev-master
```

or add

```
"spe11/changedattribute": "dev-master"
```

to the require section of your `composer.json` file.

Usage
-----

Set attributes and methods as key and values for 'before' or 'after' arrays.
Depending on arrays, the method will be executed before or after model saving.
Object's methods shold be public
```php    
    public function behaviors()
    {
        $someService = new \app\services\SomeService;
        return [
                'class' => ChangedAttributeBehavior::className(),
                'attributes' =>
                [
                    'before' =>
                    [
                        'attribute1' => 'methodName', //if current class has this method
                        'attribute2' => [$someService, 'methodName'], //if you want to set method of another class
                        'attribute3' => ['app\services\SomeService', 'staticMethodName'] //if you want to set static method of another class
                    ],
                    'after' =>
                    [
                    
                    ],
                ]
        ];
    }
```
