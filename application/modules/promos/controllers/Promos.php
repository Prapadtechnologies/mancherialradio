<?php
class Promos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        if (! $this->ion_auth->logged_in()) // || ! $this->ion_auth->is_admin()
            redirect('auth/login');
            $this->load->model('category_model');
            $this->load->model('sub_category_model');
            $this->load->model('food_orders_model');
            $this->load->model('user_model');
            $this->load->model('vendor_list_model');
            $this->load->model('promos_model');

    }
    public function index($type = 'r')
    {
    	if ($type == 'c') {
                $this->form_validation->set_rules($this->promos_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->index('r');
                } else {
                    $input_data=array(
                        'promo_title' => $this->input->post('promo_title'),
                        'promo_code' => $this->input->post('promo_code'),
                        'promo_type' => $this->input->post('promo_type'),
                        'promo_label' => $this->input->post('promo_label'),
                        'valid_from' => $this->input->post('start_date'),
                        'valid_to' => $this->input->post('end_date'),
                        'discount_type' => $this->input->post('discount_type'),
                        'discount' => $this->input->post('discount'),
                        'uses' => $this->input->post('uses'),
                        'status' => $this->input->post('status'),
                    );
                    $id = $this->promos_model->insert($input_data);
                    	$v=$this->input->post('vendors');
                    for ($i=0; $i < count($v) ; $i++) {
                    	$this->db->insert('promo_vendors',array('vendor_id'=>$v[$i],'promo_id'=>$id));
                    }

                    redirect('promos/r', 'refresh');
                }
            } elseif ($type == 'r') {
                $this->data['title'] = 'Promo Codes';
                $this->data['content'] = 'promos/promos/promos_list';

                $this->data['categories'] = $this->category_model->get_all();
            if(isset($_GET) && !empty($_GET)){
            $ven=$this->vendor_list_model->fields('id,name,vendor_user_id,status')->order_by('id', 'DESC')->where(['status'=> 1,'category_id'=>$_GET['category_id']])->get_all();
            }else{
                $ven=array();
            }
            $this->data['vendors'] = $ven;
                $this->data['promos'] = $this->promos_model->fields('id,promo_title,promo_code,promo_type,promo_label,valid_from,valid_to,discount_type,discount,uses,status')->order_by('id', 'DESC')->where(['status !='=> 0])->get_all();
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
                echo $this->promos_model->delete(['id' => $this->input->post('id')]);
            }elseif($type == 'edit'){
                $this->data['title'] = 'Edit Menu';
                $this->data['content'] = 'food/food/edit';
                $this->data['type'] = 'food_menu';
                $this->_render_page($this->template, $this->data);
            }
    }
    public function check_promo_code_unique($value='')
    {
        $validation=$this->db->get_where('promo_codes',array('promo_code'=>$value))->num_rows();
            if($validation != 0){
                $this->form_validation->set_message('check_promo_code_unique','Promo Code Existed');
                return false;
            }
            return true;
    }
    
}