<?php
namespace app\modules\warehouse\rbac;
 
use Yii;
use yii\rbac\Rule;

/**
 * Чтобы все это хозяйство работало делаем следущее:
 * 1). В AuthManager перечисляем defaulRoles все доступные роли,
 * чтобы для всех них попадать сюда при проверке прав доступа.
 * Именно здесь мы будем решать как эти роли пересекаются между собой.
 * 2). В контроллере access->rules прописываем каждому методу
 * минимальную роль для выполнения.
 * 3). Дописываем иерархическое перекрытие ролей в методе execute(...)
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';
 
    public function execute($user, $item, $params)
    {
        //\Yii::trace($user, 'rbac');
        //\Yii::trace($item, 'rbac');
        //\Yii::trace($params, 'rbac');

        $user = \Yii::$app->user;
        $roles = \Yii::$app->authManager->getRolesByUser(\Yii::$app->user->id);
        
        return true;
        
        if (!$user->isGuest) {
            switch ($item->name) {
                case 'admin' :
                    return isset($roles['admin']);
                case 'warehouse_rw' : 
                    return isset($roles['admin']) || isset($roles['warehouse_rw']);
                case 'warehouse_w' :
                    return isset($roles['admin']) || isset($roles['warehouse_rw']) || isset($roles['warehouse_r']);
                default :
                    return false;
            }
        }
        
        return false;
    }

}