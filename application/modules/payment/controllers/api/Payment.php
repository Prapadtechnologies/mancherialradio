<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class Payment extends MY_REST_Controller
{
    public $checkSum;
    public function __construct()
    {
        parent::__construct();
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        $this->load->model('user_model');
        $this->load->model('wallet_transaction_model');
        $this->load->model('bank_details_model');
    }
    
    /**
     * @author Mehar
     * @desc To manage wallet amount
     * 
     */
    public function wallet_post($type = 'withdrawal'){
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
        if($type == 'withdrawal'){
            $_POST = json_decode(file_get_contents('php://input'), TRUE);
            $source = NULL;
            if(! empty($_POST['AC'])){
                $source = $this->bank_details_model->insert([
                    'user_id' => $token_data->id,
                    'name' => $this->input->post('NAME'),
                    'ac' => $this->input->post('AC'),
                    'ifsc' => $this->input->post('IFSC'),
                    'bank_name' => $this->input->post('BANK_NAME'),
                ]);
            }
            $amount = floatval($this->input->post('TXNAMOUNT'));
            $id = $this->wallet_transaction_model->insert([
                'user_id' => $token_data->id,
                'type' => 'DEBIT',
                'cash' => $amount,
                'balance' => floatval($wallet['wallet']) - $amount,
                'paytm' => (isset($_POST['PAYTM']))? $this->input->post('PAYTM') : NULL ,
                'upi' => (isset($_POST['UPI']))? $this->input->post('UPI') : NULL ,
                'bank_id' => $source,
                'order_id' => $this->input->post('ORDERID'),
                'description' => $this->input->post('DESC'),
            ]);
            if($id){
                $wallet = $this->user_model->where('id', $token_data->id)->fields('wallet')->as_array()->get();
                $this->user_model->update([
                    'id' => $token_data->id,
                    'wallet' =>  floatval($wallet['wallet']) - $amount
                ], 'id');
                $this->set_response(floatval($wallet['wallet'])- $amount, 'Wallet Updated', REST_Controller::HTTP_OK, TRUE);
            }else{
                $this->set_response(NULL, 'Internal Error Occured', REST_Controller::HTTP_OK, FALSE);
            }
        }elseif($type == 'history'){
            $data = $this->wallet_transaction_model->order_by('id', 'DESC')->where('user_id', $token_data->id)->get_all();
            $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }elseif ($type == 'plan_pay'){
            
        }
        
    }
    
}
