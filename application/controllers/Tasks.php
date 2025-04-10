<?php
class Tasks extends CI_Controller 
{

    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model(['Task_model', 'Project_model', 'Remark_model', 'Task_report_model']);
        $this->load->helper('jwt');

        $this->auth_user = $this->authenticate();
        if (!$this->auth_user) 
        {
        $this->output->set_status_header(401)->set_output(json_encode(['message' => 'Unauthorized']));
        exit;
        }
    }


  private function authenticate() 
  {
        $headers = $this->input->request_headers();
        if (!isset($headers['Authorization'])) return false;

        $token = str_replace("Bearer ", "", $headers['Authorization']);
        return validate_jwt($token);
    }

    public function report($project_id) 
    {
        $project = $this->Project_model->get_project($project_id, $this->auth_user->id);
        if (!$project) 
        {
            $this->output->set_status_header(403)->set_output(json_encode(['message' => 'Access denied']));
            return;
        }

        $task_report = $this->Task_report_model->get_task_report($project_id, $this->auth_user->id);
        $this->output->set_output(json_encode($task_report));
    }

  

    public function index($project_id) 
    {
        $project = $this->Project_model->get_project($project_id, $this->auth_user->id);
        if (!$project) 
        {
            $this->output->set_status_header(403)->set_output(json_encode(['message' => 'Access denied']));
            return;
        }

        $tasks = $this->Task_model->get_tasks($project_id);
        $this->output->set_output(json_encode($tasks));
    }

    public function create()
    {
    $data = json_decode(file_get_contents("php://input"));

    if (!$data->project_id || !$data->title) {
    $this->output
        ->set_status_header(400)
        ->set_output(json_encode(['message' => 'project_id and title are required']));
    return;
    }

    $project = $this->Project_model->get_project($data->project_id, $this->auth_user->id);
    if (!$project) 
    {
    $this->output
        ->set_status_header(403)
        ->set_output(json_encode(['message' => 'Access denied to this project']));
    return;
    }

    $task = [
    'project_id' => $data->project_id,
    'title' => $data->title,
    'description' => $data->description ?? '',
    'status' => 'Pending'
    ];

    $task_id = $this->Task_model->create_task($task);

    $this->output
    ->set_status_header(201)
    ->set_output(json_encode([
        'message' => 'Task created successfully.',
        'task_id' => $task_id
    ]));
    }


    public function update_status($task_id) 
    {
    $task = $this->Task_model->get_task_by_id($task_id);

    if (!$task) 
    {
        $this->output->set_status_header(404)->set_output(json_encode(['message' => 'Task not found']));
        return;
    }

    $project = $this->Project_model->get_project($task->project_id, $this->auth_user->id);

    if (!$project) 
    {
        $this->output->set_status_header(403)->set_output(json_encode(['message' => 'Access denied']));
        return;
    }

    $data = json_decode(file_get_contents("php://input"));

    if (!in_array($data->status, ['Pending', 'In Progress', 'Completed'])) 
    {
        $this->output->set_status_header(400)->set_output(json_encode(['message' => 'Invalid status']));
        return;
    }

    $this->load->model('Task_status_history_model');
    $this->Task_status_history_model->log_status_change($task_id, $data->status);

    $this->Task_model->update_task_status($task_id, $data->status);
    $this->output->set_output(json_encode(['message' => 'Task status updated']));
}


    public function add_remark($task_id) 
    {
        $task = $this->Task_model->get_task_by_id($task_id);

        if (!$task) 
        {
            $this->output->set_status_header(404)->set_output(json_encode(['message' => 'Task not found']));
            return;
        }

        $project = $this->Project_model->get_project($task->project_id, $this->auth_user->id);
        if (!$project) 
        {
            $this->output->set_status_header(403)->set_output(json_encode(['message' => 'Access denied']));
            return;
        }

        $data = json_decode(file_get_contents("php://input"));
        if(!$data->remark) 
        {
            $this->output->set_status_header(400)->set_output(json_encode(['message' => 'Remark required']));
            return;
        }

        $remark = [
            'task_id' => $task_id,
            'remark' => $data->remark,
            'remark_date' => date('Y-m-d')
        ];

        $this->Remark_model->add_remark($remark);
        $this->output->set_status_header(201)->set_output(json_encode(['message' => 'Remark added']));
    }

    public function get_remarks($task_id) 
    {
        $task = $this->Task_model->get_task_by_id($task_id);

        if (!$task) 
        {
            $this->output->set_status_header(404)->set_output(json_encode(['message' => 'Task not found']));
            return;
        }

        $project = $this->Project_model->get_project($task->project_id, $this->auth_user->id);
        
        if (!$project) 
        {
            $this->output->set_status_header(403)->set_output(json_encode(['message' => 'Access denied']));
            return;
        }

        $remarks = $this->Remark_model->get_remarks_by_task($task_id);
        $this->output->set_output(json_encode($remarks));
    }
}
