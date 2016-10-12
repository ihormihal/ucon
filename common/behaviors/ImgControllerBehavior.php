<?php

namespace common\behaviors;

use yii\base\Behavior;

use yii\web\Response;

class ImgControllerBehavior extends Behavior
{
    // public $prop1;

    // private $_prop2;

    // public function getProp2()
    // {
    //     return $this->_prop2;
    // }

    // public function setProp2($value)
    // {
    //     $this->_prop2 = $value;
    // }

    // public function foo()
    // {
    //     // ...
    // }

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
}