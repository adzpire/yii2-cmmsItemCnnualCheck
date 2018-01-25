<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\inventory\models\InvtCheck */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invt Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invt-check-view">

<div class="panel panel-success">
	<div class="panel-heading">
		<span class="panel-title"><?= Html::icon('eye').' '.Html::encode($this->title) ?></span>
		<?= Html::a( Html::icon('fire').' '.Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger panbtn',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
		<?= Html::a( Html::icon('pencil').' '.Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary panbtn']) ?>
	</div>
	<div class="panel-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
 			[
				'label' => $model->attributeLabels()['id'],
				'value' => $model->id,			
				//'format' => ['date', 'long']
			],
			[
				'label' => $model->attributeLabels()['cc_id'],
				'value' => $model->invtChecks[0]->user->fullname.' ตำแหน่ง '.$model->invtChecks[0]->position.' ปี '.$model->invtChecks[0]->year,
				//'format' => ['date', 'long']
			],
			[
				'label' => $model->attributeLabels()['invt_id'],
				'value' => $model->invt->invt_name,
				//'format' => ['date', 'long']
			],
			[
				'label' => $model->attributeLabels()['old_stat'],
				'value' => $model->oldStat->invt_sname,
				//'format' => ['date', 'long']
			],
			[
				'label' => $model->attributeLabels()['stat_id'],
				'value' => $model->stat->invt_sname,
				//'format' => ['date', 'long']
			],
			[
				'label' => $model->attributeLabels()['old_loc'],
				'value' => $model->oldLoc->loc_name,
				//'format' => ['date', 'long']
			],
			[
				'label' => $model->attributeLabels()['loc_id'],
				'value' => $model->loc->loc_name,
				//'format' => ['date', 'long']
			],
     			[
				'label' => $model->attributeLabels()['occupy_by'],
				'value' => $model->occupy_by,			
				//'format' => ['date', 'long']
			],
     			[
				'label' => $model->attributeLabels()['note'],
				'value' => $model->note,			
				//'format' => ['date', 'long']
			],
     			[
				'label' => $model->attributeLabels()['created_at'],
				'value' => $model->created_at,			
				//'format' => ['date', 'long']
			],
     			[
				'label' => $model->attributeLabels()['created_by'],
				'value' => $model->created_by,			
				//'format' => ['date', 'long']
			],
     			[
				'label' => $model->attributeLabels()['updated_at'],
				'value' => $model->updated_at,			
				//'format' => ['date', 'long']
			],
     			[
				'label' => $model->attributeLabels()['updated_by'],
				'value' => $model->updated_by,			
				//'format' => ['date', 'long']
			],
    	],
    ]) ?>
	</div>
</div>
</div>