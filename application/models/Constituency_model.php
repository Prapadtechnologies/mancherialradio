<?php

class Constituency_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'constituencies';
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
    private  function _config() {
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
    }
    
    private function _relations(){
        
    }
    
    private function _form(){
        $this->rules = array(
            array(
                'field' => 'state_id',
                'lable' => 'State ',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'dist_id',
                'lable' => 'District Id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'name',
                'lable' => 'Name',
                'rules' => 'trim|required|min_length[5]',
                'errors'=>array(
                    'min_length'=>'Please give at least 5 characters'
                )
            ),
            array(
                'field' => 'pincode',
                'lable' => 'Pincode',
                'rules' => 'trim|required|min_length[6]',
                'errors'=>array(
                    'min_length'=>'Please give 6 digits'
                )
            ),
        );
    }
}

