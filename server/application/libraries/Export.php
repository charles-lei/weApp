<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class Export
{
    public function ok($data = array())
    {
        $output = array(
            'code'   => 200,
            'message'  => 'ok',
            'data'  => $data,
        );
        echo json_encode($output);
        exit;
    }
    public function paramError()
    {
        $output = array(
            'code'   => 400,
            'message'  => 'user param error',
        );
        echo json_encode($output);
        exit;
    }

    public function operateFailed()
    {
        $output = array(
            'code'   => 500,
            'message'  => 'internal server error',
        );
        echo json_encode($output);
        exit;
    }

    /**
     * error 通用错误,推荐4XX代表用户错误,5XX代表服务器处理错误
     * @param $code
     * @param $message
     */
    public function error($code,$message)
    {
        $output = array(
            'code'   => $code,
            'message'  => $message,
        );
        echo json_encode($output);
        exit;
    }
}