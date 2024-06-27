<?php
$this->load->view('food_scripts');
$this->session->set_userdata('last_page',current_url());
if (!$this->ion_auth->is_admin()){
$cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
$vendor_category_id=$cat_id['category_id'];
}
?>

<style type="text/css">
  a.btn.btn-sm.float-right.order-b {
    background-color: #d4d5d6;
    margin-left: 5px;
        color: #000;
}
a.btn.btn-sm.float-right.order-b:hover {
  background-color: #e9edf1; 
  }
</style>
<!--Add Sub_Category And its list-->
<div class="row">
  <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="ven">List of Orders</h4>
<div class="flex-row">
      <div class="d-flex justify-content-center">
        <form class="form-inline" method="get" action="">
                  <label for="email" class="mr-sm-2">Start Date:</label>
                  <input type="text" class="form-control" required name="start_date" placeholder="yyyy-mm-dd" id="start_date" value="<?=$start_date;?>">
                  &nbsp;&nbsp;
                  <label for="pwd" class="mr-sm-2">End Date:</label>
                  <input type="text" class="form-control" required name="end_date" placeholder="yyyy-mm-dd" id="end_date" value="<?=$end_date;?>">
                  <button type="submit" class="btn btn-primary ml-sm-2">Submit</button>
                </form>
      </div>
    </div>
<span id="order-notification-alert"></span>

        </div>
        <div class="card-body">
          <?php
          $url_date='?start_date='.$start_date.'&end_date='.$end_date;
          ?>
          
          <!-- <button type="button" class="btn btn-secondary float-right" onClick="window.location.reload()">Assigned Orders</button> -->
          
          <a href="<?=base_url('food_orders/r/rejected').$url_date;?>" class="btn btn-sm float-right order-b <?=($order_type == 'rejected')? 'btn-success':'';?>">Rejected From Vendor</a>
          <a href="<?=base_url('food_orders/r/cancelled').$url_date;?>" class="btn btn-sm float-right order-b <?=($order_type == 'cancelled')? 'btn-success':'';?>">Cancelled</a>
          <a href="<?=base_url('food_orders/r/past').$url_date;?>" class="btn btn-sm float-right order-b <?=($order_type == 'past')? 'btn-success':'';?>">Completed</a>
          <a href="<?=base_url('food_orders/r').$url_date;?>" class="btn btn-sm float-right order-b <?=($order_type == 'upcoming')? 'btn-success':'';?>">Active</a>
          <a href="<?=base_url('food_orders/r/all').$url_date;?>" class="btn btn-sm float-right order-b <?=($order_type == 'all')? 'btn-success':'';?>">All</a>

          <div class="table-responsive">
            <table class="table table-striped table-hover" id="tableExport"
              style="width: 100%;">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Order Number</th>
                  <th>Customer</th>
                  <?php
                  if($this->ion_auth->is_admin()){
                    ?>
                  <th>Vendor</th>
                <?php }?>
                  <th>Order Receipt</th>
                  <th>Price</th>
                  <th>Status</th>
                  <th>Payment</th>
                  <th>Created Time</th>
                  <th>Actions</th>
                  <!-- <th>Delivery Boy Assign</th> -->
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($orders)):?> 
                  <?php 
                  $sno = 1; foreach ($orders as $order):

if ($this->ion_auth->is_admin()){
    $cat_id=$this->vendor_list_model->where('vendor_user_id', $order['vendor_id'])->get();
$vendor_category_id=$cat_id['category_id'];
}
                    $ord_stay=$ord_stay_c=$ord_stay_b='';
                  $ord=$order['order_status'];
                  if($ord==0){
                    $ord_stay='<span style="color:red;">'.$order['rejected_reason'].'</span>';
                  }elseif($ord==1){
                    if($this->category_model->get_cat_desc_account_name($vendor_category_id,'order_preparing','field_status') == 1){
                      $s_b=2;
                      $r_t='';
                    }else{
                      $s_b=3;
                      $r_t=1;
                    }
                    $ord_stay ='<a href="'.base_url('food_order_status/').$order['id'].'/'.$s_b.'/'.$r_t.'" class="btn btn-sm btn-success">'.(($this->ion_auth->is_admin())? 'Order Accept' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_accepted')).'</a><br/>';
                    $ord_stay .= '<a onclick="return reject_order('.$order['id'].')" class="btn btn-sm btn-danger">'.(($this->ion_auth->is_admin())? 'Order Rejected' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_rejected')).'</a>';
                    $ord_sta='Order Accept';
                    $ord_sta_id=2;
                  }elseif($ord==2){

                    /*if($this->ion_auth_acl->has_permission('order_delivery') || $this->ion_auth_acl->has_permission('order_selfpickup') || $this->ion_auth_acl->has_permission('order_courier'))
                     {*/
                    if($this->category_model->get_cat_desc_account_name($vendor_category_id,'order_preparing','field_status') == 1){
                    if($order['delivery'] == 1 || $order['delivery'] == 2) 
                    {
                    $ord_stay='<a href="'.base_url('food_order_status/').$order['id'].'/3" class="btn btn-sm btn-success">'.(($this->ion_auth->is_admin())? 'Order Preparing' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_preparing')).'</a>';
                    $ord_sta='Preparing';
                    $ord_sta_id=3;
                    }
                    }

                    /*if($this->ion_auth_acl->has_permission('order_booking') && )
                    {*/
                    if($order['delivery'] == 3)
                    {
                    $ord_stay_b='<a href="'.base_url('food_order_status/').$order['id'].'/6" class="btn btn-sm btn-success">'.(($this->ion_auth->is_admin())? 'Order Completed' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_completed')).'</a>';
                    $ord_sta='Completed';
                    $ord_sta_id=6;
                    }

/*if($this->ion_auth_acl->has_permission('order_courier'))
                     {
                    $ord_stay_c='<a href="'.base_url('food_order_status/').$order['id'].'/4" class="btn btn-sm btn-success">'.(($this->ion_auth->is_admin())? 'Order Out For Delivery' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_out_delivery')).'</a>';
                    $ord_sta='Preparing';
                    $ord_sta_id=4;
                    }*/
                  }elseif($ord == 3){

                    /*if($this->ion_auth_acl->has_permission('order_delivery'))
                     {*/
                    if($this->category_model->get_cat_desc_account_name($vendor_category_id,'order_out_delivery','field_status') == 1){
                    if($order['delivery'] == 1)
                    {
                    $ord_stay='<a onclick="return out_for_delivery('.$order['id'].')" class="btn btn-sm btn-success">'.(($this->ion_auth->is_admin())? 'Order Out For Delivery' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_out_delivery')).'</a>';
                    $ord_sta='Out for Delivery';
                    $ord_sta_id=4;
                    }
                  }
                    if($order['delivery'] == 2)
                    {
                    $ord_stay='<a onclick="return out_for_delivery('.$order['id'].')" class="btn btn-sm btn-success">'.(($this->ion_auth->is_admin())? 'Completed' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_completed')).'</a>';
                    $ord_sta='Completed';
                    $ord_sta_id=6;
                    }
                    if($order['delivery'] == 1 && $this->ion_auth_acl->has_permission('order_courier'))
                     {
                    $ord_stay_c='<a onclick="return out_for_courier('.$order['id'].')" class="btn btn-sm btn-success">'.(($this->ion_auth->is_admin())? 'Order Completed' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'order_completed')).'</a>';
                    $ord_sta='Order Completed';
                    $ord_sta_id=6;
                    }
                  }
                  ?>
                    <tr>
                      <td><?php echo $sno++;?></td>
                      <td><?php echo $order['order_track'];?></td>
                      <td><?php echo $order['user']['first_name'];?></td>
                      <?php 
                      if ($this->ion_auth->is_admin()){?>
                      <td><?php echo $order['vendor']['name'];?></td>
                     <?php }?>
                      <td>
                        <div class="card">
  <div class="card-body">
                        <!-- <u>Order Receipt</u> -->
                        <ul>
                          <?php
                          foreach ($order['order_items'] as $ord_it) {
                          ?>
                          <li><?=$this->db->get_where('food_item',array('id'=>$ord_it['item_id']))->row()->name;?><?=(!empty($ord_it['sec_item_id']))? '<br/>'.$this->db->get_where('food_sec_item',array('id'=>$ord_it['sec_item_id']))->row()->name : '';?><span class="pull-right"><?=' X '.$ord_it['quantity'];?></span>
                            <?php
                        if(!empty($order['sub_order_items'])){
                          ?>
                          <ul>
                          <?php
                          foreach ($order['sub_order_items'] as $sub_ord_it) {
                          if($sub_ord_it['item_id'] == $ord_it['item_id']){
                          ?>
                          <li><?=$this->db->get_where('food_sec_item',array('id'=>$sub_ord_it['sec_item_id']))->row()->name;?><!-- <span class="pull-right"><?=' X '.$sub_ord_it['quantity'];?></span> --></li>
                        <?php 
                        }
                        }
                        ?>
                        </ul>
                        <?php
                        }
                        ?>
                          </li>
                        <?php }?>
                        <!-- <?php
                        if(!empty($order['sub_order_items'])){
                          foreach ($order['sub_order_items'] as $sub_ord_it) {
                          ?>
                          <li><?=$this->db->get_where('food_sec_item',array('id'=>$sub_ord_it['sec_item_id']))->row()->name;?><span class="pull-right"><?=' X '.$sub_ord_it['quantity'];?></span></li>
                        <?php }}?> -->
                        </ul>
                        </div>
</div>
                      </td>
                      <td><?php echo $order['total'];?></td>
                      <td><?php echo $order['order_stat'];?><br/>
                         <?php
                        if(!empty($order['deal_id'])){
                          echo "Assigned To: ".$order['deal_name'].'<br/>';
                        }else{
                          echo "Not Assign Yet";
                        ?>
                       <!--  <select id="del_boy" value="0" onchange="manual_assign(this.value,<?=$order['id'];?>);">
                          <option value="">Select Delivery Boy</option>
                          <?php foreach ($users as $user):?>
                                  <option value="<?=$user['id'];?>"><?=$user['first_name']?></option>
                                <?php endforeach;?>
                        </select> -->                        
                        <?php
                        }
                        ?>
                      </td>
                      <td><?php echo $order['payment_method_id'];?></td>
                      <td><?=$this->food_orders_model->time_elapsed_string($order['created_at']);?></td>
                      <td>
                        <?php
                        if(!empty($ord_stay) && !$this->ion_auth->is_admin()){
                          echo $ord_stay;
                        }
                        if(!empty($ord_stay_c) && !$this->ion_auth->is_admin()){
                          echo $ord_stay_c;
                        }
                        if(!empty($ord_stay_b) && !$this->ion_auth->is_admin()){
                          echo $ord_stay_b;
                        }
                        ?>
                <a href="<?=base_url('view_order');?>?order_id=<?=base64_encode(base64_encode($order['id']));?>" class="btn btn-sm btn-success">View</a>
                 <!--         <a href="#" class="mr-2  text-success" onclick="return showAjaxModal('<?=base_url('admin/my_test');?>');">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                       <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_ajax">
    Open modal
  </button> -->


  <?php 
if(empty($order['deal_id'])){
  if($order_type == 'upcoming'){
?>
                        <select id="del_boy" value="0" onchange="manual_assign(this.value,<?=$order['id'];?>);">
                          <option value="">Select Delivery Boy</option>
                          <?php foreach ($users as $user):
                            $u=$this->user_model->fields('id,first_name,unique_id')->where('id',$user['user_id'])->get();
                            ?>
                                  <option value="<?=$user['user_id'];?>"><?=ucwords($u['first_name']).' - '.$u['unique_id'];?></option>
                                <?php endforeach;?>
                        </select>
                        <?php
  }
}
  ?>
                      </td>
                      <!-- <td>
                        <?php
                        if(!empty($order['deal_id'])){
                          echo "Assigned To: ".$order['deal_name'].'<br/>';
                        }else{
                          echo "Not Assign Yet";
                        ?>
                        <select id="del_boy" value="0" onchange="manual_assign(this.value,<?=$order['id'];?>);">
                          <option value="">Select Delivery Boy</option>
                          <?php foreach ($users as $user):?>
                                  <option value="<?=$user['id'];?>"><?=$user['first_name']?></option>
                                <?php endforeach;?>
                        </select>
                        <?php
                        }
                        ?>
                      </td> -->
                    </tr>
                  <?php endforeach;?>
              <?php else :?>
              <tr ><th colspan='11'><h3><center>No Data Found</center></h3></th></tr>
              <?php endif;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  setInterval(function(){ get_order_alert(); }, 5000);

  function get_order_alert(){
    $.ajax({
            url: '<?php echo base_url();?>food/get_orders_count/<?=$orders_count;?>',
            type: 'get',
            dataType: 'json',
            success: function(response)
            {
              if(response.status==1){
              $('#order-notification-alert').html(response.message);
              order_bell();
              }
              
            }
        });
  }

  function reject_order(order_id){
    var reason = prompt("Enter Reason for Rejecting Order:", "");
        if(reason != null){
    if(reason == ''){
      alert('Please Enter Reason');
    } else {
      $.ajax({
            url: '<?=base_url();?>food/reject_food_order',
            type: 'post',
            data: {reason : reason, order_id : order_id},
            dataType: 'json',
            success: function(response)
            {
              if(response.status==1){
                alert('Order Rejected Successfully');
                location.reload();
              }else if(response.status==0){
                alert('Order Not Rejected');
              }
            }
          });
    }
        }
  }

    function out_for_delivery(order_id){
        var otp = prompt("Enter OTP :", "");
        
        if(otp != null){
        if(otp == ''){
            alert('Please Enter OTP');
        } else {
            $.ajax({
            url: '<?=base_url();?>food/food_out_for_delivery',
            type: 'post',
            data: {otp : otp, order_id : order_id, ord_type : 'delivery'},
            dataType: 'json',
            success: function(response)
            {
                if(response.status==1){
                    alert('Order Out For Delivery');
                    location.reload();
                }else if(response.status==0){
                    alert('In-Correct OTP');
                }
            }
            });
        }
        }
    }

    function out_for_courier(order_id){
        var otp = prompt("Enter Tracking ID :", "");
        
        if(otp != null){
        if(otp == ''){
            alert('Please Enter Tracking ID');
        } else {
            $.ajax({
            url: '<?=base_url();?>food/food_out_for_delivery',
            type: 'post',
            data: {otp : otp, order_id : order_id, ord_type : 'courier'},
            dataType: 'json',
            success: function(response)
            {
                if(response.status==1){
                    alert('Completed');
                    location.reload();
                }else if(response.status==0){
                    alert('In-Correct Tracking ID');
                }
            }
            });
        }
        }
    }

     function manual_assign(del_id,order_id){
      var ac = confirm("Are You Sure Want To Assign");
           if(ac == true){
    if(ac == ''){
      alert('Please Select Any One');
    } else {
      $.ajax({
            url: '<?=base_url();?>food/manual_assign_order',
            type: 'post',
            data: {del_id : del_id, order_id : order_id},
            dataType: 'json',
            success: function(response)
            {
              if(response.status==1){
                alert('Order Assigned Successfully');
                location.reload();
              }else if(response.status==0){
                alert('Order Not Assigned');
              }
            }
          });
        }
        }
  /*  var reason = prompt("Enter Reason for Rejecting Order:", "<input type='radio' name='g' value='m' /><input type='radio' name='g' value='f' />");
        if(reason != null){
    if(reason == ''){
      alert('Please Enter Reason');
    } else {
      $.ajax({
            url: '<?=base_url();?>food/reject_food_order',
            type: 'post',
            data: {reason : reason, order_id : order_id},
            dataType: 'json',
            success: function(response)
            {
              if(response.status==1){
                alert('Order Rejected Successfully');
                location.reload();
              }else if(response.status==0){
                alert('Order Not Rejected');
              }
            }
          });
    }
        }*/
  }
</script>



