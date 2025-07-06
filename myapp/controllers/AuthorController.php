<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Author;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class AuthorController extends Controller
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
                'debug' => ['POST'], // /author/debug не /authors/debug
            ],
        ];
        return $behaviors;
    }
    public function actionIndex()
    {
        $query = Author::find()
            ->select(['name', 'birth_year', 'country']);

        return $query->all();
    }
    public function actionCreate()
    {
        $model = new Author();

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
        $model = Author::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Author not found");
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
        $model = Author::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Author not found");
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
