<?php

namespace backend\controllers;

use Yii;
use backend\models\Category;
use backend\models\Lang;
use backend\models\CategorySearch;
use backend\models\CategoryLang;

use backend\models\CatalogSanatoriums;
use backend\models\CatalogHotels;



use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\Response;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'upload-image', 'delete-image'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['GET'],
				],
			],
		];
	}

	/**
	 * Lists all Category models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new CategorySearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Category model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		if($model){
        	if($model->model_name){
        		$model_name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $model->model_name));
        		return $this->redirect([$model_name.'/index']);
        	}
        }
	}

	/**
	 * Creates a new Category model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Category();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Category model.
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


		if($content === null){
			$content = new CategoryLang(['category_id' => $id, 'lang_id' => $lang_id]);
		}

		if ($model->load(Yii::$app->request->post()) && $content->load(Yii::$app->request->post()) && $model->save() && $content->save()) {
            return $this->redirect(['update', 'id' => $model->id, 'lang_id' => $lang_id]);
        } else {
            return $this->render('update', [
                'lang_id' => $lang_id,
				'model' => $model,
				'images' => json_encode($images),
				'languages' => $languages,
				'content' => $content
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

	protected function findModel($id)
	{
		if (($model = Category::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
