<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use DomainException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
        DomainException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];

            return $this->errorResponse($this->defaultResponse($message), $code);
        }
        if ($exception instanceof ModelNotFoundException) {
            $message = $this->parseModelName($exception->getModel()) . " no existe";
            return $this->errorResponse($this->defaultResponse($message), 404);
        }
        if ($exception instanceof DomainException) {
            $message = $exception->getMessage();
            return $this->errorResponse($this->defaultResponse($message), 400);
        }
        if ($exception instanceof UnauthorizedException) {
            return $this->errorResponse(
                $this->defaultResponse('unauthorized'),
                403
            );
        }

        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse($this->defaultResponse('Unexpected error. Try later'), Response::HTTP_INTERNAL_SERVER_ERROR);

    }

    public function parseModelName($model_name){
        return last(explode("\\",$model_name));
    }
}
