<?php

namespace backend\controllers;

use Yii;
use backend\models\Lang;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LangController implements the CRUD actions for Lang model.
 */
class LangController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all Lang models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$collection = Lang::find()->orderBy('id')->all();

		$dataProvider = new ActiveDataProvider([
			'query' => Lang::find(),
		]);

		return $this->render('index', [
			'collection' => $collection,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Lang model.
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
	 * Creates a new Lang model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Lang();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			//set default to "0" for others
			$langs = Lang::find()->where(['default = :default and id != :id', ['default' => 1, 'id' => $id]])->all();
            foreach ($langs as $lang) {
                $lang->default = 0;
                $lang->save();
            }

			return $this->redirect(['update', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Lang model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {

			//set default to "0" for others
			$langs = Lang::find()->where(['default' => 1])->andWhere(['!=', 'id', $id])->all();
            foreach ($langs as $lang) {
                $lang->default = 0;
                $lang->save();
            }

			return $this->redirect(['update', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Lang model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Lang model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Lang the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Lang::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
