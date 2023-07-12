<?php
/* @var $this BookController */
/* @var $model BookForm */

$this->breadcrumbs = array(
    'Books' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Book', 'url' => array('index')),
);
?>

<h1>Create Book</h1>

<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'book-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model, 'genres'); ?>
        <?php echo $form->checkBoxList($model, 'genres', CHtml::listData(Genre::model()->findAll(), 'id', 'name'), array('multiple' => true, 'labelOptions' => array('style' => 'display:inline'))); ?>
    </div>

    <div class="row">
        <label>Additional authors</label>
        <?php
        $user = User::model()->find('username = :username', array(':username' => Yii::app()->user->name));
        $userList = User::model()->findAll();
        if (($key = array_search($user, $userList)) !== false) {
            unset($userList[$key]);
        }
        echo $form->checkBoxList(
            $model,
            'users',
            CHtml::listData($userList, 'id', 'username'),
            array('multiple' => true, 'labelOptions' => array('style' => 'display:inline')));
        ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'image_cover'); ?>
        <?php echo $form->fileField($model, 'image_cover'); ?>
        <?php echo $form->error($model, 'image_cover'); ?>
    </div>

    <div class="row">
        <label>File <span class="required">*</span></label>
        <?php echo $form->fileField($model, 'file'); ?>
        <?php echo $form->error($model, 'file'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Create'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->