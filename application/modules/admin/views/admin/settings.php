<!-- <div class="container">
    <div class="row">
        <div class="col-md-12">
        <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#system_settings">System Settings</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1">Menu 1</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2">Menu 2</a>
  </li>
</ul>
<div class="tab-content">
  <div class="tab-pane container active" id="system_settings">
    <form id="form_site_settings" action="<?php echo base_url('settings/site');?>" method="post" class="needs-validation reset" novalidate="" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title">System Settings</h2>
                    </header>
                    <div class="card-body">
                        
                        <div class="form-group row">
                            <label class="col-sm-3 ">System Name<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="system_name" class="form-control" placeholder="System Name" required="" value="<?php echo $this->setting_model->where('key', 'system_name')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                            <?php echo form_error('system_name','<div style="color:red">','</div>');?>
                                <input type="hidden" name="id" value="">
                                <br>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">System Title <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="system_title" class="form-control" placeholder="System Title " required="" value="<?php echo $this->setting_model->where('key','system_title')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">System Title ?</div>
                            <?php echo form_error('system_title','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Mobile Number<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="mobile" class="form-control" placeholder="Mobile Number" required="" value="<?php echo $this->setting_model->where('key','mobile')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Mobile Number?</div>
                            <?php echo form_error('mobile','<div style="color:red" "margin_left=100px">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Address<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" style=" height: 70px " name="address" value=" <?php echo $this->setting_model->where('key','address')->get()['value'];?>" >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                            <?php echo form_error('address','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Facebook Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="facebook" class="form-control" placeholder="Facebook Link" value="<?php echo $this->setting_model->where('key','facebook')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Facebook Link?</div>
                            <?php echo form_error('facebook','<div style="color:red ">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Twiter Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="twiter" class="form-control" placeholder="Twiter Link" value="<?php echo $this->setting_model->where('key','twiter')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Twiter Link?</div>
                            <?php echo form_error('twiter','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Youtube Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="youtube" class="form-control" placeholder="Youtube Link" value="<?php echo $this->setting_model->where('key','youtube')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Youtube Link?</div>
                            <?php echo form_error('youtube','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Skype Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="skype" class="form-control" placeholder="Skype Link" value="<?php echo $this->setting_model->where('key','skype')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Skype Link?</div>
                            <?php echo form_error('skype','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Pinterest Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="pinterest" class="form-control" placeholder="Pinterest Link"  value="<?php echo $this->setting_model->where('key','pinterest')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Pinterest Link</div>
                            <?php echo form_error('mobile','<div style="color:red">','</div>');?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form_site_settings')" value="Reset" />
                            </div>
                        </div>

                    </div>
            
            </section>
            </form></div>
  <div class="tab-pane container fade" id="menu1">...</div>
  <div class="tab-pane container fade" id="menu2">...</div>
</div>
</div>
    </div>
</div> -->
<div class="container">
    <div class="row">
        <div class="col-md-12" style="">
            <form id="form_site_settings" action="<?php echo base_url('settings/site');?>" method="post" class="needs-validation reset" novalidate="" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">System Settings</h2>
                    </header>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 ">System Name<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="system_name" class="form-control" placeholder="System Name" required="" value="<?php echo $this->setting_model->where('key', 'system_name')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                            <?php echo form_error('system_name','<div style="color:red">','</div>');?>
                                <input type="hidden" name="id" value="">
                                <br>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">System Title <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="system_title" class="form-control" placeholder="System Title " required="" value="<?php echo $this->setting_model->where('key','system_title')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">System Title ?</div>
                            <?php echo form_error('system_title','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Mobile Number<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="mobile" class="form-control" placeholder="Mobile Number" required="" value="<?php echo $this->setting_model->where('key','mobile')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Mobile Number?</div>
                            <?php echo form_error('mobile','<div style="color:red" "margin_left=100px">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Address<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" style=" height: 70px " name="address" value=" <?php echo $this->setting_model->where('key','address')->get()['value'];?>" >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                            <?php echo form_error('address','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Facebook Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="facebook" class="form-control" placeholder="Facebook Link" value="<?php echo $this->setting_model->where('key','facebook')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Facebook Link?</div>
                            <?php echo form_error('facebook','<div style="color:red ">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Twiter Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="twiter" class="form-control" placeholder="Twiter Link" value="<?php echo $this->setting_model->where('key','twiter')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Twiter Link?</div>
                            <?php echo form_error('twiter','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Youtube Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="youtube" class="form-control" placeholder="Youtube Link" value="<?php echo $this->setting_model->where('key','youtube')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Youtube Link?</div>
                            <?php echo form_error('youtube','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Skype Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="skype" class="form-control" placeholder="Skype Link" value="<?php echo $this->setting_model->where('key','skype')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Skype Link?</div>
                            <?php echo form_error('skype','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Pinterest Link</label>
                            <div class="col-sm-9">
                                <input type="text" name="pinterest" class="form-control" placeholder="Pinterest Link"  value="<?php echo $this->setting_model->where('key','pinterest')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Pinterest Link</div>
                            <?php echo form_error('mobile','<div style="color:red">','</div>');?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form_site_settings')" value="Reset" />
                            </div>
                        </div>

                    </div>
            
            </section>
            </form>
        </div>
        <div class="col-md-6">
            <form id="form_sms" action="<?php echo base_url('settings/sms');?>" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">SMS Settings</h2>
                    </header>
                    <br>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 ">Username <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="sms_username" class="form-control" placeholder="Username" required="" value="<?php echo $this->setting_model->where('key','sms_username')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">sms_username?</div>
                            <?php echo form_error('sms_username','<div style="color:red">','</div>');?>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Sender <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="sms_sender" class="form-control" placeholder="Sender" required="" value="<?php echo $this->setting_model->where('key','sms_sender')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">sms_sender?</div>
                            <?php echo form_error('sms_sender','<div style="color:red">','</div>');?>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Hash Key <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="sms_hash" class="form-control" placeholder="Hash Key" required="" value="<?php echo $this->setting_model->where('key','sms_hash')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Hash Key?</div>
                            <?php echo form_error('sms_hash','<div style="color:red">','</div>');?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form_sms')" value="Reset" />
                            </div>
                        </div>
                    </div>
            
            </section>
        </form>
        </div>

        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/smtp');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">SMTP Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">SMTP Port <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="smtp_port" class="form-control" placeholder="SMTP Port" required="" value="<?php echo $this->setting_model->where('key','smtp_port')->get()['value']?>">
                            </div>
                            <div class="invalid-feedback">smtp_port?</div>
                            <?php echo form_error('smtp_port','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">SMTP Host<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="smtp_host" class="form-control" placeholder="SMTP Host" required="" value="<?php echo $this->setting_model->where('key','smtp_host')->get()['value']?>">
                            </div>
                            <div class="invalid-feedback">smtp_host?</div>
                            <?php echo form_error('smtp_host','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">SMTP Username<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="smtp_username" class="form-control" placeholder="SMTP Username" required="" value="<?php echo $this->setting_model->where('key','smtp_username')->get()['value']?>">
                            </div>
                            <div class="invalid-feedback">smtp_username?</div>
                            <?php echo form_error('smtp_username','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">SMTP Password<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="smtp_password" class="form-control" placeholder="SMTP Password" required="" value="<?php echo $this->setting_model->where('key','smtp_password')->get()['value']?>">
                            </div>
                            <div class="invalid-feedback">smtp_password?</div>
                            <?php echo form_error('smtp_password','<div style="color:red">','</div>');?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                            </div>
                        </div>
                    </div>
            
            </section></form>
        </div>
        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('site_logo/logo');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Logo</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Logo</label>
                            <div class="col-sm-9">
                            <input type='file' name="file" class="form-control" onchange="news_image(this);" />
                       <?php echo form_error('file', '<div style="color:red">', '</div>');?>
                       <br><br/>
                          <img id="blah" src="<?php echo base_url(); ?>assets/img/logo.png" style="height: 50px;width: 250px;" alt="Logo" />
                        </div>
                    </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                            </div>
                        </div>
                    </div>
            
            </section></form>
        </div>
        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('site_logo/favicon');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Favicon</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Favicon</label>
                            <div class="col-sm-9">
                            <input type='file' name="file" class="form-control" onchange="news_image(this);" />
                       <?php echo form_error('file', '<div style="color:red">', '</div>');?>
                       <br><br/>
                          <img id="blah" src="<?php echo base_url(); ?>assets/img/favicon.png" style="height: 30px;width: 30px !important;" alt="Favicon" />
                        </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                            </div>
                        </div>
                    
                    </div>
            
            </section></form>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/payment');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Payment Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Pay per vendor<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="pay_per_vendor" class="form-control" placeholder="Pay per vendor" required="" value="<?php echo $this->setting_model->where('key','pay_per_vendor')->get()['value']?>">
                            </div>
                            <div class="invalid-feedback">Pay per vendor?</div>
                            <?php echo form_error('pay_per_vendor','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Vendor validation count<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="vendor_validation" class="form-control" placeholder="Vendor validation count" required="" value="<?php echo $this->setting_model->where('key','vendor_validation')->get()['value']?>">
                            </div>
                            <div class="invalid-feedback">Vendor validation count?</div>
                            <?php echo form_error('vendor_validation','<div style="color:red">','</div>');?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                            </div>
                        </div>
                    </div>
            
            </section></form>
        </div>
        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/order_payment');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Orders Payment Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Pay per Order<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="pay_per_order" class="form-control" placeholder="Pay Per Order" required="" value="<?php echo $this->setting_model->where('key','pay_per_order')->get()['value']?>">
                            </div>
                            <div class="invalid-feedback">Pay Per Order?</div>
                            <?php echo form_error('pay_per_order','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Order Validation Count<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="order_validation" class="form-control" placeholder="Order Validation Count" required="" value="<?php echo $this->setting_model->where('key','order_validation')->get()['value']?>">
                            </div>
                            <div class="invalid-feedback">Order Validation Count?</div>
                            <?php echo form_error('order_validation','<div style="color:red">','</div>');?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                            </div>
                        </div>
                    </div>
            
            </section></form>
        </div>
        <div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('settings/order_settings');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Order Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Min Order Price<span class="required">*</span></label>
                            <div class="col-sm-9">
                               <input type="number" class="form-control" name="min_order_price" placeholder="Min Order Price" required="" min="1" value="<?php echo $this->vendor_settings_model->where('key', 'min_order_price')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Min Order Price ?</div>
                            <?php echo form_error('min_order_price','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Delivery Free Range (Km) <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="delivery_free_range" placeholder="Delivery Free Range (Km)" required="" min="0" value="<?php echo $this->vendor_settings_model->where('key','delivery_free_range')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Delivery Free Range (Km) ?</div>
                            <?php echo form_error('delivery_free_range','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Min Delivery Fee<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="min_delivery_fee" placeholder="Min Delivery Feee" required="" value="<?php echo $this->vendor_settings_model->where('key','min_delivery_fee')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Min Delivery Fee ?</div>
                            <?php echo form_error('min_delivery_fee','<div style="color:red" "margin_left=100px">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Extra Delivery Fee (per km)<span class="required">*</span></label>
                            <div class="col-sm-9">
                                 <input type="text" class="form-control" name="ext_delivery_fee" placeholder="Min Delivery Feee" required="" value="<?php echo $this->vendor_settings_model->where('key','ext_delivery_fee')->get()['value'];?>">
                            </div>
                            <div class="invalid-feedback">Extra Delivery Fee (per km) ?</div>
                            <?php echo form_error('ext_delivery_fee','<div style="color:red" "margin_left=100px">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Tax (in %)<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" required="" class="form-control" name="tax" placeholder="Tax" value="<?php echo $this->vendor_settings_model->where('key','tax')->get()['value'];?>" min="0">
                            </div>
                            <div class="invalid-feedback">Tax ?</div>
                            <?php echo form_error('tax','<div style="color:red" "margin_left=100px">','</div>');?>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                            </div>
                        </div>
                    </div>
            
            </section></form>
        </div>
        <!--      <div class="col-md-6">
            <form id="form-news" action="<?php echo base_url('settings/news');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">News Payment Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Pay per News<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="pay_per_news" class="form-control" placeholder="Pay Per News" required="" value="<?php echo $this->setting_model->where('key','pay_per_news')->get()['value']?>">
                            </div>
                            <div class="invalid-feedback">Pay Per Order?</div>
                            <?php echo form_error('pay_per_news','<div style="color:red">','</div>');?>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form-news')" value="Reset" />
                            </div>
                        </div>
                    </div>
            
            </section></form>
        </div> -->
    </div>
</div>
<style>
    #editor{
  padding: 0.4em 0.4em 0.4em 0;

}
</style>





