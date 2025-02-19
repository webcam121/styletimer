<?php
       if($this->session->flashdata('message')!=''){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php echo $this->session->flashdata('message');?>
				</div>
<?php } ?>

<?php
       if(isset($message) && $message!=''){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php echo $message; ?>
				</div>
<?php } ?>

<?php
       if($this->session->flashdata('error')!=''){ ?>
				  <div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php echo $this->session->flashdata('error');?>
				  </div>
<?php   }		 ?>


<?php
       if(isset($error) && $error!=''){ ?>
				  <div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php echo $error;?>
				  </div>
<?php   }		 ?>
