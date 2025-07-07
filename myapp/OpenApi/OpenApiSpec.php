<?php

declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace app\docs;

use OpenApi\Attributes as OA;

/**
 * The Spec.
 */
#[OA\OpenApi(openapi: OA\OpenApi::VERSION_3_1_0)]
#[OA\Info(
    version: '1.0.0',
    title: 'HH33 API Specification',
    attachables: [new OA\Attachable()]
)]
#[OA\Server(url: 'https://localhost', description: 'API server')]
#[OA\Tag(name: 'Author', description: 'Author API')]
#[OA\Tag(name: 'Book', description: 'Book API')]
class OpenApiSpec {}
