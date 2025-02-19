<?php $this->load->view('frontend/common/header');
      $this->load->view('frontend/common/editer_css');   ?>
      
 <div class="d-flex pt-84">
<?php $this->load->view('backend/common/editer_css'); ?>        
   <style type="text/css">
   /* .fr-box.fr-basic .fr-element{
    min-height: 450px!important;
  }
  .fr-wrapper {
    height: 470px !important;
  } */
   </style>

<link rel="stylesheet" href="<?php echo base_url("assets/cropp/croppie.css");?>" type="text/css" />
<!-- 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/backend/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinyMCE.baseURL = "<?php  echo base_url().'assets/backend/tinymce/' ?>";
tinymce.init({
      plugins: "table code save moxiemanager  directionality autosave wordcount ",
      //toolbar: "bold italic underline",
    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment|styleselect',
     extended_valid_elements : "script[src]",
     selector: "textarea",
     entity_encoding: "named",
     height : 100,
     directionality : "ltr",
     content_css : "assets/backend/css/rtl-tinymce.css", 
     entities: "169,copy,8482,trade,ndash,8212,mdash,8216,lsquo,8217,rsquo,8220,ldquo,8221,rdquo,8364,euro,163,pound",
    
    formats: {
        aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,span', classes: 'image_block',styles: {display: 'block', margin: '0px auto', textAlign: 'center'} }
             },
      menubar:true,
      statusbar:true
    
 });
 
 //~ tinymce.init({
   //~ selector: 'textarea',
   //~ plugins: 'a11ychecker advcode casechange formatpainter linkchecker lists checklist media mediaembed pageembed permanentpen powerpaste tinycomments tinydrive tinymcespellchecker',
   //~ toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter insertfile pageembed permanentpen',
   //~ toolbar_drawer: 'floating',
   //~ tinycomments_mode: 'embedded',
   //~ tinycomments_author: 'Author name'
//~ });
 
</script> -->

<?php $this->load->view('frontend/common/sidebar'); ?>

<div class="right-side-dashbord w-100 pl-30 pr-30">
          <div class="row">
            <div class="col--12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <div class="bgwhite border-radius4 box-shadow1 mb-50 mt-20">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 m-auto">
                    <!-- <div class="text-center mt-20 mb-30">
                      <h3 class="font-size-20 fontfamily-medium color333">Create Newsletter</h3>
                    </div> -->
                    <?php //print_r($newsletter); 
                      $css='';
                      if(isset($newsletter->image_path)!=""){
                        $img=base_url('assets/uploads/newsletter/').$newsletter->merchant_id.'/'.$newsletter->image_path;
                        $css='width:340px; height: 115px;';
                      }
                      else
                        $img=base_url('assets/frontend/images/dummy-grey-icon.svg');

                    ?>
                    <form id="FrmNewsletter"  method="post" action="<?php echo base_url('merchant/newsletter/').$this->uri->segment(3); ?>" enctype="multipart/form-data">
                      <div class="">
                        <div class="pt-3 pb-40 pr-3 pl-3">
                          <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <p><?php echo $this->lang->line('Upload_Logo'); ?></p>
                              <div class="relative form-group mb-40">
                              <?php if(isset($newsletter->image_path)!=""){ ?>
                              <div class=" relative display-b border-radius4 border-w height120 text-center " style="width: 350px;">
                                  <img id="NewChgImg" src="<?php echo $img; ?>" class="salon-img-upload" style=" object-fit: cover; width: 100%; height: 100%;">
                                  <label class="all_type_upload_file bgblack18" style="">
                                    <span class="fontfamily-medium font-size-12">Titelbild ändern</span>
                                    <input id="newsImg" name="newsImg" type="file">
                                </label>
                              </div>

                                <?php }
                                else{ ?>
                                    <div class="relative display-b border-radius4 border-w height120 text-center" style="width: 350px;">
                                  <label class="all_type_upload_file" style="line-height: 100px;">
                                    <img id="NewChgImg" src="<?php echo $img; ?>" class="edit_pencil_bg_white_circle_icon1" style="<?php echo $css; ?> object-fit: cover; width: 100%; height: 100%;">
                                    <input id="newsImg" name="newsImg" type="file">
                                  </label>
                                </div>
                              <?php } ?>     

                              <div id="imgerror" class="error"></div>
                            </div>
                            <input type="hidden" id="old_image" name="old_image" value="<?php echo isset($newsletter->image_path)?$newsletter->image_path:''; ?>">
                            <div class="form-group form-group-mb-50" >
                                <label class="inp">
                                  <input type="text" id="txtsubject" name="txtsubject" placeholder="&nbsp;" class="form-control" autocomplete="off" value="<?php echo isset($newsletter->subject)?$newsletter->subject:''; ?>">
                                  <span class="label"><?php echo $this->lang->line('subject'); ?></span>
                                </label> 
                                <div id="txtsubject_err" class="error"></div>
                                <!-- <span class="ml-3 fontfamily-regular color000 font-size-12 display-b" style="">Helper text</span>  -->
                              </div> 
                            
                            </div>                        
                          </div>
                          <!-- <div id="froala-editor">
  <p>The classes should be defined in <a href="http://www.w3schools.com/css/" title="CSS" target="_blank" rel="nofollow">CSS</a>, otherwise no changes will be visible on the link's appearance.</p>
</div> -->

                          <div class="form-group mb-50" >
                          
                            <textarea class="fr-emoticon fr-deletable fr-emoticon-img" id="about_salon_new"
                             name="description">
                             <?php echo isset($newsletter->description)?
                             $newsletter->description:''; ?></textarea>
                            <div id="description_err" class="error"></div>
                          </div>
                            <div class="form-group form-group-mb-50">
                              <label class="inp">
                                  <input type="text" id="txtfooter" name="txtfooter" placeholder="&nbsp;" class="form-control" autocomplete="off" value="<?php echo isset($newsletter->footer)?$newsletter->footer:''; ?>">
                                  <span class="label"><?php echo $this->lang->line('Footer_Content'); ?></span>
                                </label> 
                                <div id="txtfooter_err" class="error"></div>
                                <!-- <span class="ml-3 fontfamily-regular color000 font-size-12 display-b" style="">Helper text</span> -->
                              </div>
                          <div class="text-center">
                    <a href="<?php if(!empty($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; ?>"><button type="button" class="btn btn-large widthfit2" style="margin-right: 30px;"><?php echo $this->lang->line('abort'); ?></button></a>
                    <button type="submit" class="btn btn-large widthfit2"><?php echo $this->lang->line('Save_btn'); ?></button>
                  </div>                      
                </div>
              </div>              
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!-- dashboard right side end -->
  </div>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.7/css/froala_editor.min.css" rel="stylesheet" type="text/css" />
<script 
type="text/javascript"
 src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.7/js/languages/de.min.js"></script>
 <script 
type="text/javascript"
 src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.7/js/froala_editor.min.js"></script>

  <link href="https://cdn1.codox.io/lib/css/wave.client.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.7/js/plugins/link.min.js"></script>
 <?php $this->load->view('backend/common/editer_js'); ?> 
  <script type="text/javascript" src="<?php echo base_url('assets/froala_editor/js/languages/de.js'); ?>"></script>
  
  <script>
   new FroalaEditor('#about_salon_new', {

   
      // Set the image upload URL.
      key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
      useClasses: false,
       language: 'de',
      imageUploadURL: base_url+'backend/staticpage/upload',

      imageUploadParams: {
        id: 'my_editor'
      },
      videoUploadURL: base_url+'backend/staticpage/upload_video',

      videoUploadParams: {
        id: 'my_editor'
      },
     fileUploadURL: base_url+'backend/staticpage/upload_file',

   fileUploadParams: {id: 'my_editor'},

      events: {
      'image.removed': function ($img) {
        $.ajax({
        url: base_url+'backend/staticpage/remove_file',
        type: "POST",
        data:{path:$img.attr('src')},
        success: function (data) {
        var obj = jQuery.parseJSON( data );
        }
          
      }); 
        
      },
      'video.removed': function ($vid) {
        $.ajax({
        url: base_url+'backend/staticpage/remove_file',
        type: "POST",
        data:{path:$vid.attr('src')},
        success: function (data) {
        var obj = jQuery.parseJSON( data );
        }
          
      }); 
        
      },
      'file.unlink': function ($files) {
        $.ajax({
        url: base_url+'backend/staticpage/remove_file',
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
    $(".fr-video-progress-bar-layer").find("h3").html( response);
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

<?php $this->load->view('frontend/common/footer_script');
      $this->load->view('frontend/common/editer_js');
      $this->load->view('frontend/common/crop_pic');  ?>
<script type="text/javascript" src="<?php echo base_url('assets/cropp/croppie.js'); ?>"></script>

     
 <script>
$(document).ready(function(){
  $image_crop = $('#image_demo').croppie({
		enableExif: false,
		viewport: {
		  width:296,
		  height:100,
		  type:'square' //circle
		},
		boundary:{
		  width:500,
		  height:170
		}
	  });

  $(document).on('change','#newsImg', function(){
    var reader = new FileReader();
    console.log(reader);
    reader.onload = function (event) {
		//console.log(event);
      $image_crop.croppie('bind', {
        url:event.target.result
      }).then(function(){
        //console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type: 'canvas',
      size:{
		  width:700,
		  height:250
		   }
    }).then(function(response){
      $.ajax({
        url:"<?php echo base_url('newsletter/imagecropp'); ?>",
        type: "POST",
        data:{"image": response},
        success:function(data)
        { var obj = $.parseJSON(data);
          $('#uploadimageModal').modal('hide');
           var $el = $('#newsImg');
           $el.wrap('<form>').closest('form').get(0).reset();
           $el.unwrap();
         // $('#upload_image').attr('data-url','');
          //$('#profile-picture').attr('data-img',obj.status);
          $('#NewChgImg').attr('src',obj.image);
        }
      });
    })
  });

			 //~ $.ajax({
                    //~ url: "<?php echo base_url(); ?>restourant/deletempimage",
                    //~ type: 'post',
                    //~ success: function(datas){
                    	//~ return true;
             	//~ }           	
           //~ });

	});
		
	</script>

  <script>
 new FroalaEditor('a#froala-editor', {
   events: {
     contentChanged: function () {
       console.log ('content changed');
     }
   }
 })
  </script>
 
 
