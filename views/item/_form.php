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
    <div class='row '>
        <?php echo $model->treeWidget('appEventTree') ;?>
    </div>


    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $model->treeWidget('classEventTree') ;?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
