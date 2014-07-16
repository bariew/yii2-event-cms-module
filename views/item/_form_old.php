<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var bariew\eventModule\models\Item $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="email-config-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'trigger_class')->widget(Select2::classname(), [
        'data' => $model::classList(),
        'options' => ['class' => 'form-control']
    ]);?>
    <?= $form->field($model, 'trigger_event')->widget(DepDrop::classname(), [
            'data'=> $model::eventList($model->handler_class),
            'options' => [ 'class' => 'form-control'],
            'type' => DepDrop::TYPE_DEFAULT,
            'pluginOptions'=>[
                'depends' => ['item-trigger_class'],
                'url' => Url::toRoute(['trigger-events']),
                'loadingText' => '',
                'initialize' => true
            ]
        ]);
    ?>
    <?php echo $form->field($model, 'handler_class')->widget(Select2::classname(), [
            'data' => $model::classList(),
            'options' => ['class' => 'form-control']
        ]);?>
    <?= $form->field($model, 'handler_method')->widget(DepDrop::classname(), [
            'data'=> $model::methodList($model->handler_class),
            'options' => [ 'class' => 'form-control'],
            'type' => DepDrop::TYPE_DEFAULT,
            'pluginOptions'=>[
                'depends' => ['item-handler_class'],
                'url' => Url::toRoute(['handler-methods']),
                'loadingText' => '',
                'initialize' => true
            ]
        ]);
    ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
