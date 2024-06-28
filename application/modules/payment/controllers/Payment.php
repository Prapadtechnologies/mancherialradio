<?php

class Payment extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template = 'template/admin/main';
        $this->load->model('vendor_list_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('user_model');
    }
    
    public function wallet_transactions($type = 'list'){
        if($type == 'list'){
            $this->data['title'] = 'Transactions List';
            $this->data['content'] = 'payment/list';
            $this->data['transactions'] = $this->wallet_transaction_model->with_bank('fields:user_id, name, bank_name, ac, ifsc', 'where: status = \'1\'')->where(['status'=> 0, 'type' => 'DEBIT'])->get_all();
            if(! empty($this->data['transactions'])){
                for ($i = 0; $i < count($this->data['transactions']) ; $i++){
                    $this->data['transactions'][$i]['unique_id'] = $this->user_model->get($this->data['transactions'][$i]['user_id'])['unique_id'];
                }
            }
            $this->data['completed_transactions'] = $this->wallet_transaction_model->with_bank('fields:user_id, name, bank_name, ac, ifsc', 'where: status = \'1\'')->where(['status >'=> 0, 'type' => 'DEBIT'])->get_all();
            if(! empty($this->data['completed_transactions'])){
                for ($i = 0; $i < count($this->data['completed_transactions']) ; $i++){
                    $this->data['completed_transactions'][$i]['unique_id'] = $this->user_model->get($this->data['completed_transactions'][$i]['user_id'])['unique_id'];
                }
            }
            $this->_render_page($this->template, $this->data);
        }elseif ($type == 'change_status'){
            echo $this->wallet_transaction_model->update([
                'id' => $this->input->post('id'),
                'txn_id' => $this->input->post('txn_id'),
                'status' => $this->input->post('status'),
            ], 'id');
        }
    }
}

