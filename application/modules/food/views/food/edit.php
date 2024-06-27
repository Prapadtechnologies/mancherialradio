<?php
$this->load->view('food_scripts');
$cat_id=$cat_id['category_id']=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
$vendor_category_id=$cat_id['category_id'];
?>
<?php if($type == 'food_menu'){
    ?>


    <!--Edit Category -->
    <div class="row">
        <div class="col-12">
            <h4 class="ven"><?=(($this->ion_auth->is_admin())? 'Edit Food Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_label'));?></h4>
            <form class="needs-validation" novalidate=""  action="<?php echo base_url('food_menu/u');?>" method="post" enctype="multipart/form-data">
           <div class="card-header">
           <div class="form-row">
            <div class="form-group col-md-6">
                        <label>Sub Categories</label>
                        <select class="form-control" name="sub_cat_id" required="" >
                            <option value="" disabled>--select--</option>

                                <?php
                            if ($this->ion_auth->is_admin()){
                            for($l=0;$l<count($sub_categories);$l++){
                            ?>
                            <optgroup label="<?=$sub_categories[$l]['name'];?>">
    <?php
    $sl=$sub_categories[$l]['sub_categories'];
    if($sl != ''){
                            for($r=0;$r<count($sl);$r++){
    ?>
    <option value="<?=$sl[$r]['id'];?>"  <?php echo ($sl[$r]['id'] == $item['sub_cat_id'])? 'selected': '';?>><?=$sl[$r]['name'];?></option>
<?php }}?>
  </optgroup>
                            <?php
                        }
                            }else{
                            ?>
                                <?php foreach ($sub_categories as $s_item):?>
                                    <option value="<?php echo $s_item['id'];?>" <?php echo ($s_item['id'] == $item['sub_cat_id'])? 'selected': '';?>><?php echo $s_item['name']?></option>
                                <?php endforeach;?>

                            <?php }?>
                        </select>
                        <div class="invalid-feedback">New Sub Category?</div>
                        <?php echo form_error('sub_cat_id','<div style="color:red>"','</div>');?>
                    </div>
            <div class="form-group col-md-6">
                            <label><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_name'));?></label>
                            <input type="text" name="name" class="form-control" required="" value="<?php echo $item['name'];?>">
                            <input type="hidden" name="vendor_id" value="<?=$item['vendor_id'];?>">
                            <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Enter Valid Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_name'));?>?</div>
                        </div>
                         <input type="hidden" name="id" value="<?php echo $item['id'] ; ?>">
                         <div class="form-group mb-0 col-md-6">
                            <label><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_desc'));?></label>
                            <input type="text" name="desc" class="form-control" required="" value="<?php echo $item['desc'];?>">
                            <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give some Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_desc'));?></div>
                        </div>
                         <div class="form-group col-md-6">
                        <label><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_image'));?></label>
                        
                       <input type='file' name="file" onchange="news_image(this);" />
                       <?php echo form_error('file', '<div style="color:red">', '</div>');?>
                       <br>
                          <img id="blah" src="<?php echo base_url(); ?>uploads/food_menu_image/food_menu_<?php echo $item['id']; ?>.jpg" width="180" height="180" alt="your image" />
                      </div>

                         <div class="form-group col-md-12">
                         <button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 ">Update</button> 
                           
                        </div>
           
            </div>
            </div>
            </form>

        </div>
    </div>
    <?php }elseif ($type == 'food_item'){?>
      <!--sub_category Edit-->
        <div class="row">
            <div class="col-12">
                <h4 class="ven"><?=(($this->ion_auth->is_admin())? 'Edit Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_label'));?></h4>
                <form class="needs-validation" novalidate="" action="<?php echo base_url('food_item/u');?>" method="post" enctype="multipart/form-data">
                    <div class="card-header">

                        <div class="form-row">
                            <input type="hidden" name="id" value="<?php echo $sub_items['id'] ; ?>">
                            <div class="form-group col-md-4">
                                <label><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?></label>
                                <!-- <input type="file" class="form-control" required="">-->
                                <select class="form-control" name="menu_id" required="">
                                    <option value="0" selected disabled>select</option>
                                    <?php foreach ($items as $item):?>
                                      <option value="<?php echo $item['id'];?>" <?php echo ($item['id'] == $sub_items['menu_id'])? 'selected': '';?>><?php echo $item['name']?></option>
                                        <?php endforeach;?>
                                </select>
                                <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Select Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label><?=(($this->ion_auth->is_admin())? 'Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?></label>
                                <input type="text" class="form-control" name="name" required="" value="<?php echo $sub_items['name'];?>">
                                <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Enter valid  Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?>?</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_price'));?></label>
                                <input type="number" class="form-control" name="price" required="" value="<?php echo $sub_items['price'];?>">
                                <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Enter Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_price'));?>?</div>
                            </div>

                            <div class="form-group mb-0 col-md-2">
                        <label><?=(($this->ion_auth->is_admin())? 'Quantity' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_quantity'));?></label> 
                        <input type="number" class="form-control" name="quantity" required="" value="<?php echo $sub_items['quantity']?>" min="1">
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give Atleast 1 Quantity' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_quantity'));?></div>
                        <?php echo form_error('quantity','<div style="color:red">','</div>');?>
                    </div>
                   <?php
                    $vegnonveg=$this->category_model->get_cat_desc_account_name($vendor_category_id,'item_label','field_status');
                    if($vegnonveg == 1 || $this->ion_auth->is_admin()){
                    ?>
                    <div class="form-group mb-0 col-md-3">
                        <label><?=(($this->ion_auth->is_admin())? 'Veg / Non-Veg' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_veg_non_veg'));?></label> 
                        <?php $veg=explode('/',(($this->ion_auth->is_admin())? 'Veg / Non-Veg' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_veg_non_veg')));?>
                        <div  class="form-control"> 
                        <label><input type="radio" name="item_type" required="" value="1" <?=($sub_items['item_type'] == 1)? 'checked' : '';?>> <?=$veg[0];?> </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="item_type" required="" value="2" <?=($sub_items['item_type'] == 2)? 'checked' : '';?>> <?=$veg[1];?></label>
                        </div>
                    </div>
                    <?php }else{
                        ?>
                        <input type="hidden" name="item_type" required="" value="<?=$sub_items['item_type'];?>" checked="">
                        <?php
                    }?>
                    <div class="form-group mb-0 col-md-4">
                        <label><?=(($this->ion_auth->is_admin())? 'Item Status' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_status'));?></label> 
                        <div  class="form-control"> 
                        <label><input type="radio" name="status" required="" value="1"  <?=($sub_items['status'] == 1)? 'checked' : '';?>> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="status" required="" value="2" <?=($sub_items['status'] == 2)? 'checked' : '';?>> Not-Available</label>
                        </div>
                    </div>
                           <div class="form-group mb-0 col-md-2">
                        <label><?=(($this->ion_auth->is_admin())? 'Discount in' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_discount'));?>%</label> 
                        <input type="number" class="form-control" name="discount" required="" value="<?=$sub_items['discount'];?>" min="0">
                    </div>
<!-- 
                    <?php
                    if($vendor_category_id == 6){
                    ?>
                      <div class="form-group mb-0 col-md-2">
                        <label>Experience<?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,2));?></label> 
                        <input type="number" class="form-control" name="exp" required="" value="<?php echo $sub_items['exp']?>" min="1">
                        <div class="invalid-feedback">Experience</div>
                        <?php echo form_error('exp','<div style="color:red">','</div>');?>
                    </div>
                      <div class="form-group mb-0 col-md-2">
                        <label>Qualification</label> 
                        <input type="text" class="form-control" name="qualification" required="" value="<?php echo $sub_items['qualification']?>">
                        <div class="invalid-feedback">Enter Qualification</div>
                        <?php echo form_error('qualification','<div style="color:red">','</div>');?>
                    </div>
                <?php }?> -->

                            <div class="form-group col-md-4">
                                <label><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?></label>
                                <input type="file" name="file" class="form-control" onchange="readURL(this);" value="<?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $sub_items['id']; ?>.jpg">
                            <br><img src="<?php echo base_url(); ?>uploads/food_item_image/food_item_<?php echo $sub_items['id']; ?>.jpg" style="width: 200px;" />
                                
<!--                                 <input type="file" class="form-control" name="file"> -->
                                <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?>?</div>
                            </div>
                            <div class="form-group mb-0 col-md-4">
                        <label> Label</label> 
                        <input type="text" class="form-control" name="label" value="<?php echo $sub_items['label']?>" placeholder="Ex:ex: trending, hot deal, 50% off etc.">
                    </div>
                    <div class="form-group mb-0 col-md-4">
                        <label>Short Description</label> 
                        <textarea class="form-control"  name="short_desc" data-sample-short placeholder="Short Details" required=""><?php echo $sub_items['short_desc']?></textarea>
                        <div class="invalid-feedback">Give some Description</div>
                        <?php echo form_error('short_desc','<div style="color:red">','</div>');?>
                    </div>
<div class="form-group mb-0 col-md-12">
                                <label><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></label>
                                <textarea class="form-control ckeditor"  name="desc" data-sample-short placeholder="Product Details" required=""><?php echo $sub_items['desc']?></textarea>
                                <!-- <input type="text" class="form-control" name="desc" required="" value="<?php echo $sub_items['desc']?>"> -->
                                <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give some Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></div>
                            </div>
<!--                             <div class="form-group col-md-6"> -->
<!--                                 <img src="" width="80px"> -->
<!--                             </div> -->

                            <div class="form-group col-md-12 mt-4 pt-2">

                                <button class="btn btn-primary mt-27 ">Update</button>
                            </div>

                        </div>

                    </div>
                </form>

            </div>
        </div>
    <?php }elseif ($type == 'food_section'){?>
        <!--Edit Category -->
    <div class="row">
        <div class="col-12">
            <h4 class="ven"><?=(($this->ion_auth->is_admin())? 'Edit Section' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_label'));?></h4>
            <form class="needs-validation" novalidate=""  action="<?php echo base_url('food_section/u');?>" method="post" enctype="multipart/form-data">
           <div class="card-header">
           <div class="form-row">
            <div class="form-group col-md-3">
                        <label><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_menu'));?></label>
                        <select class="form-control" name="menu_id" required="" onchange="get_sub_item(this.value)">
                            <option value="" selected disabled>--select--</option>
                                <?php foreach ($food_items as $item):?>
                                    <option value="<?php echo $item['id'];?>" <?=($item['id'] == $section['menu_id'])? 'selected': '';?>><?php echo $item['name']?></option>
                                <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_menu'));?>?</div>
                        <?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
                    </div>
                    <div class="form-group col-md-3">
                        <label><?=(($this->ion_auth->is_admin())? 'Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_item'));?></label>
                        <select class="form-control" name="item_id" required="" id="sub_items">
                            <option value="" selected disabled>--select--</option>
                            <?php foreach ($food_sub_items as $subitem):?>
                                    <option value="<?php echo $subitem['id'];?>" <?=($subitem['id'] == $section['item_id'])? 'selected': '';?>><?php echo $subitem['name']?></option>
                                <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_item'));?>?</div>
                        <?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
                    </div>
                    <div class="form-group col-md-3">
                        <label><?=(($this->ion_auth->is_admin())? 'Section Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_name'));?></label> <input type="text" name="name"
                            required="" value="<?=$section['name'];?>"
                            class="form-control">
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Section Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_name'));?>?</div>
                        <?php echo form_error('name', '<div style="color:red">', '</div>');?>
                    </div>
                    <?php
                    $section_field=$this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_field','field_status');
$required=$this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_required','field_status');
                    if($section_field == 3){
                    ?>
                    <div class="form-group mb-0 col-md-3">
                        <label><?=(($this->ion_auth->is_admin())? 'Item Field' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_field'));?></label> 
                        <div class="form-control"> 
                        <label><input type="radio" name="item_field" required="" value="1"  <?=($section['item_field'] == 1)? 'checked' : '';?>  onclick="ch_sec_price('radio');"> Radio </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="item_field" required="" value="2" <?=($section['item_field'] == 2)? 'checked' : '';?>  onclick="ch_sec_price('check');"> Checkbox</label>
                        </div>
                    </div>
                    <?php }else{?>
                        <input type="hidden" name="item_field" required="" value="<?=$section['item_field'];?>">
                <?php }?>
                <div class="form-group mb-0 col-md-3" id="all_sec_price" style=" <?=($section['item_field'] == 2)? 'display: none;' : '';?> ">
                        <label><?=(($this->ion_auth->is_admin())? 'Section Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_price'));?></label> 
                        <div  class="form-control"> 
                        <label><input type="radio" name="sec_price" required="" value="1" <?=($section['sec_price'] == 1)? 'checked' : '';?> > Add </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="sec_price" required="" value="2" <?=($section['sec_price'] == 2)? 'checked' : '';?> > Replace </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="sec_price" required="" value="3"  <?=($section['sec_price'] == 3)? 'checked' : '';?> > No Price</label>
                        </div>
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Select any one' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_field'));?>?</div>
                        <?php echo form_error('item_field', '<div style="color:red">', '</div>');?>
                    </div>
                    <!-- <div id="check_sec_price" style=" <?=($section['item_field'] == 1)? 'display: none;' : '';?> ">
                        <input type="hidden" name="sec_price" required="" value="1">
                    </div> -->
                <?php
                    if($required == 2){
                    ?>
                    <div class="form-group col-md-6">
                        <label><input type="checkbox" name="require_items" value="1" <?=($section['required'] == 1)? 'checked': '';?>> <?=(($this->ion_auth->is_admin())? 'Required' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'sec_required'));?> ? </label>
                        <div class="invalid-feedback">Check Box?</div>
                        
                        <b>If checked, this section will require to fill.</b>
                    </div>
                    <?php }else{?>
                        <input type="hidden" name="require_items" required="" value="<?=$section['required'];?>">
                <?php }?>
                         <input type="hidden" name="id" value="<?php echo $section['id'] ; ?>" >
                        
                         <div class="form-group col-md-12">
                         <button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 ">Update</button> 
<!--                              <button class="btn btn-primary mt-27 ">Update</button> -->
                           
                        </div>
           
            </div>
            </div>
            </form>

        </div>
    </div>
  <?php }elseif ($type == 'food_sec_item'){?>
        <!--Edit Category -->
    <div class="row">
        <div class="col-12">
            <h4 class="ven"><?=(($this->ion_auth->is_admin())? 'Edit Section Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_label'));?></h4>
            <form class="needs-validation" novalidate=""  action="<?php echo base_url('food_section_item/u');?>" method="post" enctype="multipart/form-data">
           <div class="card-header">
           <div class="form-row">
            <div class="form-group col-md-4">
                        <label><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_menu'));?></label>
                        <select class="form-control" name="menu_id" required="" onchange="get_sub_item(this.value)">
                            <option value="" selected disabled>--select--</option>
                                <?php foreach ($food_items as $item):?>
                                    <option value="<?php echo $item['id'];?>" <?=($item['id'] == $section['menu_id'])? 'selected': '';?>><?php echo $item['name']?></option>
                                <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_menu'));?>?</div>
                        <?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
                    </div>
                    <div class="form-group col-md-4">
                        <label><?=(($this->ion_auth->is_admin())? 'Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_item'));?></label>
                        <select class="form-control" name="item_id" required="" id="sub_items">
                            <option value="" selected disabled>--select--</option>
                            <?php foreach ($sub_items as $subitem):?>
                                    <option value="<?php echo $subitem['id'];?>" <?=($subitem['id'] == $section['item_id'])? 'selected': '';?>><?php echo $subitem['name']?></option>
                                <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Sub Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_item'));?>?</div>
                        <?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
                    </div>
                    <div class="form-group col-md-4">
                        <label><?=(($this->ion_auth->is_admin())? 'Section' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_sec'));?></label>
                        <select class="form-control" name="sec_id" required="" id="sub_items">
                            <option value="" selected disabled>--select--</option>
                            <?php foreach ($sections as $sec):?>
                                    <option value="<?php echo $sec['id'];?>" <?=($sec['id'] == $sec_item['sec_id'])? 'selected': '';?>><?php echo $sec['name']?></option>
                                <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Section Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_sec'));?>?</div>
                        <?php echo form_error('sec_id','<div style="color:red>"','</div>');?>
                    </div>
                    <div class="form-group col-md-4">
                        <label><?=(($this->ion_auth->is_admin())? 'Section Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_name'));?></label> <input type="text" name="name"
                            required="" value="<?=$sec_item['name'];?>"
                            class="form-control">
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Section Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_name'));?>?</div>
                        <?php echo form_error('name', '<div style="color:red">', '</div>');?>
                    </div>
                    <div class="form-group mb-0 col-md-4">
                        <label><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_price'));?></label> <input type="number" class="form-control" name="price" required="" value="<?=$sec_item['price'];?>">
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_price'));?></div>
                        <?php echo form_error('price','<div style="color:red">','</div>');?>
                    </div>
                    <div class="form-group mb-0 col-md-4">
                        <label><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_desc'));?></label> <input type="text" class="form-control" name="desc" required="" value="<?=$sec_item['desc'];?>">
                        <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give some Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_desc'));?></div>
                        <?php echo form_error('desc','<div style="color:red">','</div>');?>
                    </div>
                    <div class="form-group mb-0 col-md-3">
                        <label><?=(($this->ion_auth->is_admin())? 'Section Item Status' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_status'));?></label> 
                        <div  class="form-control"> 
                        <label><input type="radio" name="status" required="" value="1"  <?=($sec_item['status'] == 1)? 'checked' : '';?>> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="status" required="" value="2" <?=($sec_item['status'] == 2)? 'checked' : '';?>> Not-Available</label>
                        </div>
                    </div>
            <input type="hidden" name="id" value="<?php echo $sec_item['id'] ; ?>" >
                        
                         <div class="form-group col-md-12 mt-4 pt-2">
                         <button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 ">Update</button> 
<!--                              <button class="btn btn-primary mt-27 ">Update</button> -->
                           
                        </div>
           
            </div>
            </div>
            </form>

        </div>
    </div>
  <?php }?>

                        
                    

<script type="text/javascript">
    function ch_sec_price(promo_type) {
        if(promo_type == 'radio'){
            $('#all_sec_price').show();
            //$('#check_sec_price').hide();
        }else if(promo_type == 'check'){
            $('#all_sec_price').hide();
            //$('#check_sec_price').show();
        }
    }
</script>