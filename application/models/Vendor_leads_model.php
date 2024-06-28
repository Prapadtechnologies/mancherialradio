<?php

class Vendor_leads_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_leads';
        $this->primary_key = 'id';
        //$this->before_create[] = '_add_created_by';
        //$this->before_update[] = '_add_updated_by';
        
       $this->_config();
       $this->_form();
       $this->_relations();
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
        $this->has_one['user'] = array('user_model', 'id', 'user_id');
        $this->has_one['vendor'] = array('Vendor_list_model', 'vendor_user_id', 'vendor_id');
        /*$this->has_one['location'] = array('Location_model', 'id', 'location_id');
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
        $this->has_many_pivot['holidays'] = array(
            'foreign_model' => 'Day_model',
            'foreign_table' => 'days',
            'pivot_table' => 'vendors_holidays',
            'local_key' => 'id',
            'pivot_local_key' => 'list_id',
            'foreign_key' => 'id',
            'pivot_foreign_key' => 'day_id',
        );
        $this->has_many['timings'] = array(
          'foreign_model' => 'vendor_timings_model',
          'foreign_table' => 'vendor_timings',
          'local_key' => 'id',
          'foreign_key' => 'list_id'
        );*/
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
                'field' => 'email',
                'lable' => 'Email',
                'rules' => 'trim|required|valid_email|callback_check_email',
                'errors' => array(
                    'callback_check_email' =>'email already exists'
                )
            ),
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
    }
}