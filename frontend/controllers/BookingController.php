<?php

namespace frontend\controllers;

use Yii;

use frontend\models\CatalogAccommodation;
use frontend\models\CatalogRoom;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BookingController extends \yii\web\Controller
{

	public function actionIndex($id = null, $variant = null)
    {
    	
    	if($id === null){
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}

    	$room = CatalogRoom::find()->where(['id' => $id])->one();

    	if($room === null){
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}

        return $this->render('index', [
            'room' => $room,
            'variant' => $variant
        ]);
    }
}