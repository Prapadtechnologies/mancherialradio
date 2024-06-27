<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class Vendor extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        if (! $this->ion_auth->logged_in()) // || ! $this->ion_auth->is_admin()
            redirect('auth/login');

        $this->load->model('vendor_bank_details_model');
        $this->load->model('vendor_list_model');
        $this->load->model('setting_model');
        $this->load->model('contact_model');
        $this->load->model('social_model');
        $this->load->model('sub_category_model');
        $this->load->model('permission_model');
        $this->load->model('amenity_model');
        $this->load->model('vendor_amenity_model');
        $this->load->model('vendor_service_model');
        $this->load->model('vendor_sub_category_model');
        $this->load->model('vendor_brand_model');
        $this->load->model('vendor_banner_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('group_model');
        $this->load->model('location_model');
        $this->load->model('user_model');
    }

    /**
     * Vendor Profile Settings
     *
     * To Manage Vendor Details
     *
     * @author Mahesh
     * @param string $type
     * @param string $target
     */
    public function vendor_profile($type = 'r', $u_type = '')
    {

        /*
         * if (! $this->ion_auth_acl->has_permission('vendor'))
         * redirect('admin');
         */
        if ($type == 'r') {
            $this->data['title'] = 'Vendor Profile';
            $this->data['content'] = 'vendor/vendor/vendor_profile';
            $this->data['bank_details'] = $this->vendor_bank_details_model->fields('id,bank_name,bank_branch,ifsc,ac_holder_name,ac_number')
                ->where('vendor_id', $this->ion_auth->get_user_id())
                ->get();
            $this->data['vendor_details'] = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
                ->with_category('fields: id, name')
                ->with_constituency('fields: id, name, state_id, district_id')
                ->with_contacts('fields: id, std_code, number, type')
                ->with_links('fields: id,   url, type')
                ->with_amenities('fields: id, name')
                ->with_services('fields: id, name')
                ->with_brands('fields: id, name')
                ->with_holidays('fields: id')
                ->where('id', $this->ion_auth->get_user_id())
                ->get();
            $this->_render_page($this->template, $this->data);
        } elseif ($type == 'u') {
            if ($u_type == 'bank_details') {
                $this->form_validation->set_rules($this->vendor_bank_details_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    echo validation_errors();
                } else {
                    $r = $this->vendor_bank_details_model->fields('id')
                        ->where('vendor_id', $this->ion_auth->get_user_id())
                        ->get();
                    if ($r != '') {
                        $this->vendor_bank_details_model->update([
                            'bank_name' => $this->input->post('bank_name'),
                            'bank_branch' => $this->input->post('bank_branch'),
                            'ifsc' => $this->input->post('ifsc'),
                            'ac_holder_name' => $this->input->post('ac_holder_name'),
                            'ac_number' => $this->input->post('ac_number')
                        ], [
                            'vendor_id',
                            $this->ion_auth->get_user_id()
                        ]);
                        redirect('vendor_profile/r', 'refresh');
                    } else {
                        $this->vendor_bank_details_model->insert([
                            'bank_name' => $this->input->post('bank_name'),
                            'bank_branch' => $this->input->post('bank_branch'),
                            'ifsc' => $this->input->post('ifsc'),
                            'ac_holder_name' => $this->input->post('ac_holder_name'),
                            'ac_number' => $this->input->post('ac_number'),
                            'vendor_id' => $this->ion_auth->get_user_id()
                        ]);
                        redirect('vendor_profile/r', 'refresh');
                    }
                }
            }
        }elseif ($type == 'edit'){
            $this->data['title'] = 'Vendor Profile edit';
            $this->data['content'] = 'vendor/vendor/edit_profile';
            $this->data['bank_details'] = $this->vendor_bank_details_model->fields('id,bank_name,bank_branch,ifsc,ac_holder_name,ac_number')
            ->where('vendor_id', $this->ion_auth->get_user_id())
            ->get();
            if($_GET['id'] != ''){
                $wher=array('id'=>$_GET['id']);
            }elseif($_GET['vendor_user_id'] != ''){
                $wher=array('vendor_user_id'=>$_GET['vendor_user_id']);
            }
            $this->data['vendor_details'] = $this->vendor_list_model->with_location('fields: id, address, latitude, longitude')
            ->with_category('fields: id, name')
            ->with_constituency('fields: id, name, state_id, district_id')
            ->with_sub_categories('fields: id, name')
            ->with_contacts('fields: id, std_code, number, type')
            ->with_links('fields: id,   url, type')
            ->with_amenities('fields: id, name')
            ->with_services('fields: id, name')
            ->with_brands('fields: id, name')
            ->with_holidays('fields: id')
            ->where($wher)
            ->get();
            //print_r($this->data['vendor_details']);die;
            $this->data['categories'] = $this->category_model->get_all();
            $this->data['amenities'] = $this->amenity_model->where('cat_id', $this->data['vendor_details']['category_id'])->get_all();
            $this->data['sub_categories'] = $this->sub_category_model->where('cat_id', $this->data['vendor_details']['category_id'])->get_all();
            $this->data['services'] = $this->service_model->get_all();
            $this->data['brands'] = $this->brand_model->get_all();
            $this->data['banners'] = $this->vendor_banner_model->where('list_id', $this->data['vendor_details']['id'])->get_all();
            //print_array($this->data['vendor_details']);
            $this->_render_page($this->template, $this->data);
        }elseif ($type == 'profile'){
            $this->form_validation->set_rules($this->vendor_list_model->rules['profile']);
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $this->vendor_list_model->update([
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'address' => $this->input->post('address'),
                    'landmark' => $this->input->post('landmark'),
                    'restaurant_status' => $this->input->post('restaurant_status'),
                    'label' => $this->input->post('label'),
                    'rating' => $this->input->post('rating'),
                    'desc' => $this->input->post('desc'),
                ], 'id');
                $is_location_exist = $this->location_model->where(['latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude')])->get();
                if(empty($is_location_exist)){
                    $location_id = $this->location_model->insert([
                        'address' => $this->input->post('location_name'),
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                    ]);
                }else{
                    $location_id = $is_location_exist['id'];
                }
                $this->vendor_list_model->update(['location_id' => $location_id], $id);
                
                if($this->contact_model->where(['list_id' => $this->input->post('id'), 'type' => 1])->get() != FALSE)
                    $this->contact_model->update(['std_code' => $this->input->post('mobile_code'), 'number' => $this->input->post('mobile')], ['list_id' => $this->input->post('id'), 'type' => 1]);
                else 
                    $this->contact_model->insert(['list_id' => $this->input->post('id'), 'std_code' => $this->input->post('mobile_code'), 'number' => $this->input->post('mobile'), 'type' => 1]);
                
                if($this->contact_model->where(['list_id' => $this->input->post('id'), 'type' => 2])->get() != FALSE)
                    $this->contact_model->update(['std_code' => $this->input->post('landline_code'), 'number' => $this->input->post('landline')], ['list_id' => $this->input->post('id'), 'type' => 2]);
                else
                    $this->contact_model->insert(['list_id' => $this->input->post('id'), 'std_code' => $this->input->post('landline_code'), 'number' => $this->input->post('landline'), 'type' => 2]);
                
                if($this->contact_model->where(['list_id' => $this->input->post('id'), 'type' => 3])->get() != FALSE)
                    $this->contact_model->update(['std_code' => $this->input->post('whatsapp_code'), 'number' => $this->input->post('whatsapp')], ['list_id' => $this->input->post('id'), 'type' => 3]);
                else 
                    $this->contact_model->insert(['list_id' => $this->input->post('id'), 'std_code' => $this->input->post('whatsapp_code'), 'number' => $this->input->post('whatsapp'), 'type' => 3]);
                
                if($this->contact_model->where(['list_id' => $this->input->post('id'), 'type' => 4])->get() != FALSE)
                    $this->contact_model->update(['std_code' => $this->input->post('helpline_code'), 'number' => $this->input->post('helpline')], ['list_id' => $this->input->post('id'), 'type' => 4]);
                else 
                    $this->contact_model->insert(['list_id' => $this->input->post('id'), 'std_code' => $this->input->post('helpline_code'), 'number' => $this->input->post('helpline'), 'type' => 4]);
                    
                redirect('vendor_profile/edit?id='.$this->input->post('id'));
            }
        }elseif ($type == 'filters'){
                $sub_categories_data  = $amenities_data = $services_data = $brands_data = [];
                $m = $n = $o = $j = 0;
                $sub_categories = $this->input->post('sub_categories');
                $amenities = $this->input->post('amenities');
                $services = $this->input->post('services');
                $brands = $this->input->post('brands');
                if(isset($services)){
                    foreach ($services as $key => $val) {
                        $services_data[$o ++] = [
                            'list_id' => $this->input->post('id'),
                            'service_id' => $val
                        ];
                    }
                }
                
                if(isset($brands)){foreach ($brands as $key => $val) {
                    $brands_data[$j ++] = [
                        'list_id' => $this->input->post('id'),
                        'brand_id' => $val
                    ];
                }}
                
                if(isset($amenities)){foreach ($amenities as $key => $val) {
                    $amenities_data[$n ++] = [
                        'list_id' => $this->input->post('id'),
                        'amenity_id' => $val
                    ];
                }}
                
                if(isset($sub_categories)){foreach ($sub_categories as $key => $val) {
                    $sub_categories_data[$m ++] = [
                        'list_id' => $this->input->post('id'),
                        'sub_category_id' => $val
                    ];
                }}
                
                $this->db->where('list_id', $this->input->post('id'));
                $this->db->delete('vendor_services');
                $this->db->where('list_id', $this->input->post('id'));
                $this->db->delete('vendor_brands');
                $this->db->where('list_id', $this->input->post('id'));
                $this->db->delete('vendor_amenities');
                $this->db->where('list_id', $this->input->post('id'));
                $this->db->delete('vendors_sub_categories');
                $this->vendor_amenity_model->insert($amenities_data);
                $this->vendor_brand_model->insert($brands_data);
                $this->vendor_service_model->insert($services_data);
                $this->vendor_sub_category_model->insert($sub_categories_data); 
                $this->db->where('user_id', $this->input->post('vendor_user_id'));
                $this->db->delete('users_permissions');
                foreach ($services as $service){
                    $service_details = $this->db->select('permission_parent_ids')->where('id', $service)->get('services')->result_array();
                    $perms = explode(',', $service_details[0]['permission_parent_ids']);
                    foreach ($perms as $perm){
                        $child_permissions = $this->permission_model->where('parent_status', $perm)->as_array()->get_all();
                        foreach($child_permissions as $child_permission){
                            $get_perm = $this->db->get_where('users_permissions', ['user_id' => $this->input->post('vendor_user_id'), 'perm_id' => $child_permission['id'], 'value' => 1])->result_array();
                            if(empty($get_perm))
                            $this->db->insert('users_permissions', ['user_id' => $this->input->post('vendor_user_id'), 'perm_id' => $child_permission['id'], 'value' => 1]);
                        }
                        $this->db->insert('users_permissions', ['user_id' => $this->input->post('vendor_user_id'), 'perm_id' => $perm, 'value' => 1]);
                    }
                }
                redirect('vendor_profile/edit?id='.$this->input->post('id'));
        }elseif ($type == 'social'){
            $this->form_validation->set_rules($this->vendor_list_model->rules['social']);
            if ($this->form_validation->run() == FALSE) {
                $this->set_response_simple(validation_errors(), 'Internal Error Occured..!', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                if($this->social_model->where(['list_id' => $this->input->post('id'), 'type' => 1])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('facebook')], ['list_id' => $this->input->post('id'), 'type' => 1]);
                else 
                    $this->social_model->insert(['list_id' => $this->input->post('id'), 'url' => $this->input->post('facebook'), 'type' => 1]);
                
                if($this->social_model->where(['list_id' => $this->input->post('id'), 'type' => 2])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('twitter')], ['list_id' => $this->input->post('id'), 'type' => 2]);
                else
                    $this->social_model->insert(['list_id' => $this->input->post('id'), 'url' => $this->input->post(''), 'type' => 2]);
                
                if($this->social_model->where(['list_id' => $this->input->post('id'), 'type' => 3])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('instagram')], ['list_id' => $this->input->post('id'), 'type' => 3]);
                else
                    $this->social_model->insert(['list_id' => $this->input->post('id'), 'url' => $this->input->post('instagram'), 'type' => 3]);
                
                if($this->social_model->where(['list_id' => $this->input->post('id'), 'type' => 4])->get() != FALSE)
                    $this->social_model->update(['url' => $this->input->post('website')], ['list_id' => $this->input->post('id'), 'type' => 4]);
                else
                    $this->social_model->insert(['list_id' => $this->input->post('id'), 'url' => $this->input->post('website'), 'type' => 4]);
                
                redirect('vendor_profile/edit?id='.$this->input->post('id'));
            }
        }elseif ($type == 'cover'){
            $id = $this->input->post('id');
            if ($_FILES['file']['name'] !== '') {
                move_uploaded_file($_FILES['file']['tmp_name'], "./uploads/list_cover_image/list_cover_$id.jpg");
            }
            redirect('vendor_profile/edit?id='.$this->input->post('id'));
        }elseif ($type == 'banners'){
            $image_id = $this->vendor_banner_model->insert([
                'list_id' => $this->input->post('id'),
                'image' => 'banner_'.$this->input->post('id').'.jpg',
                'ext' => 'jpg'
            ]);
            if ($_FILES['banner']['name'] !== '') {
                move_uploaded_file($_FILES['banner']['tmp_name'], "./uploads/list_banner_image/list_banner_$image_id.jpg");
            }
            redirect('vendor_profile/edit?id='.$this->input->post('id'));
        }elseif ($type == 'banner_edit'){
            $this->data['title'] = 'Vendor Profile edit';
            $this->data['content'] = 'vendor/vendor/edit_banner';
            $this->data['banner'] = $this->vendor_banner_model->where('id', $_GET['id'])->get();
            $this->_render_page($this->template, $this->data);
        }elseif ($type == 'update_banner'){
            if ($_FILES['banner']['name'] !== '') {
                move_uploaded_file($_FILES['banner']['tmp_name'], "./uploads/list_banner_image/list_banner_".$this->input->post('id').".jpg");
            }
            redirect('vendor_profile/edit?id='.$this->input->post('list_id'));
        }elseif ($type == 'd'){
            echo $this->vendor_banner_model->delete(['id' => $this->input->post('id')]);
        }
    }
    
    public function vendor_payments($type = 'r'){
        if($type == 'r'){
            $this->data['title'] = 'Vendor Payments';
            $this->data['content'] = 'vendor/vendor/vendor_payments';
            if(isset($_POST['id'])){
                $this->data['transactions'] = $this->wallet_transaction_model->all($_POST['id'], (empty($_POST['start_date']))? NULL: $_POST['start_date'], (empty($_POST['end_date']))? NULL: $_POST['end_date']);
                $this->session->set_flashdata('txn_search', [
                    'id' => $_POST['id'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date']
                ]);
                $this->data['vendor'] = $this->user_model->where('id', $_POST['id'])->get();
            }else{
                $this->data['transactions'] = $this->wallet_transaction_model->all($_GET['id']);
                $this->data['vendor'] = $this->user_model->where('id', $_GET['id'])->get();
            }
            
            $this->_render_page($this->template, $this->data);
        }
    }
    
    // file upload functionality
    public function vendor_excel_import() {
        $this->data['title'] = 'Vendor Excel Important';
        $this->data['content'] = 'vendor/vendor/vendor_excel_import';
        $this->form_validation->set_rules('fileURL', 'Upload File', 'callback_checkFileValidation');
        if($this->form_validation->run() == false) {
            $this->_render_page($this->template, $this->data);
        } else {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '0');
            // If file uploaded
            if(!empty($_FILES['fileURL']['name'])) {
                // get file extension
                $extension = pathinfo($_FILES['fileURL']['name'], PATHINFO_EXTENSION);
                
                if($extension == 'csv'){
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } elseif($extension == 'xlsx') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }
                // file path
                $spreadsheet = $reader->load($_FILES['fileURL']['tmp_name']);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                
                // array Count
                $arrayCount = count($allDataInSheet);
                $flag = 0;
                $createArray = array('Executive', 'Category', 'Company','Locality', 'Address', 'PIN', 'Email', 'WhatsApp', 'Phone #1', 'Phone #2', 'Phone #3', 'Phone #4', 'Latitude', 'Longitude', 'Rating', 'Reviews', 'Verified', 'Paid', 'Website');
                $makeArray = array('Executive' => 'Executive', 'Category' => 'Category', 'Company' => 'Company', 'Locality' => 'Locality', 'Address' => 'Address', 'PIN' => 'PIN', 'Email' => 'Email', 'WhatsApp' => 'WhatsApp', 'Phone#1' => 'Phone #1', 'Phone#2' => 'Phone #2', 'Phone#3' => 'Phone #3', 'Phone#4' => 'Phone #4', 'Latitude' => 'Latitude', 'Longitude' => 'Longitude', 'Rating' => 'Rating', 'Reviews' => 'Reviews', 'Verified' => 'Verified', 'Paid' => 'Paid', 'Website' => 'Website');
                /* $createArray = array('Name', 'Category_Id','Mobile', 'Email', 'Address', 'Landmark', 'Pincode', 'Latitude', 'Longitude', 'Location_Address');
                $makeArray = array('Name' => 'Name', 'Category_Id' => 'Category_Id', 'Mobile' => 'Mobile', 'Email' => 'Email', 'Address' => 'Address', 'Landmark' => 'Landmark', 'Pincode' => 'Pincode', 'Latitude' => 'Latitude', 'Longitude' => 'Longitude', 'Location_Address' => 'Location_Address'); */
                $SheetDataKey = array();
                
                foreach ($allDataInSheet as $dataInSheet) {
                    foreach ($dataInSheet as $key => $value) {
                        if (in_array(trim($value), $createArray)) {
                            $value = preg_replace('/\s+/', '', $value);
                            $SheetDataKey[trim($value)] = $key;
                        }
                    }
                }
                //print_array($SheetDataKey);
                $dataDiff = array_diff_key($makeArray, $SheetDataKey);
                if (empty($dataDiff)) {
                    $flag = 1;
                }
                // match excel sheet column
                if ($flag == 1) { $k = 0;
                    for ($i = 2; $i <= $arrayCount; $i++) {
                        
                        $executive = $SheetDataKey['Executive'];
                        $cat_id = $SheetDataKey['Category'];
                        $company = $SheetDataKey['Company'];
                        $email = $SheetDataKey['Email'];
                        $address = $SheetDataKey['Address'];
                        $landmark = $SheetDataKey['Locality'];
                        $pincode = $SheetDataKey['PIN'];
                        $latitude = $SheetDataKey['Latitude'];
                        $longitude = $SheetDataKey['Longitude'];
                        $whatsapp = $SheetDataKey['WhatsApp'];
                        $website = $SheetDataKey['Website'];
                        
                        $executive = filter_var(trim($allDataInSheet[$i][$executive]), FILTER_SANITIZE_STRING);
                        $company = filter_var(trim($allDataInSheet[$i][$company]), FILTER_SANITIZE_STRING);
                        $cat_id = filter_var(trim($allDataInSheet[$i][$cat_id]), FILTER_SANITIZE_STRING);
                        $whatsapp = filter_var(trim($allDataInSheet[$i][$whatsapp]), FILTER_SANITIZE_STRING);
                        $email = filter_var(trim($allDataInSheet[$i][$email]), FILTER_SANITIZE_EMAIL);
                        $address = filter_var(trim($allDataInSheet[$i][$address]), FILTER_SANITIZE_STRING);
                        $landmark = filter_var(trim($allDataInSheet[$i][$landmark]), FILTER_SANITIZE_STRING);
                        $pincode = filter_var(trim($allDataInSheet[$i][$pincode]), FILTER_SANITIZE_STRING);
                        $latitude = filter_var(trim($allDataInSheet[$i][$latitude]), FILTER_SANITIZE_STRING);
                        $longitude = filter_var(trim($allDataInSheet[$i][$longitude]), FILTER_SANITIZE_STRING);
                        $website = filter_var(trim($allDataInSheet[$i][$website]), FILTER_SANITIZE_STRING);
                        
                        if($company !== '' &&  $cat_id !== ''){
                            $group = $this->group_model->where('name', 'vendor')->get();
                            $unique_id = generate_serial_no($group['code'], 4, $group['last_id']);
                            $this->group_model->update([
                                'last_id' => $group['last_id'] + 1
                            ], $group['id']);
                            
                            $identity = $unique_id;
                            $additional_data = array(
                                'first_name' => $company,
                                'unique_id' => $unique_id,
                                'active' => 1
                            );
                            $group_id[] = $group['id'];
                            
                            if ($this->check_email($email) == FALSE) {
                                $this->session->set_flashdata('upload_status', ["error" => "Error occured at row no($i)--- Email is already exist"]);
                                break;
                            } else {
                                $user_id = $this->ion_auth->register($identity,  '1234', $email, $additional_data, $group_id);
                                if($user_id){
                                    $is_location_exist = $this->location_model->where(['latitude' => $latitude, 'longitude' => $longitude])->get();
                                    if(empty($is_location_exist)){
                                        $location_id = $this->location_model->insert([
                                            'address' => $address,
                                            'latitude' => $latitude,
                                            'longitude' => $longitude,
                                        ]);
                                    }else{
                                        $location_id = $is_location_exist['id'];
                                    }
                                    $exe = $this->user_model->where('unique_id', $executive)->get();
                                    $vendors_list_data = [
                                        'name' => $company,
                                        'email' => $email,
                                        'vendor_user_id' => $user_id,
                                        'executive_id' => $exe['id'],
                                        'unique_id' => $unique_id,
                                        'category_id' => $cat_id,
                                        'location_id' => $location_id,
                                        'address' => $address,
                                        'landmark' => $landmark,
                                        'pincode' => $pincode,
                                        'status' => 1
                                    ];
                                    
                                    //$this->add_permissions_to_user($user_id, $cat_id);
                                    $this->db->insert('vendors_list', $vendors_list_data);
                                    $insert_id = $this->db->insert_id();
                                    $this->contact_model->insert([['list_id' =>  $insert_id, 'std_code' => '', 'number' => $whatsapp, 'type' => 1],['list_id' => $insert_id, 'std_code' => '', 'number' => $whatsapp, 'type' => 3]]);
                                    $this->social_model->insert([ 'list_id' => $insert_id, 'url' => $website, 'type' => 4 ]);
                                    $sub_categories = $this->sub_category_model->where('cat_id', $cat_id)->get_all();
                                    if(isset($sub_categories)){$sub_categories_data = []; $m = 0;foreach ($sub_categories as $key => $val) {
                                        $sub_categories_data[$m ++] = [
                                            'list_id' => $insert_id,
                                            'sub_category_id' => $val['id']
                                        ];
                                    }}
                                    $this->db->where('list_id', $insert_id);
                                    $this->db->delete('vendors_sub_categories');
                                    $this->vendor_sub_category_model->insert($sub_categories_data); 
                                }else{
                                    echo $this->ion_auth->errors().'With '.$email;
                                    break;
                                } 
                            }
                            $this->session->set_flashdata('upload_status', ["success" => "Vendors successfully imported..!"]);
                        }else{
                            $this->session->set_flashdata('upload_status', ["error" => "Error occured at row no($i)"]);
                            $this->data['vendor'] = array('Executive' => $executive, 'Category' => $cat_id, 'Company' => $company, 'Locality' => $landmark, 'Address' => $address, 'PIN' => $pincode, 'Email' => $email, 'WhatsApp' => $whatsapp, 'Phone#1' => 'Phone #1', 'Phone#2' => 'Phone #2', 'Phone#3' => 'Phone #3', 'Phone#4' => 'Phone #4', 'Latitude' => $latitude, 'Longitude' => $longitude, 'Rating' => 'Rating', 'Reviews' => 'Reviews', 'Verified' => 'Verified', 'Paid' => 'Paid', 'Website' => 'Website');
                            break;
                        }
                    }
                } else {
                    $this->session->set_flashdata('upload_status', ["error" => "Please import correct file, did not match excel sheet column"]);
                }
                $this->_render_page($this->template, $this->data);
            }
        }
    }
    
    // checkFileValidation
    public function checkFileValidation($string) {
        $file_mimes = array('text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        if(isset($_FILES['fileURL']['name'])) {
            $arr_file = explode('.', $_FILES['fileURL']['name']);
            $extension = end($arr_file);
            if(($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv') && in_array($_FILES['fileURL']['type'], $file_mimes)){
                return true;
            }else{
                $this->form_validation->set_message('checkFileValidation', 'Please choose correct file.');
                return false;
            }
        }else{
            $this->form_validation->set_message('checkFileValidation', 'Please choose a file.');
            return false;
        }
    }
    
    
    function add_permissions_to_user($user_id, $cat_id){
        $this->db->where('cat_id', $cat_id);
        $services = $this->db->get('categories_services')->result_array();
        $this->db->where('user_id', $user_id);
        $this->db->delete('users_permissions');
        foreach ($services as $service){
            $service_details = $this->db->select('permission_parent_ids')->where('id', $service['service_id'][0])->get('services')->result_array();
            $perms = explode(',', $service_details[0]['permission_parent_ids']);
            foreach ($perms as $perm){
                $child_permissions[] = $this->permission_model->where('parent_status', $perm)->as_array()->get_all();
                //print_array($child_permissions);
                foreach($child_permissions[0] as $child_permission){
                    $this->db->insert('users_permissions', ['user_id' => $user_id, 'perm_id' => $child_permission['id'], 'value' => 1]);
                }
                //$this->db->insert('users_permissions', ['user_id' => $user_id, 'perm_id' => $perm, 'value' => 1]);
            }
        }
    }
}