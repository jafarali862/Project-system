<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller 
{

public function __construct() 
{
    parent::__construct();
    $this->load->model('User_model');
}

public function register() 
{
    $data = json_decode(file_get_contents("php://input"));

    if (!$data->email || !$data->password) 
    {
        $this->output->set_status_header(400)->set_output(json_encode(['message' => 'Missing email or password']));
        return;
    }

    if ($this->User_model->get_user_by_email($data->email)) 
    {
        $this->output->set_status_header(409)->set_output(json_encode(['message' => 'Email already exists']));
        return;
    }

    $user = [
        'email' => $data->email,
        'password' => password_hash($data->password, PASSWORD_BCRYPT)
    ];

    $this->User_model->insert_user($user);

    $this->output->set_status_header(201)->set_output(json_encode(['message' => 'User registered successfully']));
}

public function login() 
{
    $data = json_decode(file_get_contents("php://input"));

    $user = $this->User_model->get_user_by_email($data->email);

    if (!$user || !password_verify($data->password, $user->password)) 
    {
        $this->output->set_status_header(401)->set_output(json_encode(['message' => 'Invalid credentials']));
        return;
    }

    $token = generate_jwt(['id' => $user->id, 'email' => $user->email]);

    $this->output->set_output(json_encode(['token' => $token]));
}

public function profile()
 {
    $headers = $this->input->request_headers();
    if (!isset($headers['Authorization'])) 
    {
        $this->output->set_status_header(401)->set_output(json_encode(['message' => 'Missing token']));
        return;
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);
    $user_data = validate_jwt($token);

    if (!$user_data) 
    {
        $this->output->set_status_header(401)->set_output(json_encode(['message' => 'Invalid token']));
        return;
    }

    $this->output->set_output(json_encode([
        'message' => 'Access granted',
        'user' => $user_data
    ]));
}
}
