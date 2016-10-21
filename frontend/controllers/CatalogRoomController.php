<?php

namespace frontend\controllers;

use Yii;

use frontend\models\Lang;
use frontend\models\CatalogRoom;
use frontend\models\CatalogRoomLang;

//use backend\models\CatalogAttributes;
//use backend\models\CatalogAttributesValues;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogRoomController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$rooms = CatalogRoom::find()->where(['published' => 1])->all();
        return $this->render('index', [
        	'rooms' => $rooms
        ]);
    }

}