<?php

namespace App\Controllers;

use App\Repositories\ProductRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Valitron\Validator;

class Products
{
    public function __construct(private ProductRepository $repository, private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'name' => ['required'],
            'size' => ['required', 'integer', ['min', 1]]
        ]);
    }
    public function show(Request $request, Response $response, string $id): Response
    {
        try {
            $product = $request->getAttribute('product');
            $body = json_encode($product);
            $response->getBody()->write($body);
            return $response;
        } catch (\Exception $exception) {
            throw new HttpInternalServerErrorException($request, 'Error while trying to get');
        }
    }
    public function create(Request $request, Response $response): Response
    {
        try {
            $body = $request->getParsedBody();

            $this->validator = $this->validator->withData($body);

            if (!$this->validator->validate()) {

                $response->getBody()
                        ->write(json_encode($this->validator->errors()));

                return $response->withStatus(422);
            }

            $id = $this->repository->create($body);

            $body = json_encode([
                'message' => 'Product created',
                'id' => $id
            ]);

            $response->getBody()->write($body);

            return $response->withStatus(201);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function update(Request $request, Response $response, string $id) : Response{
        try {
            $body = $request->getParsedBody();

            $this->validator = $this->validator->withData($body);

            if (!$this->validator->validate()) {
                $response->getBody()
                        ->write(json_encode($this->validator->errors()));

                return $response->withStatus(422);
            }

            $this->repository->update((int) $id, $body);

            $response->getBody()->write(json_encode(['message' => 'Product updated']));

            return $response;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function delete(Request $request, Response $response, string $id) : Response
    {
        try {
            $deleted = $this->repository->delete((int) $id);
            if($deleted)
                $response->getBody()->write(json_encode(['message' => 'Product deleted']));
            else
                $response->getBody()->write(json_encode(['message' => 'Product not found']));
            return $response;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
