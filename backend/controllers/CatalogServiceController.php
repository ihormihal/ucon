<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/24/16
 * Time: 20:54
 */

namespace backend\controllers;

use Yii;
use backend\models\Lang;
use backend\models\CatalogService;
use backend\models\CatalogServiceLang;
use backend\models\CatalogAccommodation;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\Response;


class CatalogServiceController extends ImageController
{

    public $modelClass = 'backend\models\CatalogService';

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
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            //ajax
                            'upload-image', 
                            'delete-image'
                        ],
                        'allow' => true,
                        'roles' => ['vendorAccess'],
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

    public function actionCreate($accommodation_id = null, $lang_id = null)
    {
        $accommodation = CatalogAccommodation::findOne(['id' => $accommodation_id]);
        if($accommodation === null){
            throw new NotFoundHttpException('Accommodation not found for this room');
        }

        $model = new CatalogService(['accommodation_id' => $accommodation_id]);
        $model->lang_id = $lang_id === null ? Lang::getCurrent() : $lang_id;

        $languages = Lang::find()->where(['published' => 1])->all();

        $model->content = new CatalogServiceLang(['lang_id' => $model->lang_id]);

        $success = false;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->content->object_id = $model->id;
            if($model->content->load(Yii::$app->request->post()) && $model->content->save()){
                $success = true;
            }
        }

        if($success){
            return $this->redirect(['update', 'id' => $model->id, 'lang_id' => $model->lang_id]);
        }else{
            return $this->render('create', [
                'model' => $model,
                'languages' => $languages
            ]);
        }
    }

    public function actionUpdate($id, $lang_id = null)
    {
        $model = $this->findModel($id);
        $model->lang_id = $lang_id === null ? Lang::getCurrent() : $lang_id;
        if($model->content === null){
            $model->content = new CatalogServiceLang(['object_id' => $id, 'lang_id' => $lang_id]);
        }

        $languages = Lang::find()->where(['published' => 1])->all();

        $images = [];
        foreach ($model->getImages() as $image) {
            if($image->id){
                $images[] = [
                    'id' => $image->id,
                    'src' => $image->getUrl(),
                    'thumb' => $image->getUrl('250x250')
                ];
            }
        }

        $status = self::STATUS_EDIT;

        if(Yii::$app->request->isPost){

            if(
                $model->load(Yii::$app->request->post()) &&
                $model->content->load(Yii::$app->request->post()) &&
                $model->save() && $model->content->save()
            ){
                $status = self::STATUS_SUCCESS;
            }else{
                $status = self::STATUS_ERROR;
            }

        }
        return $this->render('update', [
            'status' => $status,
            'model' => $model,
            'images' => json_encode($images),
            'languages' => $languages
        ]);
    }

}