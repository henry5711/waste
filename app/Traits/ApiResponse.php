<?php


namespace App\Traits;


use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Build a success response
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse(array $data, $code = Response::HTTP_OK)
    {
        return response()->json($data, $code);
    }

    /**
     * Build error responses
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse(array $data, int $code)
    {
        $data['error'] =true;
        return response()->json($data, $code);
    }

    public function defaultResponse(string $message){
        return [
            "message"=>$message
        ];
    }
}