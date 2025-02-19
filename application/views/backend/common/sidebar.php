    <aside id="sidebar">
                <div class="sidebar-inner c-overflow">
                    <div class="profile-menu">
                       

                        <ul class="main-menu">
                        
                            <li>
                                <a href="<?php echo base_url('logout'); ?>"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                            </li>
                        </ul>
                    </div>

                    <ul class="main-menu">
                        
                        <li class="<?php echo (isset($segment1) && $segment1=='dashboard')?'active toggled':''; ?>">
							<a href="<?php echo admin_url('dashboard?short=day'); ?>"><i class="zmdi zmdi-home"></i> Dashboard</a>
						</li>
                        
                       
						<li class="sub-menu <?php echo (isset($segment1) && $segment1=='user' && $segment3 =='user')?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> User </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='user') && (isset($segment2) && $segment2=='listing'))?'active':''; ?>" href="<?php echo admin_url('user/listing/user?short=all'); ?>">List</a></li>

                            </ul>
                        </li>
                        <li class="sub-menu <?php echo (isset($segment1) && $segment1=='user' && ($segment3 =='marchant' || $segment2=='trial_period' ))?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Merchant </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='user') && (isset($segment2) && $segment2=='listing') && (isset($segment3) && $segment3=='marchant'))?'active':''; ?>" href="<?php echo admin_url('user/listing/marchant?short=all'); ?>">List</a></li>
                                 <li><a class="<?php echo ((isset($segment1) && $segment1=='user') && (isset($segment2) && $segment2=='trial_period') && (isset($segment3) && $segment3=='1'))?'active':''; ?>" href="<?php echo admin_url('user/trial_period/1'); ?>">Set Trial Period</a></li>
                            </ul>
                        </li>
                        <li class="sub-menu <?php echo (isset($segment1) && $segment1=='user' && ($segment3 =='salesman'))?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Salesman </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='user') && (isset($segment2) && $segment2=='listing') && (isset($segment3) && $segment3=='salesman'))?'active':''; ?>" href="<?php echo admin_url('user/listing/salesman?short=all'); ?>">List</a></li>
                                 <li><a class="<?php echo ((isset($segment1) && $segment1=='user') && (isset($segment2) && $segment2=='make') && (isset($segment3) && $segment3=='salesman'))?'active':''; ?>" href="<?php echo admin_url('user/make'); ?>">Add</a></li>
                            </ul>
                        </li>

                         <li class="sub-menu <?php echo (isset($segment1) && $segment1=='user' && $segment3 =='employee')?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Employee </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='user') && (isset($segment2) && $segment2=='listing') && (isset($segment3) && $segment3=='employee'))?'active':''; ?>" href="<?php echo admin_url('user/listing/employee?short=all'); ?>">List</a></li>
                            </ul>
                        </li>
                        <li class="sub-menu <?php echo (isset($segment1) && $segment1=='filter_category')?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i>Filter Category </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='filter_category') && (isset($segment2) && $segment2=='listing'))?'active':''; ?>" href="<?php echo admin_url('filter_category/listing'); ?>">List</a></li>

                                <li><a class="<?php echo ((isset($segment1) && $segment1=='filter_category') && (isset($segment2) && $segment2=='make'))?'active':''; ?>" href="<?php echo admin_url('filter_category/make'); ?>">Add</a></li>

                            </ul>
                        </li>

                        <li class="sub-menu <?php echo (isset($segment1) && $segment1=='category')?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Category </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='category') && (isset($segment2) && $segment2=='listing'))?'active':''; ?>" href="<?php echo admin_url('category/listing'); ?>">List</a></li>

                                <li><a class="<?php echo ((isset($segment1) && $segment1=='category') && (isset($segment2) && $segment2=='make'))?'active':''; ?>" href="<?php echo admin_url('category/make'); ?>">Add</a></li>

                            </ul>
                        </li>

                        <li class="sub-menu <?php echo (isset($segment1) && $segment1=='booking' && ($segment2 =='listing' || $segment2 =='set_booking_count'))?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Booking </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='booking') && (isset($segment2) && $segment2=='listing'))?'active':''; ?>" href="<?php echo admin_url('booking/listing?short=all'); ?>">List</a></li>
                                 <li><a class="<?php echo ((isset($segment1) && $segment1=='booking') && (isset($segment2) && $segment2=='set_booking_count'))?'active':''; ?>" href="<?php echo admin_url('booking/set_booking_count'); ?>">App review setting</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu <?php echo (isset($segment1) && $segment1=='payment' && $segment2 =='listing')?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Payment </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='payment') && (isset($segment2) && $segment2=='listing'))?'active':''; ?>" href="<?php echo admin_url('payment/listing'); ?>">List</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu <?php echo (isset($segment1) && $segment1=='offers' && ( $segment2 =='listing' || $segment2 =='description') )?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Offers </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='offers') && (isset($segment2) && ($segment2=='listing' || $segment2=='description')))?'active':''; ?>" href="<?php echo admin_url('offers/listing'); ?>">Offer Text List</a></li>
                            </ul>
                        </li>
                       <li class="sub-menu <?php echo (isset($segment1) && $segment1=='suggestions' && ( $segment2 =='listing') )?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Suggestions </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='suggestions') && (isset($segment2) && ($segment2=='listing')))?'active':''; ?>" href="<?php echo admin_url('suggestions/listing'); ?>">List</a></li>
                            </ul>
                        </li> 
                        <li class="sub-menu <?php echo (isset($segment1) && $segment1=='faq' && ( $segment2 =='listing') || ( $segment2 =='category') || ( $segment2 =='category_listing') )?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> FAQ </a>

                            <ul>
                                 <li><a class="<?php echo ((isset($segment1) && $segment1=='faq') && (isset($segment2) && ($segment2=='category')))?'active':''; ?>" href="<?php echo admin_url('faq/category'); ?>">FAQ Category </a></li>
                                 <li><a class="<?php echo ((isset($segment1) && $segment1=='faq') && (isset($segment2) && ($segment2=='category_listing')))?'active':''; ?>" href="<?php echo admin_url('faq/category_listing'); ?>">Category List</a></li>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='faq') && (isset($segment2) && ($segment2=='listing')))?'active':''; ?>" href="<?php echo admin_url('faq/listing'); ?>">FAQ List</a></li>
                                 <li><a class="<?php echo ((isset($segment1) && $segment1=='faq') && (isset($segment2) && ($segment2=='make')))?'active':''; ?>" href="<?php echo admin_url('faq/make'); ?>">Add FAQ</a></li>
                            </ul>
                        </li>  
                        <li class="sub-menu <?php echo (isset($segment1) && $segment1=='staticpage' && ( $segment2 =='termsandcondition' || $segment2 =='privacypolicy') )?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Pages </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='termsandcondition')) && (isset($segment3) && ($segment3=='user')))?'active':''; ?>" href="<?php echo admin_url('staticpage/termsandcondition/user'); ?>">User Terms & Condition</a></li>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='termsandcondition')) && (isset($segment3) && ($segment3=='merchant')))?'active':''; ?>" href="<?php echo admin_url('staticpage/termsandcondition/merchant'); ?>">Merchant Terms & Condition</a></li>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='privacypolicy')) && (isset($segment3) && ($segment3=='user')))?'active':''; ?>" href="<?php echo admin_url('staticpage/privacypolicy/user'); ?>">User Privacy Policy</a></li>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='privacypolicy')) && (isset($segment3) && ($segment3=='merchant')))?'active':''; ?>" href="<?php echo admin_url('staticpage/privacypolicy/merchant'); ?>">Merchant Privacy Policy</a></li>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='pages')) && (isset($segment3) && ($segment3=='Conditions')))?'active':''; ?>" href="<?php echo admin_url('staticpage/pages/conditions'); ?>">Conditions</a></li>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='pages')) && (isset($segment3) && ($segment3=='contactus')))?'active':''; ?>" href="<?php echo admin_url('staticpage/pages/contactus'); ?>">Contact us</a></li>
                                 <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='pages')) && (isset($segment3) && ($segment3=='aboutus')))?'active':''; ?>" href="<?php echo admin_url('staticpage/pages/aboutus'); ?>">About us</a></li>
                                 <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='pages')) && (isset($segment3) && ($segment3=='imprint')))?'active':''; ?>" href="<?php echo admin_url('staticpage/pages/imprint'); ?>">Imprint</a></li>

                                 <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='pages_links_name')))?'active':''; ?>" href="<?php echo admin_url('staticpage/pages_links_name'); ?>">Footer links name</a></li>
                                  <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='pages')) && (isset($segment3) && ($segment3=='salonregistration')))?'active':''; ?>" href="<?php echo admin_url('staticpage/pages/salonregistration'); ?>">Salon registration text</a></li>
                                   <li><a class="<?php echo ((isset($segment1) && $segment1=='staticpage') && (isset($segment2) && ($segment2=='pages')) && (isset($segment3) && ($segment3=='')))?'active':'dsgvo'; ?>" href="<?php echo admin_url('staticpage/pages/dsgvo'); ?>">DSGVO</a></li>
                            </ul>
                        </li>
                         <li class="sub-menu <?php echo (isset($segment1) && $segment1=='reviews' && ( $segment2 =='listing') )?'active toggled':''; ?>">
                            <a href=""><i class="zmdi zmdi-view-list"></i> Reviews </a>

                            <ul>
                                <li><a class="<?php echo ((isset($segment1) && $segment1=='reviews') && (isset($segment2) && ($segment2=='listing')))?'active':''; ?>" href="<?php echo admin_url('reviews/listing'); ?>">Reviews</a></li>
                            </ul>
                        </li> 
                    </ul>
                </div>
            </aside>
            
        <!--    <aside id="chat">
                <ul class="tab-nav tn-justified" role="tablist">
                    <li role="presentation" class="active"><a href="#friends" aria-controls="friends" role="tab" data-toggle="tab">Friends</a></li>
                    <li role="presentation"><a href="#online" aria-controls="online" role="tab" data-toggle="tab">Online Now</a></li>
                </ul>
            
                <div class="chat-search">
                    <div class="fg-line">
                        <input type="text" class="form-control" placeholder="Search People">
                    </div>
                </div>
                
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="friends">
                        <div class="listview">
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left p-relative">
                                        <img class="lv-img-sm" src="img/profile-pics/2.jpg" alt="">
                                        <i class="chat-status-busy"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Jonathan Morris</div>
                                        <small class="lv-small">Available</small>
                                    </div>
                                </div>
                            </a>
                            
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left">
                                        <img class="lv-img-sm" src="img/profile-pics/1.jpg" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">David Belle</div>
                                        <small class="lv-small">Last seen 3 hours ago</small>
                                    </div>
                                </div>
                            </a>
                            
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left p-relative">
                                        <img class="lv-img-sm" src="img/profile-pics/3.jpg" alt="">
                                        <i class="chat-status-online"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Fredric Mitchell Jr.</div>
                                        <small class="lv-small">Availble</small>
                                    </div>
                                </div>
                            </a>
                            
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left p-relative">
                                        <img class="lv-img-sm" src="img/profile-pics/4.jpg" alt="">
                                        <i class="chat-status-online"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Glenn Jecobs</div>
                                        <small class="lv-small">Availble</small>
                                    </div>
                                </div>
                            </a>
                            
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left">
                                        <img class="lv-img-sm" src="img/profile-pics/5.jpg" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Bill Phillips</div>
                                        <small class="lv-small">Last seen 3 days ago</small>
                                    </div>
                                </div>
                            </a>
                            
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left">
                                        <img class="lv-img-sm" src="img/profile-pics/6.jpg" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Wendy Mitchell</div>
                                        <small class="lv-small">Last seen 2 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left p-relative">
                                        <img class="lv-img-sm" src="img/profile-pics/7.jpg" alt="">
                                        <i class="chat-status-busy"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Teena Bell Ann</div>
                                        <small class="lv-small">Busy</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="online">
                        <div class="listview">
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left p-relative">
                                        <img class="lv-img-sm" src="img/profile-pics/2.jpg" alt="">
                                        <i class="chat-status-busy"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Jonathan Morris</div>
                                        <small class="lv-small">Available</small>
                                    </div>
                                </div>
                            </a>
                            
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left p-relative">
                                        <img class="lv-img-sm" src="img/profile-pics/3.jpg" alt="">
                                        <i class="chat-status-online"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Fredric Mitchell Jr.</div>
                                        <small class="lv-small">Availble</small>
                                    </div>
                                </div>
                            </a>
                            
                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left p-relative">
                                        <img class="lv-img-sm" src="img/profile-pics/4.jpg" alt="">
                                        <i class="chat-status-online"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Glenn Jecobs</div>
                                        <small class="lv-small">Availble</small>
                                    </div>
                                </div>
                            </a>

                            <a class="lv-item" href="">
                                <div class="media">
                                    <div class="pull-left p-relative">
                                        <img class="lv-img-sm" src="img/profile-pics/7.jpg" alt="">
                                        <i class="chat-status-busy"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="lv-title">Teena Bell Ann</div>
                                        <small class="lv-small">Busy</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </aside>-->
        
 
