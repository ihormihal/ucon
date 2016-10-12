<?php

namespace backend\controllers;

use Yii;

use backend\models\Category;
use backend\models\CatalogAccommodation;
use backend\models\CatalogAccommodationLang;
use backend\models\Lang;
use backend\models\CatalogAttributes;
use backend\models\CatalogAttributesValues;
use backend\models\CatalogRooms;

use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Json;

use yii\web\Response;

/**
 * CatalogHotelsController implements the CRUD actions for CatalogHotels model.
 */
class CatalogAccommodationController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'upload-image', 'delete-image'],
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

    public function actionIndex()
    {
        $collection = CatalogAccommodation::find()->all();
        $category = Category::findOne(['model_name' => 'CatalogAccommodation']);
        $lang = Lang::findOne(['default' => 1]);

        return $this->render('index', [
            'category' => $category,
            'collection' => $collection,
            'lang' => $lang,
        ]);
    }

    /**
     * Creates a new CatalogHotels model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CatalogAccommodation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CatalogHotels model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id, $lang_id = null)
    {
        $lang_id = ($lang_id === null) ? 1 : (int)$lang_id;

        $model = $this->findModel($id);
        $languages = Lang::find()->where(['active' => 1])->all();
        $content = $model->getContent($lang_id);

        //rooms 
        $collection = CatalogRooms::find()->where(['accommodation_id' => $id])->all();

        if($content === null){
            $content = new CatalogAccommodationLang(['object_id' => $id, 'lang_id' => $lang_id]);
        }

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

        $return = false;

        if(Yii::$app->request->isPost){

            if(isset($_POST['Attributes'])){
                foreach ($_POST['Attributes'] as $alias => $value) {
                    $attribute = $model->attrs[$alias];
                    if(is_array($value)){
                        $attribute->value = JSON::encode($value);
                    }else{
                        $attribute->value = $value;
                    }
                    $attribute->save();
                }
            }

            if (
                $model->load(Yii::$app->request->post()) && 
                $content->load(Yii::$app->request->post()) && 
                $model->save() && $content->save()
            ) {
                return $this->redirect(['update', 'id' => $model->id, 'lang_id' => $lang_id]);
            } else {
                $return = true;
            }

        }else{
            $return = true;
        }
        if($return){
            return $this->render('update', [
                'lang_id' => $lang_id,
                'model' => $model,
                'images' => json_encode($images),
                'languages' => $languages,
                'content' => $content,
                'collection' => $collection
            ]);
        }
    }

    public function actionUploadImage($id)
    {
        $model = $this->findModel($id);
        $response = ['status' => 'error', 'message' => 'No image'];

        if (Yii::$app->request->isPost) {
            $response['status'] = 'success';

            $model->image = \yii\web\UploadedFile::getInstanceByName('file');

            if($model->image){
                $path = Yii::getAlias('@webroot/upload/').$model->image->baseName.'.'.$model->image->extension;
                $model->image->saveAs($path);
                $image = $model->attachImage($path);


                $response['data'] = [
                    'id' => $image->id,
                    'src' => $image->getUrl(), 
                    'thumb' => $image->getUrl('250x250')
                ];
                $response['status'] = 'success';
                $response['message'] = 'Image successfully uploaded';
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }

    public function actionDeleteImage($id)
    {

        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $response = ['status' => 'error', 'message' => 'No image'];

        //delete images
        foreach ($model->getImages() as $image){
            if($image->id == $data['id']){
                $model->removeImage($image);
                $response['status'] = 'success';
                $response['message'] = 'Image deleted';
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }

    /**
     * Deletes an existing CatalogHotels model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        //delete languages
        foreach ($model->contents as $key => $content) {
            $content->delete();
        }
        //delete images
        foreach ($model->getImages() as $image){
            $model->removeImage($image);
        }
        //delete category
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CatalogHotels model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogHotels the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogAccommodation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}