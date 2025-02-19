        <?php if(!isset($_COOKIE['side_nav_status']))
                  $cls="hide-left-bar";
                else if($_COOKIE['side_nav_status'] =="open"){
                    $cls="hide-left-bar left-bar-dask"; ?>
                <style type="text/css">
                    .left-side-dashbord.left-bar-dask a span.colorwhite{
                        display:inline-block
                    }                    
                    .right-side-dashbord{
                        padding-left : 230px;
                    }
                     
                </style>
                <?php }
                else{ ?>
                    <script type="text/javascript">
                         //alert();
                        //  $('.testts').prop('title', 'your new title');
                        //  $('.testts').each(function(){
                             
                        //     });
                    </script>
                    <style type="text/css">
                        .right-side-dashbord{
                          padding-left : 95px;
                        }
                        @media (max-width: 1199px) and (min-width: 992px){
                            .right-side-dashbord {
                                padding-left: 90px;
                            }
                        }
                        @media (max-width: 991px) and (min-width: 768px){
                            .right-side-dashbord {
                                padding-left: 90px;
                            }
                        }
                    </style>
                  <?php  $cls="hide-left-bar";
                }
                ?>
                
    <div class="left-side-dashbord bgwhite box-shadow1 pt-10 scroll-effect <?php echo $cls; ?>" id="left-id">
        <a href="#" class="absolute left-side-arrow">
            <i class="fa fa-long-arrow-alt-right colorwhite" style="transform: translate(-15px);"></i>
          </a>
            <ul class="pl-20">
                <?php /*<li class="mb-25">
                    <a href="<?php echo base_url('merchant/dashboard'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium <?php if($this->uri->segment(2)=='dashboard') echo "active"; ?>">
                        <span class="width30 display-ib text-center mr-2"><img src="<?php echo base_url("assets/frontend/images/dashboard-icon-white.svg"); ?>" class="mb-1"></span> 
                        <span class="colorwhite">Dashboard</span>
                    </a>
                </li> */ ?>

               <li class="mb-25">
                   <a href="javascript:void(0)" class="menu-toggle">
                       <img class="menu-colleps-icon" src="<?php echo base_url("assets/frontend/images/menu-colleps-icon-white.png"); ?>" style="width:30px;">
                       
                   </a>
               </li>
                <li class="mb-25 <?php if($this->uri->segment(2)=='mydashboard') echo "active"; ?>">
                  <a href="<?php echo base_url('merchant/mydashboard'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="<?php echo $this->lang->line('dashboard'); ?>" data-placement="right"  title="<?php echo $this->lang->line('dashboard'); ?>">
                    <span class="width30 display-ib text-center mr-2">
                    <img style="" src="<?php echo base_url("assets/frontend/images/dashboard-icon-white.png"); ?>" class="mb-1"></span> 
                    <span class="colorwhite"><?php echo $this->lang->line('dashboard'); ?></span>
                  </a>
                </li>
                <li class="mb-25 relative <?php if($this->uri->segment(2)=='dashboard') echo "active"; ?>">
                    <a href="<?php echo base_url('merchant/dashboard'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts " data-toggle="tooltip" data-temp="<?php echo $this->lang->line('calender'); ?>" data-placement="right"  title="<?php echo $this->lang->line('calender'); ?>">
                        <span class="width30 display-ib text-center mr-2">
                        <img src="<?php echo base_url("assets/frontend/images/calader-icon-white.png"); ?>" class="mb-1"></span> 
                        <span class="colorwhite"><?php echo $this->lang->line('calender'); ?></span>
                        <span class="natification-count bookingCountNoti" id="booking_count" style="display: none;">0</span>
                    </a>
                </li>
                <li class="mb-25 relative   <?php if($this->uri->segment(2)=='booking_listing') echo "active"; ?>">
                    <a href="<?php echo base_url('merchant/booking_listing'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts " data-toggle="tooltip" data-temp="<?php echo $this->lang->line('Bookings'); ?>" data-placement="right"  title="<?php echo $this->lang->line('Bookings'); ?>">
                        <span class="width30 display-ib text-center mr-2">
                            <img src="<?php echo base_url("assets/frontend/images/bookings-icon-white.png"); ?>" class="mb-1">
                        </span>
                        <span class="colorwhite"><?php echo $this->lang->line('Bookings'); ?></span>
                    </a>
                    <span href="#" class="natification-count bookingCancelNoti" id="chgcancel_count" style="display: none;z-index: 1000;cursor: pointer;">0</span>
                </li>
                 <li class="mb-25 <?php if($this->uri->segment(2)=='customers') echo "active"; ?>">
                    <a href="<?php echo base_url('merchant/customers'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="<?php echo $this->lang->line('Customers'); ?>" data-placement="right"  title="<?php echo $this->lang->line('Customers'); ?>">
                        <span class="width30 display-ib text-center mr-2"><img src="<?php echo base_url("assets/frontend/images/customers-icon-white.png"); ?>" class="mb-1"></span>
                        <span class="colorwhite"><?php echo $this->lang->line('Customers'); ?></span>
                    </a>
                </li>
                <li class="mb-25 <?php if($this->uri->segment(2)=='service_listing' || $this->uri->segment(2)=='add_service') echo "active"; ?>">
                    <a href="<?php echo base_url('merchant/service_listing'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="<?php echo $this->lang->line('Services'); ?>" data-placement="right"  title="<?php echo $this->lang->line('Services'); ?>">
                        <span class="width30 display-ib text-center mr-2"><img src="<?php echo base_url("assets/frontend/images/services-icon-white.png"); ?>" class="mb-1"></span> 
                        <span class="colorwhite"><?php echo $this->lang->line('Services'); ?></span>
                    </a>
                </li>
                <li class="mb-25 <?php if($this->uri->segment(2)=='employee_listing' || $this->uri->segment(2)=='dashboard_addemployee' || $this->uri->segment(2)=='employee_shift') echo "active"; ?>">
                    <a href="<?php echo base_url('merchant/employee_listing'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="<?php echo $this->lang->line('Employees'); ?>" data-placement="right"  title="<?php echo $this->lang->line('Employees'); ?>">
                        <span class="width30 display-ib text-center mr-2"><img src="<?php echo base_url("assets/frontend/images/employees-icon-white.png"); ?>" class="mb-1"></span> 
                        <span class="colorwhite"><?php echo $this->lang->line('Employees'); ?></span>
                    </a>
                </li>
                 <li class="mb-25 <?php if($this->uri->segment(2)=='rating_review') echo "active"; ?>">
                    <a href="<?php echo base_url('merchant/rating_review'); ?>" class="relative a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="<?php echo $this->lang->line('Rating_&_Reviews'); ?>" data-placement="right"  title="<?php echo $this->lang->line('Rating_&_Reviews'); ?>">
                        <span class="width30 display-ib text-center mr-2"><img style="" src="<?php echo base_url("assets/frontend/images/reting_review_icon.png"); ?>" class="mb-1"></span> 
                        <span class="colorwhite"><?php echo $this->lang->line('Rating_&_Reviews'); ?></span>
                        <span class="natification-count" id="chgreview_count" style="display: none;">0</span>
                    </a>
                </li>
                 <li class="mb-25 <?php if($this->uri->segment(2)=='reports') echo "active"; ?>">
                    <a href="<?php echo base_url('merchant/reports'); ?>" class="relative a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="<?php echo $this->lang->line('Reports'); ?>" data-placement="right"  title="<?php echo $this->lang->line('Reports'); ?>">
                        <span class="width30 display-ib text-center mr-2"><img style="" src="<?php echo base_url("assets/frontend/images/reports.png"); ?>" class="mb-1"></span> 
                        <span class="colorwhite"><?php echo $this->lang->line('Reports'); ?></span>
                        <span class="natification-count" id="chgreview_count" style="display: none;">0</span>
                    </a>
                </li>
                  <li class="mb-25 <?php if($this->uri->segment(2)=='selectnewesletter' || $this->uri->segment(2)=='newsletter') echo "active"; ?>">
                    <a href="<?php echo base_url('merchant/selectnewesletter'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="<?php echo $this->lang->line('Newsletters'); ?>" data-placement="right"  title="<?php echo $this->lang->line('Newsletters'); ?>">
                        <span class="width30 display-ib text-center mr-2"><img style="" src="<?php echo base_url("assets/frontend/images/newsletter.png"); ?>" class="mb-1"></span> 
                        <span class="colorwhite"><?php echo $this->lang->line('Newsletters'); ?></span>
                    </a>
                </li>
                 <!-- <li class="mb-25 <?php if($this->uri->segment(2)=='payment_list') echo "active"; ?>">
                    <a href="<?php echo base_url('merchant/payment_list'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="Payment" data-placement="right"  title="Payment">
                        <span class="width30 display-ib text-center mr-2"><img src="<?php echo base_url("assets/frontend/images/payment-icon-white.png"); ?>" class="mb-1"></span>
                        <span class="colorwhite">Payment</span>
                    </a>
                </li> -->
                <!-- <li class="mb-25 <?php if($this->uri->segment(2)=='membership') echo "active"; ?>">
                    <a href="<?php echo base_url('membership'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="Membership" data-placement="right"  title="Membership">
                        <span class="width30 display-ib text-center mr-2"><img src="<?php echo base_url("assets/frontend/images/membership-icon-white.png"); ?>" class="mb-1"></span>
                        <span class="colorwhite">Membership</span>
                    </a>
                </li> -->
                <li class="mb-25 <?php if($this->uri->segment(2)=='edit_marchant_profile' || $this->uri->segment(1)=="taxes") echo "active"; ?>">
                  <a href="<?php echo base_url('profile/edit_marchant_profile'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="<?php echo $this->lang->line('Setting'); ?>" data-placement="right"  title="<?php echo $this->lang->line('Setting'); ?>">
                    <span class="width30 display-ib text-center mr-2">
                    <img style="" src="<?php echo base_url("assets/frontend/images/profile_icon_white.png"); ?>" class="mb-1"></span> 
                    <span class="colorwhite"><?php echo $this->lang->line('Setting'); ?></span>
                  </a>
                </li>
                <!-- <li class="mb-25">
                  <a href="<?php echo base_url('faq'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium testts" data-toggle="tooltip" data-temp="Frequently Asked Questions" data-placement="right"  title="Frequently Asked Questions">
                    <span class="width30 display-ib text-center mr-2">
                    <img style="" src="<?php echo base_url("assets/frontend/images/faq_icon_white.png"); ?>" class="mb-1"></span> 
                    <span class="colorwhite">FAQ</span>
                  </a>
                </li> -->
                <?php /* <li class="mb-25 dropdown">
                    <a href="javascript:void(0);" class="a_hover_cyan color333 font-size-14 fontfamily-medium dropdown-toggle cursor-p <?php if($this->uri->segment(1)=='profile') echo "active"; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        <span class="width30 display-ib text-center mr-2">
                            <img style="width: 24px;height:24px;" src="<?php echo base_url("assets/frontend/images/setting_icon.svg"); ?>" class="mb-1"></span> 
                        <span class="colorwhite">Setting</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="Menu">
                        <li class="mb-25">
                          <a href="<?php echo base_url('profile/edit_marchant_profile'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium">
                            <span class="width30 display-ib text-center mr-2">
                            <img style="width: 24px; height: 24px;" src="<?php echo base_url("assets/frontend/images/profile_icon_white.svg"); ?>" class="mb-1"></span> 
                            <span class="colorwhite">Mein Profil</span>
                          </a>
                        </li>
                        <li class="mb-25">
                          <a href="<?php echo base_url('merchant/mydashboard'); ?>" class="a_hover_cyan color333 font-size-14 fontfamily-medium">
                            <span class="width30 display-ib text-center mr-2">
                            <img style="width: 20px; height: 18px;" src="<?php echo base_url("assets/frontend/images/dashboard-icon-white.svg"); ?>" class="mb-1"></span> 
                            <span class="colorwhite">Dashboard</span>
                          </a>
                        </li>
                    </ul>

                    
                </li> */ ?>
                

            </ul>     
        </div>
