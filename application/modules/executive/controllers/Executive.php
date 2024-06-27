<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Executive extends MY_REST_Controller
{
    public $executive_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('vendor_list_model');
        $this->load->model('contact_model');
        $this->load->model('group_model');
        $this->load->model('social_model');
        $this->load->model('vendor_holiday_model');
        $this->load->model('location_model');
        $this->load->model('vendor_sub_category_model');
        $this->load->model('vendor_amenity_model');
        $this->load->model('vendor_service_model');
        $this->load->model('users_permissions_model');
        $this->load->model('vendor_timings_model');
        $this->load->model('user_model');
        $this->load->model('sub_category_model');
        $this->load->model('permission_model');
        $this->load->model('vendor_banner_model');
        $this->executive_id = 1;
    }

    public function vendors_post($method = 'r')
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        if ($method == 'c') {
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $this->form_validation->set_rules($this->vendor_list_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), 'Internal Error Occured..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $group = $this->group_model->where('name', 'vendor')->get();
                if(! empty($group)){
                    $unique_id = generate_serial_no($group['code'], 4, $group['last_id']);
                    $this->group_model->update([
                        'last_id' => $group['last_id'] + 1
                    ], $group['id']);
                    
                    $email = strtolower($this->input->post('email'));
                    $identity = ($this->config->item('identity', 'ion_auth') === 'email') ? $email : $unique_id;
                    $additional_data = array(
                        'first_name' => $this->input->post('name'),
                        'unique_id' => $unique_id,
                        'active' => 1
                    );
                    $group_id[] = $group['id'];
                    $user_id = $this->ion_auth->register($identity,  '1234', $this->input->post('email'), $additional_data, $group_id);
                    
                    if(! empty($user_id)){
                        $id = $this->vendor_list_model->insert([
                            'name' => $this->input->post('name'),
                            'email' => $this->input->post('email'),
                            'vendor_user_id' => $user_id,
                            'unique_id' => $unique_id,
                            'constituency_id' => $this->input->post('constituency_id'),
                            'category_id' => $this->input->post('category_id'),
                            'executive_id' => $token_data->id,
                            'address' => $this->input->post('address'),
                            'landmark' => $this->input->post('landmark'),
                            'pincode' => $this->input->post('pincode'),
                            'everyday_open_time' => $this->input->post('everyday_open_time'),
                            'everyday_close_time' => $this->input->post('everyday_close_time'),
                            'holiday_open_time' => $this->input->post('holiday_open_time'),
                            'holiday_close_time' => $this->input->post('holiday_close_time'),
                            'no_of_banners' => count($this->input->post('banner')),
                        ]);
                        
                            $contacts = $this->input->post('contacts');
                            $links = $this->input->post('social');
                            $holidays = $this->input->post('holidays');
                            $banners = $this->input->post('banner');
                            /* $sub_categories = $this->input->post('sub_categories');
                             $amenities = $this->input->post('amenities');
                             $services = $this->input->post('services'); */
                            $timings = $this->input->post('timings');
                            $links_data = $contact_data = $holidays_data = $sub_categories_data  = $amenities_data = $services_data = $timings_data = [];
                            $i = $j = $k = $l =  $m = $n = $o = $t = 0;
                            foreach ($contacts as $key => $val) {
                                $contact_data[$i ++] = [
                                    'list_id' => $id,
                                    'std_code' => $val['code'],
                                    'number' => $val['number'],
                                    'type' => $key
                                ];
                            }
                            if(isset($timings)){foreach ($timings as $key => $val) {
                                $timings_data[$t ++] = [
                                    'list_id' => $id,
                                    'start_time' => $val['start_time'],
                                    'end_time' => $val['end_time'],
                                    // 'type' => $key
                                ];
                            }}
                            if(isset($links)){foreach ($links as $key => $val) {
                                $links_data[$j ++] = [
                                    'list_id' => $id,
                                    'url' => $val,
                                    'type' => $key
                                ];
                            }}
                            if(isset($holidays)){foreach ($holidays as $key => $val) {
                                $holidays_data[$k ++] = [
                                    'list_id' => $id,
                                    'day_id' => $val
                                ];
                            }}
                            
                            /* if(isset($sub_categories)){foreach ($sub_categories as $key => $val) {
                             $sub_categories_data[$m ++] = [
                             'list_id' => $id,
                             'sub_category_id' => $val
                             ];
                             }}
                             if(isset($amenities)){foreach ($amenities as $key => $val) {
                             $amenities_data[$n ++] = [
                             'list_id' => $id,
                             'amenity_id' => $val
                             ];
                             }}
                             if(isset($services)){foreach ($services as $key => $val) {
                             $services_data[$o ++] = [
                             'list_id' => $id,
                             'service_id' => $val
                             ];
                             $this->add_permissions_to_user($user_id, $val);
                             }} */
                            if(isset($services)){
                                foreach ($services as $key => $val) {
                                    $services_data[$o ++] = [
                                    'list_id' => $id,
                                    'service_id' => $val
                                    ];
                                    $this->add_permissions_to_user($user_id, $val);
                                }
                            }
                            $this->user_model->update([
                                'id' => $user_id,
                                'list_id' => $id
                            ], 'id');
                            /* $this->vendor_amenity_model->insert($amenities_data);
                             $this->vendor_service_model->insert($services_data); */
                            $this->contact_model->insert($contact_data);
                            $this->social_model->insert($links_data);
                            $this->vendor_holiday_model->insert($holidays_data);
                            /* $this->vendor_sub_category_model->insert($sub_categories_data); */
                            $this->vendor_timings_model->insert($timings_data);
                            /* $location_id = $this->location_model->insert([
                                'address' => $this->input->post('location_address'),
                                'latitude' => $this->input->post('latitude'),
                                'longitude' => $this->input->post('longitude'),
                            ]); */
                            $is_location_exist = $this->location_model->where(['latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude')])->get();
                            if(empty($is_location_exist)){
                                $location_id = $this->location_model->insert([
                                    'address' => $this->input->post('location_address'),
                                    'latitude' => $this->input->post('latitude'),
                                    'longitude' => $this->input->post('longitude'),
                                ]);
                            }else{
                                $location_id = $is_location_exist['id'];
                            }
                            $this->vendor_list_model->update(['location_id' => $location_id], $id);
                            file_put_contents("./uploads/list_cover_image/list_cover_$id.jpg", base64_decode($this->input->post('cover')));
                            foreach ($banners as $key => $val) {
                                $image_id = $this->vendor_banner_model->insert([
                                    'list_id' => $id,
                                    'image' => 'banner_'.$id.'.jpg',
                                    'ext' => 'jpg'
                                ]);
                                file_put_contents("./uploads/list_banner_image/list_banner_".$image_id.".jpg", base64_decode($val));
                            }
                             
                            $this->set_response_simple($perms, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                    
                    } else {
                        $this->set_response_simple($this->ion_auth->errors(), 'User not created..!', REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
                    }
                }else{
                    $this->set_response_simple(NULL, 'Vendor Group Not Found..!', REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
                }
                
            }
        } elseif ($method = 'r') {
            $data = $this->vendor_list_model->fields('id, name, email, unique_id, executive_id, address, landmark, pincode, status')->with_contacts('fields:id, list_id, std_code, number', 'where: type = \'1\'')->where('executive_id', $token_data->id)->as_array()->get_all();
            if(! empty($data)){
                for ($i = 0; $i < count($data) ; $i++){
                    $data[$i]['image'] = base_url().'uploads/list_cover_image/list_cover_'.$data[$i]['id'].'.jpg';
                }
            }
            
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
    
    public function vendor_self_reg_post($method = 'r')
    {
        if ($method == 'c') {
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            $this->form_validation->set_rules($this->vendor_list_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), 'Internal Error Occured..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $group = $this->group_model->where('name', 'vendor')->get();
                if(! empty($group)){
                    $unique_id = generate_serial_no($group['code'], 4, $group['last_id']);
                    $this->group_model->update([
                        'last_id' => $group['last_id'] + 1
                    ], $group['id']);
                    
                    $email = strtolower($this->input->post('email'));
                    $identity = ($this->config->item('identity', 'ion_auth') === 'email') ? $email : $unique_id;
                    $additional_data = array(
                        'first_name' => $this->input->post('name'),
                        'unique_id' => $unique_id,
                        'active' => 1
                    );
                    $group_id[] = $group['id'];
                    $user_id = $this->ion_auth->register($identity,  (empty($this->input->post('password')))? '123456': $this->input->post('password'), $this->input->post('email'), $additional_data, $group_id);
                    
                    if(! empty($user_id)){
                        $id = $this->vendor_list_model->insert([
                            'name' => $this->input->post('name'),
                            'email' => $this->input->post('email'),
                            'vendor_user_id' => $user_id,
                            'unique_id' => $unique_id,
                            'constituency_id' => $this->input->post('constituency_id'),
                            'category_id' => $this->input->post('category_id'),
                            'executive_id' => $this->executive_id,
                            'address' => $this->input->post('address'),
                            'landmark' => $this->input->post('landmark'),
                            'pincode' => $this->input->post('pincode'),
                            'no_of_banners' => count($this->input->post('banner')),
                        ]);
                        
                        $contacts = $this->input->post('contacts');
                        $links = $this->input->post('social');
                        $holidays = $this->input->post('holidays');
                        $banners = $this->input->post('banner');
                        $timings = $this->input->post('timings');
                        $links_data = $contact_data = $holidays_data = $sub_categories_data  = $amenities_data = $services_data = $timings_data = [];
                        $i = $j = $k = $l =  $m = $n = $o = $t = 0;
                        foreach ($contacts as $key => $val) {
                            $contact_data[$i ++] = [
                                'list_id' => $id,
                                'std_code' => $val['code'],
                                'number' => $val['number'],
                                'type' => $key
                            ];
                        }
                        if(isset($timings)){foreach ($timings as $key => $val) {
                            $timings_data[$t ++] = [
                                'list_id' => $id,
                                'start_time' => $val['start_time'],
                                'end_time' => $val['end_time'],
                            ];
                        }}
                        if(isset($links)){foreach ($links as $key => $val) {
                            $links_data[$j ++] = [
                                'list_id' => $id,
                                'url' => $val,
                                'type' => $key
                            ];
                        }}
                        if(isset($holidays)){foreach ($holidays as $key => $val) {
                            $holidays_data[$k ++] = [
                                'list_id' => $id,
                                'day_id' => $val
                            ];
                        }}
                        
                        $this->user_model->update([
                            'id' => $user_id,
                            'list_id' => $id
                        ], 'id');
                        $this->contact_model->insert($contact_data);
                        $this->social_model->insert($links_data);
                        $this->vendor_holiday_model->insert($holidays_data);
                        $this->vendor_timings_model->insert($timings_data);
                        $is_location_exist = $this->location_model->where(['latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude')])->get();
                        if(empty($is_location_exist)){
                            $location_id = $this->location_model->insert([
                                'address' => $this->input->post('location_address'),
                                'latitude' => $this->input->post('latitude'),
                                'longitude' => $this->input->post('longitude'),
                            ]);
                        }else{
                            $location_id = $is_location_exist['id'];
                        }
                        $this->vendor_list_model->update(['location_id' => $location_id], $id);
                        file_put_contents("./uploads/list_cover_image/list_cover_$id.jpg", base64_decode($this->input->post('cover')));
                        foreach ($banners as $key => $val) {
                            $image_id = $this->vendor_banner_model->insert([
                                'list_id' => $id,
                                'image' => 'banner_'.$id.'.jpg',
                                'ext' => 'jpg'
                            ]);
                            file_put_contents("./uploads/list_banner_image/list_banner_".$image_id.".jpg", base64_decode($val));
                        }
                        
                        $this->set_response_simple($id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
                        
                    } else {
                        $this->set_response_simple($this->ion_auth->errors(), 'User not created..!', REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
                    }
                }else{
                    $this->set_response_simple(NULL, 'Vendor Group Not Found..!', REST_Controller::HTTP_INTERNAL_SERVER_ERROR, FALSE);
                }
                
            }
        } elseif ($method = 'r') {
            $data = $this->vendor_list_model->fields('id, name, email, unique_id, executive_id, address, landmark, pincode, status')->with_contacts('fields:id, list_id, std_code, number', 'where: type = \'1\'')->where('executive_id', $token_data->id)->as_array()->get_all();
            if(! empty($data)){
                for ($i = 0; $i < count($data) ; $i++){
                    $data[$i]['image'] = base_url().'uploads/list_cover_image/list_cover_'.$data[$i]['id'].'.jpg';
                }
            }
            
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    
    /**
     * existing referal id validation
     * @param string $ref_id
     * @return boolean
     */
    public function check_referance($ref_id){
        if(! empty($ref_id)){
            $parent =  $this->user_model->fields('id, unique_id')->where('unique_id', $ref_id)->as_object()->get();
            if(! empty($parent)){
                $this->executive_id = $parent->id;
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return TRUE;
        }
    }
    
    function add_permissions_to_user($user_id, $cat_id){
        $this->db->where('cat_id', $cat_id);
        $services = $this->db->get('categories_services')->result_array();
        $this->db->where('user_id', $user_id);
        $this->db->delete('users_permissions');
        foreach ($services as $service){
            $service_details = $this->db->select('permission_parent_ids')->where('id', $service['service_id'])->get('services')->result_array();
            $perms = explode(',', $service_details[0]['permission_parent_ids']);
            foreach ($perms as $perm){
                $child_permissions = $this->permission_model->where('parent_status', $perm)->as_array()->get_all();
                foreach($child_permissions as $child_permission){
                    $this->db->insert('users_permissions', ['user_id' => $user_id, 'perm_id' => $child_permission['id'], 'value' => 1]);
                }
                $this->db->insert('users_permissions', ['user_id' => $user_id, 'perm_id' => $perm, 'value' => 1]);
            }
        }
    }

}

