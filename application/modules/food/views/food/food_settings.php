
<div class="row">
	<div class="col-12">
		<form class="needs-validation" novalidate="" action="<?=base_url('food_settings/u');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">
				<h4 class="ven">Settings</h4>
				<div class="form-row">
					<!-- <div class="form-group col-md-4">
						<label for="field-1" class="control-label">Min Order Price</label>
                    <input type="number" class="form-control" name="min_order_price" placeholder="Min Order Price" required="" min="1" value="<?=$food_settings['min_order_price'];?>">
					</div> -->
					<!-- <div class="form-group col-md-4">
						 <label for="field-1" class=" control-label">Delivery Free Range (Km)</label>
                    <input type="number" class="form-control" name="delivery_free_range" placeholder="Delivery Free Range (Km)" required="" min="0" value="<?=$food_settings['delivery_free_range'];?>">
					</div> -->
					<div class="form-group col-md-6">
						 <label for="field-1" class="control-label">Preparation Time (in Minutes)</label>
                    <input type="number" class="form-control" name="preparation_time" placeholder="Preparation Time (in Minutes)" required="" min="20" value="<?=$food_settings['preparation_time'];?>">
					</div>
					<!-- <div class="form-group col-md-4">
						<label for="field-1" class="control-label">Min Delivery Fee</label>
                    <input type="text" class="form-control" name="min_delivery_fee" placeholder="Min Delivery Feee" required="" value="<?=$food_settings['min_delivery_fee'];?>">
					</div> -->
					<!-- <div class="form-group col-md-4">
						<label for="field-1" class="control-label">Extra Delivery Fee (per km)</label>
                    <input type="text" class="form-control" name="ext_delivery_fee" placeholder="Min Delivery Feee" required="" value="<?=$food_settings['ext_delivery_fee'];?>">
					</div> -->
					<div class="form-group col-md-6">
						<label>Restaurant Status</label> 
						<div  class="form-control"> 
                        <label><input type="radio" name="restaurant_status" required="" value="1"  <?=($food_settings['restaurant_status'] == 1)? 'checked' : '';?>> Available </label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="restaurant_status" required="" value="2" <?=($food_settings['restaurant_status'] == 2)? 'checked' : '';?>> Not-Available</label>
                        </div>
					</div>

<!-- 
					<div class="form-group col-md-4">
						 <label for="field-1" class="control-label">Day</label>
                    <input type="text" class="form-control" name="day[]" placeholder="Day" value="Monday" readonly="">
					</div>
					<div class="form-group col-md-4">
						 <label for="field-1" class="control-label">Opening Time</label>
              <input type="time" class="form-control" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time" id="m_ope">
					</div>
					<div class="form-group col-md-4">
						<label for="field-1" class="control-label">Closing Time</label>
                    <input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time" id="m_clo">
					</div>
					


					<div class="form-group col-md-4">
						<input type="text" class="form-control" name="day[]" placeholder="Day" value="Tuesday" readonly="">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time">
					</div>

					<div class="form-group col-md-4">
						<input type="text" class="form-control" name="day[]" placeholder="Day" value="Wednesday" readonly="">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time">
					</div>


					<div class="form-group col-md-4">
						<input type="text" class="form-control" name="day[]" placeholder="Day" value="Thursday" readonly="">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time">
					</div>
<div class="form-group col-md-4">
						<input type="text" class="form-control" name="day[]" placeholder="Day" value="Friday" readonly="">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time">
					</div>
<div class="form-group col-md-4">
						<input type="text" class="form-control" name="day[]" placeholder="Day" value="Saturday" readonly="">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time">
					</div>
<div class="form-group col-md-4">
						<input type="text" class="form-control" name="day[]" placeholder="Day" value="Sunday" readonly="">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time">
					</div>
					<div class="form-group col-md-4">
						<input type="time" class="form-control " data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time">
					</div> -->


						
					</div>
					<div class="form-group col-md-12">
						<button class="btn btn-primary mt-27 ">Update</button>
					</div>
				</div>
			
		</form>
	</div>
</div>
<br/><br/>

<!-- <div class="row">
	<div class="col-12">
		<form class="needs-validation" novalidate="" action="<?=base_url('vendor_bank_details/u');?>" method="post"
			enctype="multipart/form-data">
			<div class="card-header">
				<h4>Bank Details</h4>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="field-1" class="control-label">Bank Name</label>
                    <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" required="" value="<?=$bank_details['bank_name'];?>">
					</div>
					<div class="form-group col-md-4">
						<label for="field-1" class="control-label">Bank Branch</label>
                    <input type="text" class="form-control" name="bank_branch" placeholder="Bank Branch" required="" value="<?=$bank_details['bank_branch'];?>">
					</div>
					<div class="form-group col-md-4">
						<label for="field-1" class="control-label">IFSC Code</label>
                    <input type="text" class="form-control" name="ifsc" placeholder="IFSC" required="" value="<?=$bank_details['ifsc'];?>">
					</div>
					<div class="form-group col-md-4">
						 <label for="field-1" class=" control-label">Account Holder Name</label>
                    <input type="text" class="form-control" name="ac_holder_name" placeholder="Account Holder Name" required="" value="<?=$bank_details['ac_holder_name'];?>">
					</div>
					<div class="form-group col-md-4">
						 <label for="field-1" class="control-label">Account Number</label>
                    <input type="number" class="form-control" name="ac_number" placeholder="Account Number" required="" value="<?=$bank_details['ac_number'];?>">
					</div>						
					</div>
					<div class="form-group col-md-12">
						<button class="btn btn-primary mt-27 ">Update</button>
					</div>
				</div>
			
		</form>
	</div>
</div>
 -->
