<?php
use yii\helpers\Markdown;

$body = Yii::$app->controller->renderPartial('@backend/modules/iac/docs/guide/basic-usage.md');
echo Markdown::process($body, 'extra');