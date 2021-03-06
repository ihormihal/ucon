<?php

namespace backend\controllers;

use Yii;

use backend\models\Lang;
use backend\models\CatalogRoom;
use backend\models\CatalogRoomLang;
use backend\models\CatalogAccommodation;

use backend\models\CatalogDiscount;

use backend\models\CatalogAttribute;
use backend\models\CatalogVariant;
use backend\models\CatalogVariantLang;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\Response;

use yii\helpers\CustomHelpers;

/**
 * CatalogRoomController implements the CRUD actions for CatalogRoom model.
 */
class CatalogRoomController extends ImageController
{

	public $modelClass = 'backend\models\CatalogRoom';

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
							'get-discounts', 
							'update-discounts',
							'get-variants', 
							'update-variants', 
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


	public function actionIndex()
	{
		$dataProvider = new ActiveDataProvider([
			'query' => CatalogRoom::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}


	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	//get AJAX
	public function actionGetDiscounts($id)
	{
		$response = ['status' => 'success', 'data' => [], 'message' => 'Empty'];
		$discounts = CatalogDiscount::find()->where(['model_name' => 'CatalogRoom', 'object_id' => $id])->asArray()->all();

		$response['message'] = 'Found: '.count($discounts);
		$response['data'] = $discounts;


		Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}

	//update AJAX
	public function actionUpdateDiscounts($id)
	{
		$model = $this->findModel($id);

		if(!($model->accommodation->author == Yii::$app->user->id || Yii::$app->user->can('contentAccess'))){
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
				$variant = new CatalogDiscount(['model_name' => 'CatalogRoom', 'object_id' => $id]);
			}
			if($variant){

				$period_from = \DateTime::createFromFormat('Y-m-d', $item['period_from']);
				$period_to = \DateTime::createFromFormat('Y-m-d', $item['period_to']);

				if($period_from > $period_to){
					$response['message'] = 'Invalid period';
					$response['status'] = 'error';
					continue;
				}
				$variant->period_from = $period_from->format('Y-m-d');
				$variant->period_to = $period_to->format('Y-m-d');
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

	//get AJAX
	public function actionGetVariants($id, $lang_id = null)
	{
		$lang_id === null ? Lang::getCurrent() : $lang_id;

		$response = ['status' => 'success', 'data' => [], 'message' => 'Empty'];
		$model = $this->findModel($id);
		$variants = CatalogVariant::find()->where(['object_id' => $model->id, 'model_name' => 'CatalogRoom'])->all();
		$response['message'] = 'Found: '.count($variants);

		foreach($variants as $variant){
			$variant->lang_id = $lang_id;
			$response['data'][] = [
				'id' => $variant->id,
				'attributes' => CustomHelpers::autocompleteValues($variant->attributes, false),
				'price' => $variant->price,
				'description' => $variant->content ? $variant->content->description : '',
			];
		}

		Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}

	//post AJAX
	public function actionUpdateVariants($id, $lang_id = null)
	{
		$lang_id = $lang_id === null ? Lang::getCurrent() : $lang_id;

		$model = $this->findModel($id); //author setted in accommodation
		if(!($model->accommodation->author == Yii::$app->user->id || Yii::$app->user->can('contentAccess'))){
			throw new ForbiddenHttpException();
		}

		$response = ['status' => 'error', 'message' => 'Empty'];
		$req = file_get_contents('php://input');
		$request = $req ? json_decode($req, true) : [];

		foreach ($request as $item){

			$attributes = [];
			foreach ($item['attributes'] as $attribute){
				$attributes[] = intval($attribute['value']);
			}
			$variant = null;
			if(array_key_exists('id', $item)){
				$variant = CatalogVariant::findOne($item['id']);
				$variant->lang_id = $lang_id;
				//if removed -> delete them and continue cycle
				if(array_key_exists('removed', $item) && $item['removed'] == 1){
					$variant->content->delete();
					$variant->delete();
					continue;
				}
			}else{
				if(array_key_exists('removed', $item) && $item['removed'] == 1){
					continue;
				}
				$variant = new CatalogVariant(['model_name' => 'CatalogRoom', 'object_id' => $id, 'lang_id' => $lang_id]);
			}
			if($variant){
				$variant->attributes = json_encode($attributes);
				$variant->price = floatval($item['price']);

				if($variant->save()){
					//save content
					if($variant->content === null){
						$variant->content = new CatalogVariantLang([
							'lang_id' => $lang_id,
							'object_id' => $variant->id,
						]);
					}
					$variant->content->description = $item['description'];
					$variant->content->save();
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


	public function actionCreate($accommodation_id = null, $lang_id = null)
	{
		$accommodation = CatalogAccommodation::findOne(['id' => $accommodation_id]);
		if($accommodation === null){
			throw new NotFoundHttpException('Accommodation not found for this room');
		}

		$model = new CatalogRoom([
			'accommodation_id' => $accommodation_id,
			'lang_id' => $lang_id === null ? Lang::getCurrent() : $lang_id
		]);
		$model->content = new CatalogRoomLang(['lang_id' => $model->lang_id]);

		$languages = Lang::find()->where(['published' => 1])->all();

		

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

	/**
	 * Updates an existing CatalogRoom model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */


	public function actionUpdate($id, $lang_id = null)
	{
		$model = $this->findModel($id);
		$model->lang_id = $lang_id === null ? Lang::getCurrent() : $lang_id;
		if($model->content === null){
			$model->content = new CatalogRoomLang(['object_id' => $id, 'lang_id' => $lang_id]);
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
			'languages' => $languages
		]);
	}

	public function actionAttributes($type = null)
	{
		if($type === null) $type = 'bool';
		$attributes = CatalogAttribute::find()->where(['model_name' => 'CatalogRoom', 'type' => $type])->all();

		$response = [];
		foreach($attributes as $attribute){
			$response[] = ['text' => $attribute->name, 'value' => $attribute->id];
		}

		Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}

	/**
	 * Deletes an existing CatalogRoom model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{

		$model = $this->findModel($id);
		$accommodation_id = $model->accommodation->id;
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

		return $this->redirect(['catalog-accommodation/update', 'id' => $accommodation_id, 'lang_id' => Lang::getCurrent()]);
	}
}
