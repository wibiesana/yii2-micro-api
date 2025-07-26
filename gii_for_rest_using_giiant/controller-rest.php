<?php

use yii\helpers\StringHelper;

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass) . 'Search';

/**
 * Customizable controller class.
 */
echo "<?php\n";
?>

namespace <?= $generator->controllerNs ?>;

/**
* This is the class for REST controller "<?= $controllerClassName ?>".
*/

use Yii;
use app\controllers\base\ApiController;
use <?= $generator->modelClass ?>;
use <?= $generator->searchModelClass ?> as <?= $searchModelClass ?>;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class <?= $controllerClassName ?> extends ApiController
{
public $modelClass = <?= $modelClass ?>::class;
public $modelSearch = <?= $searchModelClass ?>::class;

public $serializer = [
'class' => 'yii\rest\Serializer',
'collectionEnvelope' => 'items',
];

public $except = [
// 'index',
// 'list-all',
// 'view',
// 'create',
// 'update',
// 'delete'
];

public function actions(): array
{
$actions = parent::actions();

unset(
$actions['index'],
$actions['create'],
$actions['update'],
$actions['delete'],
$actions['view']
);

return $actions;
}

/**
* @return array
*/
public function actionListAll(): array
{
return $this->modelClass::find()
->orderBy('name ASC')
->limit(5000)
->all();
}

/**
* @return ActiveDataProvider
*/
public function actionIndex(): ActiveDataProvider
{
$modelSearch = new $this->modelSearch;
$dataProvider = $modelSearch->search(Yii::$app->request->queryParams);
$dataProvider->query->orderBy('id DESC');
$dataProvider->pagination->pageSize = 50;
return $dataProvider;
}

/**
* @param string $id
* @return ActiveRecord
*/
public function actionView($id): ActiveRecord
{
return $this->findModel($id);
}

/**
* @return ActiveRecord|array
*/
public function actionCreate(): ActiveRecord|array
{
$model = new $this->modelClass();
$model->load(Yii::$app->getRequest()->getBodyParams(), '');

if (!$model->save()) {
Yii::$app->response->statusCode = 400;
return [
'hasErrors' => $model->hasErrors(),
'errors' => $model->getErrors(),
];
}

return $model;
}

/**
* @param string $id
* @return ActiveRecord|array
*/
public function actionUpdate($id): ActiveRecord|array
{
$model = $this->findModel($id);
$model->load(Yii::$app->getRequest()->getBodyParams(), '');

if (!$model->save()) {
Yii::$app->response->statusCode = 400;
return [
'hasErrors' => $model->hasErrors(),
'errors' => $model->getErrors(),
];
}

return $model;
}

/**
* @param string $id
*/
public function actionDelete($id): void
{
$model = $this->findModel($id);

if ($model->delete() === false) {
throw new ServerErrorHttpException('FAILED_TO_DELETE_DATA');
}

Yii::$app->getResponse()->setStatusCode(204);
}

/**
* Finds the model by ID or throws exception
*
* @param string $id
* @return ActiveRecord
* @throws HttpException if model not found
*/
protected function findModel($id): ActiveRecord
{
if (($model = $this->modelClass::findOne($id)) === null) {
throw new HttpException(404, 'Data not Found');
}

return $model;
}

/**
* HTTP verbs for CORS support
*/
protected function verbs(): array
{
return [
'index' => ['GET', 'OPTIONS'],
'list-all' => ['GET', 'OPTIONS'],
'view' => ['GET', 'OPTIONS'],
'create' => ['POST', 'OPTIONS'],
'update' => ['PUT', 'OPTIONS'],
'delete' => ['DELETE', 'OPTIONS'],
];
}
}