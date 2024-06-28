<?php

class Vendor_bank_details_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendor_bank_details';
        $this->primary_key = 'id';
        $this->before_create[] = '_add_created_by';
        $this->before_update[] = '_add_updated_by';
        
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
        
    }
    
    public function _form(){
        $this->rules = array(
            array(
                'field' => 'bank_name',
                'lable' => 'Bank Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'bank_branch',
                'lable' => 'Bank Branch',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'ifsc',
                'lable' => 'IFSC',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'ac_holder_name',
                'lable' => 'Account Holder Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'ac_number',
                'lable' => 'Account Number',
                'rules' => 'trim|required'
            ),
        );
    }
}

