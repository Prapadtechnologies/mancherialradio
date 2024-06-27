
<div class="row">
	<div class="col-12">
		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Products Approve</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Id</th>
									<th>Vendor</th>
									<th><?=(($this->ion_auth->is_admin())? 'Item Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_name'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Menu' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?></th>
									<th><?=(($this->ion_auth->is_admin())? 'Description' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_desc'));?></th>
									<th>Date</th>
									<th><?=(($this->ion_auth->is_admin())? 'Image' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_image'));?></th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($food_sub_items)):?> 
    							<?php $sno = 1; foreach ($food_sub_items as $food_sub_item):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td><?php
    									$vendor=$this->vendor_list_model->where('vendor_user_id', $food_sub_item['created_user_id'])->get();
    									if($vendor != ''){
    									echo "<b>Name:</b> ". $vendor['name']."<br/><b>ID:</b> ".$vendor['unique_id']."<br/><b>Address:</b> ".$vendor['address'];
    									}else{
    									    echo "Admin";
    									}
    									?></td>
    									<td><?php echo $food_sub_item['name'];?></td>
    									<td><?php echo $food_sub_item['menu']['name'];?></td>
    									<td><?php echo $food_sub_item['desc'];?></td>
    									<td><?php  if($food_sub_item['updated_at'] != ''){echo $food_sub_item['updated_at'];}else{echo $food_sub_item['created_at'];}?></td>
    									<td><img
    										src="<?php echo base_url();?>uploads/food_item_image/food_item_<?php echo $food_sub_item['id'];?>.jpg" class="img-thumb"></td>
    									<td>
    										<?php
    										if($this->ion_auth->is_admin()){?>
    										<a href="<?php echo base_url()?>products_approve/approve?id=<?php echo base64_encode(base64_encode($food_sub_item['id']));?>" class="btn btn-success"  >Approve
    										</a> 
    									<?php }
    									?>
    									
    										<a href="<?php echo base_url()?>food_item/edit?id=<?php echo base64_encode(base64_encode($food_sub_item['id']));?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
    									</a> <a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $food_sub_item['id'] ?>, 'food_item')"> <i
    											class="far fa-trash-alt"></i>
    									</a></td>
    
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

