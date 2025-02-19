<!-- enter pin -->
<div id="Enter-pin" class="modal" role="dialog">
  <div class="modal-dialog modal-md modal-dialog-centered" style="max-width:370px;">
    <div class="modal-content">
       <a href="javascript:history.go(-1)" class="crose-btn">
       <picture class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.webp'); ?>" type="image/webp" class="popup-crose-black-icon">
            <source srcset="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" type="image/png" class="popup-crose-black-icon">
            <img src="<?php echo base_url('assets/frontend/images/popup_crose_black_icon.png'); ?>" class="popup-crose-black-icon">
          </picture>
      </a>
      <div class="modal-body text-left">
          <div class="relative align-self-center w-100">          
          <h2 class="font-size-22 color333 fontfamily-medium mb-4 text-center">PIN eingeben</h2>
          <div class="relative">
               <form id="checkpin" method="post">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <?php if(!empty($this->session->userdata('rest_pinsuccess'))){ ?> 
                          <div class="alert alert-success" role="alert" style="position: relative;padding: 4px 7px;top: 0px;border: 1px solid transparent;border-radius: 0.25rem;height: auto;z-index: 999;margin: -15px 0px 12px !important;display: block;text-center">
                            <?php   echo $this->session->userdata('rest_pinsuccess'); ?>
                          </div> 
                        <?php } ?>
                          <p class="mb-4 font-size-14 fontfamily-regular color666 text-center">Bitte PIN eingeben, um auf diese Seite zuzugreifen</p>
                              <div class="form-group form-group-mb-50 height60" id="">
                                   <label class="inp">  
                                        <input type="password" maxlength="4" id="checkpinval" name="pin" placeholder="&nbsp;" class="form-control ">
                                        <span class="label">PIN eingeben*</span>                                               
                                   </label> 
                                   <label id="checkpinerror" generated="true" class="error">
                                   </label>                                               
                              </div>
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                              <div class="form-group text-center">
                                   <button class="btn width180" id="checkpinsubmit" type="button">Bestätigen</button> 
                </div>
                <a href="JAvaScript:Void(0);" class="colororange fontsize-14 a_hover_orange" data-href="<?php echo base_url('profile/restpin'); ?>" id="resetPin">PIN zurücksetzen</a><br/>
                
                         </div>
                    </div>
               </form>
             </div>
          </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    $("#Enter-pin").modal({backdrop: 'static', keyboard:false});
      $("#Enter-pin").modal('show');
    
    $(document).on('click','#resetPin',function(){
	 var url =$(this).attr('data-href');
	 	
		  Swal.fire({
		  title: '<?php echo $this->lang->line("are_you_sure"); ?>',
		  text: "Bist du sicher, dass du deine PIN zurücksetzen willst?",
		  type: 'warning',
		  showCancelButton: true,
		  reverseButtons:true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Bestätigen'
		}).then((result) => {
		if (result.value) {
			loading();
		  $.ajax({
                url: url,
                type: "POST",
                success: function (response){
                   location.reload();
                  }
            });
		  }else{
			return false;
			}
		});  
	});		  
      
    });
</script>
