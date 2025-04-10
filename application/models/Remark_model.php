<?php
class Remark_model extends CI_Model 
{
    public function add_remark($data) 
    {
    return $this->db->insert('task_remarks', $data);
    }

    public function get_remarks_by_task($task_id) 
    {
        return $this->db->get_where('task_remarks', ['task_id' => $task_id])->result();
    }
}
