<div class="container">
    <div class="row">
        <div class="col-md-12" style="">
            <form id="form_site_settings" action="<?php echo base_url('vendor_profile/profile');?>" method="post" class="needs-validation reset"  enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Vendor Profile - <?=$vendor_details['unique_id'];?></h2>
                    </header>
                    <div class="card-body">
						
						<div class="form-group row">
                            <label class="col-sm-3 ">Shop Location<span class="required">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" name="location_name" id="location_name" class="form-control" placeholder="Locations Name" required="" value="<?php echo $vendor_details['location']['address']?>">
                            </div>
                            <div class="col-sm-3">
                            	<button type="button" class="btn btn-lg btn-warning" onclick="initialize()">Get Location</button>
                            </div>
                            <?php echo form_error('name','<div style="color:red">','</div>');?>
                                <input type="hidden" name="latitude" id="latitude" value="<?php echo $vendor_details['location']['latitude']?>">
                                <input type="hidden" name="longitude" id="logitude" value="<?php echo $vendor_details['location']['longitude']?>">
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 ">Listing name<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" placeholder="System Name" required="" value="<?php echo $vendor_details['name']?>">
                            </div>
                            <?php echo form_error('name','<div style="color:red">','</div>');?>
                                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                        </div>
                        <div class="form-group row">
                                <label class="col-sm-3 ">Email <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="email" class="form-control" placeholder="Email id" required="" value="<?php echo $vendor_details['email']?>" readonly>
                                </div>
                                <?php echo form_error('email','<div style="color:red">','</div>');?>
                            </div>
                        <!-- <div class="form-group row">
                            <label class="col-sm-3 ">State <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <select calss="form-control" name="state" class="form-control">
                                	<option value="0" selected disabled>--select--</option>
                                </select>
                            </div>
                            <?php //echo form_error('system_title','<div style="color:red">','</div>');?>
                        </div>
                       <div class="form-group row">
                            <label class="col-sm-3 ">District <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <select calss="form-control" name="district" class="form-control">
                                	<option value="0" selected disabled>--select--</option>
                                </select>
                            </div>
                            <?php //echo form_error('system_title','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Constituency <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <select calss="form-control" name="constituency" class="form-control">
                                	<option value="0" selected disabled>--select--</option>
                                </select>
                            </div>
                            <?php //echo form_error('system_title','<div style="color:red">','</div>');?>
                        </div> -->
                        <div class="form-group row">
                            <label class="col-sm-3 ">Address</label>
                            <div class="col-sm-9">
                                <textarea rows="5" cols="100" name="address" class="form-control" ><?php echo $vendor_details['address']?></textarea>
                            </div>
                            <?php echo form_error('address','<div style="color:red ">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Landmark</label>
                            <div class="col-sm-9">
                                <input type="text" name="landmark" class="form-control" placeholder="Land mark" value="<?php echo $vendor_details['landmark']?>">
                            </div>
                            <?php echo form_error('landmark','<div style="color:red">','</div>');?>
                        </div>
                        <?php $key = array_search(2, array_column($vendor_details['contacts'], 'type'));?>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Land line number</label>
                            <div class="col-sm-2">
                                <input type="text" name="landline_code" class="form-control" placeholder="Code" value="<?php echo ($key !== FALSE)?$vendor_details['contacts'][$key]['std_code']: '';?>">
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="landline" class="form-control" placeholder="Land Line" value="<?php echo ($key !== FALSE)?$vendor_details['contacts'][$key]['number']: '';?>">
                            </div>
                        </div>
                        <?php $key1 = array_search(1, array_column($vendor_details['contacts'], 'type'));?>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Mobile number</label>
                             <div class="col-sm-2">
                                <input type="text" name="mobile_code" class="form-control" placeholder="Code" value="<?php echo ($key1 !== FALSE)?$vendor_details['contacts'][$key1]['std_code']: '';?>">
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="<?php echo ($key1 !== FALSE)?$vendor_details['contacts'][$key1]['number']: '';?>">
                            </div>
                        </div>
                        <?php $key2 = array_search(4, array_column($vendor_details['contacts'], 'type'));?>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Help line number</label>
                             <div class="col-sm-2">
                                <input type="text" name="helpline_code" class="form-control" placeholder="Code" value="<?php echo ($key2 !== FALSE)?$vendor_details['contacts'][$key2]['std_code']: '';?>">
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="helpline" class="form-control" placeholder="Help line"  value="<?php echo ($key2 !== FALSE)?$vendor_details['contacts'][$key2]['number']: '';?>">
                            </div>
                        </div>
                        <?php $key3 = array_search(3, array_column($vendor_details['contacts'], 'type'));?>
                         <div class="form-group row">
                            <label class="col-sm-3 ">Whatsapp number</label>
                             <div class="col-sm-2">
                                <input type="text" name="whatsapp_code" class="form-control" placeholder="Code" value="<?php echo ($key3 !== FALSE)?$vendor_details['contacts'][$key3]['std_code']: '';?>">
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="whatsapp" class="form-control" placeholder="Whatsapp Number"  value="<?php echo ($key3 !== FALSE)?$vendor_details['contacts'][$key3]['number']: '';?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Vendor Availability</label>
                              <div class="form-group mb-0 col-md-4">
                        <div  class="form-control"> 
                        <label><input type="radio" name="restaurant_status" required="" value="1"  <?=($vendor_details['restaurant_status'] == 1)? 'checked' : '';?>> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="restaurant_status" required="" value="2" <?=($vendor_details['restaurant_status'] == 2)? 'checked' : '';?>> Not-Available</label>
                        </div>
                    </div>
                        </div>
                        <?php
                        if($this->ion_auth_acl->has_permission('admin') || $this->ion_auth_acl->has_permission('hr')){
                        ?>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Label<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="label" class="form-control" placeholder="Label" required="" value="<?php echo $vendor_details['label']?>">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-sm-3 ">Rating<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="rating" class="form-control" placeholder="Rating" required="" value="<?php echo $vendor_details['rating']?>" min="0" max="5">
                            </div>
                        </div>
                        <?php }else{?>
                        <input type="hidden" name="label" value="<?=$vendor_details['label'];?>">
                        <input type="hidden" name="rating" value="<?=$vendor_details['rating'];?>">
                    <?php }?>
                       
                        <div class="form-group row">
                        <label class="col-sm-3 ">Description</label> 
                        <div class="col-sm-9">
                        <textarea class="form-control ckeditor"  name="desc" data-sample-short placeholder="Product Details" required=""><?=$vendor_details['desc'];?></textarea>
                        <div class="invalid-feedback">Give some Description</div>
                        <?php echo form_error('desc','<div style="color:red">','</div>');?>
                        </div>
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
       </div>
       
        <div class="row">
            <div class="col-md-6">
                <form id="form_sms" action="<?php echo base_url('vendor_profile/filters');?>" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
                    <section class="card">
                        <header class="card-header">
                            <div class="card-actions">
                                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                            </div>
                            <h2 class="card-title ven">Filters</h2>
                        </header>
                        <div class="card-body">
    						<input type="hidden" name="vendor_user_id" value="<?php echo $vendor_details['vendor_user_id'];?>"/>
    						<input type="hidden" name="id" value="<?php echo $vendor_details['id'];?>"/>
                           <div class="form-group row">
                                <label class="col-sm-3 ">Categories <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" disabled name="categories" class="form-control">
                                    	<?php foreach ($categories as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo ($vendor_details['category_id'] == $val['id']) ? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('categories','<div style="color:red">','</div>');?>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 ">Sub categories <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_sub_categories" name="sub_categories[]" class="form-control" multiple>
                                    	<?php foreach ($sub_categories as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'sub_categories']) && is_array($vendor_details[ 'sub_categories']) && in_array($val[ 'id'],array_column($vendor_details[ 'sub_categories'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('sub_categories','<div style="color:red">','</div>');?>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 ">Brands <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_brands" name="brands[]" class="form-control" multiple>
                                    	<?php foreach ($brands as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'brands']) && is_array($vendor_details[ 'brands']) && in_array($val[ 'id'],array_column($vendor_details[ 'brands'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('sub_categories','<div style="color:red">','</div>');?>
                            </div>
                           <div class="form-group row">
                                <label class="col-sm-3 ">Amenities <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_amenities" name="amenities[]" class="form-control" multiple>
                                    	<?php foreach ($amenities as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'amenities']) && is_array($vendor_details[ 'amenities']) && in_array($val[ 'id'],array_column($vendor_details[ 'amenities'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('amenities','<div style="color:red">','</div>');?>
                            </div>
                             <?php
                        if($this->ion_auth_acl->has_permission('admin') || $this->ion_auth_acl->has_permission('hr')){
                        ?>
                            <div class="form-group row">
                                <label class="col-sm-3 ">Services <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_services" name="services[]" class="form-control" multiple>
                                    	<?php foreach ($services as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'services']) && is_array($vendor_details[ 'services']) && in_array($val[ 'id'],array_column($vendor_details[ 'services'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('services','<div style="color:red">','</div>');?>
                            </div>
                        <?php 

                    }else{

                        foreach ($vendor_details[ 'services'] as $key => $val){
                            //print_r($val['id']);
                        ?>
                        <input type="hidden" name="services[]" value="<?=$val['id'];?>">
                        <?php
                    }
                    }
                    ?>
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
            <div class="<?php echo ($this->ion_auth_acl->has_permission('hr') || $this->ion_auth_acl->has_permission('admin'))? 'col-md-6':'col-md-6';?>">
                <form id="form-smtp" action="<?php echo base_url('vendor_profile/social');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                    <section class="card">
                        <header class="card-header">
                            <div class="card-actions">
                                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                            </div>
                            <h2 class="card-title ven">Social</h2>
                        </header>
                        <?php $social_key = array_search(1, array_column($vendor_details['links'], 'type'));?>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 ">Facebook link<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="facebook" class="form-control" placeholder="Facebook Link" required="" value="<?php echo ($social_key !== FALSE)?$vendor_details['links'][$social_key]['url']: '';?>">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                                </div>
                                <?php echo form_error('facebook','<div style="color:red">','</div>');?>
                            </div>
                            <?php $social_key1 = array_search(2, array_column($vendor_details['links'], 'type'));?>
                            <div class="form-group row">
                                <label class="col-sm-3 ">Twitter link<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="twitter" class="form-control" placeholder="Twitter link" required="" value="<?php echo ($social_key1 !== FALSE)?$vendor_details['links'][$social_key1]['url']: '';?>">
                                </div>
                                <?php echo form_error('twitter','<div style="color:red">','</div>');?>
                            </div>
                            <?php $social_key2 = array_search(3, array_column($vendor_details['links'], 'type'));?>
                            <div class="form-group row">
                                <label class="col-sm-3 ">Instagram link<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="instagram" class="form-control" placeholder="Instagram link" required="" value="<?php echo ($social_key2 !== FALSE)?$vendor_details['links'][$social_key2]['url']: '';?>">
                                </div>
                                <?php echo form_error('instagram','<div style="color:red">','</div>');?>
                            </div>
                            <?php $social_key3 = array_search(4, array_column($vendor_details['links'], 'type'));?>
                            <div class="form-group row">
                                <label class="col-sm-3 ">Website link<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="website" class="form-control" placeholder="Website link" required="" value="<?php echo ($social_key3 !== FALSE)?$vendor_details['links'][$social_key3]['url']: '';?>">
                                </div>
                                <?php echo form_error('website','<div style="color:red">','</div>');?>
                            </div>
    
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <button class="btn btn-primary">Submit</button>
                                    <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                                </div>
                            </div>
                        </div>
                
                </section>
            </form>
            </div>
        </div>
<!--         <div class="row"> -->
<!--         	<div class="col-md-12"> 
                <form id="form-smtp" action="<?php //echo base_url('settings/payment');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
<!--                     <section class="card"> -->
<!--                         <header class="card-header"> -->
<!--                             <div class="card-actions"> -->
<!--                                 <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a> -->
<!--                                 <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a> -->
<!--                             </div> -->
<!--                             <h2 class="card-title">Open Hour & Holidays</h2> -->
<!--                         </header> -->
<!--                         <div class="card-body"> -->
<!--                             <div class="form-group row"> -->
<!--                                 <label class="col-sm-2 ">In-time<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-3"> 
                                    <input type="time" name="pay_per_vendor" class="form-control" placeholder="in-time" required="" value="<?php echo $this->setting_model->where('key','pay_per_vendor')->get()['value']?>">
<!--                                 </div> -->
<!--                                  <label class="col-sm-2">Out-time<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-3"> 
                                    <input type="time" name="pay_per_vendor" class="form-control" placeholder="out-time" required="" value="<?php echo $this->setting_model->where('key','pay_per_vendor')->get()['value']?>">
<!--                                 </div> -->
<!--                                 <button class="btn btn-primary">+</button> -->
<!--                                 <div class="invalid-feedback">Pay per vendor?</div> -->
<!--                             </div> -->
<!--                             <div class="form-group row"> -->
<!--                                 <label class="col-sm-3 ">Holidays<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-9"> -->
<!--                                     <input type="checkbox"  name="subscribe" value="newsletter"> -->
<!--         							<label for="subscribeNews">Sunday?</label> -->
<!--         							<input type="checkbox"  name="subscribe" value="newsletter"> -->
<!--         							<label for="subscribeNews">Monday?</label> -->
<!--         							<input type="checkbox"  name="subscribe" value="newsletter"> -->
<!--         							<label for="subscribeNews">Tuesday?</label> -->
<!--         							<input type="checkbox"  name="subscribe" value="newsletter"> -->
<!--         							<label for="subscribeNews">Wednseday?</label> -->
<!--                                 </div> -->
<!--                                 <div class="invalid-feedback">Vendor validation count?</div> -->
<!--                             </div> -->
<!--     						 <div class="form-group row"> -->
<!--                                 <label class="col-sm-2 ">In-time<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-3"> 
                                    <input type="time" name="pay_per_vendor" class="form-control" placeholder="in-time" required="" value="<?php echo $this->setting_model->where('key','pay_per_vendor')->get()['value']?>">
<!--                                 </div> -->
<!--                                  <label class="col-sm-2">Out-time<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-3"> 
                                    <input type="time" name="pay_per_vendor" class="form-control" placeholder="out-time" required="" value="<?php echo $this->setting_model->where('key','pay_per_vendor')->get()['value']?>">
<!--                                 </div> -->
<!--                                 <div class="invalid-feedback">Pay per vendor?</div> -->
<!--                             </div> -->
<!--                             <div class="row justify-content-end"> -->
<!--                                 <div class="col-sm-9"> -->
<!--                                     <button class="btn btn-primary">Next</button> -->
<!--                                 </div> -->
<!--                             </div> -->
<!--                         </div> -->
                
<!--                 </section></form> -->
<!--             </div> -->
<!--     </div> -->
    <div class="row">
    	<div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('vendor_profile/cover');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven ">Cover Image</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row col-md-12">
                        	<!-- <div class="col-md-6"> -->
                            	<label class="col-sm-6">Cover Image</label>
                                <input type='file' name="file" class="form-control" onchange="news_image(this);" />
                                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                                <div class="row ">
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary mt-4 pt-2">Submit</button>
                                        <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                                    </div>
                                </div>
                        	<!-- </div> -->
                        	<div class="col-md-6">
                        		<img id="" src="<?php echo base_url(); ?>uploads/list_cover_image/list_cover_<?php echo $_GET['id']?>.jpg?<?php echo time();?>" style="height: 200px;width: 300px;" alt="Logo" />
                        	</div>
                        </div>
                    </div>
            
            </section></form>
        </div>
    <!-- </div> -->
    
    <!-- <div class="row">
    	<div class="col-md-12"> -->
    		
<!-- <div class="container"> -->
    <!-- <div class="row justify-content-center"> -->
        <div class="col-md-6">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven"> Banners</h2>
                    </header>
                    <div class="card-body">
                         <form id="form_cover" action="<?php echo base_url('vendor_profile/banners');?>" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
                        <div class="row form-group col-md-6">
                            <!-- <div class="col-md-6"> -->
                            <label>Upload Image</label> 
                            <input type="file" name="banner" required=""  class="form-control" onchange="readURL(this);">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                            <img id="blah" src="#" alt=""> 
                            <!-- </div> -->
                            <div class="col-sm-6">
                                <button class="btn btn-primary mt-4 mt-2">Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr/>
                       
                    </div>
            
                </section>
        </div>

    <!-- </div> -->
<!-- </div> -->
</div>
<div class="row">
    <div class="col-md-12">
	<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Banners</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Image</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($banners)):?>
    							<?php $sno = 1; foreach ($banners as $banner):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td width="15%"><img
    										src="<?php echo base_url();?>uploads/list_banner_image/list_banner_<?php echo $banner['id'];?>.jpg?<?php echo time();?>"
    										width="50px"></td>
    									<td><a href="<?php echo base_url()?>vendor_profile/banner_edit?id=<?php echo $banner['id'];?>&list_id=<?php echo $_GET['id']?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
    									</a> <a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $banner['id'] ?>, 'vendor_profile')"> <i
    											class="far fa-trash-alt"></i>
    									</a></td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='6'><h3><center>No Banners</center></h3></th>
                            </tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
          </div>
        </div>
</div>
</div>
<style>
    #editor{
  padding: 0.4em 0.4em 0.4em 0;

}
</style>





