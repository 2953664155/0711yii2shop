<h1><?=$goods_name?></h1>
<div id="myCarousel" class="carousel slide">
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
    <!-- Carousel items -->
    <div class="carousel-inner">
        <?php foreach ($img as $v):?>
        <div class="item"><?=\yii\bootstrap\Html::img(Yii::getAlias('@web').$v->path,['width'=>500,'class'=>'img-thumbnail center-block'])?></div>
        <?php endforeach;?>
    </div>
    <!-- Carousel nav -->
    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
</div>
<div class="container">
    <span><?=$content->content?></span>
</div>
<?php
$this->registerJs(
    <<<JS
$('.carousel-inner').find("div:first").addClass('active');
JS

);
