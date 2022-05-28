<?php


namespace App\Traits;


use Illuminate\Http\Response;

trait ManagesResponse
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @param string $status
     * @return Response
     */
    public function sendResponse($result, $message, $status = 'success')
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'status' => $status,
        ];

        return response()->json($response, 200, [], JSON_INVALID_UTF8_SUBSTITUTE );
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'status' => 'error',
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code, [], JSON_INVALID_UTF8_SUBSTITUTE );
    }
}
