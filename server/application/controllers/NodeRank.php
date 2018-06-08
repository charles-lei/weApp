<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NodeRank extends CI_Controller {

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


    /**
     * selfRank 全球排名
     */
    public function rankPage()
    {
        //加载类库
        $this->load->database();
        $this->load->library('export');
        $this->load->model('noderank_model');

        //参数获取
        $start = $this->input->get_post('start');
        $num = $this->input->get_post('num');
        //参数处理
        $start = $start ? $start : 0;
        $num = $num ? $num : 20;

        $total = $this->noderank_model->getTotal();

        $res = $this->noderank_model->getRankList($start, $num);

        //结果处理与返回
        if ($res === false || $total === false) {
            $this->export->operateFailed();
        } else {
            $this->export->ok(array(
                    'list' => $res,
                    'total' => $total,
                )
            );
        }
    }
    public function selfRank()
    {
        $result = LoginService::check();

        if ($result['loginState'] !== Constants::S_AUTH) {
            $this->export->error(405, "invalid sessionid");
        }

        $this->load->database();
        $this->load->library('export');
        $this->load->model('students_model');
        $this->load->model('noderank_model');
        $address = $this->input->get_post('address');

        //根据sessionid获取用户openid
        $openid = $result['userinfo']->openId;
        $student = $this->students_model->getStudentByOpenId($openid);
        $selfRank = $this->noderank_model->getSelfRank($student->id);

        //结果处理与返回
        if ($selfRank == false) {
            $this->export->operateFailed();
        } else {
            $this->export->ok(array(
                'self_rank' => $selfRank,
            ));
        }
    }

}
