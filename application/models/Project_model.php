<?php
class Project_model extends CI_Model 
{

    public function get_projects_by_user($user_id) 
    {
     return $this->db->get_where('projects', ['user_id' => $user_id])->result();
    }

	public function create_project($project_data)
	{
	$this->db->insert('projects', $project_data);
	return $this->db->insert_id();
	}

    public function get_project($id, $user_id) 
    {
     return $this->db->get_where('projects', ['id' => $id, 'user_id' => $user_id])->row();
    }

    public function update_project($id, $user_id, $data) 
    {
      $this->db->where(['id' => $id, 'user_id' => $user_id]);
       return $this->db->update('projects', $data);
    }

    public function delete_project($id, $user_id) 
    {
      return $this->db->delete('projects', ['id' => $id, 'user_id' => $user_id]);
    }
}
