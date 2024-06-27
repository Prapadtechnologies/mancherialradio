<?php
$this->load->view('food_scripts');
$cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
$vendor_category_id=$cat_id['category_id'];
$vegnonveg='';
if(!$this->ion_auth->is_admin()){
$vegnonveg= $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_veg_non_veg','field_status');
}
//echo $vendor_category_id;

?>
<!--Add Sub_Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven"><?=(($this->ion_auth->is_admin())? 'Add Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_label'));?></h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('food_item/c');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-row">
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?></label>
						<select class="form-control" name="menu_id" required="" >
							<option value="" selected disabled>--select--</option>
    							<?php foreach ($food_items as $item):?>
    								<option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
						<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?></label> <input type="text"
							class="form-control" name="name" required="" value="<?php echo set_value('name')?>">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?>?</div>
						<?php echo form_error('name','<div style="color:red">','</div>')?>
					</div>
					<div class="form-group mb-0 col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_price'));?></label> <input type="number" class="form-control" name="price" required="" value="<?php echo set_value('price')?>">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_price'));?></div>
						<?php echo form_error('price','<div style="color:red">','</div>');?>
					</div>

					<div class="form-group mb-0 col-md-2">
						<label><?=(($this->ion_auth->is_admin())? 'Quantity' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_quantity'));?></label> 
						<input type="number" class="form-control" name="quantity" required="" value="1" min="1">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give Atleast 1 Quantity' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_quantity'));?></div>
						<?php echo form_error('quantity','<div style="color:red">','</div>');?>
					</div>
					<?php
                    if($this->ion_auth->is_admin() || $vegnonveg == 1){
                    ?>
					<div class="form-group mb-0 col-md-3">
						<label><?=(($this->ion_auth->is_admin())? 'Veg / Non-Veg' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_veg_non_veg'));?></label> 
						<?php $veg=explode('/',(($this->ion_auth->is_admin())? 'Veg / Non-Veg' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_veg_non_veg')));?>
						<div  class="form-control"> 
						<label><input type="radio" name="item_type" required="" value="1" checked=""> <?=$veg[0];?> </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="item_type" required="" value="2"> <?=$veg[1];?></label>
						</div>
					</div>
					<?php }else{
						?>
						<input type="hidden" name="item_type" required="" value="1" checked="">
						<?php
					}?>
					<!-- <div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Section Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_sec_price'));?></label>
						<select class="form-control" name="menu_id" required="" >
							<option value="1" selected>Item Price Replace With Section Price</option>
							<option value="2">Section Price Add To Item Price</option>
							<option value="3">No Section Price</option>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
						<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
					</div> -->
					<div class="form-group mb-0 col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Item Status' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_status'));?></label> 
						<div  class="form-control"> 
						<label><input type="radio" name="status" required="" value="1" checked=""> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="status" required="" value="2"> Not-Available</label>
						</div>
					</div>
					<div class="form-group mb-0 col-md-2">
						<label> <?=(($this->ion_auth->is_admin())? 'Discount in' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_discount'));?>%</label> 
						<input type="number" class="form-control" name="discount" required="" value="0" min="0">
					</div>
					<!-- <?php
                    if($vendor_category_id == 6){
                    ?>
                      <div class="form-group mb-0 col-md-2">
                        <label>Experience<?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,2));?></label> 
                        <input type="number" class="form-control" name="exp" required="" value="<?php echo set_value('exp')?>" min="1">
                        <div class="invalid-feedback">Experience</div>
                        <?php echo form_error('exp','<div style="color:red">','</div>');?>
                    </div>
                      <div class="form-group mb-0 col-md-4">
                        <label>Qualification<?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,2));?></label> 
                        <input type="text" class="form-control" name="qualification" required="" value="<?php echo set_value('qualification')?>">
                        <div class="invalid-feedback">Enter Qualification</div>
                        <?php echo form_error('qualification','<div style="color:red">','</div>');?>
                    </div>
                <?php }?> -->
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?></label> 
						
						<input type="file" name="file" required="" value="<?php echo set_value('file')?>"
							class="form-control" onchange="readURL(this);">
						<img id="blah" src="#" alt=""> 
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?>?</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group mb-0 col-md-4">
						<label> Label</label> 
						<input type="text" class="form-control" name="label" value="" placeholder="Ex:ex: trending, hot deal, 50% off etc.">
					</div>
					<div class="form-group mb-0 col-md-4">
						<label>Short Description</label> 
						<textarea class="form-control"  name="short_desc" data-sample-short placeholder="Short Details" required=""><?php echo set_value('short_desc')?></textarea>
						<div class="invalid-feedback">Give some Description</div>
						<?php echo form_error('short_desc','<div style="color:red">','</div>');?>
					</div>
					<div class="form-group mb-0 col-md-12">
						<label><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></label> 
						<textarea class="form-control ckeditor"  name="desc" data-sample-short placeholder="Product Details" required=""><?php echo set_value('desc')?></textarea>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give some Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></div>
						<?php echo form_error('desc','<div style="color:red">','</div>');?>
					</div>
					<div class="form-group col-md-12 mt-4 pt-2">

						<button class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>


			</div>
		</form>

		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of <?=(($this->ion_auth->is_admin())? 'Items' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_label'));?></h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th><?=(($this->ion_auth->is_admin())? 'Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_price'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Discount' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_discount'));?> (%)</th>
									<?php
									 if($this->ion_auth->is_admin() || $vegnonveg == 1){
									 ?>
									<th><?=(($this->ion_auth->is_admin())? 'Veg / Non-Veg' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_veg_non_veg'));?></th>
								<?php }?>
									<th><?=(($this->ion_auth->is_admin())? 'Quantity' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_quantity'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Status' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_status'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?></th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($food_sub_items)):?> 
    							<?php $sno = 1; foreach ($food_sub_items as $food_sub_item):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $food_sub_item['name'];?></td>
    									<td><?php echo $food_sub_item['menu']['name'];?></td>
    									<td><?php echo $food_sub_item['desc'];?></td>
    									<td><?php echo $food_sub_item['price'];?></td>
    									<td><?php echo $food_sub_item['discount'];?></td>
    									<?php
									 if($this->ion_auth->is_admin() || $vegnonveg == 1){
									 ?>
    									<td><?=($food_sub_item['item_type']==1)? $veg[0] : $veg[1];?></td>
    								<?php }?>
    									<td><?php echo $food_sub_item['quantity'];?></td>
    									<td><?php echo ($food_sub_item['status']==1)? 'Available' : 'Not Available' ;?></td>
    									<td><img
    										src="<?php echo base_url();?>uploads/food_item_image/food_item_<?php echo $food_sub_item['id'];?>.jpg" class="img-thumb"></td>
    									<td><a href="<?php echo base_url()?>food_item/edit?id=<?php echo base64_encode(base64_encode($food_sub_item['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
    									</a>
    									<?php
    									if($this->ion_auth->get_user_id() == $food_sub_item['menu']['vendor_id']){
    									?> 
    									<a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $food_sub_item['id'] ?>, 'food_item')"> <i
    											class="far fa-trash-alt"></i>
    									</a>
    								<?php }else{?>
    									<a href="#" class="mr-2  text-danger " onClick="admin_item_delete_record(<?php echo $food_sub_item['id'] ?>, 'food_item')"> <i
    											class="far fa-trash-alt"></i>
    									</a>
    								<?php }?>
    								<?php
    									if($food_sub_item['approval_status'] == 2){
    										?>
    										<button class="btn-danger">Not-Approved</button>
    									<?php }elseif($food_sub_item['approval_status'] == 1){
    										?>
    										<button class="btn-success">Approved</button>
    										<?php
    									}?>
    								</td>
    
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
</div>

