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

use common\models\User;

use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

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
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'upload-image', 'delete-image'],
                        'roles' => ['vendorAccess'], //минимальная роль
                    ],
                    [
                        'allow' => true,
                        'actions' => ['publish', 'unpublish'],
                        'roles' => ['contentAccess'], //минимальная роль
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

        if(Yii::$app->user->can('contentAccess')){
            $collection = CatalogAccommodation::find()->all();
        }else{
            $collection = CatalogAccommodation::find()->where(['author' => Yii::$app->user->id])->all();
        }

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
    public function actionCreate($lang_id = null)
    {
        $model = new CatalogAccommodation(['author' => Yii::$app->user->id]);
        $lang_id = $lang_id === null ? $this->defaultLang() : $lang_id;
        $languages = Lang::find()->where(['published' => 1])->all();
        $content = new CatalogAccommodationLang(['lang_id' => $lang_id]);
        $users = User::find()->orderBy('id')->all();

        $success = false;

        if($model->load(Yii::$app->request->post()) && $model->save()){
            $content->object_id = $model->id;
            if($content->load(Yii::$app->request->post()) && $content->save()){
                $success = true;
            }
        }
        if($success){
            return $this->redirect(['update', 'id' => $model->id]);
        }else{
            return $this->render('create', [
                'model' => $model,
                'content' => $content,
                'lang_id' => $lang_id,
                'languages' => $languages,
                'users' => $users,
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

        $lang_id = $lang_id === null ? $this->defaultLang() : $lang_id;
        $model = $this->findModel($id);

        if(
            !(Yii::$app->user->can('authorAccess', ['model' => $model]) || Yii::$app->user->can('contentAccess'))
        ){
            throw new ForbiddenHttpException();
        }


        $users = User::find()->orderBy('id')->all();
        $languages = Lang::find()->where(['published' => 1])->all();
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

        $status = 'edit';

        //CatalogAccommodation
        //CatalogAccommodationLang
        //Attributes

        if(Yii::$app->request->isPost){
            //$response = ['status' => 'error', 'message' => 'Not updated'];

            //save attributes
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

            if(
                $model->load(Yii::$app->request->post()) &&
                $content->load(Yii::$app->request->post()) &&
                $model->save() && $content->save()
            ){
                $status = 'success';
                // $response['status'] = 'success';
                // $response['message'] = 'Successfully updated';
                //Yii::$app->response->format = Response::FORMAT_JSON;
                //return $response;
            }else{
                $status = 'error';
            }
        }
        return $this->render('update', [
            'status' => $status,
            'lang_id' => $lang_id,
            'model' => $model,
            'images' => json_encode($images),
            'languages' => $languages,
            'content' => $content,
            'collection' => $collection,
            'users' => $users,
        ]);
    }

    public function actionPublish($id)
    {
        $model = $this->findModel($id);
        $response = ['status' => 'error', 'message' => 'Not published'];
        $model->published = 1;
        if($model->save()){
            $response['success'] = 'success';
            $response['message'] = 'Successfully published';
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    public function actionUnpublish($id)
    {
        $model = $this->findModel($id);
        $response = ['status' => 'error', 'message' => 'Not unpublished'];
        $model->published = 0;
        if($model->save()){
            $response['success'] = 'success';
            $response['message'] = 'Successfully unpublished';
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }

    public function actionUploadImage($id)
    {
        $model = $this->findModel($id);
        $response = ['status' => 'error', 'message' => 'No image'];

        if (Yii::$app->request->isPost) {
            $response['status'] = 'success';

            $model->image = \yii\web\UploadedFile::getInstanceByName('file');

            if($model->image){
                $path = Yii::getAlias('@root/content/upload/').$model->image->baseName.'.'.$model->image->extension;
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
    protected function defaultLang()
    {
        if (($model = Lang::find()->where(['default' => 1, 'published' => 1])->one()) !== null) {
            return $model->id;
        } else {
            return 1;
        }
    }
}
