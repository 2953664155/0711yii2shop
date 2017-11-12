<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::merge(['0'=>"====请选择菜单===="],$menu));
echo $form->field($model,'route')->dropDownList(\yii\helpers\ArrayHelper::merge([''=>"====请选择路由===="],\backend\models\Menu::getParent()));
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn->info']);
\yii\bootstrap\ActiveForm::end();