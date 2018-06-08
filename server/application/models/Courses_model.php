<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: zhaoguanghui03@baidu.com
 * Date: 2018/5/19
 * Time: 下午10:28
 */
class Courses_model extends CI_Model
{
    /**
     * isOnCourse 是否在上课时间内
     * @param int $beforeMinute 可以提前多久,适用于提前签到
     * @param int $afterMinute 可以延后多久,适用于拖堂、抽奖等
     * @return mixed
     */
    public function isOnCourse($beforeMinute = 0, $afterMinute = 0)
    {
        $this->load->database();
        $currentTime1 = date("Y-m-d H:i:s", time() + 60 * $beforeMinute);
        $currentTime2 = date("Y-m-d H:i:s", time() - 60 * $afterMinute);
        $where = "start_time <= '{$currentTime1}' and end_time >= '{$currentTime2}'";
        $query = $this->db->get_where('courses', $where);
        $rowObj = $query->row();
        if (!empty($rowObj)) {
            return $rowObj->id;
        } else {
            false;
        }
    }
}