<?php

class Vendor_list_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendors_list';
        $this->primary_key = 'id';
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
       $this->_config();
       $this->_form();
       $this->_relations();
       
       $this->pagination_delimiters = array('<li class="page-item">','</li>');
       $this->pagination_arrows = array('&lt;','&gt;');
    }
    protected function _add_created_by($data)
    {
        $data['created_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    protected function _add_updated_by($data)
    {
        $data['updated_user_id'] = $this->ion_auth->get_user_id(); //add user_id
        return $data;
    }
    public function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    public function _relations(){
        $this->has_one['location'] = array('Location_model', 'id', 'location_id');
        $this->has_one['executive'] = array('User_model', 'id', 'executive_id');
        $this->has_one['category'] = array('Category_model', 'id', 'category_id');
        $this->has_one['constituency'] = array('Constituency_model', 'id', 'constituency_id');
        $this->has_many['contacts'] = array(
            'foreign_model' => 'Contact_model',
            'foreign_table' => 'contacts',
            'local_key' => 'id',
            'foreign_key' => 'list_id'
        );
        $this->has_many['links'] = array(
            'foreign_model' => 'Social_model',
            'foreign_table' => 'social',
            'local_key' => 'id',
            'foreign_key' => 'list_id'
        );
        $this->has_many_pivot['amenities'] = array(
            'foreign_model' => 'Amenity_model',
            'pivot_table' => 'vendor_amenities',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'pivot_foreign_key' => 'amenity_id',
            'foreign_key' => 'id'
        );
        $this->has_many_pivot['services'] = array(
            'foreign_model' => 'Service_model',
            'pivot_table' => 'vendor_services',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'pivot_foreign_key' => 'service_id',
            'foreign_key' => 'id'
        );
        $this->has_many_pivot['brands'] = array(
            'foreign_model' => 'Brand_model',
            'pivot_table' => 'vendor_brands',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'pivot_foreign_key' => 'brand_id',
            'foreign_key' => 'id'
        );
        $this->has_many_pivot['holidays'] = array(
            'foreign_model' => 'Day_model',
            'foreign_table' => 'days',
            'pivot_table' => 'vendors_holidays',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'foreign_key' => 'id',
            'pivot_foreign_key' => 'day_id',
        );
        
        $this->has_many_pivot['sub_categories'] = array(
            'foreign_model' => 'sub_category_model',
            'foreign_table' => 'sub_categories',
            'pivot_table' => 'vendors_sub_categories',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'foreign_key' => 'id',
            'pivot_foreign_key' => 'sub_category_id',
        );
        
        $this->has_many['timings'] = array(
          'foreign_model' => 'vendor_timings_model',
          'foreign_table' => 'vendor_timings',
          'local_key' => 'id',
          'foreign_key' => 'list_id'
        );
        $this->has_one['fields']=array(
          'foreign_model' => '',
          'foreign_table' => '',
          'local_key' => '',
          'foreign_key' => ''
        );
        /*$this->has_many['users'] = array(
            'foreign_model' => 'user_model',
            'foreign_table' => 'users',
            'local_key' => 'id',
            'foreign_key' => 'executive_id'
        );*/
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'ref_id',
                'lable' => 'Referal Id',
                'rules' => 'min_length[5]|max_length[10]|callback_check_referance',
                'errors' => array(
                    'min_length' => 'you need to give minimum 5 characters',
                    'check_referance' => 'Referal id is not valid'
                ),
            ),
            array(
                'field' => 'email',
                'lable' => 'Email',
                'rules' => 'trim|required|valid_email|callback_check_email',
                'errors' => array(
                    'callback_check_email' =>'email already exists'
                )
            ),
            
           /*  array(
                'field' => 'mobile',
                'lable' => 'Mobile',
                'rules' => 'required|callback_check_mobile',
                'errors' => array(
                    'callback_check_mobile' =>'Mobile already exists'
                )
            ), */
            array(
                'field' => 'constituency_id',
                'lable' => 'Constituency Id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'category_id',
                'lable' => 'Category Id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'address',
                'lable' => 'Address',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'landmark',
                'lable' => 'Landmark',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'pincode',
                'lable' => 'Pincode',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'cover',
                'lable' => 'Cover Image',
                'rules' => 'trim|required'
            ),
        );
        
        $this->rules['profile'] = array(
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required'
            ),
        );
        $this->rules['social'] = array(
            array(
                'field' => 'facebook',
                'lable' => 'Facebook Link',
                'rules' => 'trim|required'
            ),
        );
        
        $this->rules['filters'] = array(
            array(
                'field' => 'sub_categories[]',
                'lable' => 'Sub categories',
                'rules' => ''
            ),
        );
        
        
    }
    
    public function get_vendors($limit = NULL, $offset = NULL, $status = NULL, $state = NULL, $district = NULL, $constituency = NULL, $search = NULL, $exe = NULL, $mobile = NULL){
        $this->_query_vendors($status, $state, $district, $constituency, $search, $exe, $mobile);
        $this->db->order_by('`vendors_list`.id', 'DESC');
        $this->db->order_by('`vendors_list`.created_at', 'DESC');
        $this->db->order_by('`vendors_list`.updated_at', 'DESC');
        $this->db->group_by('`vendors_list`.`unique_id`');
        $this->db->limit($limit, $offset);
        $rs     = $this->db->get($this->table);
        //print_array($this->db->last_query());
        return   $rs->result_array();
    }
    
    public function vendor_count($status = NULL, $state = NULL, $district = NULL, $constituency = NULL, $search = NULL, $exe = NULL, $mobile = NULL){
        $this->_query_vendors($status, $state, $district, $constituency, $search, $exe, $mobile);
        return $this->db->count_all_results($this->table);
    }
    
    private function _query_vendors($status = NULL, $state = NULL, $district = NULL, $constituency = NULL, $search = NULL, $exe = NULL, $mobile = NULL){
        
        $this->load->model(array('location_model', 'category_model', 'sub_category_model', 'contact_model'));
        
        $location_table       = '`' . $this->location_model->table . '`';
        $location_primary_key = '`' . $this->location_model->primary_key . '`';
        $location_foreign_key = '`' . 'location_id' . '`';
        
        $category_table       = '`' . $this->category_model->table . '`';
        $category_primary_key = '`' . $this->category_model->primary_key . '`';
        $category_foreign_key = '`' . 'category_id' . '`';


        $contact_table       = '`' . $this->contact_model->table . '`';
        $contact_primary_key = '`' . $this->contact_model->primary_key . '`';
        $contact_foreign_key = '`' . 'list_id' . '`';
        
        
        $primary_key = '`' . $this->primary_key . '`';
        $table       = '`' . $this->table . '`';
        
        $str_select_vendor = '';
        foreach (array('created_at', 'updated_at', 'deleted_at', 'created_user_id', 'updated_user_id', 'id', 'name', 'email', 'unique_id', 'constituency_id', 'category_id', 'executive_id', 'address', 'landmark','vendor_user_id', 'status') as $v)
        {
            $str_select_vendor .= "$table.`$v`,";
        }
        
        $this->db->select($str_select_vendor."$location_table.`latitude`, $location_table.`longitude`, $location_table.`address` as location_address, $contact_table.`number`");
        $this->db->join($category_table, "$category_table.$primary_key=$table.$category_foreign_key", 'left');
        $this->db->join($location_table,"$location_table.$primary_key=$table.$location_foreign_key");
        $this->db->join($contact_table,"$contact_table.$contact_foreign_key=$table.$primary_key");
        $this->db->where($contact_table.'.type',1);
        if ( ! empty($search))
        {
            $this->db->or_like($table . '.`name`', $search);
            $this->db->or_like($table . '.`address`', $search);
        }
        if ( ! empty($exe))
        {
            $this->db->join('`users`', "vendors_list.executive_id=users.id", 'left');
            $this->db->or_where('users.unique_id', $exe);
        }
        if ( ! empty($mobile))
        {
            $this->db->join('`contacts`', "vendors_list.id=contacts.list_id", 'left');
            $this->db->or_where('contacts.number', $mobile);
        } 
        $this->db->where("$table.deleted_at", NULL);
        return $this;
    }
    
    
    public function all($cat_id = NULL, $sub_cat_id = NULL, $search = NULL, $lat = FALSE, $long = NULL, $brand_id = NULL, $limit = NULL, $offset=0)
    {
        
        $this->_query_all($cat_id, $sub_cat_id, $search, $lat, $long, $brand_id);
       /* $r=rand(1,10);
        if($r==1){
        $this->db->order_by('id', 'DESC');    
        }elseif($r==2){
        $this->db->order_by('id', 'ASC');    
        }elseif($r==3){
        $this->db->order_by('name', 'DESC');    
        }elseif($r==4){
        $this->db->order_by('name', 'ASC');    
        }elseif($r==5){
        $this->db->order_by('created_at', 'DESC');
        }elseif($r==6){
        $this->db->order_by('updated_at', 'DESC');
        }elseif($r==7){
        $this->db->order_by('created_at', 'ASC');
        }elseif($r==8){
        $this->db->order_by('updated_at', 'ASC');
        }elseif($r==9){
        $this->db->order_by('vendor_user_id', 'DESC');
        }elseif($r==10){
        $this->db->order_by('vendor_user_id', 'ASC');
        }   */
        $this->db->order_by('id', 'RANDOM');
    /*or
    $this->db->order_by('rand()');*/
    $this->db->limit($limit, $offset);
        $rs     = $this->db->get($this->table);
        $result = $rs->custom_result_object('Vendor_list_row');
        
        $this->db->reset_query();
        
        $this->_query_all($cat_id, $sub_cat_id, $search, $lat, $long, $brand_id);
        $count = $this->db->count_all_results($this->table);
        return  array(
            'result' => $result,
            'count'  => $count
        );
    }
    
    private function _query_all($cat_id = NULL,$sub_cat_id = NULL, $search = NULL, $lat = NULL, $long = NULL, $brand_id = NULL)
    {
        
        $this->load->model(array('location_model', 'category_model', 'sub_category_model'));
        
        $location_table       = '`' . $this->location_model->table . '`';
        $location_primary_key = '`' . $this->location_model->primary_key . '`';
        $location_foreign_key = '`' . 'location_id' . '`';
        
        $category_table       = '`' . $this->category_model->table . '`';
        $category_primary_key = '`' . $this->category_model->primary_key . '`';
        $category_foreign_key = '`' . 'category_id' . '`';
        
        
        $primary_key = '`' . $this->primary_key . '`';
        $table       = '`' . $this->table . '`';
        
        $str_select_vendor = '';
        foreach (array('created_at', 'updated_at', 'created_user_id', 'updated_user_id', 'id', 'name', 'email', 'unique_id', 'address', 'landmark','vendor_user_id','restaurant_status','label','rating','desc') as $v)
        {
            $str_select_vendor .= "$table.`$v`,";
        }
        
        $this->db->select($str_select_vendor."$location_table.`latitude`, $location_table.`longitude`, $location_table.`address` as location_address");
        $this->db->join($category_table, "$category_table.$primary_key=$table.$category_foreign_key", 'left');
        $this->db->join($location_table,"$location_table.$primary_key=$table.$location_foreign_key", 'left');
        $this->db->join('`vendors_sub_categories`', "$table.$primary_key=vendors_sub_categories.list_id", 'left');
        $this->db->join('`vendor_brands`', "$table.$primary_key=vendor_brands.list_id", 'left');
        
        if ($cat_id)
        {
            $this->db->where("$category_table.$category_primary_key=", $cat_id);
        }
        if ($sub_cat_id)
        {
            $this->db->where("`vendors_sub_categories`.`sub_category_id`=", $sub_cat_id);
        }
        
        if ($brand_id)
        {
            $this->db->where("`vendor_brands`.`brand_id`=", $brand_id);
        }
        
        if(! is_null($lat) && ! is_null($long)){
            $locations = $this->db->query("SELECT id, ( 3959 * acos( cos( radians($lat) ) * cos( radians( locations.latitude ) ) * cos( radians( locations.longitude ) - radians($long) ) + sin( radians($lat) ) * sin(radians(locations.latitude)) ) ) AS distance FROM locations HAVING distance < 3.16 ORDER BY distance")->result_array();
            $this->db->where_in("$table.`location_id`", (empty(array_column($locations, 'id')))? 0: array_column($locations, 'id'));
        }
        
        if ( ! is_null($search))
        {
            $this->db->or_like($table . '.`sounds_like`', metaphone($search));
        }
        $this->db->group_by('id');
        $this->db->where("$table.`status`=", '1');
        $this->db->where("$table.`deleted_at` =", NULL);
        return $this;
    }
    
}
class Vendor_list_row
{
    public $id;
    public $name;
    public $email;
    public $unique_id;
    public $address;
    public $landmark;
    public $created_at;
    public $updated_at;
    public $created_user_id;
    public $updated_user_id;
}


