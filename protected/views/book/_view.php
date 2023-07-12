<?php
/* @var $this BookController */
/* @var $data Book */
?>

<div class="view">

    <img src="data:image/jpeg;base64,<?php
    if ($data->image_cover != null) {
        echo base64_encode($data->image_cover);
    }
    ?>" style="width: 200px; height: auto" alt="Image"/>
    <br/>

    <b><?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id' => $data->id)); ?></b>
    <br/>

    <b>Genres:</b>
    <?php
    echo implode(', ', array_map(function ($genre) {
        return $genre->name;
    }, $data->genres));
    ?>
    <br/>

    <b>Authors:</b>
    <?php
    echo implode(', ', array_map(function ($user) {
        return $user->username;
    }, $data->users));
    ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
    <?php echo CHtml::encode($data->description); ?>
    <br/>


</div>