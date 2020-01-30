<?php

class Users_model extends CI_Model{

    public function __construct(){
        parent::__construct();
        $this->load->database();

    }

    public function auth_check($data){
        $query = $this->db->get_where('users', $data);
        if($query){
            return $query->row();
        } else{
            return false;
        }
    }

    public function registeration($data){
        $query = $this->db->insert('users', $data);
        if($query){
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

}

?>