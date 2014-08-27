<?php

use yii\helpers\Html;
use Yii;


/* @var $this yii\web\View */
/* @var $model bariew\eventModule\models\Item */

$this->title = Yii::t('modules/event', 'Create {modelClass}', [
    'modelClass' => 'Item',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('modules/event', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
