<?php

namespace app\controllers;

use yii\rest\Controller;

use app\models\Author;
use app\models\AuthorResponse;


use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use OpenApi\Attributes as OAT;


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

    #[OAT\Get(
        path: '/authors',
        tags: ['Author'],
        operationId: 'getGetauthors',
        summary: 'Получить список авторов',
        description: 'Возвращает список авторов без поля id.',
        responses: [
            new OAT\Response(
                response: 200,
                description: "Возвращает массив объектов авторов",
                content: [
                    new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(
                            type: 'array',
                            items: new OAT\Items(ref: '#/components/schemas/AuthorResponse',)
                        )
                    )
                ]
            )
        ]
    )]

    public function actionIndex()
    {
        $query = Author::find()
            ->select(['name', 'birth_year', 'country']);

        return $query->all();
    }


    #[OAT\Post(
        path: "/authors",
        summary: "Создать нового автора",
        tags: ["Author"],
        requestBody: new OAT\RequestBody(
            required: true,
            content: [new OAT\MediaType(mediaType: 'application/json', schema: new OAT\Schema(ref: Author::class))]
        ),
        responses: [
            new OAT\Response(
                response: 201,
                description: "Автор создан",
                content: [new OAT\MediaType(mediaType: 'application/json', schema: new OAT\Schema(ref: Author::class))]
            ),
            new OAT\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: [
                    new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: '#/components/schemas/ValidationError')
                    )
                ]
            ),
            new OAT\Response(
                response: 400,
                description: 'Ошибка запроса',
                content: [
                    new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: '#/components/schemas/HttpException')
                    )
                ]
            )
        ]
    )]

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

    #[OAT\Put(
        path: "/authors/{id}",
        summary: "Обновление объекта автора",
        tags: ["Author"],
        parameters: [
            new OAT\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "id изменяеемого автора",
                schema: new OAT\Schema(type: "integer")
            )
        ],
        requestBody: new OAT\RequestBody(
            required: true,
            content: [new OAT\MediaType(mediaType: 'application/json', schema: new OAT\Schema(ref: Author::class))]
        ),
        responses: [
            new OAT\Response(
                response: 200,
                description: "Автор обновлен",
                content: [new OAT\MediaType(mediaType: 'application/json', schema: new OAT\Schema(ref: Author::class))]
            ),
            new OAT\Response(
                response: 404,
                description: 'запись не найдена',
                content: [
                    new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: '#/components/schemas/HttpException')
                    )
                ]
            ),
            new OAT\Response(
                response: 400,
                description: 'Ошибка запроса',
                content: [
                    new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: '#/components/schemas/HttpException')
                    )
                ]
            ),
            new OAT\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: [
                    new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: '#/components/schemas/ValidationError')
                    )
                ]
            )
        ]
    )]

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

    #[OAT\Delete(
        path: "/authors/{id}",
        summary: "Удалить автора",
        tags: ["Author"],
        parameters: [
            new OAT\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID автора",
                schema: new OAT\Schema(type: "integer")
            )
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: "Автор успешно удален",
                content: [
                    new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(
                            type: 'object',
                            properties: [
                                new OAT\Property(
                                    property: 'message',
                                    type: 'string',
                                    example: 'Removed'
                                )
                            ],
                            required: ['message']
                        )
                    )
                ]
            ),
            new OAT\Response(
                response: 404,
                description: 'Автор не найден',
                content: [
                    new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: '#/components/schemas/HttpException')
                    )
                ]
            ),
            new OAT\Response(
                response: 500,
                description: 'Ошибка при удалении',
                content: [
                    new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(
                            type: 'object',
                            properties: [
                                new OAT\Property(
                                    property: 'errors',
                                    type: 'string',
                                    example: 'Delete failed'
                                )
                            ],
                            required: ['errors']
                        )
                    )
                ]
            )
        ]
    )]


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
