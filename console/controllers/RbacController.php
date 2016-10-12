<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // добавляем разрешение "updateCategory"
        //$updateCategory = $auth->createPermission('updateCategory');
        //$updateCategory->description = 'Update a category';
        //$auth->add($updateCategory);


        // добавляем роль "admin" и даём роли разрешение "updateCategory"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        //$auth->addChild($admin, $updateCategory);

        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
        // обычно реализуемый в модели User.
        $auth->assign($admin, 1);
    }
}