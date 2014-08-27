<?php

use yii\helpers\Html;
use yii\grid\GridView;
use Yii;

/* @var $this yii\web\View */
/* @var $searchModel bariew\eventModule\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('modules/event', 'Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('modules/event', 'Create {modelClass}', [
            'modelClass' => 'Item',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'trigger_class',
            'trigger_event',
            'handler_class',
            'handler_method',
            [
                'attribute'=>'active',
                'format'    => 'raw',
                'value' => function($data) {
                    return Html::activeCheckbox($data, 'active', [
                        'data-link' => \yii\helpers\Url::toRoute(['activate', 'id'=>$data->id]),
                        'onchange'  => 'var el = $(this); $.get(
                            el.data("link")+"&active="+(el.is(":checked")*1)
                        )'
                    ]);
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<div class="row">
    <?php echo $searchModel->treeWidget('appEventTree');?>
</div>
