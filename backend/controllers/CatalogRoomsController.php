<?php

namespace backend\controllers;

use Yii;

use backend\models\Lang;
use backend\models\CatalogRooms;
use backend\models\CatalogRoomsLang;
use backend\models\CatalogAccommodation;

use backend\models\CatalogAttributes;
use backend\models\CatalogAttributesValues;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\Response;

/**
 * CatalogRoomsController implements the CRUD actions for CatalogRooms model.
 */
class CatalogRoomsController extends Controller
{

    /**
     * @inheritdoc
     */
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

    /**
     * Lists all CatalogRooms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CatalogRooms::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CatalogRooms model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CatalogRooms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($accommodation_id = null, $lang_id = null)
    {
        $lang_id = $lang_id === null ? $this->defaultLang() : $lang_id;
        $languages = Lang::find()->where(['published' => 1])->all();

        $accommodation_id = ($accommodation_id === null) ? 0 : (int)$accommodation_id;
        $accomodation = CatalogAccommodation::findOne(['id' => $accommodation_id]);
        if($accomodation === null) return false; //accommodation not found

        $model = new CatalogRooms(['accommodation_id' => $accommodation_id]);
        $content = new CatalogRoomsLang(['lang_id' => $lang_id]);

        $success = false;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $content->object_id = $model->id;
            if($content->load(Yii::$app->request->post()) && $content->save()){
                $success = true;
            }
        }

        if($success){
            return $this->redirect(['update', 'id' => $model->id, 'lang_id' => $this->defaultLang()]);
        }else{
            return $this->render('create', [
                'model' => $model,
                'content' => $content,
                'accomodation' => $accomodation,
                'lang_id' => $lang_id,
                'languages' => $languages
            ]);
        }
    }

    /**
     * Updates an existing CatalogRooms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id, $lang_id = null)
    {
        $lang_id = $lang_id === null ? $this->defaultLang() : $lang_id;

        $model = $this->findModel($id);
        $languages = Lang::find()->where(['published' => 1])->all();
        $content = $model->getContent($lang_id);

        $accomodation = CatalogAccommodation::findOne(['id' => $model->accommodation_id]);

        if($content === null){
            $content = new CatalogRoomsLang(['object_id' => $id, 'lang_id' => $lang_id]);
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

        if(Yii::$app->request->isPost){

            //$response = ['status' => 'error', 'message' => 'Not updated'];

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
                //$response['status'] = 'success';
                //$response['message'] = 'Successfully updated';
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
            'accomodation' => $accomodation
        ]);
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
     * Deletes an existing CatalogRooms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $accomodation_id = $model->accommodation->id;
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

        return $this->redirect(['catalog-accommodation/update', 'id' => $accomodation_id, 'lang_id' => $this->defaultLang()]);
    }

    /**
     * Finds the CatalogRooms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogRooms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogRooms::findOne($id)) !== null) {
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
