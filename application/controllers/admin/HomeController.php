<?php

defined('BASEPATH') or exit('No direct script access allowed');

#[\AllowDynamicProperties]

class HomeController extends CI_Controller
{    
    /**
     * Method __construct
     *
     * @return void
     */
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }
    
    /**
     * Method index [Dashboard]
     *
     * @return void
     */
    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'dashboard';
            $settings = getSettings('system_settings', true);

            $this->data['title'] = 'Admin Panel | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Admin Panel | ' . $settings['app_name'];
            $this->data['curreny'] = getSettings('currency');

            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/auth/login', 'refresh');
        }
    }
    
    /**
     * Method profile
     *
     * @return void
     */
    public function profile()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['users'] = $this->ion_auth->user()->row();
            $settings = getSettings('system_settings', true);
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = FORMS . 'profile';
            $this->data['title'] = 'Change Password | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Change Password | ' . $settings['app_name'];
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/auth/login', 'refresh');
        }
    }
    
    /**
     * Method update_user
     *
     * @return void
     */
    public function updateUser()
    {
        $identity_column = $this->config->item('identity', 'ion_auth');
        $identity = $this->session->userdata('identity');
        $user = $this->ion_auth->user()->row();
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email|editUnique[users.email.' . $user->id . ']');
        $this->form_validation->set_rules('username', 'Username', 'required|xss_clean|trim');

        if (!empty($_POST['old']) || !empty($_POST['new']) || !empty($_POST['new_confirm'])) {
            $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
            $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]', ['matches' => 'The confirm password field does not match password field']);
            $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
        }


        $tables = $this->config->item('tables', 'ion_auth');
        if (!$this->form_validation->run()) {
            if (validation_errors()) {
                $response = [
                    'error' => true,
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                    'message' => validation_errors()
                ];

                echo json_encode($response);
                return false;
                exit();
            }
            if ($this->session->flashdata('message')) {
                $response = [
                    'error' => true,
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                    'message' => $this->session->flashdata('message')
                ];

                echo json_encode($response);
                return false;
                exit();
            }
        } else {

            if (!empty($_POST['old']) || !empty($_POST['new']) || !empty($_POST['new_confirm'])) {
                if (!$this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'))) {
                    // if the login was un-successful
                    $response = [
                        'error' => true,
                        'csrfName' => $this->security->get_csrf_token_name(),
                        'csrfHash' => $this->security->get_csrf_hash(),
                        'message' => $this->ion_auth->errors()
                    ];

                    echo json_encode($response);
                    return;
                    exit();
                }
            }
            $post = $this->input->post();
            $set = ['username' => $post['username'], 'email' => $post['email']];
            $set = escapeArray($set);
            $this->db->set($set)->where($identity_column, $identity)->update($tables['login_users']);
            $response = [
                'error' => false,
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                'message' => 'Profile Update Succesfully'
            ];
            
            echo json_encode($response);
            return;
        }
    }

}
