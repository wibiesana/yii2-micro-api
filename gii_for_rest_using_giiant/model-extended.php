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
return [
<?php foreach ($tableSchema->columns as $column): ?>
    '<?= $column->name ?>',
<?php endforeach; ?>

<?php foreach ($tableSchema->columns as $column): ?>
    <?php if (preg_match('/_id$/', $column->name)):
        $relationName = StringHelper::basename(rtrim($column->name, '_id'));
        $camelRelation = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $relationName))));
    ?>
        '<?= $column->name ?>_name' => function ($model) {
        return $model-><?= $camelRelation ?>->name ?? null;
        },
    <?php endif; ?>
<?php endforeach; ?>

<?php foreach ($relations as $name => $relation): ?>
    <?php if (strpos($relation, 'hasOne(') !== false): ?>
        '<?= $name ?>' => function ($model) {
        if (!$model-><?= $name ?>) {
        return null;
        }
        return [
        'id' => $model-><?= $name ?>->id ?? null,
        'name' => $model-><?= $name ?>->name ?? null,
        ];
        },
    <?php endif; ?>
<?php endforeach; ?>
];
}
}