<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Master extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('vendor_list_model');
        $this->load->model('news_model');
        $this->load->model('users_address_model');
        $this->load->model('location_model');
        $this->load->model('setting_model');
        $this->load->model('user_service_model');
        $this->load->model('vendor_banner_model');
    }

    /**
     * To get list of vendor depends upon category, search & near by location
     *
     * @author Mehar
     * @param integer $limit
     * @param integer $offset
     * @param integer $cat_id
     */
    public function vendor_list_get($cat_id = NULL)
    {
        // if (! empty($cat_id)) {
            $data = $this->vendor_list_model->all($cat_id, (isset($_GET['sub_cat_id'])) ? $this->input->get('sub_cat_id') : NUll, (isset($_GET['q'])) ? $this->input->get('q') : NUll, (isset($_GET['latitude'])) ? $this->input->get('latitude') : NUll, (isset($_GET['longitude'])) ? $this->input->get('longitude') : NUll, (isset($_GET['brand_id'])) ? $this->input->get('brand_id') : NUll, (isset($_GET['limit'])) ? $this->input->get('limit') : NUll);
            if (! empty($data['result'])) {
                foreach ($data['result'] as $d) {

                    $r=$this->db->select('id,perm_id')->where('user_id',$d->vendor_user_id)->get('users_permissions')->result_array();
                    $j=0;
                    foreach ($r as $k) {
                        $this->load->model('permission_model');
                        $p=$this->permission_model->fields('id,perm_key,perm_name')->where('id',$k['perm_id'])->get();
                        if($p['perm_key'] == 'leads'){
                            $j=1;
                        }
                    }

                    $d->lead_permission=$j;

                    if (file_exists('./uploads/list_cover_image/list_cover_' . $d->id . '.jpg')) {
                    $d->image = base_url() . 'uploads/list_cover_image/list_cover_' . $d->id . '.jpg';
                    }else{
                    $d->image = base_url() . 'assets/img/no-img.png';
                    }
                }
            }
            $this->set_response_simple((empty($data['result'])) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        /*} else {
            $this->set_response_simple(NULL, 'Please provide valid data..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        }*/
    }

    /**
     * User address
     *
     * To Manage address
     *
     * @author Trupti
     * @param string $type
     */
    public function user_address_post($type = 'r')
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if ($type == 'c') {
            $this->form_validation->set_rules($this->users_address_model->rules);
            if ($this->form_validation->run() == false) {
                $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $v = $this->location_model->where('latitude', $this->input->post('latitude'))
                    ->where('longitude', $this->input->post('longitude'))
                    ->get();
                if ($v != '') {
                    $l_id = $v['id'];
                } else {
                    $l_id = $this->location_model->insert([
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                        'address' => $this->input->post('address')
                    ]);
                }

                $id = $this->users_address_model->insert([
                    'user_id' => $token_data->id,
                    'name' => $this->input->post('name'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'address' => $this->input->post('address'),
                    'location_id' => $l_id
                ]);
                $this->set_response_simple($id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
            }
        } elseif ($type == 'r') {
            $data = $this->users_address_model->with_location('fields: id, address, latitude, longitude')->fields('id, user_id, name, phone, email, address, location_id')->where('user_id', $token_data->id)->get_all();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 's') {
            $data = $this->users_address_model->fields('id, user_id, name, phone, email, address, location_id')->get('id', $this->input->post('id'));
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        } elseif ($type == 'u') {
            $this->form_validation->set_rules($this->users_address_model->rules);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $v = $this->location_model->where('latitude', $this->input->post('latitude'))
                    ->where('longitude', $this->input->post('longitude'))
                    ->get();
                if ($v != '') {
                    $l_id = $v['id'];
                } else {
                    $l_id = $this->location_model->insert([
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                        'address' => $this->input->post('address')
                    ]);
                }
                $ll = $this->users_address_model->update([
                    'id' => $this->input->post('id'),
                    'user_id' => $token_data->id,
                    'name' => $this->input->post('name'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'address' => $this->input->post('address'),
                    'location_id' => $l_id
                ], 'id');
                $this->set_response_simple($ll, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
        } elseif ($type == 'd') {
            $ll = $this->users_address_model->delete([
                'id' => $this->input->post('id')
            ]);
            $this->set_response_simple($ll, 'Deleted..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get the individual vendor details
     *
     * @author Mehar
     * @param number $target
     */
    public function vendor_get($target = 1,$type='vendor_id')
    {
        if($type == 'vendor_id'){
            $arr=array('id'=>$target);
        }elseif($type == 'vendor_user_id'){
            $arr=array('vendor_user_id'=>$target);
        }
        $data = $this->vendor_list_model->fields('id, vendor_user_id, name, landmark, email, unique_id, created_at, status,restaurant_status,label,rating,desc')
            ->with_timings('fields: list_id, start_time, end_time, status')
            ->with_location('fields: id, address, latitude, longitude')
            ->with_category('fields: id, name, status')
            ->with_sub_categories('fields: id, name, status')
            ->with_constituency('fields: id, name, state_id, district_id')
            ->with_contacts('fields: id, std_code, number, type')
            ->with_links('fields: id, url, type')
            ->with_amenities('fields: id, name')
            ->with_services('fields: id, name')
            ->with_holidays('fields: id')
            ->where($arr)
            ->get();
            $data['sub_categories'] = array_values($data['sub_categories']);
            $data['category']['coming_soon_image'] = base_url(). 'uploads/coming_soon_image/coming_soon_'.$data['category']['id'].'.jpg';
        $data['user_services'] = $this->user_service_model->order_by('id', 'DESC')
            ->where('vendor_id', $data['vendor_user_id'])
            ->get_all();
        if ($data != FALSE) {
            $vendor_banners = $this->vendor_banner_model->where('list_id', $data['id'])->get_all();
            $data['banners'] = [];
            if ($vendor_banners) {
                foreach ($vendor_banners as $key => $banner) {
                    $data['banners'][$key] = base_url() . "uploads/list_banner_image/list_banner_" . $banner['id'] . ".jpg";
                }
            }
            if (! empty($data['sub_categories'])) {
                for ($i = 0; $i < count($data['sub_categories']); $i ++) {
                    $data['sub_categories'][$i]['image'] = base_url() . 'uploads/sub_category_image/sub_category_' . $data['sub_categories'][$i]['id'] . '.jpg';
                }
            }
            //$data['cover'] = base_url() . "uploads/list_cover_image/list_cover_" . $data['id'] . ".jpg";
        if (file_exists('./uploads/list_cover_image/list_cover_' . $data['id'] . '.jpg')) {
                $data['cover'] = base_url() . 'uploads/list_cover_image/list_cover_' . $data['id'] . '.jpg';
                }else{
                $data['cover'] = base_url() . 'assets/img/no-img.png';
                }


                $r=$this->db->select('id,perm_id')->where('user_id',$data['vendor_user_id'])->get('users_permissions')->result_array();
                    $j=0;
                    foreach ($r as $k) {
                        $this->load->model('permission_model');
                        $p=$this->permission_model->fields('id,perm_key,perm_name')->where('id',$k['perm_id'])->get();
                        if($p['perm_key'] == 'leads'){
                            $j=1;
                        }
                    }

                    $data['lead_permission']=$j;



            $field_where="(`desc` = 'app_ord_label' OR `desc` = 'app_ord_quantity' OR `desc` = 'app_ord_address')";
            $data['fields']=$this->db->where($field_where)->select('acc_id,name,desc,field_status')->get_where('manage_account_names',array('status'=>1,'category_id'=>$data['category_id']))->result();
           
        
        }
        $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get the news
     *
     * @author Mehar
     * @param number $limit,offset
     */
    public function news_get($limit = 10, $offset = 0)
    {
        $data = $this->news_model->all($limit, $offset);
        if (! empty($data['result'])) {
            foreach ($data['result'] as $d) {
                $d->image = base_url() . 'uploads/news_image/news_' . $d->id . '.jpg';
            }
        }
        $this->set_response_simple((empty($data['result'])) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get the User Dettails
     *
     * @author Mahesh
     *        
     */
    public function user_details_get()
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $this->load->model('user_model');
        $result = $this->user_model->order_by('id', 'DESC')
            ->fields('id,unique_id,first_name,last_name,email,phone, wallet')
            ->with_groups('fields:name,id')
            ->where('id', $token_data->id)
            ->get();
        $this->set_response_simple(($result == FALSE) ? FALSE : $result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
    
    /**
     * @desc Manage profile
     * 
     * @author Mehar
     */
    public function profile_post($type = 'r'){
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $this->load->model('user_model');
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        if($type == 'r'){
            $data = $this->user_model
            ->fields('id,unique_id,first_name,last_name,email,phone, wallet')
            ->with_groups('fields:name,id')
            ->where('id', $token_data->id)
            ->get();
            if (file_exists('./uploads/profile_image/profile_' . $data['unique_id'] . '.jpg')) {
            $data['image'] = base_url() . 'uploads/profile_image/profile_' . $data['unique_id'] . '.jpg';
                    }else{
                    $data['image'] = base_url() . 'assets/img/user-app.png';
                    }
            
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }elseif($type == 'u'){
            $is_updated = $this->user_model->update([
                'id' => $token_data->id,
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
            ], 'id');
            if($this->input->post('image')){
            file_put_contents("./uploads/profile_image/profile_".$this->input->post('unique_id').".jpg", base64_decode($this->input->post('image')));
            }
            if($is_updated){
                $this->set_response_simple(($is_updated == FALSE) ? FALSE : $is_updated, 'Success..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }else {
                $this->set_response_simple(($is_updated == FALSE) ? FALSE : $is_updated, 'Failed..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, TRUE);
            }
        }
    }

    public function payment_settings_get()
    {
        $result['pay_per_vendor'] = $this->setting_model->where('key', 'pay_per_vendor')->get()['value'];
        $result['vendor_validation'] = $this->setting_model->where('key', 'vendor_validation')->get()['value'];
        $this->set_response_simple(($result == FALSE) ? FALSE : $result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * Vendor Lead Generation
     *
     * To Manage Lead Generation
     *
     * @author Mahesh
     * @param string $type
     */
    public function LeadGeneration_post($type = 'c')
    {
        $this->load->model('vendor_leads_model');
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        if ($type == 'c') {
            $id = $this->vendor_leads_model->insert([
                'user_id' => $token_data->id,
                'vendor_id' => $this->input->post('vendor_id'),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $this->set_response_simple($id, 'We Will Contact You Soon', REST_Controller::HTTP_CREATED, TRUE);
        }
        if ($type == 'array') {
            $_POST = json_decode(file_get_contents("php://input"), TRUE);
            foreach ($_POST['vendors'] as $vendor) {
                $data[] = array(
                    'user_id' => $token_data->id,
                    'vendor_id' => $vendor['vendor_id'],
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
            // print_r($data);die;

            /*
             * $data = array(
             * array(
             * 'title' => 'My title' ,
             * 'name' => 'My Name' ,
             * 'date' => 'My date'
             * ),
             * array(
             * 'title' => 'Another title' ,
             * 'name' => 'Another Name' ,
             * 'date' => 'Another date'
             * )
             * );
             */

            $id = $this->db->insert_batch('vendor_leads', $data);
            /*
             * if($s){
             * echo "string";
             * }else{
             * echo "string1";
             * }die;
             */
            /*
             * $id=$this->vendor_leads_model->insert([
             * 'user_id' => $token_data->id,
             * 'vendor_id' => $this->input->post('vendor_id'),
             * 'created_at' => date('Y-m-d H:i:s'),
             * ]);
             */

            $this->set_response_simple($id, 'We Will Contact You Soon', REST_Controller::HTTP_CREATED, TRUE);
        }
    }
}

