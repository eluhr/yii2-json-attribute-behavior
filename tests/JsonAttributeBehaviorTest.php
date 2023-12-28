<?php declare(strict_types=1);

use eluhr\jsonAttributeBehavior\behaviors\JsonAttributeBehavior;
use PHPUnit\Framework\TestCase;
use yii\base\Model;

final class JsonAttributeBehaviorTest extends TestCase
{
    public function testValidateAttributeWithString(): void
    {
        $this->assertTrue((new Item(['data_json' => '{"a": "b"}']))->validate());
    }

    public function testValidateAttributeWithArray(): void
    {
        $this->assertTrue((new Item(['data_json' => ['a' => 'b']]))->validate());
    }

    public function testValueIsOriginalTypeAfterValidationForString(): void
    {
        $item = new Item(['data_json' => '{"a": "b"}']);
        $item->validate();
        $this->assertIsArray($item->data_json);
    }

    public function testValueIsOriginalTypeAfterValidationForArray(): void
    {
        $item = new Item(['data_json' => ['a' => 'b']]);
        $item->validate();
        $this->assertIsArray($item->data_json);
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
