<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';
use Firebase\JWT\JWT;

class Food extends MY_REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('food_menu_model');
        $this->load->model('food_item_model');
        $this->load->model('food_section_model');
        $this->load->model('food_sec_item_model');
        $this->load->model('food_orders_model');
        $this->load->model('food_order_items_model');
        $this->load->model('food_sub_order_items_model');
        $this->load->model('food_order_deal_model');
        $this->load->model('food_settings_model');
        $this->load->model('delivery_boy_status_model');
        $this->load->model('users_address_model');
        $this->load->model('order_rating_model');
        $this->load->model('vendor_list_model');
        /* $this->load->model('food_cart_model'); */
    }

    /**
     * To get list of Menus and targeted Menu as well
     *
     * @author Mahesh
     * @param string $target
     */
    public function food_menus_get($vendor_id)
    {
        if (! empty($vendor_id)) {

            $w_r='vendor_id = '. $vendor_id .'OR vendor_id = 1';
            if ((isset($_GET['sub_cat_id'])) && ($_GET['sub_cat_id'] != '')) {
                $data = $this->food_menu_model->fields('id,name,desc')
                    ->where($w_r)
                    ->where('sub_cat_id', $_GET['sub_cat_id'])
                    ->get_all();
            } else {
                $data = $this->food_menu_model->fields('id,name,desc')
                    ->where($w_r)
                    ->get_all();
            }

            // $data = $this->food_menu_model->fields('id,name,desc')->where('vendor_id', $vendor_id)->get_all();
            if (! empty($data)) {
                for ($i = 0; $i < count($data); $i ++) {
                    if (file_exists('./uploads/food_menu_image/food_menu_' . $data[$i]['id'] . '.jpg')){
                    $data[$i]['image'] = base_url() . 'uploads/food_menu_image/food_menu_' . $data[$i]['id'] . '.jpg';
                    }else{
                    $data[$i]['image'] = base_url() . 'assets/img/no-img.png';
                    }
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To Check Vendor Avaliable status
     *
     * @author Mahesh
     * @param string $vendor_id
     */
    public function FoodSettings_get($vendor_id)
    {
        if (!empty($vendor_id)) {
           // $r=$this->db->where('user_id',$vendor_id)->get('users_permissions');
           // echo $this->db->last_query();die;
            //print_r($r->result_array());die;
            $data = $this->food_settings_model->with_vendor('fields:id,vendor_user_id,name')->fields('id,min_order_price,delivery_free_range,preparation_time,min_delivery_fee,ext_delivery_fee,restaurant_status,tax,label')
                ->where('vendor_id', $vendor_id)
                ->get();
                // if($data != ''){
                   /* $this->load->model('Users_permissions_model');
                    $r=$this->users_permissions_model->fields('id,perm_id')->where('user_id',$vendor_id)->get_all();*/
                   /* $r=$this->users_permissions_model->fields('id,perm_id')->where('vendor_id', $vendor_id)->get_all();*/
                    //$r=$this->db->select('id,perm_id')->where('user_id',$vendor_id)->get('users_permissioin')->result_array();
                   //print_r($r);die;
                   $rr=$this->vendor_list_model->fields('id,vendor_user_id,name,restaurant_status,label')->where('vendor_user_id', $vendor_id)->get();
                   if($data == ''){
                    $this->load->model('vendor_settings_model');
                    $data['vendor']['id']=$rr['id'];
                    $data['vendor']['vendor_user_id']=$rr['vendor_user_id'];
                    $data['vendor']['name']=$rr['name'];
                    $data['min_order_price']=$this->vendor_settings_model->where('key', 'min_order_price')->get()['value'];
                    $data['delivery_free_range']=$this->vendor_settings_model->where('key', 'delivery_free_range')->get()['value'];
                    $data['preparation_time']=$this->vendor_settings_model->where('key', 'preparation_time')->get()['value'];
                    $data['min_delivery_fee']=$this->vendor_settings_model->where('key', 'min_delivery_fee')->get()['value'];
                    $data['ext_delivery_fee']=$this->vendor_settings_model->where('key', 'ext_delivery_fee')->get()['value'];
                    $data['tax']=$this->vendor_settings_model->where('key', 'tax')->get()['value'];
                   }
                    $data['restaurant_status']=$rr['restaurant_status'];
                    $data['label']=$rr['label'];
                   $r=$this->db->select('id,perm_id')->where('user_id',$vendor_id)->get('users_permissions')->result_array();
                    $j['order_delivery']=$j['order_courier']=$j['order_booking']=$j['order_selfpickup']=0;
                    foreach ($r as $k) {
                        $this->load->model('permission_model');
                        $p=$this->permission_model->fields('id,perm_key,perm_name')->where('id',$k['perm_id'])->get();
                        if($p['perm_key'] == 'order_delivery'){
                            $j['order_delivery']=1;
                        }elseif($p['perm_key'] == 'order_selfpickup'){
                            $j['order_selfpickup']=2;
                        }elseif($p['perm_key'] == 'order_booking'){
                            $j['order_booking']=3;
                        }elseif($p['perm_key'] == 'order_courier'){
                            $j['order_courier']=1;
                        }
                    }
                    $data['permission']=$j;
                // }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get list of Items
     *
     * @author Mahesh
     * @param string $target
     */
    public function food_items_get($menu_id,$vendor_id)
    {
        if (! empty($menu_id) && !empty($vendor_id)) {
            
            if ($vendor_id == 1){
                    $w_r1='(created_user_id = '.$vendor_id.')';    
                }else{
                    $w_r1='(created_user_id = '. $vendor_id .' OR vendor_id = 1)';
                }
                $limit=null;
                $offset=null;
                if($this->get('limit') != ''){
                    $limit=$this->get('limit');
                }
                if($this->get('offset') != ''){
                    $offset=$this->get('offset');
                }
                if (! empty($this->get('item_type'))) {
                $this->db->where('item_type', $this->get('item_type'));
            }
            $data = $this->food_item_model->fields('id,name,desc,price,discount,quantity,item_type,status,created_user_id')
                ->where($w_r1)
                ->where('menu_id', $menu_id)
                ->where('approval_status',1)
                ->limit($limit,$offset)
                ->get_all();
            if (! empty($data)) {
                $items=array();
                for ($i = 0; $i < count($data); $i ++) {

                    $cou=$this->db->get_where('deleted_items',array('vendor_id'=>$vendor_id,'item_id'=>$data[$i]['id']))->num_rows();
                    
                    if($cou == 0){
                        if (file_exists('./uploads/food_item_image/food_item_' . $data[$i]['id'] . '.jpg')){
                    $data[$i]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $data[$i]['id'] . '.jpg';
                    }else{
                    $data[$i]['image'] = base_url() . 'assets/img/no-img.png';
                    }
                    $data[$i]['item_status'] = ($data[$i]['status'] == 1) ? 'Available' : 'Not Available';
                    if ($data[$i]['discount'] > 0) {
                        $d_pri = $data[$i]['price'] - ($data[$i]['price'] * ($data[$i]['discount'] / 100));
                        $data[$i]['discount_price'] = number_format((float) $d_pri, 2, '.', '');
                    }
                $sec_cou=$this->db->get_where('food_sec_item',array('item_id'=>$data[$i]['id']))->num_rows();
                $data[$i]['section_status']=0;
                    if($sec_cou > 0){
                        $data[$i]['section_status']=1;
                    }
                    $items[]=$data[$i];
                    }
                }
                $res['result'] = $items;
            }
            $res['item_type'] = [
                '1' => 'Veg',
                '2' => 'Non-Veg'
            ];
            $this->set_response_simple(($res == FALSE) ? FALSE : $res, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }



    /**
     * To get list of Menus and targeted Menu as well
     *
     * @author Mahesh
     * @param string $target
     */
    public function MenusItems_get($vendor_id)
    {
        if (! empty($vendor_id)) {

            $w_r='vendor_id = '. $vendor_id .'OR vendor_id = 1';
            if ((isset($_GET['sub_cat_id'])) && ($_GET['sub_cat_id'] != '')) {
                $data = $this->food_menu_model->fields('id,name,desc')
                    ->where($w_r)
                    ->where('sub_cat_id', $_GET['sub_cat_id'])
                    ->get_all();
            } else {
                $data = $this->food_menu_model->fields('id,name,desc')
                    ->where($w_r)
                    ->get_all();
            }

            if (! empty($data)) {
                for ($i = 0; $i < count($data); $i ++) {
                    if (file_exists('./uploads/food_menu_image/food_menu_' . $data[$i]['id'] . '.jpg')){
                    $data[$i]['image'] = base_url() . 'uploads/food_menu_image/food_menu_' . $data[$i]['id'] . '.jpg';
                    }else{
                    $data[$i]['image'] = base_url() . 'assets/img/no-img.png';
                    }
                    // $data[$i]['image'] = base_url() . 'uploads/food_menu_image/food_menu_' . $data[$i]['id'] . '.jpg';

                        /*Items Detils*/
                        if ($vendor_id == 1){
                    $w_r1='(created_user_id = '.$vendor_id.')';    
                }else{
                    $w_r1='(created_user_id = '. $vendor_id .' OR vendor_id = 1)';
                }
                $limit=null;
                $offset=null;
                if($this->get('limit') != ''){
                    $limit=$this->get('limit');
                }
                if($this->get('offset') != ''){
                    $offset=$this->get('offset');
                }
                if (! empty($this->get('item_type'))) {
                $this->db->where('item_type', $this->get('item_type'));
                }
            $data1 = $this->food_item_model->fields('id,name,desc,price,discount,quantity,item_type,status,created_user_id')
                ->where($w_r1)
                ->where('menu_id', $data[$i]['id'])
                ->where('approval_status',1)
                ->limit($limit,$offset)
                ->get_all();
            if (! empty($data1)) {
                $items=array();
                for ($j = 0; $j < count($data1); $j ++) {

                    $cou=$this->db->get_where('deleted_items',array('vendor_id'=>$vendor_id,'item_id'=>$data1[$j]['id']))->num_rows();
                    
                    if($cou == 0){
                        if (file_exists('./uploads/food_item_image/food_item_' . $data1[$j]['id'] . '.jpg')){
                    $data1[$j]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $data1[$j]['id'] . '.jpg';
                    }else{
                    $data1[$j]['image'] = base_url() . 'assets/img/no-img.png';
                    }
                    // $data1[$j]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $data1[$j]['id'] . '.jpg';
                    $data1[$j]['item_status'] = ($data1[$j]['status'] == 1) ? 'Available' : 'Not Available';
                    if ($data1[$j]['discount'] > 0) {
                        $d_pri = $data1[$j]['price'] - ($data1[$j]['price'] * ($data1[$j]['discount'] / 100));
                        $data1[$j]['discount_price'] = number_format((float) $d_pri, 2, '.', '');
                    }
                $sec_cou=$this->db->get_where('food_sec_item',array('item_id'=>$data1[$j]['id']))->num_rows();
                $data1[$j]['section_status']=0;
                    if($sec_cou > 0){
                        $data1[$j]['section_status']=1;
                    }
                    $items[]=$data1[$j];
                    }
                }
                $data[$i]['items'] = $items;
            }
                        /*Items Detils*/
















                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get list of Items
     *
     * @author Mahesh
     * @param string $target
     */
    public function FoodItems_get($vendor_id)
    {
        if (! empty($vendor_id)) {
            $menus = $this->food_menu_model->fields('id,name,desc')
                ->where('vendor_id', $vendor_id)
                ->get_all();
            $list = array();
            $k = 0;
            $res = array();
            if (! empty($menus)) {
                $j = 0;
                foreach ($menus as $menu) {
                    $menu_id = $menu['id'];
                    if (! empty($menu_id)) {
                        $data = $this->food_item_model->fields('id,name,desc,price,discount,quantity,item_type,status')
                            ->where('menu_id', $menu_id)
                            ->get_all();
                        if (! empty($data)) {
                            for ($i = 0; $i < count($data); $i ++) {
                                $data[$i]['image'] = base_url() . 'uploads/food_item_image/food_item_' . $data[$i]['id'] . '.jpg';
                                $data[$i]['item_status'] = ($data[$i]['status'] == 1) ? 'Available' : 'Not Available';
                                if ($data[$i]['discount'] > 0) {
                                    $d_pri = $data[$i]['price'] - ($data[$i]['price'] * ($data[$i]['discount'] / 100));
                                    $data[$i]['discount_price'] = number_format((float) $d_pri, 2, '.', '');
                                }
                                $k ++;
                            }
                        }

                        $ress[$j][$menu['name']] = $data;
                        $re[$j] = $k - 1;
                        $res['result'][$j] = $ress[$j];
                    }
                    $j ++;
                }
                $res['count'] = implode(',', $re);
            }
            $this->set_response_simple(($res == FALSE) ? FALSE : $res, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            /* $this->set_response_simple(($list == FALSE)? FALSE : $list, 'Success..!', REST_Controller::HTTP_OK, TRUE); */
        }
    }

    /**
     * To get Details of Single Item
     *
     * @author Mahesh
     * @param string $item_id
     */
    public function food_item_get($item_id)
    {
        if (! empty($item_id)) {
            $data = $this->food_item_model->fields('id,name,desc,price,discount,quantity,status,menu_id')
                ->where('id', $item_id)
                ->get();
            if (! empty($data)) {
                $data['image'] = base_url() . 'uploads/food_item_image/food_item_' . $data['id'] . '.jpg';
                $data['item_status'] = ($data['status'] == 1) ? 'Available' : 'Not Available';
                if ($data['discount'] > 0) {
                    $d_pri = $data['price'] - ($data['price'] * ($data['discount'] / 100));
                    $data['discount_price'] = number_format((float) $d_pri, 2, '.', '');
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get list of Sections
     *
     * @author Mahesh
     * @param string $target
     */
    /*
     * public function FoodSections_get($item_id) {
     * if(!empty($item_id)){
     * $data['result'] = $this->food_section_model->fields('id,name,required,item_field')->where('item_id', $item_id)->get_all();
     * $data['item_filed_types']=['1'=>'Radion','2'=>'Checkbox'];
     * $data['required_types']=['1'=>'Yes','0'=>'No'];
     * $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
     * }
     * }
     */
    public function FoodSections_get($item_id)
    {
        if (! empty($item_id)) {
            $sec = $this->food_section_model->fields('id,name,required,item_field,sec_price')
                ->where('item_id', $item_id)
                ->get_all();
            $sections = array();
            $s = 0;
            foreach ($sec as $sc) {

                $sec_item = $this->food_sec_item_model->fields('id,name,desc,price,status')
                    ->where('sec_id', $sc['id'])
                    ->get_all();
                $section_items = array();
                if (! empty($sec_item)) {
                    for ($i = 0; $i < count($sec_item); $i ++) {
                        $section_items[$i] = $sec_item[$i];
                        /* $section_items[$i]['image'] = base_url().'uploads/food_sec_item_image/food_sec_item_'.$sec_item[$i]['id'].'.jpg'; */
                        $section_items[$i]['sec_item_status'] = ($sec_item[$i]['status'] == 1) ? 'Available' : 'Not Available';
                    }
                }
                $sc['sec_items'] = $section_items;
                $sections[] = $sc;
            }
            
            $data['result'] = $sections;

            $data['item_filed_types'] = [
                '1' => 'Radion',
                '2' => 'Checkbox'
            ];
            $data['required_types'] = [
                '1' => 'Yes',
                '0' => 'No'
            ];
            $data['section_price'] = [
                '1' => 'Add',
                '2' => 'Replace',
                '3' => 'No Price'
            ];
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get list of Section Items
     *
     * @author Mahesh
     * @param string $target
     */
    public function FoodSecItems_get($sec_id)
    {
        if (! empty($sec_id)) {
            $data['result'] = $this->food_sec_item_model->fields('id,name,desc,price,status')
                ->where('sec_id', $sec_id)
                ->get_all();
            if (! empty($data)) {
                for ($i = 0; $i < count($data['result']); $i ++) {
                    /* $data['result'][$i]['image'] = base_url().'uploads/food_sec_item_image/food_sec_item_'.$data['result'][$i]['id'].'.jpg'; */
                    $data['result'][$i]['sec_item_status'] = ($data['result'][$i]['status'] == 1) ? 'Available' : 'Not Available';
                }
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * To get Details of Section Items
     *
     * @author Mahesh
     * @param string $target
     */
    public function FoodSecItem_get($sec_item_id)
    {
        if (! empty($sec_item_id)) {
            $data = $this->food_sec_item_model->fields('id,name,desc,price,status')
                ->where('id', $sec_item_id)
                ->get();
            if (! empty($data)) {
                $data['image'] = base_url() . 'uploads/food_sec_item_image/food_sec_item_' . $data['id'] . '.jpg';
                $data['sec_item_status'] = ($data['status'] == 1) ? 'Available' : 'Not Available';
            }
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     *
     * @author Mahesh
     *         To Place an Order
     */
    /*
     * public function OrderFood_POST()
     * {
     * $this->form_validation->set_rules($this->food_menu_model->rules);
     * // Run Validations
     * if ($this->form_validation->run() == FALSE) {
     * $this->set_response_simple(validation_errors(), 'Failed..!', REST_Controller::HTTP_OK, TRUE);
     * }else{
     * $data=1;
     * $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
     * }
     *
     * }
     */
    /**
     * Food Order
     *
     * To Manage Food Order
     *
     * @author Mahesh
     * @param
     *            string
     */
    public function OrderSupport_POST($type='r')
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $this->load->model('order_support_model');

        if($type == 'r'){
            $data=$this->order_support_model->where('order_id',$_POST['order_id'])->order_by('id', 'DESC')->get_all();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'post') {
        $arr=array(
            'message'=>$_POST['message'],
            'from_id'=>$token_data->id,
            'to_id'=>1,
            'order_id'=>$_POST['order_id']
        );
        $res=$this->db->insert('order_support',$arr);
        $order_id=$this->db->insert_id();
        /*if($res){
            return $this->db->insert_id();
        }else{
            return false;
        }*/
        $this->set_response_simple($order_id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
        }
    }
    /**
     * Food Order
     *
     * To Manage Food Order
     *
     * @author Mahesh
     * @param
     *            string
     */
    public function FoodOrder_POST()
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);

        // $this->form_validation->set_rules($this->users_address_model->rules);
        $this->form_validation->set_rules($this->food_orders_model->rules);
        if ($this->form_validation->run() == false) {
            $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
        } else {
            /*
             * $address_id = '';
             * if(empty($_POST['address_id'])){
             * $address_id = $this->users_address_model->insert([
             * 'user_id' => $token_data->id,
             * 'name' => $this->input->post('name'),
             * 'email' => $this->input->post('email'),
             * 'phone' => $this->input->post('mobile'),
             * 'address' => $this->input->post('address'),
             * ]);
             * }else {
             * $address_id = $_POST['address_id'];
             * }
             */
            $order_track=rand();
            $order_id = $this->food_orders_model->insert([
                'user_id' => $token_data->id,
                'order_track' => $order_track,
                'discount' => $this->input->post('discount'),
                'delivery_fee' => $this->input->post('delivery_fee'),
                'tax' => $this->input->post('tax'),
                'sub_total' => $this->input->post('sub_total'),
                'total' => $this->input->post('total'),
                'promo_id' => $this->input->post('promo_id'),
                'promo_code' => $this->input->post('promo_code'),
                /*'promo_discount' => $this->input->post('promo_discount'),*/
                'address_id' => $this->input->post('address_id'),
                'delivery' => $this->input->post('delivery'),
                'payment_method_id' => $this->input->post('payment_method_id'),
                'instructions' => $this->input->post('instructions'),
                'vendor_id' => $this->input->post('vendor_id'),
                'used_walet' => $this->input->post('used_walet'),
                'used_walet_amount' => $this->input->post('used_walet_amount')
            ]);
            if (! empty($order_id)) {
                if (! empty($_POST['promo_id'])) {
                    $this->load->model('promos/promos_used_model');
                    $sub_id = $this->promos_used_model->insert([
                        'user_id' => $token_data->id,
                        'promo_id' => $_POST['promo_id'],
                        'promo_code' => $_POST['promo_code'],
                        'promo_discount' => $_POST['discount']
                    ]);
                }
                foreach ($_POST['items'] as $item) {
                    /*
                     * $sec_it=NULL;
                     * if(!empty($item['sec_item_id'])){
                     * $sec_it=$item['sec_item_id'];
                     * }
                     */
                    $item_id = $this->food_order_items_model->insert([
                        'order_id' => $order_id,
                        'item_id' => $item['item_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                        /* 'sec_item_id' => $sec_it, */
                    ]);
                }
                if (! empty($_POST['sec_items'])) {
                    foreach ($_POST['sec_items'] as $sub_item) {
                        $sub_id = $this->food_sub_order_items_model->insert([
                            'order_id' => $order_id,
                            'item_id' => $sub_item['item_id'],
                            'sec_item_id' => $sub_item['sec_item_id'],
                            'quantity' => $sub_item['sec_quantity'],
                            'price' => $sub_item['sec_price']
                        ]);
                    }
                }
                if($this->input->post('used_walet') == 1){
                    $this->load->model('user_model');
                    $this->user_model->update_walet($token_data->id,$this->input->post('used_walet_amount'),'Order: '.$order_track,'DEBIT');
                }
            }
            $this->set_response_simple($order_id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
        }
    }

    /**
     * Food Order
     *
     * To Manage Food Orders
     *
     * @author Mahesh
     * @param
     *            string
     */
    public function FoodOrders_get($type, $user_id)
    {
        /*
         * $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
         * $user_id=$token_data->id;
         */
        if (! empty($type) && ! empty($user_id)) {
            if ($type == 'past') {
                /* $this->db->where('order_status',6); */
                $where_order_status = 'order_status = 6';
            } elseif ($type == 'upcoming') {
                /*
                 * $this->db->where('order_status !=',0);
                 * $this->db->where('order_status !=',6);
                 * $this->db->where('order_status !=',7);
                 */
                $where_order_status = 'order_status != 0 AND order_status != 6 AND order_status != 7';
            }
            $data = array();
            $this->db->where($where_order_status);
            $data = $this->food_orders_model->with_vendor('fields:name,landmark')
                ->with_order_items('fields:item_id,price,quantity')
                ->with_sub_order_items('fields:item_id,sec_item_id,price,quantity')
                ->fields('id,discount,delivery_fee,delivery,otp,tax,sub_total,total,deal_id,order_track,order_status,created_at')
                ->where('user_id', $user_id)
                ->order_by('id', 'DESC')
                ->get_all();

            if (! empty($data)) {
                /* $status=['0'=>'Rejected','1'=>'Order Received','2'=>'Accepted','3'=>'Preparing','4'=>'Out for delivery','5'=>'Order on the way','6'=>'Order Completed','7'=>'Cancelled']; */
                for ($i = 0; $i < count($data); $i ++) {
                    $cat_id = $this->vendor_list_model->where('vendor_user_id', $data[$i]['vendor_id'])->get();
                    $vendor_category_id = $cat_id['category_id'];
                    /*$status = [
                        '0' => (($this->ion_auth->is_admin()) ? 'Order Rejected' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_rejected')),
                        '1' => (($this->ion_auth->is_admin()) ? 'Order Received' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_received')),
                        '2' => (($this->ion_auth->is_admin()) ? 'Order Accept' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_accepted')),
                        '3' => (($this->ion_auth->is_admin()) ? 'Order Preparing' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_preparing')),
                        '4' => (($this->ion_auth->is_admin()) ? 'Out for delivery' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_out_delivery')),
                        '5' => (($this->ion_auth->is_admin()) ? 'Order on the way' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_on_way')),
                        '6' => (($this->ion_auth->is_admin()) ? 'Order Completed' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_completed')),
                        '7' => (($this->ion_auth->is_admin()) ? 'Order Cancelled' : $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_canceled'))
                    ];*/
                    $status = [
                        '0' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_rejected'),
                        '1' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_received'),
                        '2' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_accepted'),
                        '3' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_preparing'),
                        '4' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_out_delivery'),
                        '5' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_on_way'),
                        '6' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_completed'),
                        '7' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_canceled')
                    ];
                    $data[$i]['vendor']['image']=base_url() . "uploads/list_cover_image/list_cover_" . $cat_id['id'] . ".jpg";
                    $data[$i]['order_status'] = $status[$data[$i]['order_status']];
                }
            }
            $data1 = array();
            if (!empty($data)) {
                $ord_rep = '';
                foreach ($data as $order) {

                    $ord_rep .= '<ul>';
                    foreach ($order['order_items'] as $ord_it) {

                        $ord_rep .= '<li>' . $this->db->get_where('food_item', array(
                            'id' => $ord_it['item_id']
                        ))->row()->name;
                        (! empty($ord_it['sec_item_id'])) ? '<br/>' . $this->db->get_where('food_sec_item', array(
                            'id' => $ord_it['sec_item_id']
                        ))->row()->name : '';
                        $ord_rep .= '<span class="pull-right">' . ' X ' . $ord_it['quantity'] . '</span>';

                        if (! empty($order['sub_order_items'])) {
                            $ord_rep .= '<ul>';
                        foreach ($order['sub_order_items'] as $sub_ord_it) {
                            if($sub_ord_it['item_id'] == $ord_it['item_id']){

                            $ord_rep .= '<li>' . $this->db->get_where('food_sec_item', array(
                                'id' => $sub_ord_it['sec_item_id']
                            ))->row()->name . '<span class="pull-right">' . ' X ' . $sub_ord_it['quantity'] . '</span></li>';
                        }
                        }
                        $ord_rep .= '</ul>';
                    }
                        
                          $ord_rep .= '</li>';
                    }

                    
                    $ord_rep .= '</ul>';
                    /*
                     * $data['order_receipt']=$ord_rep;
                     * echo "<pre>";
                     * print_r($data);die;
                     * $data[]=$order;
                     */
                    $ord = $order;
                    $ord_r=$this->order_rating_model->where('order_id',$order['id'])->get();
                    $ord['ord_rating']=0;
                    if($ord_r != ''){
                        $ord['ord_rating']=1;
                        $ord['rating']=$ord_r['rating'];
                        $ord['review']=$ord_r['review'];
                        $ord['del_rating']=$ord_r['del_rating'];
                        $ord['del_review']=$ord_r['del_review'];
                    }
                    $ord['order_receipt'] = $ord_rep;

                    $data1[] = $ord;
                    /* $data1['order_receipt']=$ord; */
                    /*
                     * echo "<pre>";
                     * print_r($data1);die;
                     */
                }
            }

            /*
             * echo "<pre>";
             * print_r($data);die;
             */
            /* $data['order_status']=['0'=>'Rejected','1'=>'Order Recived','2'=>'Accepted','3'=>'Preparing','4'=>'Out for delivery','5'=>'Order on the way','6'=>'Order Completed']; */
            $this->set_response_simple(($data1 == FALSE) ? FALSE : $data1, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     * Food Order
     *
     * To Manage Food Order
     *
     * @author Mahesh
     * @param
     *            string
     */
    public function Order_get($order_id)
    {
        /*
         * $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
         * $user_id=$token_data->id;
         */
        if (! empty($order_id)) {
            
            $data = $this->food_orders_model->with_vendor('fields:name')
                ->with_order_items('fields:item_id,price,quantity')
                ->with_sub_order_items('fields:item_id,sec_item_id,price,quantity')
                ->fields('id,discount,delivery_fee,delivery,otp,tax,sub_total,total,deal_id,order_track,order_status,address_id,created_at')
                ->where('id', $order_id)
                ->order_by('id', 'DESC')
                ->get();

            if (! empty($data)) {
                    $cat_id = $this->vendor_list_model->where('vendor_user_id', $data['vendor_id'])->get();
                    $vendor_category_id = $cat_id['category_id'];
                    $status = [
                        '0' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_rejected'),
                        '1' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_received'),
                        '2' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_accepted'),
                        '3' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_preparing'),
                        '4' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_out_delivery'),
                        '5' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_on_way'),
                        '6' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_completed'),
                        '7' => $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_canceled')
                    ];

                    $oror=$data['order_status'];
$r[]=[
    'name'=> $this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_received'),
    'status'=> 1
    ];
if($oror != 0 && $oror != 7){
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_accepted'),
    'status'=>(($oror >1 && $oror <=6 )? 1 : 0 )
    ];
if($this->category_model->get_cat_desc_account_name($vendor_category_id,'order_preparing','field_status') == 1){
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_preparing'),
    'status'=>(($oror >2 && $oror <=6 )? 1 : 0 )
    ];
}
if($this->category_model->get_cat_desc_account_name($vendor_category_id,'order_out_delivery','field_status') == 1){
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_out_delivery'),
    'status'=>(($oror >3 && $oror <=6 )? 1 : 0 )
    ];
}
if($this->category_model->get_cat_desc_account_name($vendor_category_id,'order_on_way','field_status') == 1){
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_on_way'),
    'status'=>(($oror >4 && $oror <=6 )? 1 : 0 )
    ];
}
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_completed'),
    'status'=>(($oror >5 && $oror <=6 )? 1 : 0 )
    ];
}elseif($oror == 0){
    $r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_rejected'),
    'status'=> 1
    ];
}elseif($oror == 7){
    $r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_canceled'),
    'status'=> 1
    ];
}
                    $data['ord_state']=$r;

                    $data['order_status'] = $status[$data['order_status']];
                    $v = $this->vendor_list_model->with_location('fields:latitude,longitude')
                        ->where('vendor_user_id', $data['vendor_id'])
                        ->get();
                    $u = $this->users_address_model->with_location('fields:latitude,longitude')
                        ->where('id', $data['address_id'])
                        ->get();
                    $data['resturant'] = $v['name'];
                    $data['resturant_address'] = $v['address'];
                    $data['resturant_lat'] = $v['location']['latitude'];
                    $data['resturant_lng'] = $v['location']['longitude'];
                    $data['customer'] = $u['name'];
                    $data['customer_address'] = $u['address'];
                    $data['customer_lat'] = $u['location']['latitude'];
                    $data['customer_lng'] = $u['location']['longitude'];
            }
            $data1 = '';
            if (! empty($data)) {
                $ord_rep = '';

                    $ord_rep .= '<ul>';
                    $k=0;
                    foreach ($data['order_items'] as $ord_it) {
                        
                        $ord_rep .= '<li>' . $this->db->get_where('food_item', array(
                            'id' => $ord_it['item_id']
                        ))->row()->name;
                        $data['order_items'][$k]['item_name']=$this->db->get_where('food_item', array(
                            'id' => $ord_it['item_id']
                        ))->row()->name;
                        (! empty($ord_it['sec_item_id'])) ? '<br/>' . $this->db->get_where('food_sec_item', array(
                            'id' => $ord_it['sec_item_id']
                        ))->row()->name : '';
                        $ord_rep .= '<span class="pull-right">' . ' X ' . $ord_it['quantity'] . '</span>';
                            if (! empty($data['sub_order_items'])) {
                            $s=0;
                            $ord_rep .= '<ul>';
                        foreach ($data['sub_order_items'] as $sub_ord_it) {
                            if($sub_ord_it['item_id'] == $ord_it['item_id']){
                            $data['sub_order_items'][$s]['sec_item_name']=$this->db->get_where('food_sec_item', array(
                                'id' => $sub_ord_it['sec_item_id']
                            ))->row()->name;
                            $ord_rep .= '<li>' . $this->db->get_where('food_sec_item', array(
                                'id' => $sub_ord_it['sec_item_id']
                            ))->row()->name . '<span class="pull-right">' . ' X ' . $sub_ord_it['quantity'] . '</span></li>';
                        $s++;}
                        }
                        $ord_rep .= '</ul>';
                    }
                        
                          $ord_rep .= '</li>';
                          $k++;
                    }

                    
                    $ord_rep .= '</ul>';
                    
                    $ord = $data;
                    $ord_r=$this->order_rating_model->where('order_id',$data['id'])->get();
                    $ord['ord_rating']=0;
                    if($ord_r != ''){
                        $ord['ord_rating']=1;
                        $ord['rating']=$ord_r['rating'];
                        $ord['review']=$ord_r['review'];
                        $ord['del_rating']=$ord_r['del_rating'];
                        $ord['del_review']=$ord_r['del_review'];
                    }
                    $ord['order_receipt'] = $ord_rep;

                    $data1 = $ord;
                    /* $data1['order_receipt']=$ord; */
                    /*
                     * echo "<pre>";
                     * print_r($data1);die;
                     */
                
            }

            $this->set_response_simple(($data1 == FALSE) ? FALSE : $data1, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }

    /**
     *
     * @author Mahesh
     *         To Accept the requested orders
     */

    /*
     * public function FoodOrderReviews_POST()
     * {
     * $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
     * $input=$this->post();
     * $address_id = $this->users_address_model->insert([
     * 'user_id' => $token_data->id,
     * 'name' => $this->input->post('name'),
     * 'email' => $this->input->post('email'),
     * 'phone' => $this->input->post('mobile'),
     * 'address' => $this->input->post('address'),
     * ]);
     * $this->set_response_simple(($r == FALSE)? FALSE : $r, 'Success..!', REST_Controller::HTTP_OK, TRUE);
     *
     * }
     */

    /* Food Delivery Boy App Start */
    /**
     *
     * @author Mahesh
     *         To get list of All Past Delivery Orders
     */
    public function FoodDealOldHistory_get()
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $data = $this->food_order_deal_model->with_order('fields:vendor_id,user_id,address_id,order_track,order_status,payment_method_id,sub_total,total,created_at')
            ->with_deal_boy('fields: id, first_name')
            ->fields('id,order_id,deal_id,otp')
            ->where('deal_id', $token_data->id)
            ->where('ord_deal_status', 2)
            ->order_by('id', 'DESC')
            ->get_all();
        $result = array();
        if (! empty($data)) {
            $i = 0;
            foreach ($data as $row) {
                $result[$i]=$row;
                if ($row['order']['order_status'] == 6) {
                    $v = $this->vendor_list_model->where('vendor_user_id', $row['order']['vendor_id'])->get();
                    $u = $this->users_address_model->where('id', $row['order']['address_id'])->get();
                    $result[$i]['order_number'] = $row['order']['order_track'];
                    $result[$i]['resturant'] = $v['name'];
                    $result[$i]['resturant_address'] = $v['address'];
                    $result[$i]['customer'] = $u['name'];

                    if ($row['order']['payment_method_id'] == 1) {
                        $result[$i]['price'] = $row['order']['total'];
                    }

                    $i ++;
                }
            }
        }

        $this->set_response_simple(($result == FALSE) ? FALSE : $result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     * To get list of All Notifications of Orders from Resturant
     *
     * @author Mahesh
     * @param string $ord_deal_status
     */
    public function FoodDealOrdRequests_get($ord_deal_status)
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $data = $this->food_order_deal_model->with_order('fields:vendor_id,user_id,address_id,order_status,payment_method_id,sub_total,total,order_track,used_walet,used_walet_amount')
            ->with_deal_boy('fields: id, first_name')
            ->fields('id,order_id,deal_id,otp,created_at')
            ->where('deal_id', $token_data->id)
            ->where('ord_deal_status', $ord_deal_status)
            ->order_by('id', 'DESC')
            ->get_all();
        $result = array();
        if (! empty($data)) {
            $i = 0;
            foreach ($data as $row) {
                if ($row['order']['order_status'] == 2 || $row['order']['order_status'] == 3 || $row['order']['order_status'] == 4 || $row['order']['order_status'] == 5) {
                    $v = $this->vendor_list_model->with_location('fields:latitude,longitude')
                        ->where('vendor_user_id', $row['order']['vendor_id'])
                        ->get();
                    $u = $this->users_address_model->with_location('fields:latitude,longitude')
                        ->where('id', $row['order']['address_id'])
                        ->get();
                    $result[$i]['id'] = $row['id'];
                    $result[$i]['order_id'] = $row['order_id'];
                    $result[$i]['created_at'] = $row['created_at'];
                    $result[$i]['order_no'] = $row['order']['order_track'];
                    $result[$i]['resturant'] = $v['name'];
                    $result[$i]['resturant_address'] = $v['address'];
                    $result[$i]['resturant_lat'] = $v['location']['latitude'];
                    $result[$i]['resturant_lng'] = $v['location']['longitude'];
                    $result[$i]['customer'] = $u['name'];
                    $result[$i]['customer_address'] = $u['address'];
                    $result[$i]['customer_lat'] = $u['location']['latitude'];
                    $result[$i]['customer_lng'] = $u['location']['longitude'];
                    if ($ord_deal_status == 2) {
                        $result[$i]['otp'] = $row['otp'];
                    }
                    if ($row['order']['order_status'] == 4) {
                        $result[$i]['orderstatus'] = 'Order Received';
                        $result[$i]['orderstatus_id'] = 5;
                    }

                    if ($row['order']['order_status'] == 5) {
                        if ($row['order']['payment_method_id'] == 1) {
                            $total='';
                            if($row['order']['total']==''){
                                $total='0';
                            }elseif($row['order']['total'] != '' && $row['order']['used_walet'] == 1){
                                $total=$row['order']['total']-$row['order']['used_walet_amount'];
                            }elseif($row['order']['total'] != '' && $row['order']['used_walet'] == 0){ 
                                $total=$row['order']['total'];
                            }
                            $result[$i]['price'] = $total;
                        }
                        $result[$i]['customer_number'] = $u['phone'];
                        $result[$i]['orderstatus'] = 'Order Completed';
                        $result[$i]['orderstatus_id'] = 6;
                    }
                    $i ++;
                }
            }
        }

        $this->set_response_simple(($result == FALSE) ? FALSE : $result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     *
     * @author Mahesh
     *         To Accept the requested orders
     */
    public function FoodDealOrdRequest_POST()
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $input = $this->post();
        $v = $this->food_order_deal_model->where('id', $input['id'])->get();
        if ($input['status'] == 2) {
            $otp = rand(1234, 9567);
            $ord_id = $this->food_order_deal_model->update([
                'id' => $input['id'],
                'ord_deal_status' => $input['status'],
                'otp' => $otp
            ], 'id');
            if ($ord_id) {
                $this->food_orders_model->update([
                    'deal_id' => $v['deal_id'],
                    'otp' => $otp
                ], [
                    'id' => $v['order_id']
                ]);
                /*
                 * $r=$this->food_order_deal_model->update([
                 * 'deleted_at' =>date('Y-m-d H:i:s')
                 * ], [
                 * 'id !='=>$input['id'],
                 * 'order_id'=>$v['order_id'],
                 * 'ord_deal_status'=>1
                 * ]);
                 */
                $where = [
                    'id !=' => $input['id'],
                    'order_id' => $v['order_id'],
                    'ord_deal_status' => 1
                ];
                $this->db->where($where);
                $this->db->delete('food_order_deal');
            }
        } elseif ($input['status'] == '') {
            $r = $this->food_order_deal_model->update([
                'id' => $input['id'],
                'deleted_at' => date('Y-m-d H:i:s')
            ], 'id');
        }

        $this->set_response_simple(($r == FALSE) ? FALSE : $r, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     *
     * @author Mahesh
     *         To Update Received order Status
     */
    public function FoodDealOrdReceived_POST()
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
        $v = $this->food_order_deal_model->where('id', $_POST['id'])->get();
        $ord_id = $this->food_orders_model->update([
            'id' => $v['order_id'],
            'order_status' => $_POST['orderstatus_id']
        ], 'id');

        if ($_POST['orderstatus_id'] == 6) {
            $this->load->model('user_model');
            $this->load->model('setting_model');
            $order = $this->food_orders_model->where('id', $v['order_id'])->get();
            $this->user_model->update_walet($order['vendor_id'], $order['total'], 'Order: ' . $order['order_track']);
            $this->user_model->update_walet($v['deal_id'], floatval($this->setting_model->where('key','pay_per_order')->get()['value']), 'Order: ' . $order['order_track']);
        }

        $this->set_response_simple(($ord_id == FALSE) ? FALSE : $ord_id, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }

    /**
     *
     * @author Mahesh
     *         To Update Delivery Boy Status
     */
    public function DeliveryBoyStatus_POST()
    {
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        $input = $this->post();
        $v = $this->delivery_boy_status_model->where('user_id', $token_data->id)->get();
        if ($v != '') {
            $id_deal = $this->delivery_boy_status_model->update([
                'delivery_boy_status' => $this->input->post('delivery_boy_status')
            ], [
                'user_id',
                $token_data->id
            ]);
        } else {
            $id_deal = $this->delivery_boy_status_model->insert([
                'delivery_boy_status' => $this->input->post('delivery_boy_status'),
                'user_id' => $token_data->id
            ]);
        }
        $this->set_response_simple(($id_deal == FALSE) ? FALSE : $id_deal, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
    /* Food Delivery Boy App End */


/*Order Rating Start*/
public function OrderRating_POST()
{
    $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
    $_POST = json_decode(file_get_contents("php://input"), TRUE);
    $id_deal = $this->order_rating_model->insert([
                'order_id' => $_POST['order_id'],
                'rating' => $_POST['rating'],
                'review' => $_POST['review'],
                'del_rating' => $_POST['del_rating'],
                'del_review' => $_POST['del_review'],
                'user_id' => $token_data->id
            ]);
        $this->set_response_simple(($id_deal == FALSE) ? FALSE : $id_deal, 'Success..!', REST_Controller::HTTP_OK, TRUE);
}
/*Order Rating End*/

/**
 * To get list of Food Cart Based on vendor and user id
 *
 * @author Mahesh
 * @param string $vendor_id
 * @param string $user_id
 */
    /*
     * public function FoodCart_get($user_id) {
     * if(!empty($user_id)){
     * $data = $this->food_cart_model->fields('id,item_id,quantity')->where('user_id', $user_id)->get_all();
     * $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
     * }
     * }
     */
/**
 *
 * @author Mahesh
 *         @ POST Add Cart
 */
    /*
     * public function FoodCart_POST()
     * {
     *
     * $input=$this->post();
     * for ($i=0; $i < count($input['item_id']); $i++) {
     * if(empty($input['id'])){
     * $cart_id = $this->food_cart_model->insert([
     * 'user_id' => $input['user_id'],
     * 'item_id' => $input['item_id'][$i],
     * 'quantity' => $input['quantity'][$i]
     * ]);
     * }else{
     * $cart_id = $this->food_menu_model->update([
     * 'id' => $input['id'],
     * 'quantity' => $input['quantity'][$i]
     * ], 'id');
     * }
     * }
     * $this->set_response_simple(($cart_id == FALSE)? FALSE : $cart_id, 'Success..!', REST_Controller::HTTP_OK, TRUE);
     *
     * }
     */

    /*
     * public function user_details_get($u_type='')
     * {
     * $this->load->model('user_model');
     * $this->load->model('group_model');
     * $result=$this->user_model->order_by('id', 'DESC')->fields('id,unique_id,first_name,last_name,email,phone')->with_groups('fields:name,id')->get_all();
     * $this->set_response_simple(($result == FALSE)? FALSE : $result, 'Success..!', REST_Controller::HTTP_OK, TRUE);
     * }
     */
}