<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\components\rbac\AuthorRule;

class RbacController extends Controller
{
	public function actionInit()
	{
		$auth = Yii::$app->authManager;
		$auth->removeAll(); //удаляем старые данные
		//return false;

		// добавляем разрешение "vendor"
		$vendor = $auth->createPermission('vendorAccess');
		$vendor->description = 'Vendor permissions';
		$auth->add($vendor);

		// добавляем разрешение "content"
		$content = $auth->createPermission('contentAccess');
		$content->description = 'Content permissions';
		$auth->add($content);

		// добавляем разрешение "admin"
		$admin = $auth->createPermission('adminAccess');
		$admin->description = 'All admins permissions';
		$auth->add($admin);
		
		//добавляем разрешение (вернее проверку) "author"
		$author_rule = new AuthorRule();
		$auth->add($author_rule);
		$author = $auth->createPermission('authorAccess');
		$author->description = 'CRUD own model';
		$author->ruleName = $author_rule->name;
		$auth->add($author);


		// добавляем роль "vendor_role"
		$vendor_role = $auth->createRole('vendor');
		$vendor_role->description = "Vendor role";
		$auth->add($vendor_role);
		

		// добавляем роль "content_role"
		$content_role = $auth->createRole('content');
		$content_role->description = "Vendor role";
		$auth->add($content_role);
		

		// добавляем роль "admin_role"
		$admin_role = $auth->createRole('admin');
		$admin_role->description = "Admin role";
		$auth->add($admin_role);

		//назначаем дочерние разрешения - вендор может быть автором 
		//назначаем, потому что проверку на авторство будем вешать только на вендора
		$auth->addChild($vendor, $author);
		
		//назначаем ролям разрешения
		$auth->addChild($vendor_role, $vendor);
		$auth->addChild($content_role, $content);
		$auth->addChild($admin_role, $admin);


		//назначаем дочерние роли
		$auth->addChild($content_role, $vendor_role); //контенщик может все что и вендор
		$auth->addChild($admin_role, $content_role); //админ может все что и контетнщик
		

		// Назначение ролей пользователям по их ID.
		$auth->assign($admin_role, 1);
		$auth->assign($vendor_role, 3);
		$auth->assign($content_role, 4);
	}
}