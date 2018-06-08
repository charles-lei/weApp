<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function isOnCourse()
    {
        $this->load->database();
        $this->load->library('export');
        $this->load->model('courses_model');
//        $wechatid = $this->input->get_post('wechatid');

        //提前十分钟可以算课程内
        $courseId = $this->courses_model->isOnCourse($beforeMinute = 10, $afterMinute = 0);

        $this->export->ok(
            array(
                'onCourse' => $courseId ? true : false
            )
        );
        return;
    }

}

