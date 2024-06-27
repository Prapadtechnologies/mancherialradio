<?php
class Food extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        if (! $this->ion_auth->logged_in()) // || ! $this->ion_auth->is_admin()
            redirect('auth/login');
            $this->load->model('category_model');
            $this->load->model('sub_category_model');
            $this->load->model('food_menu_model');
            $this->load->model('food_item_model');
            $this->load->model('food_section_model');
            $this->load->model('food_sec_item_model');
            $this->load->model('food_orders_model');
            $this->load->model('food_order_items_model');
            $this->load->model('food_sub_order_items_model');
            $this->load->model('food_order_deal_model');
            $this->load->model('food_settings_model');
            $this->load->model('user_model');
            $this->load->model('vendor_list_model');
            $this->load->model('vendor_leads_model');
            $this->load->model('order_support_model');
    }
    



    /**
     * Food Sub Item crud
     *
     * @author Mahesh
     * @desc To Manage Food Sub Items
     * @param string $type
     * @param string $target
     */
    public function products_approve($type = 'r')
    {
        
        /* if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */
            if ($type == 'r') {
                $this->data['title'] = 'Products Approve';
                $this->data['content'] = 'food/food/products_approve';
                $me=$this->food_item_model->with_menu('fields:id,name,vendor_id')->where('approval_status',2)->order_by('id', 'DESC')->get_all();
$this->data['food_sub_items'] =$me;
                $this->_render_page($this->template, $this->data);
            } elseif ($type == 'approve') {
                $id=base64_decode(base64_decode($this->input->get('id')));
                $this->food_item_model->update(array('approval_status'=>1), $id);
                 redirect('products_approve/r', 'refresh');
            } elseif ($type == 'u') {
                $this->form_validation->set_rules($this->food_item_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    echo validation_errors();die;
                } else {
$cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                    $vendor_category_id=$cat_id['category_id'];
                    if ($this->ion_auth->is_admin()){
                        $approval_status=1;
                    }else{
                        $approval_status=2;
                    }
                     /*if($vendor_category_id == 5)
                    {*/
                        $input_data=array(
                        'menu_id' => $this->input->post('menu_id'),
                        'price' => $this->input->post('price'),
                        'quantity' => $this->input->post('quantity'),
                        'status' => $this->input->post('status'),
                        'item_type' => $this->input->post('item_type'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc'),
                        'discount' => $this->input->post('discount'),
                        'label' => $this->input->post('label'),
                        'approval_status' => $approval_status
                    );
                    /*}*/
                    if($vendor_category_id == 6)
                    {
                        $input_data['exp']=$this->input->post('exp');
                        $input_data['qualification']=$this->input->post('qualification');
                    }

                    $this->food_item_model->update($input_data, $this->input->post('id'));
                    if ($_FILES['file']['name'] !== '') {
                        $path = $_FILES['file']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $this->file_up("file", "food_item", $this->input->post('id'), '', 'no');
                    }
                    redirect('food_item/r', 'refresh');
                }
            } elseif ($type == 'd') {
                $this->ecom_sub_category_model->delete(['id' => $this->input->post('id')]);
            }
    }


    /**
     * @author Mahesh
     * @desc To Manage Food Items
     * @param string $type
     */
    public function food_menu($type = 'r'){
        /* if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */
            
            if ($type == 'c') {
                $this->form_validation->set_rules($this->food_menu_model->rules);
                if (empty($_FILES['file']['name'])) {
                    $this->form_validation->set_rules('file', 'Food Menu Image', 'required');
                }
                if ($this->form_validation->run() == FALSE) {
                    $this->food_item('r');
                } else {
                    $cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                    $vendor_category_id=$cat_id['category_id'];
                    /*if($vendor_category_id == 1 || $vendor_category_id == 2 || $vendor_category_id == 7){
                        $input_data['vendor_id'] = $this->input->post('vendor_id');
                    }*/
                    $input_data=array(
                        'vendor_id' => $this->input->post('vendor_id'),
                        'sub_cat_id' => $this->input->post('sub_cat_id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    );
                    $id = $this->food_menu_model->insert($input_data);
                    
                    $path = $_FILES['file']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $this->file_up("file", "food_menu", $id, '', 'no');
                    redirect('food_menu/r', 'refresh');
                }
            } elseif ($type == 'r') {
                $this->data['title'] = 'Menu';
                $this->data['content'] = 'food/food/food_menu';
                
                if ($this->ion_auth->is_admin()){
                $cat_data= $this->category_model->fields('id,name,desc')->get_all();
                $r=array();
                foreach ($cat_data as $c) {
                    $c['sub_categories']=$this->sub_category_model->fields('id,name,desc,cat_id')->where('cat_id',$c['id'])->get_all();
                    
                    $r[]=$c;
                }
                $this->data['sub_categories']=$r;
                }else{
                    $cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                    $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')->where('cat_id',$cat_id['category_id'])->get_all();
                }
             /* $this->data['food_items'] = $this->food_menu_model->with_subcat('fields:id,name,sub_cat_id')->fields('id,name,desc,vendor_id,sub_cat_id')->order_by('id', 'ASCE')->where('vendor_id',$this->ion_auth->get_user_id())->get_all();*/
if ($this->ion_auth->is_admin()){
    $me= $this->food_menu_model->with_subcat('fields:id,name')->where('vendor_id',$this->ion_auth->get_user_id())->order_by('id', 'ASCE')->get_all();
}else{
              $me=array();
                foreach ($this->data['sub_categories'] as $sub_categories) {
                
                     $a=$this->data['food_sub_items'] = $this->food_menu_model->with_subcat('fields:id,name')->where('vendor_id',$this->ion_auth->get_user_id())->where('sub_cat_id',$sub_categories['id'])->order_by('id', 'ASCE')->get_all();
                  if(!empty($a)){ 
                  foreach ($a as $s) {
                      $me[]=$s;
                  }
                }
                }
            }
$this->data['food_items'] =$me;

                $this->_render_page($this->template, $this->data);
            }  elseif ($type == 'u') {
                $this->form_validation->set_rules($this->food_menu_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    echo validation_errors();
                } else {
                    $this->food_menu_model->update([
                        'id' => $this->input->post('id'),
                        'sub_cat_id' => $this->input->post('sub_cat_id'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc')
                    ], 'id');
                    
                    if ($_FILES['file']['name'] !== '') {
                        $path = $_FILES['file']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $this->file_up("file", "food_menu", $this->input->post('id'), '', 'no');
                    }
                    redirect('food_menu/r', 'refresh');
                }
            }elseif ($type == 'd') {
                echo $this->food_menu_model->delete(['id' => $this->input->post('id')]);
            }elseif($type == 'edit'){
                $this->data['title'] = 'Edit Menu';
                $this->data['content'] = 'food/food/edit';
                $this->data['type'] = 'food_menu';
                if ($this->ion_auth->is_admin()){
                $cat_data= $this->category_model->fields('id,name,desc')->get_all();
                $r=array();
                foreach ($cat_data as $c) {
                    $c['sub_categories']=$this->sub_category_model->fields('id,name,desc,cat_id')->where('cat_id',$c['id'])->get_all();
                    
                    $r[]=$c;
                }
                $this->data['sub_categories']=$r;
                }else{
                $cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                $this->data['sub_categories'] = $this->sub_category_model->fields('id,name,desc,cat_id')->where('cat_id',$cat_id['category_id'])->get_all();
                }
                $this->data['item'] = $this->food_menu_model->fields('id,name,desc,vendor_id,sub_cat_id')->where('id',base64_decode(base64_decode($this->input->get('id'))))->get();
                $this->data['i'] = $this->food_menu_model->where('file',$this->input->get('file'))->get();
                $this->data['food_item'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->order_by('id', 'DESC')->where('id', base64_decode(base64_decode($this->input->get('id'))))->get();
                $this->_render_page($this->template, $this->data);
            }
    }
    /**
     * Food Sub Item crud
     *
     * @author Mahesh
     * @desc To Manage Food Sub Items
     * @param string $type
     * @param string $target
     */
    public function food_item($type = 'r')
    {
        
        /* if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */
            
            if ($type == 'c') {
                
                $this->form_validation->set_rules($this->food_item_model->rules);
                
                if (empty($_FILES['file']['name'])) {
                    $this->form_validation->set_rules('file', 'Food Item Image', 'required');
                }
                if ($this->form_validation->run() == FALSE) {
                    /*echo validation_errors();die;*/
                    $this->food_item('r');
                } else {
                    $cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                    $vendor_category_id=$cat_id['category_id'];
                    if ($this->ion_auth->is_admin()){
                        $approval_status=1;
                    }else{
                        $approval_status=2;
                    }
                    /*if($vendor_category_id == 5)
                    {*/
                        $input_data=array(
                        'menu_id' => $this->input->post('menu_id'),
                        'price' => $this->input->post('price'),
                        'quantity' => $this->input->post('quantity'),
                        'status' => $this->input->post('status'),
                        'item_type' => $this->input->post('item_type'),
                        'name' => $this->input->post('name'),
                        'short_desc' => $this->input->post('short_desc'),
                        'desc' => $this->input->post('desc'),
                        'discount' => $this->input->post('discount'),
                        'label' => $this->input->post('label'),
                        'approval_status' => $approval_status
                    );
                    /*}*/
                    /*if($vendor_category_id == 6)
                    {
                        $input_data['exp']=$this->input->post('exp');
                        $input_data['qualification']=$this->input->post('qualification');
                    }*/
                    $id = $this->food_item_model->insert($input_data);
                    $path = $_FILES['file']['name']; 
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $this->file_up("file", "food_item", $id, '', 'no');
                    redirect('food_item/r', 'refresh');
                }
            } elseif ($type == 'r') {
                $this->data['title'] = 'Items';
                $this->data['content'] = 'food/food/food_item';
                 if ($this->ion_auth->is_admin()){
                    $w_r='(vendor_id = '.$this->ion_auth->get_user_id().')';    
                }else{
                    $w_r='(vendor_id = '. $this->ion_auth->get_user_id() .' OR vendor_id = 1)';
                }
                if(!$this->ion_auth->is_admin()){
                $cat_id=$this->vendor_list_model->with_sub_categories('fields: id, name, status')->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                $sub_categories=$cat_id['sub_categories'];
                
                $su='';
                    foreach($sub_categories as $s){
                       if($su == ''){
                        $su = 'sub_cat_id = '.$s['id'];
                       }else{
                        $su = $su.' OR sub_cat_id = '.$s['id'];
                       }
                    }
                    $w_r=$w_r .' and ('. $su.')';
                }

                $this->db->where($w_r);
                $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->get_all();
                
                $menus=array_column($this->data['food_items'],'id');
                if ($this->ion_auth->is_admin()){
                    $w_r1='(created_user_id = '.$this->ion_auth->get_user_id().')';    
                }else{
                    $w_r1='(created_user_id = '. $this->ion_auth->get_user_id() .' OR vendor_id = 1)';
                }
                $me=array();
                foreach ($this->data['food_items'] as $menu) {
                     $a=$this->data['food_sub_items'] = $this->food_item_model->with_menu('fields:id,name,vendor_id')->where($w_r1)->where('menu_id',$menu['id'])->order_by('id', 'ASCE')->get_all();
                  if(!empty($a)){ 
                  foreach ($a as $s) {
                    $cou=$this->db->get_where('deleted_items',array('vendor_id'=>$this->ion_auth->get_user_id(),'item_id'=>$s['id']))->num_rows();
                    if($cou == 0){
                      $me[]=$s;  
                    }
                  }
                }
                }

$this->data['food_sub_items'] =$me;
               // $this->data['food_sub_items'] = $this->food_item_model->with_menu('fields:id,name,vendor_id','where: vendor_id='.$this->ion_auth->get_user_id())->get_all();
                $this->_render_page($this->template, $this->data);
            } elseif ($type == 'u') {
                $this->form_validation->set_rules($this->food_item_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    echo validation_errors();die;
                } else {
$cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                    $vendor_category_id=$cat_id['category_id'];
                    if ($this->ion_auth->is_admin()){
                        $approval_status=1;
                    }else{
                        $approval_status=2;
                    }
                     /*if($vendor_category_id == 5)
                    {*/
                        $input_data=array(
                        'menu_id' => $this->input->post('menu_id'),
                        'price' => $this->input->post('price'),
                        'quantity' => $this->input->post('quantity'),
                        'status' => $this->input->post('status'),
                        'item_type' => $this->input->post('item_type'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc'),
                        'short_desc' => $this->input->post('short_desc'),
                        'discount' => $this->input->post('discount'),
                        'label' => $this->input->post('label'),
                        'approval_status' => $approval_status
                    );
                    /*}*/
                    if($vendor_category_id == 6)
                    {
                        $input_data['exp']=$this->input->post('exp');
                        $input_data['qualification']=$this->input->post('qualification');
                    }
                    $item_id=$this->input->post('id');   
                    $s=0;
                    if($this->ion_auth->is_admin()){
                        $s=1;
                    }else{
                        $cou=$this->db->get_where('deleted_items',array('vendor_id'=>$this->ion_auth->get_user_id(),'item_id'=>$item_id))->num_rows();
                        if($cou>0){
                           $s=1;
                        }else{
                            $s=2;
                        }
                    }

                    if($s == 1){
                        $this->food_item_model->update($input_data, $item_id);
                    }elseif($s == 2){
                        $this->db->insert('deleted_items',array('vendor_id'=>$this->ion_auth->get_user_id(),'item_id'=>$item_id,'deleted_at'=>date('Y-m-d h:i:s')));
                        $old=$item_id;
                        $item_id = $this->food_item_model->insert($input_data);
                        copy('uploads/food_item_image/food_item_'.$old.'.jpg', 'uploads/food_item_image/food_item_'.$item_id.'.jpg');
                    }
                    
                    if ($_FILES['file']['name'] !== '') {
                        $path = $_FILES['file']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $this->file_up("file", "food_item", $item_id, '', 'no');
                    }
                    redirect('food_item/r', 'refresh');
                }
            } elseif ($type == 'd') {
                $this->food_item_model->delete(['id' => $this->input->post('id')]);
            } elseif ($type == 'ven_item') {
                $this->db->insert('deleted_items',array('vendor_id'=>$this->ion_auth->get_user_id(),'item_id'=>$this->input->post('id'),'deleted_at'=>date('Y-m-d h:i:s')));
            }elseif($type == 'edit'){
                $this->data['title'] = 'Edit Item';
                $this->data['content'] = 'food/food/edit';
                $this->data['type'] = 'food_item';
                $this->data['sub_items']=$this->food_item_model->order_by('id', 'DESC')->where('id', base64_decode(base64_decode($this->input->get('id'))))->get();

                if ($this->ion_auth->is_admin()){
                    $w_r='(vendor_id = '.$this->ion_auth->get_user_id().')';    
                }else{
                    $w_r='(vendor_id = '. $this->ion_auth->get_user_id() .' OR vendor_id = 1)';
                }
                if(!$this->ion_auth->is_admin()){
                $cat_id=$this->vendor_list_model->with_sub_categories('fields: id, name, status')->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
                $sub_categories=$cat_id['sub_categories'];
                
                $su='';
                    foreach($sub_categories as $s){
                       if($su == ''){
                        $su = 'sub_cat_id = '.$s['id'];
                       }else{
                        $su = $su.' OR sub_cat_id = '.$s['id'];
                       }
                    }
                    $w_r=$w_r .' and ('. $su.')';
                }

                $this->db->where($w_r);
                $this->data['items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->order_by('id', 'DESC')->get_all();
                $this->data['food_sub_items'] = $this->food_item_model->where('id', base64_decode(base64_decode($this->input->get('id'))))
                ->get();
                $this->_render_page($this->template, $this->data);
            }
    }
   /**
     * @author Mahesh
     * @desc To Manage Food Sections
     * @param string $type
     */
    public function food_section($type = 'r'){
        /* if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */
            
            if ($type == 'c') {
                $this->form_validation->set_rules($this->food_section_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->food_section('r');
                } else {
                    /*$required=0;
                    if(!empty($this->input->post('require_items'))){
                        $required=$this->input->post('require_items');
                    }*/
                    if($this->input->post('item_field') == 2){
                        $sec_price=1;
                    }elseif($this->input->post('item_field') == 1){
                        $sec_price=$this->input->post('sec_price');
                    }
                    $id = $this->food_section_model->insert([
                        'menu_id' => $this->input->post('menu_id'),
                        'item_id' => $this->input->post('item_id'),
                        'item_field' => $this->input->post('item_field'),
                        'sec_price' => $sec_price,
                        'required' => $this->input->post('require_items'),
                        'name' => $this->input->post('name')
                    ]);
                    
                    redirect('food_section/r', 'refresh');
                }
            } elseif ($type == 'r') {
                $this->data['title'] = 'Section';
                $this->data['content'] = 'food/food/section';
                $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->where('vendor_id',$this->ion_auth->get_user_id())->order_by('id', 'ASCE')->with_groups('fields:name,id')->get_all();
                $me=array();
                foreach ($this->data['food_items'] as $menu) {
                  $a=$this->food_section_model->with_menu('fields:id,name,vendor_id')->with_item('fields:name')->where('menu_id',$menu['id'])->order_by('id', 'ASCE')->get_all(); 
                  if(!empty($a)){ 
                  foreach ($a as $s) {
                      $me[]=$s;
                  }
                }
                }
                $this->data['food_section'] = $me;
                /*$sec=array();
                foreach ($this->data['food_items'] as $key => $value) {
                    $sec[]=$this->food_section_model->with_menu('fields:id,name,vendor_id')->with_item('fields:name,menu_id')->order_by('id', 'ASCE')->where('menu_id',$value['id'])->get_all();
                }
                print_r($sec);die;*/
                /*$this->data['food_section'] = $this->food_section_model->with_menu('fields:id,name,vendor_id')->with_item('fields:name')->where('menu_id',1)->order_by('id', 'ASCE')->get_all();*/
                /*echo "<pre>";
                print_r($this->data['food_section']);die;*/
                $this->_render_page($this->template, $this->data);
            }  elseif ($type == 'u') {
                $this->form_validation->set_rules($this->food_section_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    echo validation_errors();
                } else {
                    if($this->input->post('item_field') == 2){
                        $sec_price=1;
                    }elseif($this->input->post('item_field') == 1){
                        $sec_price=$this->input->post('sec_price');
                    }
                    $this->food_section_model->update([
                        'id' => $this->input->post('id'),
                        'menu_id' => $this->input->post('menu_id'),
                        'item_id' => $this->input->post('item_id'),
                        'item_field' => $this->input->post('item_field'),
                        'sec_price' => $sec_price,
                        'required' => $this->input->post('require_items'),
                        'name' => $this->input->post('name')
                    ], 'id');
                    
                    redirect('food_section/r', 'refresh');
                }
            }elseif ($type == 'd') {
                echo $this->food_section_model->delete(['id' => $this->input->post('id')]);
            }elseif($type == 'edit'){
                $this->data['title'] = 'Edit Section';
                $this->data['content'] = 'food/food/edit';
                $this->data['type'] = 'food_section';
                $this->data['section'] = $this->food_section_model->where('id',base64_decode(base64_decode($this->input->get('id'))))->get();
                /*$this->data['i'] = $this->food_menu_model->where('file',$this->input->get('file'))->get();*/
                $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->where('vendor_id',$this->ion_auth->get_user_id())->order_by('id', 'DESC')->get_all();
                $this->data['food_sub_items'] = $this->food_item_model->order_by('id', 'DESC')->where('menu_id',$this->data['section']['menu_id'])->get_all();
                $this->_render_page($this->template, $this->data);
            }
    }
    /**
     * Food Sub Item crud
     *
     * @author Mahesh
     * @desc To Manage Food Sub Items
     * @param string $type
     * @param string $target
     */
    public function food_section_item($type = 'r')
    {
        
       /*  if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */
            
            if ($type == 'c') {
                
                $this->form_validation->set_rules($this->food_sec_item_model->rules);
                
                if ($this->form_validation->run() == FALSE) {
                    $this->food_section_item('r');
                } else {
                    $id = $this->food_sec_item_model->insert([
                        'menu_id' => $this->input->post('menu_id'),
                        'item_id' => $this->input->post('item_id'),
                        'sec_id' => $this->input->post('sec_id'),
                        'price' => $this->input->post('price'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc'),
                        'status' => $this->input->post('status')
                    ]);

                    redirect('food_section_item/r', 'refresh');
                }
            } elseif ($type == 'r') {
                $this->data['title'] = 'Section Item';
                $this->data['content'] = 'food/food/sec_item';
                $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->where('vendor_id',$this->ion_auth->get_user_id())->order_by('id', 'ASCE')->get_all();
                $me=array();
                foreach ($this->data['food_items'] as $menu) {
                  $a=$this->food_sec_item_model->with_menu('fields:name')->with_item('fields:name')->with_sec('fields:name')->where('menu_id',$menu['id'])->order_by('id', 'ASCE')->get_all();
                  if(!empty($a)){ 
                  foreach ($a as $s) {
                      $me[]=$s;
                  }
                }
                }
                $this->data['food_sec_items'] = $me;
                /*$this->data['food_sec_items'] = $this->food_sec_item_model->with_menu('fields:name')->with_item('fields:name')->with_sec('fields:name')->order_by('id', 'ASCE')->get_all();*/
                $this->_render_page($this->template, $this->data);
            } elseif ($type == 'u') {
                $this->form_validation->set_rules($this->food_sec_item_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    echo validation_errors();
                } else {
                    $this->food_sec_item_model->update([
                        'menu_id' => $this->input->post('menu_id'),
                        'item_id' => $this->input->post('item_id'),
                        'sec_id' => $this->input->post('sec_id'),
                        'price' => $this->input->post('price'),
                        'name' => $this->input->post('name'),
                        'desc' => $this->input->post('desc'),
                        'status' => $this->input->post('status'),
                    ], $this->input->post('id'));
                    redirect('food_section_item/r', 'refresh');
                }
            } elseif ($type == 'd') {
                $this->ecom_sub_category_model->delete(['id' => $this->input->post('id')]);
            }elseif($type == 'edit'){
                $this->data['title'] = 'Edit Section Item';
                $this->data['content'] = 'food/food/edit';
                $this->data['type'] = 'food_sec_item';
                $this->data['sec_item'] = $this->food_sec_item_model->where('id',base64_decode(base64_decode($this->input->get('id'))))->get();
                $this->data['section'] = $this->food_section_model->order_by('id', 'DESC')->where('id',$this->data['sec_item']['sec_id'])->get();
                $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc,vendor_id')->where('vendor_id',$this->ion_auth->get_user_id())->order_by('id', 'DESC')->get_all();
                $this->data['sub_items'] = $this->food_item_model->order_by('id', 'DESC')->where('menu_id',$this->data['section']['menu_id'])->get_all();
                $this->data['sections'] = $this->food_section_model->order_by('id', 'DESC')->where('item_id',$this->data['section']['item_id'])->get_all();

                $this->_render_page($this->template, $this->data);
            }
    }

    /**
     * @author Mahesh
     * @desc To Manage Food Orders History
     * @param string $type
     */
    public function food_orders($type = 'r',$order_type = 'upcoming'){
        /* if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */

            if ($type == 'r') {
                $this->data['title'] = 'Orders';
                $this->data['content'] = 'food/food/orders';
                /*$this->data['users'] = $this->user_model->fields('id,first_name')->get_all();*/
                $l=$this->vendor_list_model->with_location('fields: id, address, latitude, longitude')->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
        $lat      = $l['location']['latitude'];
        $lng      = $l['location']['longitude'];
        $distance = 10; // Kilometers
//echo $lat.'/'.$lng;
        $query = $this->db->query(
            '
            SELECT 
                *,
                6371 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - ABS(delivery_boy_settings.latitude))), 2) + COS(RADIANS(?)) * COS(RADIANS(ABS(delivery_boy_settings.latitude))) * POWER(SIN(RADIANS(? - delivery_boy_settings.longitude)), 2))) AS distance
            FROM delivery_boy_settings where delivery_boy_status=1
            HAVING distance < ?
            ', 
            [
                $lat,
                $lat,
                $lng,
                $distance
            ] 
            );
        $deal=$query->result_array();
        $this->data['users'] =$deal;
                $c=0;
                $s=$this->food_orders_model->where('vendor_id',$this->ion_auth->get_user_id())->get_all();
                if($s!=''){
                $c=count($s);
                }
                $this->data['orders_count'] = $c;
                $this->data['order_type'] = $order_type;

        if($order_type=='past'){
            //$this->db->where('order_status',6);
          $where_order_status='order_status = 6';
        }elseif($order_type=='upcoming'){
            $where_order_status='order_status != 0 AND order_status != 6 AND order_status != 7';
          /*$this->db->where('order_status !=',0);
          $this->db->where('order_status !=',6);
          $this->db->where('order_status !=',7);*/
        }elseif($order_type=='cancelled'){
            $where_order_status='order_status = 7';
          //$this->db->where('order_status',7);
        }elseif($order_type=='rejected'){
            $where_order_status='order_status = 0';
          //$this->db->where('order_status',0);
        }
        $data=array();
/*if ($this->ion_auth->is_admin()){
    $this->db->where($where_order_status);
       $data = $this->food_orders_model->with_user('fields:first_name')->with_vendor('fields:name')->with_order_items('fields:item_id,order_id,price,quantity,sec_item_id')->with_sub_order_items('fields:sec_item_id,order_id,price,quantity')->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,total,deal_id,order_track,order_status,delivery,rejected_reason')->order_by('id', 'DESC')->get_all();
       
         }else{
            $this->db->where($where_order_status);
       $data = $this->food_orders_model->with_user('fields:first_name')->with_vendor('fields:name')->with_order_items('fields:item_id,order_id,price,quantity,sec_item_id')->with_sub_order_items('fields:sec_item_id,order_id,price,quantity')->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,total,deal_id,order_track,order_status,delivery,rejected_reason')->where('vendor_id',$this->ion_auth->get_user_id())->order_by('id', 'DESC')->get_all();
         }*/


         if (!$this->ion_auth->is_admin()){
            if($order_type == 'all'){
                $where_order_status = 'vendor_id = '.$this->ion_auth->get_user_id();
            }else{
            $where_order_status .= ' AND vendor_id = '.$this->ion_auth->get_user_id();    
            }
         }

         $start_date=date('Y-m-d');
         $end_date=date('Y-m-d', strtotime('+2 months'));
         if((isset($_GET['start_date']) && $_GET['start_date'] !='') && (isset($_GET['end_date']) && $_GET['end_date'] !='')){
            $start_date=$_GET['start_date'];
            $end_date=$_GET['end_date'];
         }
        if($start_date !='' && $end_date != ''){
            if($where_order_status !=''){
                $where_order_status .= ' AND created_at >= "'.$start_date.' 00:00:00" AND created_at <= "'.$end_date.' 23:59:59"';    
            }else{
                $where_order_status .= 'created_at >= "'.$start_date.' 00:00:00" AND created_at <= "'.$end_date.' 23:59:59"';    
            }
            
        }
        $this->data['start_date'] = $start_date;
        $this->data['end_date'] = $end_date;
         
        if($where_order_status !=''){
            $this->db->where($where_order_status);
        }

       $data = $this->food_orders_model->with_user('fields:first_name')->with_vendor('fields:name')->with_order_items('fields:item_id,order_id,price,quantity,sec_item_id')->with_sub_order_items('fields:sec_item_id,order_id,item_id,price,quantity')->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,sub_total,total,deal_id,order_track,order_status,delivery,rejected_reason,otp')->order_by('id', 'DESC')->get_all();

       /*echo "<pre>";
       print_r($data);die;*/
       if(! empty($data)){
                /*$status=['0'=>'Rejected','1'=>'Order Received','2'=>'Accepted','3'=>'Preparing','4'=>'Out for delivery','5'=>'Order on the way','6'=>'Order Completed','7'=>'Cancelled'];*/
                for ($i = 0; $i < count($data) ; $i++){
                    $cat_id=$this->vendor_list_model->where('vendor_user_id', $data[$i]['vendor_id'])->get();
                    $vendor_category_id=$cat_id['category_id'];
                    /*$status=[
                        '0'=>(($this->ion_auth->is_admin())? 'Order Rejected' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_rejected')),
                        '1'=>(($this->ion_auth->is_admin())? 'Order Received' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_received')),
                        '2'=>(($this->ion_auth->is_admin())? 'Order Accept' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_accepted')),
                        '3'=>(($this->ion_auth->is_admin())? 'Order Preparing' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_preparing')),
                        '4'=>(($this->ion_auth->is_admin())? 'Out for delivery' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_out_delivery')),
                        '5'=>(($this->ion_auth->is_admin())? 'Order on the way' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_on_way')),
                        '6'=>(($this->ion_auth->is_admin())? 'Order Completed' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_completed')),
                        '7'=>(($this->ion_auth->is_admin())? 'Order Cancelled' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_canceled'))];*/

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

                    $data[$i]['order_stat']=$status[$data[$i]['order_status']];
                    $deal=$this->food_order_deal_model->with_deal_boy('fields:first_name')->fields('id,deal_id,otp')->where('ord_deal_status',2)->where('order_id',$data[$i]['id'])->get();
                    /*$data[$i]['deal_id'] = $deal['deal_id'];*/
                    $data[$i]['ord_deal_id'] = $deal['id'];
                    /*$data[$i]['otp'] = $deal['otp'];*/
                    $data[$i]['deal_name'] = $deal['deal_boy']['first_name'];
                }
            }
            $this->data['orders']=$data;
                $this->_render_page($this->template, $this->data);
            }
    }

    /**
     * @author Mahesh
     * @desc To Manage Vendor Leads History
     * @param string $type
     */
    public function VendorLeads($type = 'r',$lead_type = 'Received'){
            if ($type == 'r') {
                $this->data['title'] = 'Vendor Leads';
                $this->data['content'] = 'food/food/vendor_leads';
                $c=0;
                $s=$this->vendor_leads_model->where('vendor_id',$this->ion_auth->get_user_id())->get_all();
                if($s != ''){
                $c=count($s);
                }
                $this->data['leads_count'] = $c;
                $this->data['lead_type'] = $lead_type;
        if($lead_type=='Received'){
          $this->db->where('lead_status',1);
        }elseif($lead_type=='Processing'){
          $this->db->where('lead_status',2);
        }elseif($lead_type=='Completed'){
          $this->db->where('lead_status',3);
        }elseif($lead_type=='Canceled'){
          $this->db->where('lead_status',4);
        }

        
       $data = $this->vendor_leads_model->with_user('fields:first_name,unique_id,phone,email')->with_vendor('fields:name')->fields('id,user_id,vendor_id,created_at,lead_status')->where('vendor_id',$this->ion_auth->get_user_id())->order_by('id', 'DESC')->get_all();

            $this->data['leads']=$data;
                $this->_render_page($this->template, $this->data);
            }
    }
 public function vendor_lead_status($id,$status)
    {
        $res=$this->vendor_leads_model->update([
                        'id' => $id,
                        'lead_status' => $status
                    ], 'id');
        redirect($this->session->userdata('last_page'));
    }
    /**
     * Food Settings
     *
     * @author Mahesh
     * @desc To Manage Food Settings
     * @param string $type
     * @param string $target
     */
    public function food_settings($type = 'r')
    {
        
        /* if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */

            if ($type == 'r') {
                $this->data['title'] = 'Section Item';
                $this->data['content'] = 'food/food/food_settings';
               /* $this->data['food_items'] = $this->food_menu_model->fields('id,name,desc')->order_by('id', 'ASCE')->get_all();
                $this->data['food_sec_items'] = $this->food_sec_item_model->with_menu('fields:name')->with_item('fields:name')->with_sec('fields:name')->order_by('id', 'ASCE')->get_all();*/
                $this->data['food_settings'] = $this->food_settings_model->fields('id,min_order_price,delivery_free_range,preparation_time,min_delivery_fee,ext_delivery_fee,restaurant_status')->where('vendor_id', $this->ion_auth->get_user_id())->get();
                
$this->load->model('vendor_bank_details_model');

                $this->data['bank_details'] = $this->vendor_bank_details_model->fields('id,bank_name,bank_branch,ifsc,ac_holder_name,ac_number')->where('vendor_id', $this->ion_auth->get_user_id())->get();

                $this->_render_page($this->template, $this->data);
            } elseif ($type == 'u') {
                $this->form_validation->set_rules($this->food_settings_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    echo validation_errors();
                } else {
                    $r=$this->food_settings_model->fields('id')->where('vendor_id',$this->ion_auth->get_user_id())->get();
                    if($r!=''){
                    $this->food_settings_model->update([
                        /*'min_order_price' => $this->input->post('min_order_price'),
                        'min_delivery_fee' => $this->input->post('min_delivery_fee'),
                        'ext_delivery_fee' => $this->input->post('ext_delivery_fee'),
                        'delivery_free_range' => $this->input->post('delivery_free_range'),*/
                        'preparation_time' => $this->input->post('preparation_time'),
                        'restaurant_status' => $this->input->post('restaurant_status'),
                        
                    ], ['vendor_id',$this->ion_auth->get_user_id()]);
                    redirect('food_settings/r', 'refresh');
                }else{
                    $this->food_settings_model->insert([
                        /*'min_order_price' => $this->input->post('min_order_price'),
                        'delivery_free_range' => $this->input->post('delivery_free_range'),
                        'min_delivery_fee' => $this->input->post('min_delivery_fee'),
                        'ext_delivery_fee' => $this->input->post('ext_delivery_fee'),*/
                        'preparation_time' => $this->input->post('preparation_time'),
                        'restaurant_status' => $this->input->post('restaurant_status'),
                        'vendor_id'=>$this->ion_auth->get_user_id()
                        
                    ]);
                    redirect('food_settings/r', 'refresh');
                }
                }
            }
    }




    public function get_orders_count($count)
    {
        $data = $this->food_orders_model->where('vendor_id',$this->ion_auth->get_user_id())->get_all();
            $mes['status']=0;
            $mes['message']='hi';
            if($data!=''){
            if($count < count($data)){
                $c=count($data)-$count;
                $mes['message']='<a href="'.base_url('food_orders/r/').'" class="btn btn-success">New Order Received <span class="badge badge-dark">'.$c.'</span></a>';
                $mes['status']=1;
            }
            }
            echo json_encode($mes);
    }

    public function reject_food_order()
    {
        $mes['status']=0;
        $res=$this->food_orders_model->update([
                        'id' => $this->input->post('order_id'),
                        'rejected_reason' => $this->input->post('reason'),
                        'order_status' => 0
                    ], 'id');
        if($res){
            $mes['status']=1;
        }
        echo json_encode($mes);
    }
    public function manual_assign_order()
    {
        $mes['status']=0;
        $otp=rand(1234,9567);
        $ord_del_id=$this->food_order_deal_model->insert([
                        'order_id' => $this->input->post('order_id'),
                        'deal_id' => $this->input->post('del_id'),
                        'ord_deal_status' => 2,
                        'otp' =>$otp
                    ]);

        if($ord_del_id){
          $r=$this->food_order_deal_model->update([
                        'deleted_at' =>date('Y-m-d H:i:s')
                    ], [
                    'id !='=>$ord_del_id,
                    'order_id'=>$this->input->post('order_id'),
                    'ord_deal_status'=>1
                  ]);
          $res=$this->food_orders_model->update([
                        'id' => $this->input->post('order_id'),
                        'deal_id' => $this->input->post('del_id'),
                        'otp' => $otp,
                        'order_status' => 4
                    ], 'id');
        }

        if($ord_del_id){
            $mes['status']=1;
        }
        echo json_encode($mes);
    }
    public function food_out_for_delivery()
    {
        $order_id=$this->input->post('order_id');
        $otp=$this->input->post('otp');
        $mes['status']=0;
        /*$deal=$this->food_order_deal_model->where('id',$this->input->post('ord_deal_id'))->get();*/
        $order=$this->food_orders_model->where('id',$order_id)->get();
        if($this->input->post('ord_type') == 'delivery'){
        if($order['otp'] == $this->input->post('otp')){
            if($order['delivery'] == 1){
        $res=$this->food_orders_model->update([
                        'id' => $order_id,
                        'order_status' => 4
                    ], 'id');
        }
        if($order['delivery'] == 2){
        $res=$this->food_orders_model->update([
                        'id' => $order_id,
                        'order_status' => 6
                    ], 'id');
        $total=$order['sub_total']+$order['tax'];
        $this->user_model->update_walet($order['vendor_id'],$total,'Order: '.$order['order_track']);
        }
        if($res){
            $mes['status']=1;
        }
        }
        }
        if($this->input->post('ord_type') == 'courier'){
        $res=$this->food_orders_model->update([
                        'id' => $order_id,
                        'otp' => $otp,
                        'order_status' => 6,
                    ], 'id');
        $total=$order['sub_total']+$order['tax'];
        $this->user_model->update_walet($order['vendor_id'],$total,'Order: '.$order['order_track']);
        if($res){
            $mes['status']=1;
        }
        }
        echo json_encode($mes);
    }
    public function food_order_status($id,$status,$r_t='')
    {
        $res=$this->food_orders_model->update([
                        'id' => $id,
                        'order_status' => $status
                    ], 'id');
        
        if($status==6){
            $order=$this->food_orders_model->where('id',$id)->get();
            $total=$order['sub_total']+$order['tax'];
            $this->user_model->update_walet($order['vendor_id'],$total,'Order: '.$order['order_track']);
        }
        
        if($res && ($status==2 || ($status == 3 && $r_t == 1)))
        {
            $order=$this->food_orders_model->where('id',$id)->get();
            if($order['delivery'] == 1){
        /*$lat      = '17.4468978';
        $lng      = '78.3788169';*/
        /*$ip = $_SERVER['REMOTE_ADDR'];*/
        /*$ip='223.230.124.22';*/
        /*$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));*/
        /*$d=explode(',',$details->loc);*/
        $l=$this->vendor_list_model->with_location('fields: id, address, latitude, longitude')->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
        $lat      = $l['location']['latitude'];
        $lng      = $l['location']['longitude'];
        $distance = 10; // Kilometers
//echo $lat.'/'.$lng;
        $query = $this->db->query(
            '
            SELECT 
                *,
                6371 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - ABS(delivery_boy_settings.latitude))), 2) + COS(RADIANS(?)) * COS(RADIANS(ABS(delivery_boy_settings.latitude))) * POWER(SIN(RADIANS(? - delivery_boy_settings.longitude)), 2))) AS distance
            FROM delivery_boy_settings where delivery_boy_status=1
            HAVING distance < ?
            ', 
            [
                $lat,
                $lat,
                $lng,
                $distance
            ] 
            );
        $deal=$query->result_array();
      /*  $setlat = 13.5234412; 
$setlong = 144.8320897; */
/*$awaka = "SELECT 'location_id',
    ( 3959 * acos( cos( radians(?) ) * cos( radians('location_latitude') ) * cos( radians(?) - radians('location_longitude') )
    + sin( radians(?) ) * sin( radians('location_latitude') ) ) ) AS 'distance'
    FROM 'locations' HAVING 'distance < 10'";
$result = $this->db->query($awaka, array($lat, $lng, $lat));  */

        //echo $this->db->last_query();
        //print_r($_POST);die;
        /*$deal=$this->user_model->fields('id')->get_all();*/
            for($i=0;$i<count($deal);$i++){
            $acc = $this->food_order_deal_model->insert([
                        'order_id' => $id,
                        'deal_id' => $deal[$i]['user_id']
                    ]);
            }
        }elseif($order['delivery'] == 2){
            $acc = $this->food_orders_model->update([
                        'id' => $id,
                        'otp' => rand(1234,9567)
                    ],'id');
        }
        }
        //redirect($this->session->userdata('last_page'));
        redirect(base_url('/food_orders/r'));
    }

     public function get_orders_list($order_type)
    {
        if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin');

        if($order_type=='past'){
          $this->db->where('order_status',6);
        }elseif($order_type=='upcomping'){
          $this->db->where('order_status !=',0);
          $this->db->where('order_status !=',6);
          $this->db->where('order_status !=',7);
        }elseif($order_type=='cancelled'){
          $this->db->where('order_status',0);
        }
        
       $data = $this->food_orders_model->with_order_items('fields:item_id,price,quantity')->with_sub_order_items('fields:sec_item_id,price,quantity')->fields('id,discount,delivery_fee,tax,sub_total,total,deal_id,order_track,order_status')->where('user_id',$this->ion_auth->get_user_id())->order_by('id', 'DESC')->get_all();
       if(! empty($data)){
                 $status=['0'=>'Rejected','1'=>'Order Received','2'=>'Accepted','3'=>'Preparing','4'=>'Out for delivery','5'=>'Order on the way','6'=>'Order Completed','7'=>'Cancelled'];
                for ($i = 0; $i < count($data) ; $i++){
                    $data[$i]['order_status']=$status[$data[$i]['order_status']];
            }
           
            }
            //print_r($data);die;
            echo json_encode($data);
    }

    public function get_sub_item_list($item_id)
    {
        /* if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */

        $sub_items=$this->food_item_model->order_by('id', 'DESC')->where('menu_id', $item_id)->get_all();
        echo '<option value="" selected disabled>--select--</option>';
        foreach ($sub_items as $item) {
            echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
        }
    }
    public function get_food_menus($vendor_id)
    {
        /* if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */

        $sub_items=$this->food_menu_model->order_by('id', 'DESC')->where('vendor_id', $vendor_id)->get_all();
        echo '<option value="" selected disabled>--select--</option>';
        foreach ($sub_items as $item) {
            echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
        }
    }
    public function get_food_sections_list($item_id)
    {
        /* if (! $this->ion_auth_acl->has_permission('food'))
            redirect('admin'); */

        $sub_items=$this->food_section_model->order_by('id', 'DESC')->where('item_id', $item_id)->get_all();
        echo '<option value="" selected disabled>--select--</option>';
        foreach ($sub_items as $item) {
            echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
        }
    }

    public function view_order()
    {
        $order_id=base64_decode(base64_decode($_GET['order_id']));
        $this->data['title'] = 'Order';
        $this->data['content'] = 'food/food/view_order';
        $data = $this->food_orders_model->with_user('fields:first_name,email,phone')->with_vendor('fields:name,address')->with_order_items('fields:item_id,order_id,price,quantity,sec_item_id')->with_sub_order_items('fields:sec_item_id,order_id,item_id,price,quantity')->fields('id,discount,delivery_fee,payment_method_id,created_at,tax,sub_total,total,deal_id,order_track,order_status,delivery,rejected_reason,otp,instructions,promo_code,promo_id,used_walet,used_walet_amount')->where('id',$order_id)->get();
           if(! empty($data)){
                    $cat_id=$this->vendor_list_model->where('vendor_user_id', $data['vendor_id'])->get();
                    $vendor_category_id=$cat_id['category_id'];
                    /*$status=[
                        '0'=>(($this->ion_auth->is_admin())? 'Order Rejected' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_rejected')),
                        '1'=>(($this->ion_auth->is_admin())? 'Order Received' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_received')),
                        '2'=>(($this->ion_auth->is_admin())? 'Order Accept' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_accepted')),
                        '3'=>(($this->ion_auth->is_admin())? 'Order Preparing' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_preparing')),
                        '4'=>(($this->ion_auth->is_admin())? 'Out for delivery' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_out_delivery')),
                        '5'=>(($this->ion_auth->is_admin())? 'Order on the way' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_on_way')),
                        '6'=>(($this->ion_auth->is_admin())? 'Order Completed' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_completed')),
                        '7'=>(($this->ion_auth->is_admin())? 'Order Cancelled' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_canceled'))];*/


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
    'status'=> true
    ];
if($oror != 0 && $oror != 7){
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_accepted'),
    'status'=>(($oror >1 && $oror <=6 )? true : false )
    ];
if($this->category_model->get_cat_desc_account_name($vendor_category_id,'order_preparing','field_status') == 1){
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_preparing'),
    'status'=>(($oror >2 && $oror <=6 )? true : false )
    ];
}
if($this->category_model->get_cat_desc_account_name($vendor_category_id,'order_out_delivery','field_status') == 1){
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_out_delivery'),
    'status'=>(($oror >3 && $oror <=6 )? true : false )
    ];
}
if($this->category_model->get_cat_desc_account_name($vendor_category_id,'order_on_way','field_status') == 1){
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_on_way'),
    'status'=>(($oror >4 && $oror <=6 )? true : false )
    ];
}
$r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_completed'),
    'status'=>(($oror >5 && $oror <=6 )? true : false )
    ];
}elseif($oror == 0){
    $r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_rejected'),
    'status'=> true
    ];
}elseif($oror == 7){
    $r[]=[
    'name'=>$this->category_model->get_cat_desc_account_name($vendor_category_id, 'order_canceled'),
    'status'=> true
    ];
}
                    $data['ord_state']=$r;
                    $data['order_stat']=$status[$data['order_status']];
                    $deal=$this->food_order_deal_model->with_deal_boy('fields:first_name')->fields('id,deal_id,otp')->where('ord_deal_status',2)->where('order_id',$data['id'])->get();
                    $this->load->model('order_rating_model');
                    $ord_r=$this->order_rating_model->where('order_id',$data['id'])->get();
                    $data['ord_rating']=0;
                    if($ord_r != ''){
                        $data['ord_rating']=1;
                        $data['rating']=$ord_r['rating'];
                        $data['review']=$ord_r['review'];
                        $data['del_rating']=$ord_r['del_rating'];
                        $data['del_review']=$ord_r['del_review'];
                    }
                    /*$data[$i]['deal_id'] = $deal['deal_id'];*/
                    $data['ord_deal_id'] = $deal['id'];
                    /*$data[$i]['otp'] = $deal['otp'];*/
                    $data['deal_name'] = $deal['deal_boy']['first_name'];
                
            }
/*            echo "<pre>";
            print_r($data['order_stat']);die;*/
            $this->data['order']=$data;
        $this->_render_page($this->template, $this->data);
    }
   


    public function order_support($type='r')
    {
        
        if($type == 'r'){
            $this->data['title'] = 'Order Support';
            $this->data['content'] = 'food/food/order_support';
            $this->_render_page($this->template, $this->data);
        }elseif ($type == 'ul') {
             $mer=array();
        $support=$this->order_support_model->get_support_chat($this->ion_auth->get_user_id())->result_array();

    $from_u=array_column($support, 'from_id');
    $to_u=array_column($support, 'to_id');
    $mer=array_unique(array_merge($from_u,$to_u));
$mer= array_keys(array_count_values($mer));

$j=1;
for ($i=0; $i <count($mer) ; $i++) {
    if($mer[$i] != $this->ion_auth->get_user_id()){$c='';
            $c=$this->order_support_model->get_support_chat_unread_c($mer[$i]);
            echo '<li class="" onclick="user_chat_support('.$mer[$i].')" id="active_chat_user'.$mer[$i].'">
                            <div class="d-flex bd-highlight">
                                <div class="img_cont">
                                    <img src="'.base_url($this->order_support_model->get_image_url($mer[$i])).'" class="rounded-circle user_img">'. (($c != 0)? '<span class="online_icon badge badge-dark">'.$c.'</span>' : "") .'
                                    
                                </div>
                                <div class="user_info">
                                    <span>'.ucwords($this->order_support_model->get_type_name_by_where('users','id',$mer[$i],'first_name')).'</span>
                                    <p>'.ucwords($this->order_support_model->get_type_name_by_where('users','id',$mer[$i],'unique_id')).'</p>
                                </div>
                            </div>
                </li>';
        $j++;}
    }
        }
    }
public function get_support_chat()
    {
    $mer=array();

        //$support=$this->order_support_model->get_support_chat()->result_array();
    /*$support=$this->order_support_model->with_order('fields:order_track')->with_from('fields:first_name,unique_id')->with_to('fields:first_name,unique_id')->where('row_status',1)->order_by('id', 'DESC')->get_all();*/
    $support=$this->order_support_model->fields('id,order_id')->where('row_status',1)->order_by('id', 'DESC')->get_all();
    
     /*echo "<pre>";
     print_r($support);die;*/
    //$from_u=array_column($support, 'from_id');
    //$to_u=array_column($support, 'to_id');
    $order=array_column($support, 'order_id');
    $mer=array_unique($order);
$mer= array_keys(array_count_values($mer));
$j=1;
for ($i=0; $i <count($mer) ; $i++) {
   $c='';
            $c=$this->order_support_model->get_support_chat_unread_c($mer[$i],$this->ion_auth->get_user_id());
            echo '<li class="" onclick="user_chat_support('.$mer[$i].')" id="active_chat_user'.$mer[$i].'">
                            <div class="d-flex bd-highlight">
                                <div class="img_cont">
                                    <img src="'.base_url($this->order_support_model->get_image_url($mer[$i])).'" class="rounded-circle user_img">'. (($c != 0)? '<span class="online_icon badge badge-dark"><span class="mynum">'.$c.'</span></span>' : "") .'
                                </div>
                                <div class="user_info">
                                    <span>'.ucwords($this->order_support_model->get_type_name_by_where('food_orders','id',$mer[$i],'order_track')).'</span>
                                </div>
                            </div>
                </li>';
        $j++;
    }
    }



     public function get_support_chat_box($order_id)
    {
        /*$support=$this->order_support_model->get_support_chat_box($u_id,$this->ion_auth->get_user_id())->result_array();*/
        /*$support=$this->order_support_model->with_order('fields:order_track')->with_from('fields:first_name,unique_id')->with_to('fields:first_name,unique_id')->where('order_id',$u_id)->get_all();*/
        $support = $this->food_orders_model
                ->fields('id,order_track')
                ->where('id', $order_id)
                ->get();
        $data='<div class="card">
                        <div class="card-header msg_head">
                            <div class="d-flex bd-highlight">
                                <div class="user_info">
                                    <span class="mtext">'.$support['order_track'].'</span>
                                </div>
                                <div class="video_cam">
                                    <a href="'.base_url('view_order').'?order_id='.base64_encode(base64_encode($order_id)).'" target="_blank"><span><i class="fas fa-eye" style="font-size: 30px;color: #fff;"></i></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body msg_card_body" id="chat_body_content'.$order_id.'">
                            

                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                
                                <textarea name="" class="form-control type_msg" placeholder="Type your message..." id="my_chat_sms"></textarea>
                                <div class="input-group-append">
                                    <span class="input-group-text send_btn" onclick="send_chat_sms();"><i class="fas fa-location-arrow"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>';
                    echo $data;
    }

 public function chat_body_content($order_id)
    {
        /*$support=$this->order_support_model->get_support_chat_box($u_id,$this->ion_auth->get_user_id())->result_array();*/
        $support=$this->order_support_model->where('order_id',$order_id)->order_by('id', 'DESC')->get_all();

        foreach ($support as $su) {
            if($su['from_id']==$this->ion_auth->get_user_id()){
                echo '<div class="d-flex justify-content-end mb-4">
                                <div class="msg_cotainer_send">'.$su['message'].'<span class="msg_time_send"><br/>'.date('h:i A, d-D-Y',strtotime($su['created_at'])).'</span>
                                </div>
                                <div class="img_cont_msg">
                        <img src="'.base_url($this->order_support_model->get_image_url($su['from_id'])).'" class="rounded-circle user_img_msg">
                                </div>
                            </div>';
            }else{
                echo '<div class="d-flex justify-content-start mb-4">
                                <div class="img_cont_msg">
                                    <img src="'.base_url($this->order_support_model->get_image_url($su['to_id'])).'" class="rounded-circle user_img_msg">
                                </div>
                                <div class="msg_cotainer">'.$su['message'].'<span class="msg_time">'.date('h:i A, d-D-Y',strtotime($su['created_at'])).'</span>
                                </div>
                            </div>';
            }
        }
    }

    public function send_chat_sms()
    {
        $input=$this->input->post();
        $support = $this->food_orders_model
                ->fields('id,user_id')
                ->where('id', $input['to_id'])
                ->get();
        $arr=array(
            'message'=>$input['message'],
            'from_id'=>$this->ion_auth->get_user_id(),
            'to_id'=>$support['user_id'],
            'order_id'=>$input['to_id']
        );
        $res=$this->db->insert('order_support',$arr);

        if($res){
            return $this->db->insert_id();
        }else{
            return false;
        }
        //$this->order_support_model->saving_insert_details('support',$arr);
    }
    public function update_sms_read_tick($order_id)
    {
        $arr=array(
            'read_status'=>1,
        );
        $where=array(
            'to_id'=>$this->ion_auth->get_user_id(),
            'order_id'=>$order_id
        );
        return $this->db->where($where)->update('order_support',$arr);
        //$this->order_support_model->update_operation($arr,'support',$where);
    }


}