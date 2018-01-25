<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\modules\inventory\models\InvtCheck */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'หน้าแรก'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'เลือกปี');
?>
<div class="invt-check-update">
<?php
    $cookies = Yii::$app->request->cookies;
//    print_r($cookies);
//    $ccyear = $cookies->getValue('ccyear');
//    echo $cookies->getValue('ccyear');
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<span class="panel-title"><?= Html::icon('dashboard').' '.Html::encode($this->title) ?></span>
    </div>
	<div class="panel-body">
        <?php $form = ActiveForm::begin([
            'layout' => 'horizontal',
            //'method' => 'get',
        ]); ?>

        <?= $form->field($model, 'id')->dropDownList($list)->label('ปีที่ตรวจ'); ?>

        <div class="form-group text-center">
            <?= Html::submitButton(Html::icon('ok').' เลือก', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
	</div>
</div>

</div>
