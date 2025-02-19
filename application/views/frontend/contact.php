
  <?php $this->load->view('frontend/common/header'); ?>  
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <style>
        .contact-section1{
            
            padding-top: 104px;
        }
        .contact-img-bg{
            background: url(assets/frontend/images/contact-img-bg.png) no-repeat;
            background-size: 100% 100%;
            background-position: center;
            height: 204px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .contact-section2{
            padding: 80px 0px 60px;
            position: relative;
            background: #ffffff;
        }
        ul.list-style-disk,ul.list-style-disk li{
            list-style: disc;
        }
        .contact-img-box{
            width: 350px;
            height: 262px;
            overflow: hidden;
            border-radius: 4px;
        }
        .contact-img-box img{
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .contact-section3{
            background: #F6F8F8;
            padding: 50px 0px 60px 0px;
            text-align: center;
        } 
        .max-width-564{
            max-width: 564px;
            width: 100%;
            margin: auto;
            text-align: center;
        }
    </style>
    <!-- start mid contact start section-->
        <section class="clear contact-section1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 contact-img-bg">
                        <div class="relative text-center ">
                            <h3 class="fontsize-34 colorwhite  fontfamily-semibold mb-0"><?php if(!empty($contact->title)) echo $contact->title; else echo 'Contact Us';  ?>
                    </div></h3>
                          
                        </div>
                    </div>
                </div>
            </div>
        </section>  
        
        <section class="clear contact-section2">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                       <?php if(!empty($contact->text)) echo $contact->text;  ?>
                    </div>
                </div>
                
            </div>
        </section>

        <section class="clear contact-section3">
          <div class="alert alert-success absolute vinay top w-100 alert_message alert-top-0 text-center <?php if(!empty($this->session->flashdata('success'))) echo ''; else echo 'display-n'; ?>" style="top: auto;position: relative;"> <!-- <button type="button" class="close ml-10" data-dismiss="alert">&times; </button> -->
                  
                   <img src="<?php echo base_url('assets/frontend/images/alert-massage-icon.svg'); ?>" class="mr-10"> <span id="alert_message"><?php if($this->session->flashdata('success')) echo $this->session->flashdata('success'); ?> </span>
                 </div>
            <div class="container">
            <h2 style="color:red"><?php echo $this->session->flashdata('error'); ?></h2> 
                   
                <div class="max-width-564">

                    <form method="post" action="<?php echo base_url('home/contact_post'); ?>" id="contact_post">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-xl-12"> 
                            <h4 class="color333 fontfamily-semibold fontsize-24 mb-5"><?php echo $this->lang->line('Get-in-touch-with-us'); ?></h4>    
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-md-6 col-xl-6">
                                <div class="form-group form-group-mb-50" id="name_validate">
                                    <label class="inp ">
                                      <input type="text" placeholder="&nbsp;" class="form-control" name="name">
                                      <span class="label">Name *</span>                                      
                                    </label>                                                
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-md-6 col-xl-6">
                                <div class="form-group form-group-mb-50" id="email_validate">
                                    <label class="inp">
                                      <input type="text" name="email" placeholder="&nbsp;" class="form-control">
                                      <span class="label"><?php echo $this->lang->line('Email'); ?> *</span>                                      
                                    </label>                                                
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-md-12 col-xl-12">
                                <div class="form-group form-group-mb-50" id="subject_validate">
                                    <label class="inp">
                                      <input type="text" name="subject" placeholder="&nbsp;" class="form-control">
                                      <span class="label"><?php echo $this->lang->line('subject1'); ?> *<span>                                      
                                    </label>                                                
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-md-12 col-xl-12">
                                <div class="form-group form-group-mb-50" id="message_validate">
                                    <label class="inp height90v">
                                      <textarea placeholder="&nbsp;" class="form-control height90v custom_scroll " style="background: #fff; width: 100% !important" name="message"></textarea>
                                      <span class="label"><?php echo $this->lang->line('Your-message'); ?> *<span>                                      
                                    </label>                                                
                                </div>
                            </div>
                            <div class="col-12 col-sm-12  col-md-7 col-xl-7 mx-auto">
                                <div class="g-recaptcha" data-sitekey="6Lel0UoeAAAAAJm755rLYXI9_DCfeeKIQ6TqRA9Z" style="margin: auto;margin-bottom: 20px;"></div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-md-12 col-xl-12">
                                <button type="submit" class="btn" id="contact_req_send" ><?php echo $this->lang->line('Send'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    <!-- end mid  contact end section-->



<?php $this->load->view('frontend/common/footer'); ?>
