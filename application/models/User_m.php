<?php

class User_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->library('datagrid');
	}

	/**
     * Check User Credentials
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */
	
	public function attempt($input)
	{
		$query = $this->db->from('users u')
						->select('u.*, g.group_name')
						->where('email', $input['email'])
						->where('password', $input['password'])
						->join('groups as g', 'g.id = u.id', 'left')
						->get();

		return $query->row();
	}

	/**
     * Get User by ID
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function get_user($id)
	{
		$query = $this->db->from('users u')
						->select('u.*, g.group_name')
						->where('u.id', $id)
						->join('groups as g', 'g.id = u.id', 'left')
						->get();

		return $query->row();
	}

	/**
     * Datagrid Data
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function getJson($input)
	{
		$table  = 'users as a';
		$select = 'a.*, g.group_name';

		$replace_field  = [
			['old_name' => 'name', 'new_name' => 'a.name'],
			['old_name' => 'group_name', 'new_name' => 'g.group_name']
		];

		$param = [
			'input'     => $input,
			'select'    => $select,
			'table'     => $table,
			'replace_field' => $replace_field
		];

		$data = $this->datagrid->query($param, function($data) use ($input) {
			return $data->join('groups as g', 'g.id = a.group_id', 'left')
						->where('a.id !=', $this->session->userdata('active_user')->id);
		});

		return $data;
	}

}