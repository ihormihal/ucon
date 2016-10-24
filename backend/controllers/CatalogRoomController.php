<?php

namespace backend\controllers;

use Yii;

use backend\models\Lang;
use backend\models\CatalogRoom;
use backend\models\CatalogRoomLang;
use backend\models\CatalogAccommodation;

use backend\models\CatalogDiscount;

use backend\models\CatalogAttribute;
use backend\models\CatalogAttributeValue;
use backend\models\PriceVariant;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\Response;

use yii\helpers\CustomHelpers;

/**
 * CatalogRoomController implements the CRUD actions for CatalogRoom model.
 */
class CatalogRoomController extends Controller
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
						'actions' => [
							'index', 
							'view', 
							'create', 
							'update', 
							'delete', 
							//ajax
							'get-discounts', 
							'update-discounts',
							'get-prices', 
							'update-prices', 
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

	//get AJAX
	public function actionGetPrices($id)
	{
		$response = ['status' => 'success', 'data' => [], 'message' => 'Empty'];
		$model = $this->findModel($id);
		$variants = PriceVariant::find()->where(['object_id' => $model->id, 'model_name' => 'CatalogRoom'])->all();
		$response['message'] = 'Found: '.count($variants);

		foreach($variants as $variant){
			$response['data'][] = [
				'id' => $variant->id,
				'attributes' => CustomHelpers::autocompleteValues($variant->attributes, false),
				'price' => $variant->price,
			];
		}

		Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}

	//post AJAX
	public function actionUpdatePrices($id)
	{
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
				$variant = PriceVariant::findOne($item['id']);
				//if removed -> delete them and continue cycle
                if(array_key_exists('removed', $item) && $item['removed'] == 1){
                	$variant->delete();
                	continue;
                }
			}else{
				$variant = new PriceVariant(['model_name' => 'CatalogRoom', 'object_id' => $id]);
			}
			if($variant){
				$variant->attributes = json_encode($attributes);
				$variant->price = floatval($item['price']);
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


	public function actionCreate($accommodation_id = null, $lang_id = null)
	{
		$lang_id = $lang_id === null ? $this->defaultLang() : $lang_id;
		$languages = Lang::find()->where(['published' => 1])->all();

		$accommodation_id = ($accommodation_id === null) ? 0 : (int)$accommodation_id;
		$accommodation = CatalogAccommodation::findOne(['id' => $accommodation_id]);
		if($accommodation === null) return false; //accommodation not found

		$model = new CatalogRoom(['accommodation_id' => $accommodation_id]);
		$content = new CatalogRoomLang(['lang_id' => $lang_id]);

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
				'accommodation' => $accommodation,
				'lang_id' => $lang_id,
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
		$lang_id = $lang_id === null ? $this->defaultLang() : $lang_id;

		$model = $this->findModel($id);
		$languages = Lang::find()->where(['published' => 1])->all();
		$content = $model->getContent($lang_id);

		$accommodation = CatalogAccommodation::findOne(['id' => $model->accommodation_id]);

		if($content === null){
			$content = new CatalogRoomLang(['object_id' => $id, 'lang_id' => $lang_id]);
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
			'accommodation' => $accommodation
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

		return $this->redirect(['catalog-accommodation/update', 'id' => $accommodation_id, 'lang_id' => $this->defaultLang()]);
	}

	/**
	 * Finds the CatalogRoom model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return CatalogRoom the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = CatalogRoom::findOne($id)) !== null) {
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
