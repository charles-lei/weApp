<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class Node
{
    /**
     * sendNode 发送Node积分,调用js合约代码
     * @param string $walletAddress 接收者地址
     * @param int|double $sendNodesCount 数量
     * @return bool
     */
    public function sendNode($walletAddress, $sendNodesCount)
    {
        $env = ENVIRONMENT == 'production' ? 'mainnet' : 'testnet';
        //调试智能合约
        $cmd = "node contracts.js {$walletAddress} {$sendNodesCount} {$env}";
//        $cmd = "pwd";//测试用,待以上智能合约可用时,即可被替换
        exec($cmd, $output, $return_var);
        return $return_var != 0 ? false : true;
    }

    //todo 查询Node积分总量,需要依赖查询脚本
    public function queryNodeCount($walletAddress)
    {
        $env = ENVIRONMENT == 'production' ? 'mainnet' : 'testnet';
        //todo 调试智能合约
//        $cmd = "node contracts.js {$walletAddress} {$sendNodesCount} {$env}";
        $cmd = "pwd";//测试用,待以上智能合约可用时,即可被替换
        exec($cmd, $output, $return_var);
        if ($return_var != 0) {
            return false;
        }
        return true;
    }

}