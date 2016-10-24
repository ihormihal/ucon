<?php

namespace backend\controllers;

use Yii;

use backend\models\Category;
use backend\models\CatalogAttribute;

use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\Response;

class CatalogAttributeController extends Controller
{

    const STATUS_EDIT = 'edit';
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'attributes'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($model_name = null)
    {	

		$collection = CatalogAttribute::find()->where(['model_name' => $model_name])->all();
        $category = Category::findOne(['model_name' => $model_name]);

        return $this->render('index', [
            'category' => $category,
            'collection' => $collection,
        ]);
    }

    public function actionCreate($model_name = null)
    {
        $model = new CatalogAttribute();
        $category = Category::findOne(['model_name' => $model_name]);
        $success = false;

        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['update', 'id' => $model->id, 'model_name' => $category->model_name]);
        }else{
            return $this->render('create', [
                'category' => $category,
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $category = Category::findOne(['model_name' => $model->model_name]);

        $status = self::STATUS_EDIT;

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                $status = self::STATUS_SUCCESS;
            }else{
                $status = self::STATUS_ERROR;
            }
        }

        return $this->render('update', [
            'status' => $status,
            'category' => $category,
            'model' => $model,
        ]);

    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model_name = $model->model_name;
        //delete category
        $model->delete();

        return $this->redirect(['index', 'model_name' => $model_name]);
    }

    //for autocomplete
    public function actionAttributes($type = null, $model_name = null)
    {
            if($type === null) $type = 'bool';
            $attributes = CatalogAttribute::find()->where(['model_name' => $model_name, 'type' => $type])->all();

            $response = [];
            foreach($attributes as $attribute){
                $response[] = ['text' => $attribute->name, 'value' => $attribute->id];
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
    }

    protected function findModel($id)
    {
        if (($model = CatalogAttribute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
