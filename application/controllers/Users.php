<?php

class Users extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('url', 'html', 'form'));
        $this->load->model('users_model');
        $this->load->library(array('form_validation', 'session'));
        $userId = $this->session->userdata('userid');
    }

    public function index(){
        $this->load->view('login');
    }

    public function post_login(){
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>s');
        $this->form_validation->set_message('required', 'Enter %s');

        if($this->form_validation->run() === FALSE){
            $this->load->view('login');
        } else {
            $data = array(
                'email' => $this->input->post('email'),
                'password' => md5($this->input->post('password'))
            );
            $check = $this->users_model->auth_check($data);
            if($check != FALSE){
                $userdata = array(
                    'userid' => $check->id,
                    'name' => $check->name,
                    'email' => $check->email
                );
                $this->session->set_userdata($userdata);
                redirect(base_url('users/dashboard'));
            } else {
                redirect(base_url('login'));
            }
        }
    }

    public function register(){
        $this->load->view('register');
    }

    public function post_register(){
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_error_delimiters('<div class="errors>', '</div>');
        $this->form_validation->set_message('required', 'Enter %s');

        if($this->form_validation->run() ===  FALSE){
            redirect(base_url('register'));
        } else {
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'name' => $this->input->post('name'),
                'password' => md5($this->input->post('password'))
            );
            $check = $this->users_model->registeration($data);
            if($check != false){
                $userdata = array(
                    'userid' => $check->id,
                    'name' => $check->name,
                    'email' => $check->email
                );
                $this->session->set_userdata($userdata);
                redirect(base_url('users/dashboard'));
            } else {
                redirect(base_url('register'));
            }
        }
    }

    public function dashboard(){
        if(!empty($this->session->userdata('userid'))){
            $this->load->view('dashboard');
        } else{
            redirect(base_url('users/index'));
        }
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url('users/index'));
        redirect(base_url('users/register'));
    }
}


?>