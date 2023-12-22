<?php declare(strict_types=1);

use eluhr\jsonAttributeBehavior\behaviors\JsonAttributeBehavior;
use PHPUnit\Framework\TestCase;
use yii\base\Model;

final class JsonAttributeBehaviorTest extends TestCase
{
    public function testValidateAttributeWithString()
    {
        $this->assertTrue((new Item(['data_json' => '{"a": "b"}']))->validate());
    }

    public function testValidateAttributeWithArray()
    {
        $this->assertTrue((new Item(['data_json' => ['a' => 'b']]))->validate());
    }
}

class Item extends Model
{
    /**
     * @var string|array
     */
    public $data_json;

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['json-attribute'] = [
            'class' => JsonAttributeBehavior::class,
            'attributes' => [
                'data_json'
            ]
        ];
        return $behaviors;
    }
}
