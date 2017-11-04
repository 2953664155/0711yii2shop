<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'status',['inline'=>1])->radioList(['0'=>'隐藏','1'=>'显示']);
echo \yii\bootstrap\Html::submitInput('添加',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();