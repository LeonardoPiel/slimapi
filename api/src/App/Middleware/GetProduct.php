<?php

namespace App\Middleware;

use App\Repositories\ProductRepository;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

class GetProduct
{
    public function __construct(private ProductRepository $repository) {}
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        try {

            $context = RouteContext::fromRequest($request);
            $route = $context->getRoute();
            $id = $route->getArgument('id');
            $data = $this->repository->getById((int) $id);

            if (!$data) throw new HttpNotFoundException($request, 'empty data');    
            $request = $request->withAttribute('product', $data);
        
            return $handler->handle($request);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
