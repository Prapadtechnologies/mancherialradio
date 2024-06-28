 <?php

class Food_item_model extends MY_Model
{
    public $rules;
    public function __construct()
    {
        parent::__construct();
        $this->table="food_item";
        $this->primary_key="id";
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
    public function _config(){
        $this->timestamps = TRUE;
        $this->soft_deletes = TRUE;
        $this->delete_cache_on_save = TRUE;
        
    }
    public function _relations()
    {       
    $this->has_one['menu'] = array('Food_menu_model','id','menu_id');
   /*   $this->has_many['menu'] = array(
            'foreign_model' => 'Food_menu_model',
            'foreign_table' => 'food_menu',
            'local_key' => 'id',
            'foreign_key' => 'vendor_id',
            'get_relate' => FALSE
        );*/
    /*$this->has_many_pivot['brands'] = array(
        'foreign_model' => 'Ecom_brand_model',
        'pivot_table' => 'subcategories_brands',
        'local_key' => 'id',
        'pivot_local_key' => 'sub_cat_id',
        'pivot_foreign_key' => 'brand_id',
        'foreign_key' => 'id',
        'get_relate' => FALSE
    );*/
    }
    public function _form(){
        $this->rules = array(
            array(
                'field'=>'menu_id',
                'label'=>'Menu',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please select Item'
                )
            ),
            array(
                'field'=>'name',
                'label'=>'Name',
                'rules'=>'trim|required|min_length[3]',
                'errors'=>array(
                    'min_length'=>'Please give minimum 3 characters'
                )
            ),
            array(
                'field'=>'price',
                'label'=>'Price',
                'rules'=>'trim|required',
                'errors'=>array(
                    'required'=>'Please Give Price'
                )
            ),
            array(
                'field'=>'desc',
                'label'=>'Description',
                'rules'=>'trim|required',
                'erors'=>array(
                    'max_length'=>'Please Give Description'
                )
                
            ),
            array(
                'field'=>'quantity',
                'label'=>'Quantity',
                'rules'=>'trim|required',
                'erors'=>array(
                    'max_length'=>'Please Give Quantity'
                )
                
            ),
            array(
                'field'=>'status',
                'label'=>'Status',
                'rules'=>'trim|required'
            ),
            array(
                'field'=>'item_type',
                'label'=>'Item Type',
                'rules'=>'trim|required'
            ),
        );
    }
}
?>