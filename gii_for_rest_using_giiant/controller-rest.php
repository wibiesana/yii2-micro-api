<?php

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
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

class <?= $controllerClassName ?> extends ApiController
{
public $modelClass = <?= $modelClass ?>::class;
public $searchModel = '<?= $generator->searchModelClass ?>';
public $serializer = [
'class' => 'yii\rest\Serializer',
'collectionEnvelope' => 'items',
];
public $except = [
// Uncomment any of the routes below to allow access without authentication
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

// By default, all actions from the parent controller are available.
// To override any action, leave it in the unset() call below.
// To keep a default action, simply remove it from the unset() list.
// Delete any action you don't want to support in this controller.

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
* @return mixed
*/
public function actionLiatAll(): array
{
return $this->modelClass::find()
->orderBy('name ASC')
//->where(['is_active' => 1])
->limit(5000)
->all();

}

/**
* @return mixed
*/
public function actionIndex(): ActiveDataProvider
{
$modelSearch = new $this->searchModel;
$dataProvider = $modelSearch->search(Yii::$app->request->queryParams);
//$dataProvider->query->where(['is_active' => 1]);
$dataProvider->query->orderBy('id DESC');
$dataProvider->pagination->pageSize = 50;
return $dataProvider;
}

/**
* @param string $id
* @return mixed
*/
public function actionView($id): ActiveRecord
{
return $this->findModel($id, false);
}

/**
* @return mixed
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
* @return mixed
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
* @return mixed
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
* If the model is not found, a 404 HTTP exception will be thrown.
* @param string $id
* @throws HttpException if the model cannot be found
*/
protected function findModel($id): ActiveRecord
{
if (($model = $this->modelClass::findOne($id)) === null) {
throw new HttpException(404, 'Data not Found');
}
return $model;
}
/**
* List of HTTP request method
*/

// Add 'OPTIONS' to enable CORS preflight requests
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