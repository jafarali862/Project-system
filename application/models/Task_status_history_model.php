<?php
class Task_status_history_model extends CI_Model 
{
    public function log_status_change($task_id, $status) 
    {
        $data = [
            'task_id' => $task_id,
            'status' => $status,
            'changed_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('task_status_history', $data);
    }
}
