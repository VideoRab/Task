<?php

class BookController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'download'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'delete'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new BookForm;
        if (isset($_POST['BookForm'])) {
            $model->attributes = $_POST['BookForm'];
            $book = new Book;
            if ($model->validate()) {
                $book->title = $model->title;
                $book->description = $model->description;
                $book->image_cover = $this->getImageDataFromModel($model);
                $book->file = $this->getFileDataFromModel($model);
            }
            if ($book->save()) {
                $bookUser = new BookUser;
                $user = User::model()->find('username = :username', array(':username' => Yii::app()->user->name));
                $bookUser->user_id = $user->id;
                $bookUser->book_id = $book->id;
                $bookUser->save();
                foreach ($model->users as $user) {
                    $bookUser = new BookUser;
                    $bookUser->user_id = $user;
                    $bookUser->book_id = $book->id;
                    $bookUser->save();
                }

                foreach ($model->genres as $genre) {
                    $bookGenre = new BookGenre;
                    $bookGenre->book_id = $book->id;
                    $bookGenre->genre_id = $genre;
                    $bookGenre->save();
                }
                $this->redirect(array('view', 'id' => $book->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionDownload($id)
    {
        $model = Book::model()->findByPk($id);
        if($model === null) {
            throw new CHttpException(404, 'File not found');
        }

        $content = $model->file;
        $filename = $model->title;
        $filesize = strlen($content);
        $mimeType = 'application/pdf';

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="'.$filename.'.pdf');
        header('Content-Length: '.$filesize);

        echo $content;
    }
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $bookForm = new BookForm();
        $bookForm->id = $model->id;
        $bookForm->title = $model->title;
        $bookForm->description = $model->description;

        if (isset($_POST['BookForm'])) {
            print_r($_POST['BookForm']);
            $bookForm->attributes = $_POST['BookForm'];

            echo $bookForm->image_cover != null;
            if ($bookForm->title != null) {
                $model->title = $bookForm->title;
            }

            if ($bookForm->description != null) {
                $model->description = $bookForm->description;
            }

            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $bookForm,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        $this->redirect(array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Book');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Book the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Book::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    private function getImageDataFromModel($model) {
        $image = CUploadedFile::getInstance($model, 'image_cover');
        if ($image != null) {
            return file_get_contents($image->tempName);
        }

        return null;
    }

    private function getFileDataFromModel($model) {
        $pdfFile = CUploadedFile::getInstance($model, 'file');
        if ($pdfFile != null) {
            return file_get_contents($pdfFile->tempName);
        }

        return null;
    }

    /**
     * Performs the AJAX validation.
     * @param Book $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'book-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
