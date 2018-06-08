<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use QCloud_WeApp_SDK\Constants as Constants;

class Student extends CI_Controller
{
    /**
     * 绑定用户信息和openid
     * 需要先登录获取到sessionid
     * bindUser
     */
    public function bindUser()
    {
        $result = LoginService::check();

        if ($result['loginState'] !== Constants::S_AUTH) {
            $this->export->error(405, "invalid sessionid");
        }

        $this->load->database();
        $this->load->library('export');
        $this->load->model('students_model');

        $phone = $this->input->get_post('phone');
        $name = $this->input->get_post('name');

        if (!$phone || !$name) {
            $this->export->paramError();
        }

        //根据sessionid获取用户openid
        $openid = $result['userinfo']->openId;

        $student = $this->students_model->getStudentByOpenId($openid);

        $student->phone = $phone;
        $student->name = $name;
        $student->updated_at = date("Y-m-d H:i:s");

        $res = $this->students_model->update($student);
        if ($res == 1) {
            $this->export->ok();
        } else {
            $this->export->operateFailed();
        }
    }

    /**
     * 绑定地址(可重复绑定)
     * bindAddress
     */
    public function bindAddress()
    {
        $result = LoginService::check();

        if ($result['loginState'] !== Constants::S_AUTH) {
            $this->export->error(405, "invalid sessionid");
        }
        $this->load->database();
        $this->load->library('export');
        $this->load->model('students_model');
        $address = $this->input->get_post('address');

        if (empty($sessionId) || empty($address)) {
            $this->export->paramError();
        }

        //根据sessionid获取用户openid
        $openid = $result['userinfo']->openId;
        $student = $this->students_model->getStudentByOpenId($openid);

        //结果处理与返回
        $student->wallet_address = $address;
        $student->updated_at = date("Y-m-d H:i:s");
        $res = $this->students_model->update($student);
        if ($res == 1) {
            $this->export->ok();
        } else {
            $this->export->operateFailed();
        }
    }

    public function getInfo()
    {
        $result = LoginService::check();

        if ($result['loginState'] !== Constants::S_AUTH) {
            $this->export->error(405, "invalid sessionid");
        }

        $this->load->database();
        $this->load->library('export');
        $this->load->model('students_model');
        //根据sessionid获取用户openid
        $openid = $result['userinfo']->openId;

        $student = $this->students_model->getStudentByOpenId($openid);
        if (!empty($student)) {
            $this->export->ok($student);
        } else {
            $this->export->operateFailed();
        }
    }

}

