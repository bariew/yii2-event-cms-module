<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Yii;

/**
 * @var yii\web\View $this
 * @var bariew\eventModule\models\Item $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="event-edit-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->errorSummary($model) ;?>
        <div class="hide">
            <?php echo $form->field($model, 'trigger_class')->textInput(['class'=>'owner classEventTree']) ;?>
            <?php echo $form->field($model, 'trigger_event')->textInput(['class'=>'method classEventTree']) ;?>
            <?php echo $form->field($model, 'handler_class')->textInput(['class'=>'owner classHandlerTree']) ;?>
            <?php echo $form->field($model, 'handler_method')->textInput(['class'=>'method classHandlerTree']) ;?>
        </div>
        <div class="row form-group">
            <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <div class="row">
        <div class='col-sm-5 well'>
            <h4><?= Yii::t('modules/event', 'Trigger'); ?></h4>
            <?php echo $model->treeWidget('classEventTree') ;?>
        </div>
        <div class="col-sm-2 "></div>
        <div class='col-sm-5 well'>
            <h4><?= Yii::t('modules/event', 'Handler'); ?></h4>
            <?php echo $model->treeWidget('classHandlerTree') ;?>
        </div>
        <div class="clearfix"></div></div>
    </div>
</div>
