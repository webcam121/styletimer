<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
<?php $this->load->view('backend/common/editer_css'); ?>       
    <style type="text/css">
    	.new-img img{
    		width: 100%;
			height: 145px;
			object-fit: cover;
    	}
    	.fr-box.fr-basic .fr-element {
    		min-height: 350px !important;
    	}
    </style>       
            
          <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>FAQ</h2>
                    </div>
                
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $query_type; ?> Faq 
                            <small></small>
                            </h2>
                        </div>
                       
                        <div class="card-body card-padding">
                            <form name="form-faq" id="form-faq" class="form-user" action="" method="post" enctype="multipart/form-data">
								
								<div class="row">
									<div class="col-xs-12" >
										<div class="fg-line form-group">
											<label for="exampleInputEmail1">Category</label>
											<select id="category" name="category" class="selectpicker">
													<option value='0' <?php if(!empty($faq->cat_id) && $faq->cat_id=='0') echo 'selected="selected"'; ?>> select category </option>
												<?php if($categories){
													foreach($categories as $cat){ ?>
														 <option value='<?php echo $cat->id; ?>' <?php if(!empty($faq->cat_id) && $faq->cat_id==$cat->id) echo 'selected="selected"'; ?>><?php echo $cat->name; ?></option>
														<?php } } ?>
												  </select>
												  <span class="error" id="category_error"><?php 
												if(form_error('question')){
													echo form_error('question');
												}
												echo $this->session->flashdata('err_mesg');
												?></span>
										</div>
									</div>
                            	</div>
                            	<div class="row">
									<div class="col-xs-12" >
										<div class="fg-line form-group">
											<label for="exampleInputEmail1">Question</label>
											<input type="text" class="form-control input-sm" id="question" name="question" placeholder="Enter question " value="<?php echo (isset($faq->question))?$faq->question:((isset($question))?$question:''); ?>">
											<span class="error" id="question_error"><?php 
												if(form_error('question')){
													echo form_error('question');
												}
												echo $this->session->flashdata('err_mesg');
												?></span>
										</div>
									</div>
                            	</div>
                            	<div class="row">
									<div class="col-xs-12" >
										<div class="fg-line form-group">
											<label for="exampleInputEmail1">Answer</label>
											 <textarea class="form-control" rows="5" id="answer" name="answer" placeholder="Enter answer...."><?php echo (isset($faq->answer))?$faq->answer:((isset($answer))?$answer:''); ?></textarea>
											
											<span class="error" id="answer_error"><?php 
												if(form_error('answer')){
													echo form_error('answer');
												}
												echo $this->session->flashdata('err_mesg');
												?></span>
										</div>
									</div>
                            	</div>
								
								<button type="submit" id="faq_submit" class="btn btn-primary btn-sm m-t-10">Submit</button>
                            </form>
                        </div>
                    </div>
                    
                      
                </div>
            </section>
        
  <?php $this->load->view('backend/common/footer'); ?>
  
  <?php $this->load->view('backend/common/editer_js'); ?>  

<script>
 

	/* new FroalaEditor('#answer', {
    
  })*/

new FroalaEditor('#answer', {


      // Set the image upload URL.
      key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
      useClasses: false,
      imageUploadURL: base_url+'backend/faq/upload',

      imageUploadParams: {
        id: 'my_editor'
      },
      videoUploadURL: base_url+'backend/faq/upload_video',

      videoUploadParams: {
        id: 'my_editor'
      },
   	 fileUploadURL: base_url+'backend/faq/upload_file',

	 fileUploadParams: {id: 'my_editor'},

      events: {
      'image.removed': function ($img) {
      	$.ajax({
				url: base_url+'backend/faq/remove_file',
				type: "POST",
				data:{path:$img.attr('src')},
				success: function (data) {
				var obj = jQuery.parseJSON( data );
				}
					
			}); 
      	
      },
      'video.removed': function ($vid) {
      	$.ajax({
				url: base_url+'backend/faq/remove_file',
				type: "POST",
				data:{path:$vid.attr('src')},
				success: function (data) {
				var obj = jQuery.parseJSON( data );
				}
					
			}); 
      	
      },
      'file.unlink': function ($files) {
      	$.ajax({
				url: base_url+'backend/faq/remove_file',
				type: "POST",
				data:{path:$files.getAttribute('href')},
				success: function (data) {
				var obj = jQuery.parseJSON( data );
				}
					
			}); 
      	
      },
      'image.error': function (error, response) {
      		var $popup = editor.popups.get('image.insert');
			var $layer = $popup.find('.fr-image-progress-bar-layer');
			$layer.find('h3').text(response.message);
      		 console.log(this);
      	},
  	 'video.error': function (error, response) {
  		//alert(error);
  		//alert(response)
  		//var $popup = editor.popups.get('video.insert');
		//var $layer = $popup.find('.fr-video-progress-bar-layer');
		//$layer.find('h3').text(response.message);
		//alert(response);
		$( ".fr-video-progress-bar-layer" ).find( "h3" ).html( response);
		//$(".fr-video-progress-bar-layer h3").html("dsdds");

  	    //console.log(response);
  	    //console.log(error);
  	  },
  	  'file.error': function (error, response) {
  		$( ".fr-file-progress-bar-layer" ).find( "h3" ).html( response);
		}
    }
      
      //imageDeleteURL: 'delete_image.php'
    })


</script>
