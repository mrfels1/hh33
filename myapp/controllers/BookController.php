<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Book;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class BookController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index' => ['GET'],
                'create' => ['POST'],
                'update' => ['PUT', 'PATCH'], //    ??? 
                'delete' => ['DELETE'],
                'debug' => ['POST'], // /book/debug не /books/debug
            ],
        ];
        return $behaviors;
    }
    public function actionIndex()
    {
        $query = Book::find();

        $query->select(['title', 'author_id', 'pages', 'language', 'genre']);

        $request = \Yii::$app->request;

        $search = $request->get('search');
        if (!empty($search)) {
            $query->andWhere([
                'or',
                ['like', 'title', $search],
                ['like', 'description', $search],
            ]);
        }

        $authorIds = $request->get('author');
        if (is_array($authorIds) && !empty($authorIds)) {
            $query->andWhere(['author_id' => $authorIds]);
        }

        return $query->all();
    }
    public function actionCreate()
    {
        $model = new Book();

        $model->load(\Yii::$app->request->getBodyParams(), '');

        if ($model->validate() && $model->save()) {
            \Yii::$app->response->statusCode = 201;
            return $model;
        }

        \Yii::$app->response->statusCode = 422;
        return ['errors' => $model->getErrors()];
    }
    public function actionUpdate($id)
    {
        $model = Book::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Book not found");
        }

        $model->load(\Yii::$app->request->getBodyParams(), '');

        if ($model->validate() && $model->save()) {
            return $model;
        }

        \Yii::$app->response->statusCode = 422;
        return ['errors' => $model->getErrors()];
    }
    public function actionDelete($id)
    {
        $model = Book::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Book not found");
        }

        if ($model->delete() === false) {
            \Yii::$app->response->statusCode = 500;
            return ['errors' => 'Delete failed'];
        }

        return ['message' => "Removed"];
    }

    public function actionDebug()
    {
        return [
            'method' => \Yii::$app->request->method,
            'contentType' => \Yii::$app->request->contentType,
            'rawBody' => \Yii::$app->request->rawBody,
            'bodyParams' => \Yii::$app->request->getBodyParams(),
        ];
    }
};
