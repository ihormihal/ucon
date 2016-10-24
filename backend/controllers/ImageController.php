<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/24/16
 * Time: 22:10
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\web\Response;


class ImageController extends Controller
{

    public $modelClass;

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

    protected function findModel($id)
    {

        $model = $model = call_user_func(array($this->modelClass, 'findOne'), $id);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}