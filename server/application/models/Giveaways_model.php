<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class Giveaways_model extends CI_Model
{
    public function getNodesByCourseId($courseId)
    {
        $this->load->database();
        $query = $this->db->get_where('giveaways', array('course_id' => $courseId));
        $rowObj = $query->row();
        if (!empty($rowObj)) {
            return $rowObj->nodes;
        } else {
            false;
        }
    }
}