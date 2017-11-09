<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,"password_hash")->passwordInput();
echo $form->field($model,"cookie")->checkboxList(['1'=>"是否记住登录"]);
echo \yii\bootstrap\Html::submitInput("登录",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();