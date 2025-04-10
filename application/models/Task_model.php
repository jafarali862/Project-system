<?php
class Task_model extends CI_Model 
{
    public function get_tasks($project_id) 
    {
        return $this->db->get_where('tasks', ['project_id' => $project_id])->result();
    }

    public function create_task($task_data)
    {
    $this->db->insert('tasks', $task_data);
    return $this->db->insert_id();
    }

    public function update_task_status($id, $status) 
    {
    return $this->db->update('tasks', ['status' => $status], ['id' => $id]);
    }

    public function get_task_by_id($id)
    {
     return $this->db->get_where('tasks', ['id' => $id])->row();
    }
}
