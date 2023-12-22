Yii2 JSON Attribute Behavior
============================
This behavior automatically decodes attributes from JSON to arrays before validation, handling errors and re-encoding if validation fails.

Installation
------------

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```
composer require --prefer-dist eluhr/yii2-json-attribute-behavior "*"
```

or add

```
"eluhr/yii2-json-attribute-behavior": "*"
```

to the `require` section of your `composer.json` file.


Usage
-----

In a yii\base\Model or a derivation thereof, the behavior can be used as follows:

```php
public function behaviors(): array
{
    $behaviors = parent::behaviors();
    $behaviors['json-attribute'] = [
        'class' => eluhr\jsonAttributeBehavior\JsonAttributeBehavior::class,
        'attributes' => [
            'data_json'
        ]
    ];
    return $behaviors;
}
