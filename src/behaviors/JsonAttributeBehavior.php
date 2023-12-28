<?php

namespace eluhr\jsonAttributeBehavior\behaviors;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\behaviors\AttributeBehavior;
use yii\helpers\Json;

/**
 *
 * This behavior ensures that the attribute value is an array if it is a string. It automatically decodes attributes
 * from JSON strings to arrays before validation, adding validation errors if the JSON is invalid, and re-encoding
 * them as JSON strings after validation if errors occur.
 *
 * In a yii\base\Model or a derivation thereof, the behavior can be used as follows:
 *
 * ```php
 * public function behaviors(): array
 * {
 *      return [
 *          'jsonAttributes' => [
 *              'class' => JsonAttributeBehavior::class,
 *              'attributes' => [
 *                  'data_json'
 *              ]
 *          ]
 *      ];
 * }
 *
 * @property Model $owner
 * @property string[] $_convertedAttributes
 * @property bool $alwaysConvertBackToOriginalType
 */
class JsonAttributeBehavior extends AttributeBehavior
{
    /**
     * List of attribute that has been decoded from JSON.
     *
     * @var array
     */
    private array $_convertedAttributes = [];

    /**
     * Whether to always convert the attribute back to its original type, even if no validation errors occur.
     *
     * @var bool
     */
    public bool $alwaysConvertBackToOriginalType = false;

    /**
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Model::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            Model::EVENT_AFTER_VALIDATE => 'afterValidate'
        ];
    }

    /**
     * Ensure that the attribute value is an array if it is a string.
     */
    public function beforeValidate(): void
    {
        foreach ($this->attributes as $attribute) {
            if (is_string($this->owner->$attribute)) {
                // Decode the JSON string into an array and assign it to the attribute.
                // If the JSON string is invalid, the attribute will not be changed but an error will be added.
                try {
                    $this->owner->$attribute = Json::decode($this->owner->$attribute);
                    $this->_convertedAttributes[] = $attribute;
                } catch (InvalidArgumentException $e) {
                    Yii::error($e->getMessage());
                    $this->owner->addError($attribute, Yii::t('json-attribute-behavior', '{attribute} must be a valid JSON string.', ['attribute' => $attribute]));
                }
            }
        }
    }

    /**
     * Ensure that the attribute will be converted back to its original type if an error occurs.
     */
    public function afterValidate(): void
    {
        if ($this->owner->hasErrors() || $this->alwaysConvertBackToOriginalType) {
            foreach ($this->attributes as $attribute) {
                // If the attribute was originally a string, convert it back to a string.
                if (in_array($attribute, $this->_convertedAttributes)) {
                    // Convert the attribute back to its original type.
                    $this->owner->$attribute = Json::encode($this->owner->$attribute);
                    // Remove the attribute from the list of converted attributes.
                    $this->_convertedAttributes = array_diff($this->_convertedAttributes, [$attribute]);
                }
            }
        }
    }
}
