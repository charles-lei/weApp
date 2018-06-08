<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use QCloud_WeApp_SDK\Constants as Constants;

class Login extends CI_Controller {
    public function index() {
        //加载
        $this->load->database();
        $this->load->library('export');
        $this->load->model('students_model');

        $result = LoginService::login();
        if ($result['loginState'] === Constants::S_AUTH) {
          $openid = $result['userinfo']['userinfo']->openId;
          $profile = $result['userinfo']['userinfo']->avatarUrl;
          $nickname = $result['userinfo']['userinfo']->nickName;
          $student = $this->students_model->getStudentByOpenId($openid);
          if (empty($student)) {
              $insertData = array(
                  'openid' => $openid,
                  'profile' => $profile,
                  'nickname' => $nickname,
                  'created_at' => date("Y-m-d H:i:s"),
                  'updated_at' => date("Y-m-d H:i:s"),
              );
              $res = $this->students_model->insert($insertData);
              if ($res == false) {
                  $this->export->operateFailed();
              }
          } else {
              $student->openid =  $openid;
              $student->profile = $profile;
              $student->nickname = $nickname;
              $this->students_model->update($student);
          }
          $this->json([
              'code' => 0,
              'data' => $result['userinfo']
            ]);
        } else {
           $this->json([
              'code' => -1,
              'error' => $result['error']
            ]);
        }
    }
}
