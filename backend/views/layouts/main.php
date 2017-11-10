<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => '管理员', 'url' => ['/user/index'],'items'=>[
            ['label'=>'管理员列表', 'url'=>'/user/index'],
            ['label'=>'添加管理员', 'url'=>'/user/add'],
        ]],
        ['label' => 'RBAC', 'url' => ['/auth/index'],'items'=>[
            ['label'=>'权限列表', 'url'=>'/auth/index-permission'],
            ['label'=>'添加权限', 'url'=>'/auth/add-permission'],
            ['label'=>'角色列表', 'url'=>'/auth/index-role'],
            ['label'=>'添加角色', 'url'=>'/auth/add-role'],
        ]],
        ['label' => '文章管理', 'url' => ['/article/index'],'items'=>[
            ['label'=>'文章列表', 'url'=>'/article/index'],
            ['label'=>'添加文章', 'url'=>'/article/add'],
            ['label'=>'文章分类列表', 'url'=>'/article-category/index'],
            ['label'=>'添加文章分类', 'url'=>'/article-category/add'],
        ]],
        ['label' => '商品管理', 'url' => ['/goods/index'],'items'=>[
            ['label'=>'商品列表', 'url'=>'/goods/index'],
            ['label'=>'添加商品', 'url'=>'/goods/add'],
            ['label'=>'商品分类', 'url'=>'/goods-category/index'],
            ['label'=>'添加商品分类', 'url'=>'/goods-category/add'],
        ]],
        ['label' => '品牌管理', 'url' => ['/brand/index'],'items'=>[
            ['label'=>'品牌列表', 'url'=>'/brand/index'],
            ['label'=>'添加品牌', 'url'=>'/brand/add'],
        ]],
        ['label' => '修改密码', 'url' => ['/user/password']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/user/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                '退出登录 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
