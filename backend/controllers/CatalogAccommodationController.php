<?php

namespace backend\controllers;

use Yii;

use backend\models\Category;
use backend\models\CatalogAccommodation;
use backend\models\CatalogAccommodationLang;
use backend\models\Lang;
use backend\models\CatalogRoom;

use backend\models\CatalogDiscount;

use common\models\User;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Json;

use yii\web\Response;


class CatalogAccommodationController extends Controller
{

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
						'allow' => true,
						'actions' => [
							'index', 
							'view', 
							'create', 
							'update', 
							'delete', 
							//ajax
							'get-discounts',
							'update-discounts',
							'upload-image', 
							'delete-image'
						],
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

	//get AJAX
	public function actionGetDiscounts($id)
	{
		$response = ['status' => 'success', 'data' => [], 'message' => 'Empty'];
		$discounts = CatalogDiscount::find()->where(['model_name' => 'CatalogAccommodation', 'object_id' => $id])->asArray()->all();

		$response['message'] = 'Found: '.count($discounts);
		$response['data'] = $discounts;


		Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}

	//apdate AJAX
	public function actionUpdateDiscounts($id)
	{
		$model = $this->findModel($id);

		if(!($model->author == Yii::$app->user->id || Yii::$app->user->can('contentAccess'))){
			throw new ForbiddenHttpException();
		}

		$response = ['status' => 'error', 'message' => 'Empty'];
		$req = file_get_contents('php://input');
		$request = $req ? json_decode($req, true) : [];

		foreach ($request as $key => $item){
			$variant = null;
			if(array_key_exists('id', $item)){
                $variant = CatalogDiscount::findOne($item['id']);
				//if removed -> delete them and continue cycle
				if(array_key_exists('removed', $item) && $item['removed'] == 1){
					$variant->delete();
					continue;
				}
			}else{
			    if(array_key_exists('removed', $item) && $item['removed'] == 1){
                    continue;
                }
				$variant = new CatalogDiscount(['model_name' => 'CatalogAccommodation', 'object_id' => $id]);
			}
			if($variant){
				$period_from = \DateTime::createFromFormat('Y-m-d', $item['period_from']);
				$period_to = \DateTime::createFromFormat('Y-m-d', $item['period_to']);
				if($period_from > $period_to){
					$response['message'] = 'Invalid period';
					$response['status'] = 'error';
					continue;
				}
				$variant->period_from = $item['period_from'];
				$variant->period_to = $item['period_to'];
				$variant->discount = floatval($item['discount']);
				if($variant->save()){
					$response['status'] = 'success';
					$response['message'] = 'Variants saved';
				}else{
					$response['status'] = 'error';
					$response['message'] = 'Saving error...';
					break;
				}
			}
		}


		Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}


	public function actionCreate($lang_id = null)
	{
		$model = new CatalogAccommodation(['author' => Yii::$app->user->id]);
        $model->lang_id = $lang_id === null ? Lang::getCurrent() : $lang_id;

		$languages = Lang::find()->where(['published' => 1])->all();
		$model->content = new CatalogAccommodationLang(['lang_id' => $lang_id]);
		$users = User::find()->orderBy('id')->all();

		$success = false;

		if($model->load(Yii::$app->request->post()) && $model->save()){
            $model->content->object_id = $model->id;
			if($model->content->load(Yii::$app->request->post()) && $model->content->save()){
				$success = true;
			}
		}
		if($success){
			return $this->redirect(['update', 'id' => $model->id]);
		}else{
			return $this->render('create', [
				'model' => $model,
				'languages' => $languages,
				'users' => $users,
			]);
		}
	}


	public function actionUpdate($id, $lang_id = null)
	{

		$model = $this->findModel($id);
        $model->lang_id = $lang_id === null ? Lang::getCurrent() : $lang_id;

        if(!($model->author == Yii::$app->user->id || Yii::$app->user->can('contentAccess'))){
            throw new ForbiddenHttpException();
        }

        if($model->content === null){
            $model->content = new CatalogAccommodationLang(['object_id' => $id, 'lang_id' => $lang_id]);
        }

		$users = User::find()->orderBy('id')->all();
		$languages = Lang::find()->where(['published' => 1])->all();

		//rooms 
		$collection = CatalogRoom::find()->where(['accommodation_id' => $id])->all();


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

		//CatalogAccommodation
		//CatalogAccommodationLang
		//Attributes

        $status = self::STATUS_EDIT;
		if(Yii::$app->request->isPost){

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
			'languages' => $languages,
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
