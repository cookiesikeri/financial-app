<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use App\Traits\ManagesResponse;

class Handler extends ExceptionHandler
{
    use ManagesResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @param $request
     * @param Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */

    public function render($request, Throwable $e) {
        switch($e) {
            case ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException):
                return response()->json(["error" => $e->getMessage()], 401);
                break;
            case ($e instanceof WithdrawalLimitException):
                //return response()->view('errors.withdrawal', [], 405);
//                return response()->json(['message'=>'You can not withdraw above your daily limit of '.auth()->user()->withdrawl_limit].' naira');
                break;
            case ($e instanceof \Spatie\Permission\Exceptions\UnauthorizedException):
                return $this->sendError('You do not have the required authorization.',[], 403);
                break;
            case ($e instanceof \Spatie\Permission\Exceptions\RoleDoesNotExist):
                return $this->sendError('Role does not exist.',[], 403);
                break;
            case ($e instanceof \Spatie\Permission\Exceptions\RoleAlreadyExists):
                return $this->sendError('Role already exists.',[], 403);
                break;
            case ($e instanceof \Spatie\Permission\Exceptions\PermissionAlreadyExists):
                return $this->sendError('Permission already exists.',[], 403);
                break;
            case ($e instanceof \Spatie\Permission\Exceptions\PermissionDoesNotExist):
                return $this->sendError('Permission does not exist.',[], 403);
                break;
            default:
                return $this->sendError($e->getMessage(),[], 500);
//                return parent::render($request, $e);
        }
    }

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
