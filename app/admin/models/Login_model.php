<?php
class Login_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	public function get_login(){
		$username = $this->input->post('username');
		$userpwd = $this->input->post('password');
		$query = $this->db->query("SELECT * FROM baseoption WHERE optionType='AdminContrl' AND optionKey='{$username}'");
		$row = $query->row();
		//echo password_hash($userpwd, PASSWORD_BCRYPT);
		return password_verify($userpwd, $row->optionValue);
	}
}
?>