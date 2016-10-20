<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\UserForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * LangController implements the CRUD actions for Lang model.
 */
class UsersController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'roles' => ['adminAccess'], //минимальная роль
                    ]
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
		$collection = User::find()->orderBy('id')->all();

		return $this->render('index', [
			'collection' => $collection
		]);
	}


	public function actionCreate()
	{
		$model = new UserForm();

		if ($model->load(Yii::$app->request->post()) && $user = $model->save()) {
			return $this->redirect(['update', 'id' => $user->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	public function actionUpdate($id)
	{
		$user = $this->findModel($id);

		$model = new UserForm([
			'id' => $user->id,
			'username' => $user->username,
			'email' => $user->email,
			'role' => $user->role
		]);

		$status = 'edit';

		if(Yii::$app->request->isPost){
			if ($model->load(Yii::$app->request->post()) && $user = $model->save()) {
				$status = 'success';
			} else {
				$status = 'error';
			}
		}

		return $this->render('update', [
			'model' => $model,
			'status' => $status
		]);
	}

	public function actionDelete($id)
	{
		$auth = Yii::$app->authManager;
		$auth->revokeAll($id);
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
