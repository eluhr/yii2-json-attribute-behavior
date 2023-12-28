Yii2 JSON Attribute Behavior
============================
This behavior automatically decodes attributes from JSON to arrays before validation, handling errors and re-encoding if validation fails.
With this a "real" json string can be stored in the database.

![CI Workflow](https://github.com/eluhr/yii2-json-attribute-behavior/actions/workflows/ci.yml/badge.svg)

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

In a `yii\base\Model` or a derivation thereof, the behavior can be used as follows:

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
```

By using this behavior it does not matter if the attribute is a string or an array. 
The behavior will always ensure, that the attribute is an array before saving the data to the database and yii will handle the rest.

This behavior supports [i18n](https://www.yiiframework.com/doc/guide/2.0/en/tutorial-i18n). By adding the `json-attribute-behavior` category in your config you can overwrite the default error messages.
