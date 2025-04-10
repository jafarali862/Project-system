<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Project_model');
        $this->load->model('User_model');
        $this->load->helper('jwt');

        // Authenticate JWT on all requests
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

    public function index() 
    {
        $projects = $this->Project_model->get_projects_by_user($this->auth_user->id);
        $this->output->set_output(json_encode($projects));
    }

    
    public function create()
    {
    $data = json_decode(file_get_contents("php://input"));

    if (!$data->title) 
    {
    $this->output
        ->set_status_header(400)
        ->set_output(json_encode(['message' => 'Title is required']));
    return;
    }

    $project = [
    'title' => $data->title,
    'description' => $data->description ?? '',
    'user_id' => $this->auth_user->id
    ];

    $project_id = $this->Project_model->create_project($project); // ✅ Get inserted ID

    $this->output
    ->set_status_header(201)
    ->set_output(json_encode([
        'message' => 'Project created successfully.',
        'project_id' => $project_id 
    ]));
    }


    public function update($id) 
    {
        $data = json_decode(file_get_contents("php://input"));
        $project = $this->Project_model->get_project($id, $this->auth_user->id);

        if (!$project) 
        {
            $this->output->set_status_header(404)->set_output(json_encode(['message' => 'Project not found']));
            return;
        }

        $update_data = [];
        if (isset($data->title)) $update_data['title'] = $data->title;
        if (isset($data->description)) $update_data['description'] = $data->description;

        $this->Project_model->update_project($id, $this->auth_user->id, $update_data);
        $this->output->set_output(json_encode(['message' => 'Project updated']));
    }

    public function delete($id) 
    {
        $project = $this->Project_model->get_project($id, $this->auth_user->id);
        if (!$project) 
        {
            $this->output->set_status_header(404)->set_output(json_encode(['message' => 'Project not found']));
            return;
        }

        $this->Project_model->delete_project($id, $this->auth_user->id);
        $this->output->set_output(json_encode(['message' => 'Project deleted']));
    }
}
