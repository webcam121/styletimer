<?php  //print_r($_SESSION); die; ?>
<div class="alert alert-success absolute vinay top w-100 mt-15 alert_message alert-top-0 text-center display-n <?php if(!empty($_SESSION['booking_complete_success'])) echo 'messageAlert'; ?>">
    <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times; </button> -->
  
   <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span><?php if(!empty($_SESSION['booking_complete_success'])){ 
	   echo $_SESSION['booking_complete_success']; 
	   $_SESSION['booking_complete_success']=""; 
	   } ?> </span>
 </div>
            
<div class="alert alert-success absolute vinay top w-100  alert_message alert-top-0 text-center display-n <?php if(!empty($this->session->flashdata('success'))) echo 'messageAlert';  ?>">
    <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times; </button> -->
  
   <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span id="alert_message"><?php if($this->session->flashdata('success')) echo $this->session->flashdata('success'); ?> </span>
 </div>
 <div class="alert alert-danger absolute vinay top w-100 h-100  alert_message alert-top-0 display-n <?php if(!empty($this->session->flashdata('error'))) echo 'messageAlert';  ?>">
    <button type="button" class="close ml-10" data-dismiss="alert">&times;</button>
    <span id="alert_message"><?php if($this->session->flashdata('error')) echo $this->session->flashdata('error'); ?> </span>
 </div>

<div class="alert alert-success absolute vinay top w-100  alert_message alert-top-0 text-center display-n <?php if(!empty($_SESSION['booking_complete_success'])) echo 'messageAlert'; ?>">
    <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times; </button> -->
  
   <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span id="alert_message"><?php if(!empty($_SESSION['booking_complete_success'])){ echo $_SESSION['booking_complete_success']; $_SESSION['booking_complete_success']=""; } ?> </span>
 </div>


