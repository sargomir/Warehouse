<?php
use app\modules\warehouse\Warehouse as Module;
$user = Yii::$app->user;
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= \yii\helpers\Url::base() ?>/img/nopic.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php if (isset ($user->identity->id)) echo $user->identity->id ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
<!--        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->

        <?= app\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'СКЛАД', 'options' => ['class' => 'header']],
                    
                    [
                        'label' => Module::t('app', 'Documents'),
                        'url' => ['/warehouse/documents/index'],
                        'icon' => 'fa fa-file-text',
//                         'visible' => Yii::$app->user->can('admin')                     
                    ],                          
                    [
                        'label' => Module::t('app', 'Products'),
                        'url' => ['/warehouse/products/index'],
                        'icon' => 'fa fa-cube',
//                         'visible' => Yii::$app->user->can('worker')                     
                    ],
                    [
                        'label' => Module::t('app', 'Manufacturers'),
                        'url' => ['/warehouse/manufacturers/index'],
                        'icon' => 'fa fa-wrench',
//                         'visible' => Yii::$app->user->can('worker')                     
                    ],
                    [
                        'label' => Module::t('app', 'Warehouses'),
                        'url' => ['/warehouse/warehouses/index'],
                        'icon' => 'fa fa-cubes',                        
//                         'visible' => Yii::$app->user->can('user_manager')
                    ],
                    [
                        'label' => Module::t('app', 'Help'),
                        'url' => ['/warehouse/module/index'],
                        'icon' => 'fa fa-info',
//                         'visible' => Yii::$app->user->can('worker')                     
                    ],

                    
                    ['label' => 'Login', 'url' => ['projects/default/login'], 'visible' => Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>