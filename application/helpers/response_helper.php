<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController as REST_Controller;

/**
 * HTTP_OK response
 */
if (!function_exists('successResponse')) {   

    /**
     * Method successResponse
     *
     * @param string $message 
     * @param array  $data 
     *
     * @return array
     */
    function successResponse($message, $data = [])
    {
        $ci = &get_instance();
        if (!empty($data)) {
            $array = $data;
        } else {
            $array = null;
        }
        return $ci->response(
            [
            'error' => false,
            'message' => $message,
            'data' => $array
            ], REST_Controller::HTTP_OK
        );
    }
}

/**
 * HTTP_BAD_REQUEST response
 */
if (!function_exists('errorResponse')) {    
    
    /**
     * Method errorResponse
     *
     * @param string $message 
     * @param array  $data 
     *
     * @return array
     */
    function errorResponse($message, $data = [])
    {
        $ci = &get_instance();
        if (!empty($data)) {
            $array = $data;
        } else {
            $array = null;
        }
        return $ci->response(
            [
            'error' => true,
            'message' => $message,
            'data' => $array
            ], REST_Controller::HTTP_BAD_REQUEST
        );
    }
}

/**
 * HTTP_NOT_FOUND response
 */
if (!function_exists('notFoundResponse')) {
        
    /**
     * Method notFoundResponse
     *
     * @param string $message 
     * @param array  $data 
     *
     * @return array
     */
    function notFoundResponse($message, $data = [])
    {
        $ci = &get_instance();
        if (!empty($data)) {
            $array = $data;
        } else {
            $array = null;
        }
        return $ci->response(
            [
            'error' => true,
            'message' => $message,
            'data' => $array
            ], REST_Controller::HTTP_NOT_FOUND
        );
    }
}

if (!function_exists('arrayResponse')) {
        
    /**
     * Method arrayResponse
     *
     * @param string $message 
     * @param array  $data 
     *
     * @return array
     */
    function arrayResponse($message, $data = [])
    {
        $ci = &get_instance();
        if (!empty($data)) {
            successResponse(sprintf($ci->lang->line('Record_found'), $message), $data);
        } else {
            notFoundResponse(sprintf($ci->lang->line('Record_not_found'), $message));
        }
    }
}

if (!function_exists('pre')) {    
    /**
     * Method pre
     *
     * @param array $data 
     *
     * @return void
     */
    function pre($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die;
    }
}

if (!function_exists('getErrorResponse')) {
        
    /**
     * Method getErrorResponse
     *
     * @param array $errors 
     *
     * @return string
     */
    function getErrorResponse($errors)
    {
        $validation_message = array();
        foreach ($errors as $key => $value) {
            array_push($validation_message, $value);
        }
        return $validation_message[0];
    }
}
