<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
<?php $this->load->view('backend/common/editer_css'); ?>        
   <style type="text/css">
   .fr-box.fr-basic .fr-element{
    min-height: 450px!important;
  }
  .fr-wrapper {
    height: 470px !important;
  }
   </style>
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Terms And Conditions</h2>
                        
                    </div>
                    
                    
                    <div class="mini-charts">
                        
                    </div>
                <div class="card">
                     <?php if(!empty($terms)){ 
                        $dis="";
                        $edit="none";
                        }
                        else{
                         $dis="none";
                         $edit="";   
                        } 
                    ?>
                    <div class="card-header">
                            <h2 style="display: inline-block;">Terms and Conditions <?php echo ucfirst($segment3); ?></h2>
                            <button type="button" class="btn btn-icon command-edit display-ib ml-auto edit_about" style="display: inline-block;float: right; display: <?php echo $dis; ?>"><span class="zmdi zmdi-edit" ></span></button>
                        </div>
                   
                    <div class="p-25" id="about_div" style="display: <?php echo $dis; ?>">
                      <h3><?php echo isset($terms->title)?$terms->title:'';?></h3>
                       <?php echo isset($terms->text)?$terms->text:'';?>
                    </div>

                    <div class="row" id="editor_div" style="padding: 20px 50px 50px 50px; display: <?php echo $edit; ?>">
                    <form id="static_frm" method="post" action="">    
                      <div class="fg-line form-group">
                        <label for="exampleInputEmail1">Title</label>
                        <input type="text" class="form-control input-sm" id="title_name" name="title_name" placeholder="Enter term and conditions title" value="<?php echo isset($terms->title)?$terms->title:''; ?>">
                        <div id="title_err" class="error"></div>
                      </div>
                      
                      <div class="form-group mb-50" id="description_validate">
                        <textarea class="text-editor" id="about_salon" name="description">
                            <?php echo isset($terms->text)?$terms->text:''; ?></textarea>
                        <div id="description_err" class="error"></div>
                      </div>
                      <button type="submit" class="btn btn-primary btn-sm m-t-10 waves-effect" style="display: inline-block;">Save</button>
                      <input type="hidden" name="edit_check" value="<?php echo isset($terms->id)?$terms->id:'0'; ?>">
                    </form>
                    </div>
                </div>
                    
                </div>
            </section>
  <?php $this->load->view('backend/common/footer'); ?>
 
  <?php $this->load->view('backend/common/editer_js'); ?>  
<script>
 new FroalaEditor('#about_salon', {


      // Set the image upload URL.
      key:'PYC4mB3A11A11D9C7E5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5D4A1E3A9A3B5A3==',
      useClasses: false,
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