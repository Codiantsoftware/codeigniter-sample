<?php

defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController as REST_Controller;

if (!function_exists('uploadFile')) {
        
    /**
     * Method uploadFile
     *
     * @param string $folder 
     * @param string $filename 
     *
     * @return array
     */
    function uploadFile($folder, $filename)
    {
        $ci = &get_instance();
        // Load the upload library
        $ci->load->library('upload');

        // Set the configuration for file upload
        $config['upload_path'] = 'uploads/' . $folder;
        $config['allowed_types'] = '*';
        $config['encrypt_name'] = true;

        // Initialize the upload library with the configuration
        $ci->upload->initialize($config);

        // Check if the specified folder exists, if not, create it
        if (!is_dir($config['upload_path'])) {
            if (!mkdir($config['upload_path'], 0755, true)) {
                return 'Permission denied to create a folder.'; // Failed to create folder
            }
        }

        // Check if the upload is successful
        if ($ci->upload->do_upload($filename)) {
            // Get the uploaded file data
            $file_data = $ci->upload->data();
            $response = [
                'error' => false,
                'image' => $config['upload_path'] . '/' . $file_data['file_name'],
                'message' => 'Uploaded successfully!'
            ];

            return $response;
        } else {
            $error_array = array('error' => $ci->upload->display_errors());

            $response = [
                'error' => true,
                'image' => null,
                'message' => $error_array['error']
            ];
            return $response;
        }
    }
}

if (!function_exists('uploadMultipleFiles')) {
        
    /**
     * Method uploadMultipleFiles
     *
     * @param string $filename 
     * @param string $folder 
     * @param string $table 
     *
     * @return bool|string
     */
    function uploadMultipleFiles($filename, $folder, $table)
    {
        $ci = &get_instance();
        $ci->load->library('upload');
        $images = array();

        // If files are selected to upload 
        if (!empty($_FILES[$filename]['name']) && count(array_filter($_FILES[$filename]['name'])) > 0) {
            $filesCount = count($_FILES[$filename]['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['file']['name']     = $_FILES[$filename]['name'][$i];
                $_FILES['file']['type']     = $_FILES[$filename]['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES[$filename]['tmp_name'][$i];
                $_FILES['file']['error']     = $_FILES[$filename]['error'][$i];
                $_FILES['file']['size']     = $_FILES[$filename]['size'][$i];

                // File upload configuration 
                $config['upload_path'] = 'uploads/' . $folder;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['encrypt_name'] = true;

                $ci->upload->initialize($config);

                // Upload file to server 
                if ($ci->upload->do_upload('file')) {
                    // Uploaded file data 
                    $fileData = $ci->upload->data();
                    $uploaded_image = $config['upload_path'] . '/' . $fileData['file_name'];
                    compressAndResizeImage($uploaded_image, $uploaded_image);
                    $uploadData = [
                        'path' => $uploaded_image,
                        'created_at' => date("Y-m-d H:i:s")
                    ];

                    $ci->db->insert($table, $uploadData);
                    $id = $ci->db->insert_id();
                    $images[] = $id;
                }
            }

            return implode(',', $images);
        } else {
            return false;
        }
    }
}

if (!function_exists('generateOtp')) {
        
    /**
     * Method generateOtp
     *
     * @param int $n 
     *
     * @return string
     */
    function generateOtp($n)
    {
        $generator = "1357902468";


        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }

        // Return result
        return $result;
    }
}

if (!function_exists('calculatePercentage')) {
    
    /**
     * Method calculatePercentage
     *
     * @param int|float $number 
     * @param int|float $total 
     *
     * @return int|float
     */
    function calculatePercentage($number, $total)
    {
        if ($total == 0) {
            return 0; // To avoid division by zero error
        }

        return ($number / $total) * 100;
    }
}

// Function to compress image size
if (!function_exists('compressAndResizeImage')) {

        
    /**
     * Method compressAndResizeImage
     *
     * @param string $uploadedFile  
     * @param string $targetPath   
     * @param int    $targetSize 
     *
     * @return bool
     */
    function compressAndResizeImage($uploadedFile, $targetPath, $targetSize = 1024)
    {
        // Get the original image size
        $originalSize = filesize($uploadedFile);

        // Check if the image size is already within the target range
        if ($originalSize <= $targetSize * 1024) {
            // The image is already within the target size range, no need to compress or resize
            return move_uploaded_file($uploadedFile, $targetPath);
        }

        // Calculate the target size in bytes
        $targetSizeBytes = $targetSize * 1024;

        // Get the image dimensions
        list($width, $height, $type) = getimagesize($uploadedFile);

        // Create a new image from the uploaded file
        switch ($type) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($uploadedFile);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($uploadedFile);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($uploadedFile);
            break;
        default:
            // Unsupported image type
            return false;
        }

        // Calculate the new image dimensions to maintain aspect ratio
        $aspectRatio = $width / $height;
        $newWidth = min((int)sqrt($targetSizeBytes * $aspectRatio), $width);
        $newHeight = (int)($newWidth / $aspectRatio);

        // Create a new image with the target dimensions
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        // Resize the original image to the new dimensions
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save the resized image to the target path with a compression quality of 80 (adjust as needed)
        $img_path = imagejpeg($resizedImage, $targetPath, 80);

        // Free up memory by destroying the images
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        return true;
    }
}

if (!function_exists('generateSlug')) {
        
    /**
     * Method generateSlug
     *
     * @param string $string 
     *
     * @return string
     */
    function generateSlug($string)
    {
        // Convert the string to lowercase
        $string = strtolower($string);

        // Replace spaces with hyphens
        $string = str_replace(' ', '-', $string);

        // Remove special characters and symbols
        $string = preg_replace('/[^a-z0-9-]/', '', $string);

        // Remove consecutive hyphens
        $string = preg_replace('/-+/', '-', $string);

        // Trim hyphens from the beginning and end of the string
        $string = trim($string, '-');

        return $string;
    }
}

if (!function_exists('getFileExtension')) {
        
    /**
     * Method getFileExtension
     *
     * @param string $filepath 
     *
     * @return string
     */
    function getFileExtension($filepath)
    {
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);
        return $extension;
    }
}

if (!function_exists('formatDateTime')) {

        
    /**
     * Method formatDateTime
     *
     * @param string $datetime 
     *
     * @return string
     */
    function formatDateTime($datetime)
    {
        $now = new DateTime();
        $inputDatetime = new DateTime($datetime);
        $interval = $now->diff($inputDatetime);

        if ($interval->days > 10) {
            return $inputDatetime->format('d-m-Y h:i A');
        } elseif ($interval->h >= 2) {
            return $interval->h . ' hours ago';
        } elseif ($interval->i >= 30) {
            return '1 hour ago';
        } elseif ($interval->i >= 2) {
            return $interval->i . ' minutes ago';
        } else {
            return 'Just Now';
        }
    }
}

if (!function_exists('getSettings')) {
    
    /**
     * Method getSettings
     *
     * @param string $type 
     * @param bool   $is_json 
     *
     * @return array
     */
    function getSettings($type = 'system_settings', $is_json = false)
    {
        $t = &get_instance();

        $res = $t->db->select(' * ')->where('variable', $type)->get('settings')->result_array();
        if (!empty($res)) {
            if ($is_json) {
                return json_decode($res[0]['value'], true);
            } else {
                return outputEscaping($res[0]['value']);
            }
        }
    }
}

if (!function_exists('hasPermissions')) {    
    /**
     * Method hasPermissions
     *
     * @param string $role 
     * @param string $module 
     * @param int    $user_id 
     *
     * @return void
     */
    function hasPermissions($role, $module, $user_id = "")
    {
        $role = trim($role);
        $module = trim($module);

        $t = &get_instance();
        $id = (isset($user_id) && !empty($user_id)) ? $user_id : $t->session->userdata('user_id');
        $t->load->config('permissions');
        $general_system_permissions  = $t->config->item('system_modules');
        $userData = getUserPermissions($id);
        if (!empty($userData)) {

            if (intval($userData[0]['role']) > 0) {
                $permissions = json_decode($userData[0]['permissions'], 1);
                if (array_key_exists($module, $general_system_permissions) && array_key_exists($module, $permissions)) {
                    if (array_key_exists($module, $permissions)) {
                        if (in_array($role, $general_system_permissions[$module])) {
                            if (!array_key_exists($role, $permissions[$module])) {
                                return false; //User has no permission
                            }
                        }
                    }
                } else {
                    return false; //User has no permission
                }
            }
            return true; //User has permission
        }
    }
}

if (!function_exists('getUserPermissions')) {        
    /**
     * Method getUserPermissions
     *
     * @param int $id 
     *
     * @return array
     */
    function getUserPermissions($id)
    {
        $userData = fetchDetails('user_permissions', ['user_id' => $id]);
        return $userData;
    }
}

if (!function_exists('fetchDetails')) {   
             
    
    /**
     * Method fetchDetails
     *
     * @param string $table 
     * @param array  $where 
     * @param array  $fields 
     * @param string $groupBy 
     * @param int    $limit 
     * @param int    $offset 
     * @param string $sort 
     * @param string $order 
     * @param string $whereInKey 
     * @param string $whereInValue 
     *
     * @return void
     */
    function fetchDetails($table, $where = null, $fields = '*', $groupBy = '', $limit = '', $offset = '', $sort = '', $order = '', $whereInKey = '', $whereInValue = '')
    {

        $t = &get_instance();
        $t->db->select($fields);
        if (!empty($where)) {
            $t->db->where($where);
        }

        if (!empty($whereInKey) && !empty($whereInValue)) {
            $t->db->where_in($whereInKey, $whereInValue);
        }

        if (!empty($limit)) {
            $t->db->limit($limit);
        }

        if (!empty($offset)) {
            $t->db->offset($offset);
        }

        if (!empty($order) && !empty($sort)) {
            $t->db->order_by($sort, $order);
        }

        if (!empty($groupBy)) {
            $t->db->group_by($groupBy);
        }

        $res = $t->db->get($table)->result_array();
        return $res;
    }
}

if (!function_exists('updateDetails')) {
        
    /**
     * Method updateDetails
     *
     * @param array  $set 
     * @param array  $where 
     * @param string $table 
     * @param bool   $escape 
     *
     * @return bool
     */
    function updateDetails($set, $where, $table, $escape = true)
    {
        $t = &get_instance();
        $t->db->trans_start();
        if ($escape) {
            $set = escapeArray($set);
        }
        $t->db->set($set)->where($where)->update($table);
        $t->db->trans_complete();
        $response = false;
        if ($t->db->trans_status() === true) {
            $response = true;
        }
        return $response;
    }
}


if (!function_exists('outputEscaping')) {
    
    /**
     * Method output_escaping
     *
     * @param array $array 
     *
     * @return void
     */
    function outputEscaping($array)
    {
        $exclude_fields = ["images", "other_images"];
        $t = &get_instance();

        if (!empty($array)) {
            if (is_array($array)) {
                $data = array();
                foreach ($array as $key => $value) {
                    if (!in_array($key, $exclude_fields)) {
                        $data[$key] = stripcslashes($value  ?? '');
                    } else {
                        $data[$key] = $value;
                    }
                }
                return $data;
            } else if (is_object($array)) {
                $data = new stdClass();
                foreach ($array as $key => $value) {
                    if (!in_array($key, $exclude_fields)) {
                        $data->$key = stripcslashes($value  ?? '');
                    } else {
                        $data[$key] = $value;
                    }
                }
                return $data;
            } else {
                return stripcslashes($array);
            }
        }
    }
}

if (!function_exists('escapeArray')) {
        
    /**
     * Method escapeArray
     *
     * @param array $array 
     *
     * @return array
     */
    function escapeArray($array) 
    {
        $t = &get_instance();
        $posts = array();
        if (!empty($array)) {
            if (is_array($array)) {
                foreach ($array as $key => $value) {
                    $posts[$key] = $t->db->escape_str($value ?? '');
                }
            } else {
                return $t->db->escape_str($array);
            }
        }
        return $posts;
    }
}

if (!function_exists('editUnique')) {
    
    /**
     * Method edit_unique
     *
     * @param string $value 
     * @param string $params 
     *
     * @return bool
     */
    function editUnique($value, $params)
    {
        $CI = &get_instance();

        $CI->form_validation->set_message('edit_unique', "Sorry, that %s is already being used.");

        list($table, $field, $current_id) = explode(".", $params ?? '');

        $query = $CI->db->select()->from($table)->where($field, $value)->limit(1)->get();
        if ($query->row() && $query->row()->id != $current_id) {
            return false;
        } else {
            return true;
        }
    }
}
