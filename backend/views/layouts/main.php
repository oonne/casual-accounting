<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use backend\assets\AppAsset;

AppAsset::register($this);

$route = Yii::$app->requestedAction->uniqueId;

$menu = [
    [
        'label' => '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-set"></use></svg>  系统',
        'url' => '#',
        'items' => [
            'site' => ['label' => '系统信息', 'url' => ['site/index'], 'active' => in_array($route, ['site/index'])],
            'usersuper' => ['label' => '用户管理', 'url' => ['usersuper/index'], 'active' => in_array($route, ['usersuper/index', 'usersuper/create-user', 'usersuper/update-user'])],
            'handlersuper' => ['label' => '经手人管理', 'url' => ['handlersuper/index'], 'active' => in_array($route, ['handlersuper/index', 'handlersuper/create-handler', 'handlersuper/update-handler'])],
        ],
    ],
    [
        'label' => '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-bill"></use></svg>  账单',
        'url' => '#',
        'items' => [
            'categorysuper' => ['label' => '分类管理', 'url' => ['categorysuper/index'], 'active' => in_array($route, ['categorysuper/index', 'categorysuper/create-category', 'categorysuper/update-category'])],
        ]
    ],
];

?>

<?php $this->beginContent('@app/views/layouts/base.php'); ?>
    <div id="wrapper">
         <nav class="sidebar">
            <?= Html::a('<img src="/img/aura.png" class="logo-rotation">', Yii::$app->homeUrl, ['class' => 'sidebar-logo']) ?>
            <?= Menu::widget([
                'encodeLabels' => false,
                'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\">\n{items}\n</ul>\n",
                'options' => [
                    'class' => 'nav',
                    'id' => 'side-menu'
                ],
                'items' => $menu
            ]) ?>
        </nav>

        <div id='page-wrapper'>
            <?= $content ?>
        </div>

    </div>

<?php $this->endContent() ?>