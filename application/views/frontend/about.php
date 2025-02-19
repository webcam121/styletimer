
  <?php $this->load->view('frontend/common/header'); ?>  
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
                            <h3 class="fontsize-34 colorwhite  fontfamily-semibold mb-0"><?php if(!empty($about->title)) echo $about->title; else echo 'About Us';  ?></h3>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>  
        
        <section class="clear contact-section2">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                       <?php if(!empty($about->text)) echo $about->text;  ?>
                    </div>
                </div>
                
            </div>
        </section>
<?php $this->load->view('frontend/common/footer'); ?>
