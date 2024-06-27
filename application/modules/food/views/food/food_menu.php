<?php
$cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
$vendor_category_id=$cat_id['category_id'];
?>
<!--Add Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven"><?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_label'));?></h4>
		<form class="needs-validation" novalidate="" action="<?php echo base_url('food_menu/c');?>" method="post" enctype="multipart/form-data">
			<div class="card-header">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label>Sub Categories</label>
						<!-- <?php
						echo "<pre>";
						print_r($sub_categories);
						?> -->
						<select class="form-control" name="sub_cat_id" required=""  id="cars">
							<option value="" selected disabled>--select--</option>
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
    <option value="<?=$sl[$r]['id'];?>"><?=$sl[$r]['name'];?></option>
<?php }}?>
  </optgroup>
							<?php
						}
							}else{
							?>
    							<?php foreach ($sub_categories as $item):?>
    								<option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
    							<?php endforeach;?>
    						<?php }?>
						</select>
						<div class="invalid-feedback">New Sub Category?</div>
						<?php echo form_error('sub_cat_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-6">
						<label><?=(($this->ion_auth->is_admin())? 'Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_name'));?></label> <input type="text" name="name"
							required="" value="<?php echo set_value('name')?>"
							class="form-control">
							<input type="hidden" name="vendor_id" value="<?=$this->ion_auth->get_user_id();?>">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_name'));?>?</div>
						<?php echo form_error('name', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group mb-0 col-md-6">
						<label><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_desc'));?></label> <input type="text" name="desc"
							required="" value="<?php echo set_value('desc')?>"
							class="form-control">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give some Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_desc'));?></div>
						<?php echo form_error('desc', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group col-md-6">
						<label><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_image'));?></label> <input type="file" name="file"
							required="" value="<?php echo set_value('file')?>"
							class="form-control" onchange="readURL(this);"> <br> <img id="blah"
							src="#" alt="" style="width: 216px;">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Upload Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_image'));?>?</div>
						<?php echo form_error('file', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group col-md-2">
						<button type="submit" name="upload" id="upload" value="Apply"
							class="btn btn-primary mt-27 ">Submit</button>
					</div>
				</div>
			</div>
		</form>

		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of <?=(($this->ion_auth->is_admin())? 'Menus' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_label'));?></h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Sub Category</th>
									<th><?=(($this->ion_auth->is_admin())? 'Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_name'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_desc'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'menu_image'));?></th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($food_items)):?>
    							<?php  $sno = 1; foreach ($food_items as $food_item): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									<td><?=$food_item['subcat']['name']?></td>
									<td><?php echo $food_item['name'];?></td>
									<td><?php echo $food_item['desc'];?></td>
									<td><img
										src="<?php echo base_url();?>uploads/food_menu_image/food_menu_<?php echo $food_item['id'];?>.jpg"
										class="img-thumb"></td>
									<td><a
										href="<?php echo base_url()?>food_menu/edit?id=<?php echo base64_encode(base64_encode($food_item['id'])); ?>"
										class=" mr-2  " type="ecom_category"> <i class="fas fa-pencil-alt"></i>
									</a> <a href="#" class="mr-2  text-danger "
										onClick="delete_record(<?php echo $food_item['id'] ?>, 'food_menu')">
											<i class="far fa-trash-alt"></i>
									</a></td>

								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr>
									<th colspan='7'><h3>
											<center>No Data Found</center>
										</h3></th>
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
