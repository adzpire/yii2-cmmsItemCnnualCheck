<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\modules\inventory\models\InvtCheck */
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<span class="panel-title"><?= Html::icon('search').' เลือกปีที่ตรวจ'; ?></span>
    </div>
	<div class="panel-body">
        <?php $form = ActiveForm::begin([
            'action' => 'brokenreport',
            'layout' => 'horizontal',
            'method' => 'get',
        ]); ?>

        <?= $form->field($model, 'checkyear')->dropDownList($yearlist)->label('ปีที่ตรวจ'); ?>

        <div class="form-group text-center">
            <?= Html::submitButton(Html::icon('ok').' เลือก', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
	</div>
</div>

