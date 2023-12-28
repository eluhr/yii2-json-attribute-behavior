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

    public function testAllowNull(): void
    {
        $this->assertTrue((new Item(['data_json' => null, 'scenario' => Item::SCENARIO_ALLOW_EMPTY]))->validate());
    }

    public function testAllowEmptyString(): void
    {
        $this->assertTrue((new Item(['data_json' => '', 'scenario' => Item::SCENARIO_ALLOW_EMPTY]))->validate());
    }

    public function testAllowEmptyArray(): void
    {
        $this->assertTrue((new Item(['data_json' => [], 'scenario' => Item::SCENARIO_ALLOW_EMPTY]))->validate());
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

    public const SCENARIO_ALLOW_EMPTY = 'allowEmpty';

    /**
     * @inheritdoc
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ALLOW_EMPTY] = ['data_json'];
        return $scenarios;
    }

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

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules[] = [['data_json'], 'required', 'except' => 'allowEmpty'];
        $rules[] = [['data_json'], 'safe', 'on' => 'allowEmpty'];
        return $rules;
    }
}
