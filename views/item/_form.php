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

<div class="event-edit-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-sm-5 well'>
            <h4>Trigger</h4>
            <?php echo $model->treeWidget('classEventTree') ;?>
            <?php echo $form->field($model, 'trigger_class')->textInput(['class'=>'owner']) ;?>
            <?php echo $form->field($model, 'trigger_event')->textInput(['class'=>'method']) ;?>
        </div>
        <div class="col-sm-2 "></div>
        <div class='col-sm-5 well'>
            <h4>Handler</h4>
            <?php echo $model->treeWidget('classHandlerTree') ;?>
            <?php echo $form->field($model, 'handler_class')->textInput(['class'=>'owner']) ;?>
            <?php echo $form->field($model, 'handler_method')->textInput(['class'=>'method']) ;?>
        </div>
    </div>


    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
