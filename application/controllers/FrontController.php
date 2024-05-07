<?php

defined('BASEPATH') or exit('No direct script access allowed');

#[\AllowDynamicProperties]
/**
 * FrontController
 */
class FrontController extends CI_Controller
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
     * @return void
     */
    public function index()
    {
        return redirect('admin/auth/login');
    }

}
