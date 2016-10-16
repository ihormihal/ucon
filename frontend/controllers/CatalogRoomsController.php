<?php

namespace frontend\controllers;

use Yii;

use frontend\models\Lang;
use frontend\models\CatalogRooms;
use frontend\models\CatalogRoomsLang;

//use backend\models\CatalogAttributes;
//use backend\models\CatalogAttributesValues;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogRoomsController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$rooms = CatalogRooms::find()->where(['published' => 1])->all();
        return $this->render('index', [
        	'rooms' => $rooms
        ]);
    }

}