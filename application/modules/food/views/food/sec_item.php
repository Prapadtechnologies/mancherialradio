<?php
$this->load->view('food_scripts');
$cat_id=$this->vendor_list_model->where('vendor_user_id', $this->ion_auth->get_user_id())->get();
$vendor_category_id=$cat_id['category_id'];
?>
<!--Add Sub_Category And its list-->
<div class="row">
	<div class="col-12">
		<h4 class="ven"><?=(($this->ion_auth->is_admin())? 'Add Section Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_label'));?></h4>
		<form class="needs-validation" novalidate=""
			action="<?php echo base_url('food_section_item/c');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">

				<div class="form-row">
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_menu'));?></label>
						<select class="form-control" name="menu_id" required="" onchange="get_sub_item(this.value)">
							<option value="" selected disabled>--select--</option>
    							<?php foreach ($food_items as $item):?>
    								<option value="<?php echo $item['id'];?>"><?php echo $item['name']?></option>
    							<?php endforeach;?>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_menu'));?>?</div>
						<?php echo form_error('menu_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_item'));?></label>
						<select class="form-control" name="item_id" required="" id="sub_items" onchange="get_food_sections(this.value)">
							<option value="" selected disabled>--select--</option>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Sub Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_item'));?>?</div>
						<?php echo form_error('item_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Section' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_sec'));?></label>
						<select class="form-control" name="sec_id" required="" id="sections_list">
							<option value="" selected disabled>--select--</option>
						</select>
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Section Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_sec'));?>?</div>
						<?php echo form_error('sec_id','<div style="color:red>"','</div>');?>
					</div>
					<div class="form-group col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Section Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_name'));?></label> <input type="text" name="name"
							required="" value="<?php echo set_value('name')?>"
							class="form-control">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Section Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_name'));?>?</div>
						<?php echo form_error('name', '<div style="color:red">', '</div>');?>
					</div>
					<div class="form-group mb-0 col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_price'));?></label> <input type="number" class="form-control" name="price" required="" value="<?php echo set_value('price')?>">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_price'));?></div>
						<?php echo form_error('price','<div style="color:red">','</div>');?>
					</div>
					 <div class="form-group mb-0 col-md-4">
                        <label><?=(($this->ion_auth->is_admin())? 'Section Item Status' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_status'));?></label> 
                        <div  class="form-control"> 
                        <label><input type="radio" name="status" required="" value="1" checked=""> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="status" required="" value="2"> Not-Available</label>
                        </div>
                    </div>
					<div class="form-group mb-0 col-md-4">
						<label><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_desc'));?></label> <input type="text" class="form-control" name="desc" required="" value="<?php echo set_value('desc')?>">
						<div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'Give some Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_desc'));?></div>
						<?php echo form_error('desc','<div style="color:red">','</div>');?>
					</div>

					<div class="form-group col-md-12">
<br/>
						<button class="btn btn-primary mt-27 ">Submit</button>
					</div>


				</div>


			</div>
		</form>

		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of <?=(($this->ion_auth->is_admin())? 'Section Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_label'));?></h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th><?=(($this->ion_auth->is_admin())? 'Section Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_name'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_desc'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Price' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_price'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Section' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_sec'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Item' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_item'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'seci_menu'));?></th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($food_sec_items)):?> 
    							<?php $sno = 1; foreach ($food_sec_items as $food_sec_item):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php echo $food_sec_item['name'];?></td>
    									<td><?php echo $food_sec_item['desc'];?></td>
    									<td><?php echo $food_sec_item['price'];?></td>
    									<td><?php echo $food_sec_item['sec']['name'];?></td>
    									<td><?php echo $food_sec_item['item']['name'];?></td>
    									<td><?php echo $food_sec_item['menu']['name'];?></td>
    									<td><a href="<?php echo base_url()?>food_section_item/edit?id=<?php echo base64_encode(base64_encode($food_sec_item['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
    									</a> <a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $food_sec_item['id'] ?>, 'food_section_item')"> <i
    											class="far fa-trash-alt"></i>
    									</a></td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='6'><h3><center>No Data Found</center></h3></th></tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>


		</div>

	</div>
</div>

