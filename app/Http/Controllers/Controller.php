<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
    }

    protected function display($result)
    {
        return $result;
    }

    protected function success($data = [], $message = '操作成功')
    {
        return ['status' => true, 'data' => $data, 'message' => $message];
    }

    protected function error($data = [], $message = '操作失败')
    {
        return ['status' => false, 'data' => $data, 'message' => $message];
    }
}
