<?php
class Task_report_model extends CI_Model 
{

    public function get_task_report($project_id, $user_id) 
    {
    $tasks = $this->db->get_where('tasks', ['project_id' => $project_id])->result();

    foreach ($tasks as $task)
    {
        $task->status_history = $this->db->order_by('changed_at', 'ASC')
                                          ->get_where('task_status_history', ['task_id' => $task->id])
                                          ->result();

        $task->remarks = $this->db->get_where('task_remarks', ['task_id' => $task->id])->result();
    }

    return $tasks;
}

}
