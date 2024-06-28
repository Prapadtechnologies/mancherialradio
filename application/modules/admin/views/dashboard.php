<!-- <a href="<?=base_url('accounting/payment/index/payumoney');?>" target="_blank">payumoney</a><br/>
<a href="<?=base_url('accounting/payment/index/paypal');?>" target="_blank">paypal</a><br/>
<a href="<?=base_url('accounting/payment/index/ccavenue');?>" target="_blank">ccavenue</a><br/>
<a href="<?=base_url('accounting/payment/index/paytm');?>" target="_blank">paytm</a><br/>
 -->


<?php
// You would supply real latitude and longitude values here.
// I used a location in the southwestern United States.
/*$lat      = '17.4468978';
$lng      = '78.3788169';
$distance = 10; // Kilometers*/



/*$query = $this->db->query(
    '
    SELECT 
        id,
        6371 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(? - ABS(locations.latitude))), 2) + COS(RADIANS(?)) * COS(RADIANS(ABS(locations.latitude))) * POWER(SIN(RADIANS(? - locations.longitude)), 2))) AS distance
    FROM locations
    HAVING distance < ?
    ', 
    [
        $lat,
        $lat,
        $lng,
        $distance
    ] 
);
echo "<pre>";
echo $this->db->last_query();
$local_users = $query->num_rows() > 0
    ? $query->result()
    : NULL;


    print_r($local_users);
    $first_names = array_column($local_users, 'id');
print_r($first_names);


$ids = join("','",$first_names);   
$sql = "SELECT * FROM users WHERE location_id IN ('$ids')";
echo $sql;*/
?>
<?php if($this->ion_auth_acl->has_permission('admin') || $this->ion_auth_acl->has_permission('hr')){?>
<div class="row">
						<div class="col-xl-3  col-md-4 col-sm-4">
							<a href="<?php echo base_url('emp_list/executive');?>">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Executives</h6>
										</div>
										<!-- <i class="fas fa-address-card card-icon col-orange font-30 p-r-30"></i> -->
									</div>
									<!-- <canvas id="cardChart1" height="80"></canvas> -->
									<br/>
    									<div class="alert alert-sm alert-primary "><center><i class="fas fa-user-circle card-icon font-20 p-r-30">  <?php echo $this->db->query('SELECT COUNT(*) AS `no_of_executives` FROM `users` as u LEFT JOIN users_groups as ug ON u.id = ug.user_id WHERE ug.group_id = (SELECT id FROM `groups` WHERE name = "executive")')->row()->no_of_executives;?></i></center></div>
								</div>
							</div>
						</div>
						<div class="col-xl-3  col-md-4 col-sm-4">
							<a href="<?php echo base_url('wallet');?>">
    							<div class="card">
    								<div class="card-bg">
    									<div class="p-t-20 d-flex justify-content-between">
    										<div class="col">
    											<h6 class="mb-0">Wallet</h6>
    											<span class="font-weight-bold mb-0 font-20"></span>
    										</div>
    									</div>
    									<!-- <canvas id="cardChart4" height="80"></canvas> -->
    									<br/>
    									<div class="alert alert-sm alert-primary "><center><i class="fas fa-hand-holding-usd card-icon font-20 p-r-30">  <?php echo $this->db->where('id', $this->ion_auth->get_user_id())->get('users')->row()->wallet;?>₹</i></center></div>
    								</div>
    							</div>
							</a>
						</div>
						 <div class="col-xl-3  col-md-4 col-sm-4">
						 <a href="<?php echo base_url('admin/master/vendors_filter/0');?>">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Vendors</h6>
										</div>
									</div>
									<br/>
    									<div class="alert alert-sm alert-primary "><center><b><i class="fas fa-check-circle card-icon font-20 p-r-30 "  title="Active Vendors" > <?php echo $this->db->query('SELECT COUNT(*) AS active FROM vendors_list WHERE status=1')->row()->active;?></i></b></<br><b><i class="fas fa-times-circle card-icon font-20 p-r-30" title="Inactive Vendors"> <?php echo $this->db->query('SELECT COUNT(*) AS inactive FROM vendors_list WHERE status=2')->row()->inactive;?></i></b></center></div>
								
</div>
							</div>
						</div>
						<div class="col-xl-3  col-md-4 col-sm-4">
							<a href="<?php echo base_url('food_orders/r');?>">
    							<div class="card">
    								<div class="card-bg">
    									<div class="p-t-20 d-flex justify-content-between">
    										<div class="col">
    											<h6 class="mb-0">Overall sales</h6>
    											<span class="font-weight-bold mb-0 font-20"></span>
    										</div>
    									</div>
    									<!-- <canvas id="cardChart4" height="80"></canvas> -->
    									<br/>
    									<div class="alert alert-sm alert-primary "><center><i class="fas fa-chart-bar card-icon font-20 p-r-30">  <?php echo (($this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE `order_status` = 6')->row()->total)? $this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE `order_status` = 6')->row()->total : 0);?>₹</i></center></div>
    								</div>
    							</div>
							</a>
						</div>
						<div class="col-xl-3  col-md-4 col-sm-4">
							<a href="<?php echo base_url('food_orders/r');?>">
    							<div class="card">
    								<div class="card-bg">
    									<div class="p-t-20 d-flex justify-content-between">
    										<div class="col">
    											<h6 class="mb-0">Today sales</h6>
    											<span class="font-weight-bold mb-0 font-20"></span>
    										</div>
    									</div>
    									<!-- <canvas id="cardChart4" height="80"></canvas> -->
    									<br/>
    									<div class="alert alert-sm alert-primary "><center><i class="fas fa-chart-bar card-icon font-20 p-r-30">  <?php echo ($this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE CURRENT_DATE = DATE(created_at) AND order_status = 6')->row()->total)? $this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE CURRENT_DATE = DATE(created_at) AND order_status = 6')->row()->total: 0;?>₹</i></center></div>
    								</div>
    							</div>
							</a>
						</div>
<?php }else{?>
	<center><h1>Welcome to Nextclick</h1></center>
	<br/><br/>
	<div class="row">
	
	<div class="col-xl-3  col-md-4 col-sm-4">
							<a href="<?php echo base_url('food_orders/r');?>">
    							<div class="card">
    								<div class="card-bg">
    									<div class="p-t-20 d-flex justify-content-between">
    										<div class="col">
    											<h6 class="mb-0">Overall sales</h6>
    											<span class="font-weight-bold mb-0 font-20"></span>
    										</div>
    									</div>
    									<!-- <canvas id="cardChart4" height="80"></canvas> -->
    									<br/>
    									<div class="alert alert-sm alert-primary "><center><i class="fas fa-chart-bar card-icon font-20 p-r-30">  <?php echo (($this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE `order_status` = 6 AND `vendor_id` = '.$this->ion_auth->get_user_id())->row()->total)? $this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE `order_status` = 6 AND `vendor_id` = '.$this->ion_auth->get_user_id())->row()->total : 0);?>₹</i></center></div>
    								</div>
    							</div>
							</a>
						</div>
						<div class="col-xl-3  col-md-4 col-sm-4">
							<a href="<?php echo base_url('food_orders/r');?>">
    							<div class="card">
    								<div class="card-bg">
    									<div class="p-t-20 d-flex justify-content-between">
    										<div class="col">
    											<h6 class="mb-0">Today sales</h6>
    											<span class="font-weight-bold mb-0 font-20"></span>
    										</div>
    									</div>
    									<!-- <canvas id="cardChart4" height="80"></canvas> -->
    									<br/>
    									<div class="alert alert-sm alert-primary "><center><i class="fas fa-chart-bar card-icon font-20 p-r-30">  <?php echo ($this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE CURRENT_DATE = DATE(created_at) AND order_status = 6 AND `vendor_id` = '.$this->ion_auth->get_user_id())->row()->total)? $this->db->query('SELECT SUM(total) AS total FROM `food_orders` WHERE CURRENT_DATE = DATE(created_at) AND order_status = 6 AND `vendor_id` = '.$this->ion_auth->get_user_id())->row()->total: 0;?>₹</i></center></div>
    								</div>
    							</div>
							</a>
						</div>
	</div>
<?php }?>
						<!-- <div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Users</h6>
											<span class="font-weight-bold mb-0 font-20">2,687</span>
										</div>
										<i class="fas fa-hand-holding-usd card-icon col-cyan font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart4" height="80"></canvas>
								</div>
							</div>
						</div>
                        <div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Delivery boys</h6>
											<span class="font-weight-bold mb-0 font-20">622</span>
										</div>
										<i class="fas fa-chart-bar card-icon col-indigo font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart5" height="80"></canvas>
								</div>
							</div>
						</div>
						<!--<div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Accountants</h6>
											<span class="font-weight-bold mb-0 font-20">687</span>
										</div>
										<i class="fas fa-hand-holding-usd card-icon col-cyan font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart6" height="80"></canvas>
								</div>
							</div>
						</div>
                        
                        <div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Accountants</h6>
											<span class="font-weight-bold mb-0 font-20">62</span>
										</div>
										<i class="fas fa-address-card card-icon col-orange font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart6" height="80"></canvas>
								</div>
							</div>
						</div>
						<!-- <div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">HR's</h6>
											<span class="font-weight-bold mb-0 font-20">895</span>
										</div>
										<i class="fas fa-diagnoses card-icon col-green font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart7" height="80"></canvas>
								</div>
							</div>
						</div>
						<div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Controlers</h6>
											<span class="font-weight-bold mb-0 font-20">22</span>
										</div>
										<i class="fas fa-chart-bar card-icon col-indigo font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart8" height="80"></canvas>
								</div>
							</div>
						</div>
						<div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Co-ordinators</h6>
											<span class="font-weight-bold mb-0 font-20">2,687</span>
										</div>
										<i class="fas fa-hand-holding-usd card-icon col-cyan font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart9" height="80"></canvas>
								</div>
							</div>
						</div>
                        <div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Zonal Heads</h6>
											<span class="font-weight-bold mb-0 font-20">622</span>
										</div>
										<i class="fas fa-chart-bar card-icon col-indigo font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart10" height="80"></canvas>
								</div>
							</div>
						</div>
                        <div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">CEO's</h6>
											<span class="font-weight-bold mb-0 font-20">2,687</span>
										</div>
										<i class="fas fa-hand-holding-usd card-icon col-cyan font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart11" height="80"></canvas>
								</div>
							</div>
						</div>
                        <div class="col-xl-3  col-md-4 col-sm-4">
							<div class="card">
								<div class="card-bg">
									<div class="p-t-20 d-flex justify-content-between">
										<div class="col">
											<h6 class="mb-0">Managing Directors</h6>
											<span class="font-weight-bold mb-0 font-20">622</span>
										</div>
										<i class="fas fa-chart-bar card-icon col-indigo font-30 p-r-30"></i>
									</div>
									<canvas id="cardChart12" height="80"></canvas>
								</div>
							</div>
						</div>
                        
                        
					</div>
					
                    
                    <div class="row">
						<div class="col-xl-8 col-md-12 col-lg-8">
							<div class="card">
								<div class="card-header">
									<h4>Revenue Chart</h4>
								</div>
								<div class="card-body">
									<ul class="list-inline text-center">
										<li class="list-inline-item p-r-30"><i data-feather="arrow-up-circle"
												class="col-green"></i>
											<h5 class="m-b-0">$675</h5>
											<p class="text-muted font-14 m-b-0">Weekly Earnings</p>
										</li>
										<li class="list-inline-item p-r-30"><i data-feather="arrow-down-circle"
												class="col-orange"></i>
											<h5 class="m-b-0">$1,587</h5>
											<p class="text-muted font-14 m-b-0">Monthly Earnings</p>
										</li>
										<li class="list-inline-item p-r-30"><i data-feather="arrow-up-circle"
												class="col-green"></i>
											<h5 class="mb-0 m-b-0">$45,965</h5>
											<p class="text-muted font-14 m-b-0">Yearly Earnings</p>
										</li>
									</ul>
									<div id="myChart"></div>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-md-12 col-lg-4">
							<div class="card l-bg-orange">
								<div class="card-body">
									<div class="text-white">
										<div class="row">
											<div class="col-md-6 col-lg-5">
												<h4 class="mb-0 font-26">$1,235</h4>
												<p class="mb-2">Avg Sales Per Month</p>
												<p class="mb-0">
													<span class="font-20">+11.25% </span>Increase
												</p>
											</div>
											<div class="col-md-6 col-lg-7">
												<div class="sparkline-bar p-t-50"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card l-bg-cyan">
								<div class="card-body">
									<div class="text-white">
										<div class="row">
											<div class="col-md-6 col-lg-5">
												<h4 class="mb-0 font-26">758</h4>
												<p class="mb-2">Avg new Cust Per Month</p>
												<p class="mb-0">
													<span class="font-20">+25.11%</span> Increase
												</p>
											</div>
											<div class="col-md-6 col-lg-7">
												<div class="sparkline-line p-t-50"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-4 col-md-12 col-12 col-sm-12">
							<div class="card mt-sm-5 mt-md-0">
								<div class="card-header">
									<h4>Messages</h4>
								</div>
								<div class="card-body">
									<ul class="list-unstyled list-unstyled-border user-list" id="message-list">
										<li class="media"><img alt="image" src="<?php echo base_url()?>assets/img/users/user-1.png"
												class="mr-3 user-img-radious-style user-list-img">
											<div class="media-body">
												<div class="mt-0 font-weight-bold">Cara Stevens</div>
												<div class="text-small">Hello How R You?</div>
											</div>
										</li>
										<li class="media"><img alt="image" src="<?php echo base_url()?>assets/img/users/user-4.png"
												class="mr-3 user-img-radious-style user-list-img">
											<div class="media-body">
												<div class="mt-0 font-weight-bold">Airi Satou</div>
												<div class="text-small">Please email me regarding this</div>
											</div>
										</li>
										<li class="media"><img alt="image" src="<?php echo base_url()?>assets/img/users/user-7.png"
												class="mr-3 user-img-radious-style user-list-img">
											<div class="media-body">
												<div class="mt-0 font-weight-bold">Angelica Ramos</div>
												<div class="text-small">Will You marry me??</div>
											</div>
										</li>
										<li class="media"><img alt="image" src="<?php echo base_url()?>assets/img/users/user-2.png"
												class="mr-3 user-img-radious-style user-list-img">
											<div class="media-body">
												<div class="mt-0 font-weight-bold">Ashton Cox</div>
												<div class="text-small">Can't talk!!!</div>
											</div>
										</li>
										<li class="media"><img alt="image" src="<?php echo base_url()?>assets/img/users/user-3.png"
												class="mr-3 user-img-radious-style user-list-img">
											<div class="media-body">
												<div class="mt-0 font-weight-bold">Cara Stevens</div>
												<div class="text-small">Request for leave application</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 col-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<h4>Revenue</h4>
								</div>
								<div class="card-body">
									<h3 class="card-title">
										<i class="fas fa-dollar-sign col-green font-30 p-b-10"></i>
										763,215
									</h3>
									<canvas id="myChart3" height="170"></canvas>
									<div class="row p-t-20">
										<div class="col-6">
											<p class="text-muted font-15 text-truncate m-b-5">Target</p>
											<h5>
												<i class="fas fa-arrow-circle-up col-green m-r-5"></i>$15.3k
											</h5>
										</div>
										<div class="col-6">
											<p class="text-muted text-truncate m-b-5">Last Month</p>
											<h5>
												<i class="fas fa-arrow-circle-up col-green m-r-5"></i>$12.5k
											</h5>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 col-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<h4>TODO</h4>
								</div>
								<div class="card-body">
									<div class="tdl-content">
										<ul class="to-do-list ui-sortable">
											<li class="clearfix">
												<div class="form-check m-l-10">
													<label class="form-check-label"> <input class="form-check-input"
															type="checkbox" value="">
														Add salary details in system <span class="form-check-sign">
															<span class="check"></span>
														</span>
													</label>
												</div>
												<div class="todo-actionlist pull-right clearfix">
													<a href="#"> <i class="material-icons">clear</i>
													</a>
												</div>
											</li>
											<li class="clearfix">
												<div class="form-check m-l-10">
													<label class="form-check-label"> <input class="form-check-input"
															type="checkbox" value="">
														Announcement for holiday <span class="form-check-sign">
															<span class="check"></span>
														</span>
													</label>
												</div>
												<div class="todo-actionlist pull-right clearfix">
													<a href="#"> <i class="material-icons">clear</i>
													</a>
												</div>
											</li>
											<li class="clearfix">
												<div class="form-check m-l-10">
													<label class="form-check-label"> <input class="form-check-input"
															type="checkbox" value="">
														call bus driver <span class="form-check-sign"> <span
																class="check"></span>
														</span>
													</label>
												</div>
												<div class="todo-actionlist pull-right clearfix">
													<a href="#"> <i class="material-icons">clear</i>
													</a>
												</div>
											</li>
											<li class="clearfix">
												<div class="form-check m-l-10">
													<label class="form-check-label"> <input class="form-check-input"
															type="checkbox" value="">
														Office Picnic <span class="form-check-sign"> <span
																class="check"></span>
														</span>
													</label>
												</div>
												<div class="todo-actionlist pull-right clearfix">
													<a href="#"> <i class="material-icons">clear</i>
													</a>
												</div>
											</li>
											<li class="clearfix">
												<div class="form-check m-l-10">
													<label class="form-check-label"> <input class="form-check-input"
															type="checkbox" value="">
														Website Must Be Finished <span class="form-check-sign">
															<span class="check"></span>
														</span>
													</label>
												</div>
												<div class="todo-actionlist pull-right clearfix">
													<a href="#"> <i class="material-icons">clear</i>
													</a>
												</div>
											</li>
											<li class="clearfix">
												<div class="form-check m-l-10">
													<label class="form-check-label"> <input class="form-check-input"
															type="checkbox" value="">
														Recharge My Mobile <span class="form-check-sign"> <span
																class="check"></span>
														</span>
													</label>
												</div>
												<div class="todo-actionlist pull-right clearfix">
													<a href="#"> <i class="material-icons">clear</i>
													</a>
												</div>
											</li>
											<li class="clearfix">
												<div class="form-check m-l-10">
													<label class="form-check-label"> <input class="form-check-input"
															type="checkbox" value="">
														Add salary details in system <span class="form-check-sign">
															<span class="check"></span>
														</span>
													</label>
												</div>
												<div class="todo-actionlist pull-right clearfix">
													<a href="#"> <i class="material-icons">clear</i>
													</a>
												</div>
											</li>
										</ul>
									</div>
									<div class="">
										<input type="text" class="tdl-new form-control">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="card ">
								<div class="card-header">
									<h4>World wide sales</h4>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
											<div id="visitorMap"></div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<p class="text-muted mb-1">World wide sell in different countries</p>
											<div class="mb-4">
												<div class="progress-title">
													<span>Egypt</span>
													<span class="float-right">45%</span>
												</div>
												<div class="progress mb-3" data-height="5">
													<div class="progress-bar" role="progressbar" data-width="45%"
														aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
											<div class="mb-4">
												<div class="progress-title">
													<span>Nigeria</span>
													<span class="float-right">55%</span>
												</div>
												<div class="progress mb-3" data-height="5">
													<div class="progress-bar l-bg-purple" role="progressbar"
														data-width="55%" aria-valuenow="55" aria-valuemin="0"
														aria-valuemax="100"></div>
												</div>
											</div>
											<div class="mb-4">
												<div class="progress-title">
													<span>Canada</span>
													<span class="float-right">25%</span>
												</div>
												<div class="progress mb-3" data-height="5">
													<div class="progress-bar l-bg-orange" role="progressbar"
														data-width="25%" aria-valuenow="25" aria-valuemin="0"
														aria-valuemax="100"></div>
												</div>
											</div>
											<div class="mb-4">
												<div class="progress-title">
													<span>Turkey</span>
													<span class="float-right">43%</span>
												</div>
												<div class="progress mb-3" data-height="5">
													<div class="progress-bar l-bg-cyan" role="progressbar"
														data-width="43%" aria-valuenow="43" aria-valuemin="0"
														aria-valuemax="100"></div>
												</div>
											</div>
											<div class="mb-4">
												<div class="progress-title">
													<span>Australia</span>
													<span class="float-right">72%</span>
												</div>
												<div class="progress mb-3" data-height="5">
													<div class="progress-bar l-bg-green" role="progressbar"
														data-width="72%" aria-valuenow="72" aria-valuemin="0"
														aria-valuemax="100"></div>
												</div>
											</div>
											<div class="mb-4">
												<div class="progress-title">
													<span>Thailand</span>
													<span class="float-right">55%</span>
												</div>
												<div class="progress mb-3" data-height="5">
													<div class="progress-bar l-bg-purple" role="progressbar"
														data-width="55%" aria-valuenow="55" aria-valuemin="0"
														aria-valuemax="100"></div>
												</div>
											</div>
											<div class="mb-4">
												<div class="progress-title">
													<span>Panama</span>
													<span class="float-right">34%</span>
												</div>
												<div class="progress mb-3" data-height="5">
													<div class="progress-bar l-bg-orange" role="progressbar"
														data-width="34%" aria-valuenow="34" aria-valuemin="0"
														aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>-->
							</div>
						</div>
						
					</div>


<!--
<div class="row">
	<div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Editable DataTables</h4>
                     
                       
                      
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="mainTable" class="table table-striped">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Profit</th>
                            <th>Fun</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Car</td>
                            <td>100</td>
                            <td>200</td>
                            <td>0</td>
                          </tr>
                          <tr>
                            <td>Bike</td>
                            <td>330</td>
                            <td>240</td>
                            <td>1</td>
                          </tr>
                          <tr>
                            <td>Plane</td>
                            <td>430</td>
                            <td>540</td>
                            <td>3</td>
                          </tr>
                          <tr>
                            <td>Yacht</td>
                            <td>100</td>
                            <td>200</td>
                            <td>0</td>
                          </tr>
                          <tr>
                            <td>Segway</td>
                            <td>330</td>
                            <td>240</td>
                            <td>1</td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>
                              <strong>TOTAL</strong>
                            </th>
                            <th>1290</th>
                            <th>1420</th>
                            <th>5</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>	
</div>

 <script>$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
      });
 </script>-->