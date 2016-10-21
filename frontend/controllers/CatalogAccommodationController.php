<?php

namespace frontend\controllers;

use Yii;

use yii\data\ActiveDataProvider;

use frontend\models\Lang;
use frontend\models\Category;
use frontend\models\CatalogAccommodation;
use frontend\models\CatalogRoom;

//use frontend\models\CatalogAttributes;
use frontend\models\CatalogAttributeValue;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogAccommodationController extends \yii\web\Controller
{

	public function actionIndex()
    {
        // $dataProvider = new ActiveDataProvider([
        //     'query' => CatalogAccommodation::find(),
        // ]);

        // return $this->render('index', [
        //     'dataProvider' => $dataProvider,
        // ]);
    	$model = Category::find()->where(['model_name' => 'CatalogAccommodation'])->one();
    	if($model->published == 0 || $model->content === null || $model->content['published'] == 0){
			return $this->redirect(['site/index']);
		}
    	$hotels = CatalogAccommodation::find()->where(['published' => 1])->all();

        return $this->render('index', [
            'model' => $model,
            'hotels' => $hotels,
        ]);
    }

	public function actionView($id)
	{	
		$model = $this->findModel($id);

		if($model->published == 0 || $model->content === null || $model->content['published'] == 0){
			return $this->redirect(['index']);
		}
		$rooms = CatalogRoom::find()->where(['accommodation_id' => $model->id, 'published' => 1])->all();
		return $this->render('view', [
            'model' => $model,
            'rooms' => $rooms,
        ]);
	}

	protected function findModel($id)
	{
		if (($model = CatalogAccommodation::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}