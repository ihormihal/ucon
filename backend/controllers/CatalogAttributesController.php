<?php

namespace backend\controllers;

use Yii;

use backend\models\Category;
use backend\models\CatalogAttributes;

use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\Response;

class CatalogAttributesController extends Controller
{

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
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

		$collection = CatalogAttributes::find()->where(['model_name' => $model_name])->all();
        $category = Category::findOne(['model_name' => $model_name]);

        return $this->render('index', [
            'category' => $category,
            'collection' => $collection,
        ]);
    }

    public function actionCreate($model_name = null)
    {
        $model = new CatalogAttributes();
        $category = Category::findOne(['model_name' => $model_name]);
        $success = false;

        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['update', 'id' => $model->id, 'model_name' => $category->model_name]);
        }else{
            return $this->render('update', [
                'category' => $category,
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $category = Category::findOne(['model_name' => $model->model_name]);

        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['update', 'id' => $model->id, 'model_name' => $category->model_name]);
        }else{
            return $this->render('update', [
                'category' => $category,
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model_name = $model->model_name;
        //delete category
        $model->delete();

        return $this->redirect(['index', 'model_name' => $model_name]);
    }



    protected function findModel($id)
    {
        if (($model = CatalogAttributes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
