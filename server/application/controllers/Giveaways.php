<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Giveaways extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $result = LoginService::check();

        if ($result['loginState'] !== Constants::S_AUTH) {
            $this->export->error(405, "invalid sessionid");
        }

        $this->load->database();
        $this->load->library('export');
        $this->load->library('node');
        $this->load->model('courses_model');
        $this->load->model('giveaways_model');
        $this->load->model('students_model');

        //根据sessionid获取用户openid
        $openid = $result['userinfo']->openId;
        $student = $this->students_model->getStudentByOpenId($openid);

        //判断是否可抽奖
        $courseId = $this->courses_model->isOnCourse();
        if ($courseId == false) {
            $this->export->error(450, "not on course time");
        }
        $currentNodes = $this->giveaways_model->getNodesByCourseId($courseId);
        if ($currentNodes != false) {
            $this->export->error(451, "no chance");
        }
        //业务逻辑
        $sendNodesCount = mt_rand(1, 200);//生成范围内随机整数
        $sendRet = $this->node->sendNode($student->wallet_address, $sendNodesCount);
        if ($sendRet === false) {
            $this->export->error(550, "send node failed");
        }
        $data = array(
            'course_id' => $courseId,
            'student_id' => $student->id,
            'nodes' => $sendNodesCount,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'is_sent' => 1,
        );
        $res = $this->db->insert('giveaways', $data);
        //结果处理与返回
        if ($res == false) {
            $this->export->operateFailed();
        } else {
            $data = array(
                'nodes' => $sendNodesCount,
            );
            $this->export->ok($data);
        }
	}


}
