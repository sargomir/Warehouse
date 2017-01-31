<?php

namespace app\modules\warehouse;

use Yii;

class Warehouse extends \yii\base\Module
{
    public static $version = 1.3;
	
    public $controllerNamespace = 'app\modules\warehouse\controllers';
    
    public $company;
    
    public function init()
    {
        parent::init();

        // Overwrite error route so we will stay in module default controller and see module main.php layout
        Yii::$app->errorHandler->errorAction = 'warehouse/module/error';
        
        // Load module specified users
        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\warehouse\models\AuthUser',
            'enableAutoLogin' => false,
            //'loginUrl' => ['projects/default/login'],
            'loginUrl' => ['warehouse/module/login'],
            'identityCookie' => ['name' => 'editor', 'httpOnly' => true],
            'idParam' => 'easpm_id', //this is important !
        ]);
        
        // Load module specified rbac for users
        Yii::$app->set('authManager', [
            'class' => 'yii\rbac\DbManager',
            // Enumerate all roles as default because we have UserGroupRule with roles hierarchy
            'defaultRoles' => ['worker'],
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=192.168.0.21;dbname=auth',
				//'tablePrefix'=>'wh_',
                'username' => 'root',
                'password' => 'HSoJpGqji*',
                'charset' => 'utf8',
            ],

        ]);
		
        // Custom initialization code goes here
        $this->layout = 'main.php';
        $this->registerTranslations();
    }
    
    // Enable translations
    public function registerTranslations()
    {
    	Yii::$app->i18n->translations['modules/warehouse/*'] = [
    			'class' => 'yii\i18n\PhpMessageSource',
    			'sourceLanguage' => 'en-US',
    			'basePath' => '@app/modules/warehouse/messages',
    			'fileMap' => [
    					'modules/warehouse/app' => 'app.php',
    			],
    	];
    }    
    
    public static function t($category, $message, $params = [], $language = null)
    {
    	return Yii::t('modules/warehouse/' . $category, $message, $params, $language);
    }    
}
