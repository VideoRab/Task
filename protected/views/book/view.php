<?php
/* @var $this BookController */
/* @var $model Book */

$this->breadcrumbs = array(
    'Books' => array('index'),
    $model->title,
);
if (in_array(Yii::app()->user->name, array_map(
        function ($user) {
            return $user->username;
        }, $model->users)
)) {
    $this->menu = array(
        array('label' => 'List Book', 'url' => array('index')),
        array('label' => 'Update Book', 'url' => array('update', 'id' => $model->id)),
        array('label' => 'Delete Book', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    );
}
else{
    $this->menu = array(
        array('label' => 'List Book', 'url' => array('index')),
    );
}
?>
<div class="view">

    <h1><?php echo $model->title; ?></h1>

    <img src="data:image/jpeg;base64,<?php
    if ($model->image_cover != null) {
        echo base64_encode($model->image_cover);
    }
    ?>" style="width: 200px; height: auto" alt="Image"/>
    <br/>
    <br/>

    <b>Genres:</b>
    <?php
    echo implode(', ', array_map(function ($genre) {
        return $genre->name;
    }, $model->genres));
    ?>

    <br/>
    <b>Authors:</b>
    <?php
    echo implode(', ', array_map(function ($user) {
        return $user->username;
    }, $model->users));
    ?>
    <br/>

    <b><?php echo CHtml::encode($model->getAttributeLabel('description')); ?>:</b>
    <?php echo CHtml::encode($model->description); ?>
    <br/>

    <b><?php echo CHtml::encode($model->getAttributeLabel('file')); ?>:</b>
    <a href="<?php echo $this->createUrl('book/download', array('id' => $model->id)); ?>">
        <?php echo $model->title . ".pdf" ?>
    </a>
    <br/>
</div>
