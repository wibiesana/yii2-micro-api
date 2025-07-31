<?php

use yii\helpers\StringHelper;

/**
 * This is the template for generating the model class of a specified table.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\model\Generator $generator
 * @var string $tableName
 * @var string $className
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels
 * @var string[] $rules
 * @var array $relations
 */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use yii\helpers\ArrayHelper;
use <?= $generator->ns ?>\base\<?= $className ?> as Base<?= $className ?>;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
* This is the model class for table "<?= $tableName ?>".
*/
class <?= $className ?> extends Base<?= $className . "\n" ?>
{
public function behaviors()
{
return ArrayHelper::merge(
parent::behaviors(),
[
// custom behaviors
[
'class' => BlameableBehavior::class,
],
[
'class' => TimestampBehavior::class,
'value' => new Expression('NOW()')
],
]
);
}

public function rules()
{
return ArrayHelper::merge(
parent::rules(),
[
// custom validation rules
]
);
}

public function fields()
{
$fields = [
<?= implode(",\n", array_map(fn($col) => "        '" . $col->name . "'", $tableSchema->columns)) ?>
];

<?php foreach ($relations as $name => $relation): ?>
    <?php $camelName = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name)))); ?>
    $fields['<?= $camelName ?>'] = function ($model) {
    $rel = $model-><?= $camelName ?>;
    return $rel ? [
    'id' => $rel->id ?? null,
    'name' => $rel->name ?? null,
    ] : null;
    };
<?php endforeach; ?>

return $fields;
}


}