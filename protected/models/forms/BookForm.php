<?php

class BookForm extends CFormModel
{
    public $id;
    public $title;
    public $description;
    public $image_cover;
    public $genres;
    public $users;
    public $file;

    public function rules()
    {
        return array(
            array('file', 'file', 'allowEmpty' => false, 'types' => 'pdf', 'maxSize' => 1024 * 1024 * 1024 * 4, 'tooLarge' => 'File has to be smaller than 4GB'),
            array('title, description, genres, users', 'required'),
            array('title, description', 'length', 'max' => 255),
            array('image_cover', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'title' => 'Title',
            'genres' => 'Genres',
            'users' => 'Users',
            'description' => 'Description',
            'image_cover' => 'Image Cover',
            'file' => 'File',
        );
    }
}