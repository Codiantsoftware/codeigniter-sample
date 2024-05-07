<?php

defined('BASEPATH') or exit('No direct script access allowed');

#[\AllowDynamicProperties]

class AuthController extends CI_Controller
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
     * Method index
     *
     * @return redirect|view
     */
    public function index()
    {
        if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_admin()) {

            $settings = getSettings('system_settings', true);

            $this->data['main_page'] = FORMS . 'login';
            $this->data['title'] = 'Login Panel | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Login Panel | ' . $settings['app_name'];
            $this->data['logo'] = getSettings('logo');

            $identity = $this->config->item('identity', 'ion_auth');
            if (empty($identity)) {
                $identity_column = 'text';
            } else {
                $identity_column = $identity;
            }
            $this->data['identity_column'] = $identity_column;
            $this->load->view('admin/login_register', $this->data);
        } else {
            if ($this->session->has_userdata('url')) {
                $url = $this->session->userdata('url');
                $this->session->unset_userdata('url');
                redirect($url, 'refresh');
            } else {
                redirect('admin/dashboard', 'refresh');
            }
        }
    }
    
    /**
     * Method login
     *
     * @return json
     */
    public function login()
    {
        $this->data['title'] = $this->lang->line('login_heading');
        $identity_column = $this->config->item('identity', 'ion_auth');
        // validate form input
        $this->form_validation->set_rules('identity', ucfirst($identity_column), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

        if ($this->form_validation->run() === true) {
            $tables = $this->config->item('tables', 'ion_auth');
            $identity = $this->input->post('identity', true);
            $res = $this->db->select('id')->where($identity_column, $identity)->get($tables['login_users'])->result_array();
            if (!empty($res)) {
                if ($this->ion_auth_model->in_group('admin', $res[0]['id'])) {
                    // check to see if the user is logging in
                    // check for "remember me"
                    $remember = (bool)$this->input->post('remember');

                    if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                        //if the login is successful
                        if (!$this->input->is_ajax_request()) {
                            redirect('admin/home', 'refresh');
                        }

                        $response = [
                            'error' => false,
                            'csrfName' => $this->security->get_csrf_token_name(),
                            'csrfHash' => $this->security->get_csrf_hash(),
                            'message' => $this->ion_auth->messages()
                        ];
                        echo json_encode($response);
                    } else {
                     
                        // if the login was un-successful
                        $response = [
                            'error' => true,
                            'csrfName' => $this->security->get_csrf_token_name(),
                            'csrfHash' => $this->security->get_csrf_hash(),
                            'message' => 'Email or Password is wrong.'
                        ];
                        echo json_encode($response);
                    }
                } else {
                    $response = [
                        'error' => true,
                        'csrfName' => $this->security->get_csrf_token_name(),
                        'csrfHash' => $this->security->get_csrf_hash(),
                        'message' => '<div>Incorrect Login</div>'
                    ];
                    echo json_encode($response);
                }
            } else {
                $response = [
                    'error' => true,
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                    'message' => '<div>Incorrect Login</div>'
                ];
                echo json_encode($response);
            }
        } else {
            // the user is not logging in so display the login page
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
                    'error' => false,
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                    'message' => $this->session->flashdata('message')
                ];

                echo json_encode($response);
                return false;
                exit();
            }

            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
            ];

            $this->data['password'] = [
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
            ];

            $this->load->view('auth/login', $this->data);
        }
    }
    
    /**
     * Method register
     *
     * @return view
     */
    public function register()
    {
        if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_admin()) {

            $settings = getSettings('system_settings', true);

            $this->data['main_page'] = FORMS . 'register';
            $this->data['title'] = 'Register | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Register | ' . $settings['app_name'];
            $this->data['logo'] = getSettings('logo');

            $identity = $this->config->item('identity', 'ion_auth');
            if (empty($identity)) {
                $identity_column = 'text';
            } else {
                $identity_column = $identity;
            }
            $this->data['identity_column'] = $identity_column;
            $this->load->view('admin/login_register', $this->data);
        } else {
            if ($this->session->has_userdata('url')) {
                $url = $this->session->userdata('url');
                $this->session->unset_userdata('url');
                redirect($url, 'refresh');
            } else {
                redirect('admin/dashboard', 'refresh');
            }
        }
    }

    /**
     * Method register [Not implemented yet]
     *
     * @return json
     */
    public function registerProcess()
    {

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'E-Mail', 'trim|required|xss_clean|valid_email|is_unique[users.email]', array('is_unique' => ' The email is already registered . Please login'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]', ['matches' => 'The password and confirm password field does not match']);

        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();

        if (!$this->form_validation->run()) {

            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {

            $post = $this->input->post();
            $identity_column = 'email';
            $email = strtolower($post['email']);
            $identity = $email;
            $password = $post['password'];

            $additional_data = [
                'type' => 'email',  // identity column
                'username' => $post['username'],
                'first_name' => !empty($post['first_name']) ? $post['first_name'] : '',
                'last_name' => !empty($post['last_name']) ? $post['last_name'] : '',
                'active' => 1,
            ];

            $res = $this->ion_auth->register($identity, $password, $email, $additional_data, ['2']);    // Group ID ['2'] for users
            updateDetails(['active' => 1], [$identity_column => $identity], 'users');
            $data = fetchDetails('users', [$identity_column => $identity], 'id, username, email', 'email');

            $this->response['error'] = false;
            $this->response['message'] = 'Registered Successfully';
            $this->response['redirect'] = base_url('admin/auth/login');
            $this->response['data'] = $data;
        }
        print_r(json_encode($this->response));
    }
    
    /**
     * Method logout
     *
     * @return redirect
     */
    public function logout()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $this->ion_auth->logout();

        // redirect them to the login page
        redirect('admin/auth/login', 'refresh');
    }

}
