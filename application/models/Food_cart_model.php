<?php

class Food_cart_model extends MY_Model
{
    public $rules;
    public $foreign_key;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'food_cart';
        $this->primary_key = 'id';
        
       $this->_config();
       $this->_form();
       $this->_relations();
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
                'field' => 'item_id',
                'lable' => 'Item',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must Select a %s.'
                )
            ),
            array(
                'field' => 'user_id',
                'lable' => 'User ID',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'quantity',
                'lable' => 'Quantity',
                'rules' => 'trim|required|min_length[1]',
                'errors' => array(
                    'required' => 'You must provide a %s.'
                )
            )
        );
    }
}

