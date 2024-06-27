<?php
require APPPATH . '/libraries/MY_REST_Controller.php';
require APPPATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;

class News extends MY_REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->model('news_category_model');
        $this->load->model('local_news_model');
        $this->load->model('location_model');
    }
    /**
     * @author Mehar
     * @desc To get list of news categories and targeted category as well
     * @param string $target
     */
    public function news_categories_get($target = '') {
        if(empty($target)){
            $data = $this->news_category_model->fields('id, name')->get_all();
            if(! empty($data)){
                for ($i = 0; $i < count($data) ; $i++){
                    
                    if (file_exists('./uploads/news_category_image/news_category_'.$data[$i]['id'].'.jpg')) {
                    $data[$i]['image'] = base_url().'uploads/news_category_image/news_category_'.$data[$i]['id'].'.jpg';
                    }else{
                    $data[$i]['image'] = base_url() . 'assets/img/no-img.png';
                    }
                }
            }
            $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }else{
            $data = $this->news_category_model->fields('id, name')->where('id', $target)->get();
            $data['image'] = base_url().'uploads/news_category_image/news_category_'.$target.'.jpg';
            $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    /**
     * To get the news
     *
     * @author Mehar
     * @param number $limit,offset
     */
    public function news_get($limit = 10, $offset = 0)
    {   
        $news_id = $this->input->get('news_id');
        if(empty($news_id)){
            $data = $this->news_model->all($limit, $offset, $this->input->get('cat_id'));
            if (! empty($data['result'])) {
                foreach ($data['result'] as $d) {
                    
                    if (file_exists('./uploads/news_image/news_' . $d->id . '.jpg')) {
                    $d->image = base_url() . 'uploads/news_image/news_' . $d->id . '.jpg';
                    }else{
                    $d->image = base_url() . 'assets/img/no-img.png';
                    }
                }
            }
            $this->set_response_simple((empty($data['result'])) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }else{
            $data = $this->news_model->where('id', $news_id)->get();
            if(!empty($data)){
                if (file_exists('./uploads/news_image/news_' . $news_id . '.jpg')) {
                    $d->image = base_url() . 'uploads/news_image/news_' . $news_id . '.jpg';
                    }else{
                    $d->image = base_url() . 'assets/img/no-img.png';
                    }
            }
            $this->set_response_simple((empty($data)) ? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
        }
    }
    /**
     * To get the local news
     *
     * @author trupti
     * @param method, target
     */
    public function local_news_post($method = 'r', $target = NULL){
        $token_data = $this->validate_token($this->input->get_request_header('X_AUTH_TOKEN'));
      if($method == 'c'){
          $_POST = json_decode(file_get_contents("php://input"), TRUE);
          $this->form_validation->set_rules($this->local_news_model->rules);
            if ($this->form_validation->run() == false) {
                $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
            } else {
                $v = $this->location_model->where('latitude', $this->input->post('latitude'))
                ->where('longitude', $this->input->post('longitude'))
                ->get();
                if ($v != '') {
                    $l_id = $v['id'];
                } else {
                    $l_id = $this->location_model->insert([
                        'latitude' => $this->input->post('latitude'),
                        'longitude' => $this->input->post('longitude'),
                        'address' => $this->input->post('address')
                    ]);
                }
                $id = $this->local_news_model->insert([
                    'user_id' => $token_data->id,
                    'title' => $this->input->post('title'),
                    'category' => $this->input->post('category'),
                    'video_link' => $this->input->post('video_link'),
                    'news' => $this->input->post('news'),
                    'location_id' => $l_id
                ]);
                if (!file_exists('uploads/local_news_image/')) {
                    mkdir('uploads/local_news_image/', 0777, true);
                }
                file_put_contents("./uploads/local_news_image/local_news_$id.jpg", base64_decode($this->input->post('local_news_image')));
                $this->set_response_simple($id, 'Success..!', REST_Controller::HTTP_CREATED, TRUE);
            }
        }elseif ($method == 'r'){
            if(empty($target)){
                $data = $this->local_news_model->get_all();
                if(! empty($data)){
                    for ($i = 0; $i < count($data) ; $i++){
                        
                        if (file_exists('./uploads/local_news_image/local_news_'.$data[$i]['id'].'.jpg')) {
                    $data[$i]['image'] = base_url().'uploads/local_news_image/local_news_'.$data[$i]['id'].'.jpg';
                    }else{
                    $data[$i]['image'] = base_url() . 'assets/img/no-img.png';
                    }
                    }
                }
                $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }else{
                $data = $this->local_news_model->where('id', $target)->get();
                
                if (file_exists('./uploads/local_news_image/local_news_'.$target.'.jpg')) {
                    $data['image'] = base_url().'uploads/local_news_image/local_news_'.$target.'.jpg';
                    }else{
                    $data['image'] = base_url() . 'assets/img/no-img.png';
                    }
                $this->set_response_simple(($data == FALSE)? FALSE : $data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
            }
        }elseif($method == 'u'){
            if(! empty($target)){
                $_POST = json_decode(file_get_contents("php://input"), TRUE);
                $this->form_validation->set_rules($this->local_news_model->rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->set_response_simple(validation_errors(), 'Validation Error', REST_Controller::HTTP_NON_AUTHORITATIVE_INFORMATION, FALSE);
                }else{
                    $v = $this->location_model->where('latitude', $this->input->post('latitude'))
                    ->where('longitude', $this->input->post('longitude'))
                    ->get();
                    if ($v != '') {
                        $l_id = $v['id'];
                    } else {
                        $l_id = $this->location_model->insert([
                            'latitude' => $this->input->post('latitude'),
                            'longitude' => $this->input->post('longitude'),
                            'address' => $this->input->post('address')
                        ]);
                    }
                    $id =  $this->local_news_model->update([
                    'id' => $target,
                    'title' => $this->input->post('title'),
                    'category' => $this->input->post('category'),
                    'video_link' => $this->input->post('video_link'),
                    'news' => $this->input->post('news'),
                    'location_id' => $l_id
              ],'id');
              $this->set_response_simple($id, 'Updated..!', REST_Controller::HTTP_ACCEPTED, TRUE);
            }
           }
        }elseif ($method == 'd'){
            $data = $this->local_news_model->where('id', $target)->delete();
            $this->set_response_simple(($data == FALSE) ? FALSE : $data, 'Deleted..!', REST_Controller::HTTP_OK, TRUE);
            }
    }
    /**
     * To get the local news
     *
     * @author Trupti
     * @param $cat_id
     */
    public function local_news_get($cat_id = NULL,$target = NULL){
        if(! empty($cat_id))
        {
            $data = $this->local_news_model->all($cat_id,(isset($_GET['latitude'])) ? $this->input->get('latitude') : NUll,(isset($_GET['longitude'])) ? $this->input->get('longitude') : NUll);
            if (! empty($data['result'])) {
                foreach ($data['result'] as $key => $val) {
                    $data['result'][$key]['image'] = base_url() . 'uploads/local_news_image/local_news_' . $val['id'] . '.jpg';
                }
            }
        }else{
            $data = $this->local_news_model->get_all();
        }
        $this->set_response_simple($data, 'Success..!', REST_Controller::HTTP_OK, TRUE);
    }
}
