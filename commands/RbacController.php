<?php
namespace app\commands;
 
use Yii;
use yii\console\Controller;
use \app\rbac\UserGroupRule;
 
class RbacController extends Controller
{
    public function actionInit()
    {
        $authManager = \Yii::$app->authManager;
 
        // Create roles
        $guest  = $authManager->createRole('guest');
        $admin  = $authManager->createRole('admin');
 
        // Create simple, based on action{$NAME} permissions
        $login  = $authManager->createPermission('login');
        $logout = $authManager->createPermission('logout');
        $search  = $authManager->createPermission('search');
        $signUp = $authManager->createPermission('sign-up');
        $index  = $authManager->createPermission('index');
        $add = $authManager->createPermission('add');
 
        // Add permissions in Yii::$app->authManager
        $authManager->add($login);
        $authManager->add($logout);
        $authManager->add($search);
        $authManager->add($signUp);
        $authManager->add($index);
        $authManager->add($add);
 
 
        // Add rule, based on UserExt->group === $user->group
        $userGroupRule = new UserGroupRule();
        $authManager->add($userGroupRule);
 
        // Add rule "UserGroupRule" in roles
        $guest->ruleName  = $userGroupRule->name;
        $admin->ruleName  = $userGroupRule->name;
 
        // Add roles in Yii::$app->authManager
        $authManager->add($guest);
        $authManager->add($admin);
 
        // Add permission-per-role in Yii::$app->authManager
        // Guest
        $authManager->addChild($guest, $login);
        $authManager->addChild($guest, $logout);
        $authManager->addChild($guest, $search);
        $authManager->addChild($guest, $signUp);
        $authManager->addChild($guest, $index);
 
        // Admin
        $authManager->addChild($admin, $add);
        $authManager->addChild($admin, $guest);
    }
}