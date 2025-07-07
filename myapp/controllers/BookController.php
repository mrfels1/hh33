<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Book;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

use OpenApi\Attributes as OAT;

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

    #[OAT\Get(
        path: "/books",
        summary: "Список книг",
        description: "Получить список книг с возможностью фильтрации по названию/описанию и авторам",
        tags: ["Book"],
        parameters: [
            new OAT\Parameter(
                name: "search",
                in: "query",
                required: false,
                description: "Поиск по названию или описанию книги",
                schema: new OAT\Schema(type: "string")
            ),
            new OAT\Parameter(
                name: "author[]",
                in: "query",
                required: false,
                description: "Фильтр по id авторов",
                schema: new OAT\Schema(type: "array", items: new OAT\Items(type: "integer"))
            )
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: "Список книг",
                content: [
                    new OAT\MediaType(
                        mediaType: "application/json",
                        schema: new OAT\Schema(
                            type: "array",
                            items: new OAT\Items(ref: '#/components/schemas/BookResponse',)
                        )
                    )
                ]
            )
        ]
    )]


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

    #[OAT\Post(
        path: "/books",
        summary: "Создать новую книгу",
        tags: ["Book"],
        requestBody: new OAT\RequestBody(
            required: true,
            content: [new OAT\MediaType(mediaType: 'application/json', schema: new OAT\Schema(ref: Book::class))]
        ),
        responses: [
            new OAT\Response(
                response: 201,
                description: "Кинига создана",
                content: [new OAT\MediaType(mediaType: 'application/json', schema: new OAT\Schema(ref: Book::class))]
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
        $model = new Book();

        $model->load(\Yii::$app->request->getBodyParams(), '');

        if ($model->validate() && $model->save()) {
            \Yii::$app->response->statusCode = 201;
            return $model;
        }

        \Yii::$app->response->statusCode = 422;
        return ['errors' => $model->getErrors()];
    }

    #[OAT\Put(
        path: "/books/{id}",
        summary: "Обновление объекта книги",
        tags: ["Book"],
        parameters: [
            new OAT\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "id изменяеемого книги",
                schema: new OAT\Schema(type: "integer")
            )
        ],
        requestBody: new OAT\RequestBody(
            required: true,
            content: [new OAT\MediaType(mediaType: 'application/json', schema: new OAT\Schema(ref: Book::class))]
        ),
        responses: [
            new OAT\Response(
                response: 200,
                description: "Книга обновлена",
                content: [new OAT\MediaType(mediaType: 'application/json', schema: new OAT\Schema(ref: Book::class))]
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

    #[OAT\Delete(
        path: "/books/{id}",
        summary: "Удалить книгу",
        tags: ["Book"],
        parameters: [
            new OAT\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "id удаляемой книги",
                schema: new OAT\Schema(type: "integer")
            )
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: "Книга успешно удалена",
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
                description: 'Книга не найдена',
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
