
<link href="<?php echo admin_assets(); ?>css/vinu_style.css" rel="stylesheet">

<?php $this->load->view('backend/common/header'); ?>
   
     <?php $this->load->view('backend/common/sidebar'); ?>
    
   <style type="text/css">
        html{overflow:scroll !important;}
       .count h2{
            display: inline-block !important;width: 45%;text-align: center;
       }
       .count small{
            display: inline-block !important;width: 50%;font-size: 14px;
       }
       #pie-chart-heard-about.flot-chart-pie{
        width: 50%;
        display: inline-block;
       }
       #pie-chart-heard-about.flot-chart-pie+.flc-pie-heard-about{
        display: inline-block;
        vertical-align: top;
        text-align: left;
        padding-top: 20px;
        font-size: 14px;
       }
       #pie-chart-heard-about-user.flot-chart-pie{
        width: 50%;
        display: inline-block;
       }
       #pie-chart-heard-about-user.flot-chart-pie+.flc-pie-heard-about-user{
        display: inline-block;
        vertical-align: top;
        text-align: left;
        padding-top: 20px;
        font-size: 14px;
       }
        .modal{
        padding:30px 30px 24px 30px !important;
      }
      .modal a.close-modal {
        top: 6.5px !important;
        right: 6.5px !important;
        width: 20px !important;
        height: 20px !important;
      }
      .overflow-scroll{
        max-height: 60vh;
        overflow: auto;
      }
        .bootgrid-header .actions {
        z-index: 9 !important;
    }
    .border-radius50{border-radius: 50%;}
    .mr-3{margin-right: 16px;}
   </style>
   
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Dashboard</h2>
                         
                    </div>
                    
                 <div class="row">
                        <?php if(empty($_GET['short']))
                                $dis='';
                              else
                                $dis="display: none;";
                        ?>

                     <div class="col-sm-3 m-b-25">
                        <p class="f-500 m-b-15 c-black">Show For</p>
                        
                        <select id="over_all_filter" class="selectpicker on_change_short">
                             <option value="">Search By Date</option>
                             <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="all")?'selected="selected"':''; } ?> value="all">Over All</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="day")?'selected="selected"':''; } ?> value="day">Today</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="current_week")?'selected="selected"':''; } ?> value="current_week">Current Week</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="current_month")?'selected="selected"':''; } ?> value="current_month">Current Month</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="last_month")?'selected="selected"':''; } ?> value="last_month">Last Month</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="last_sixmonth")?'selected="selected"':''; } ?> value="last_sixmonth">Last 6 Months</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="current_year")?'selected="selected"':''; } ?> value="current_year">Current Year</option>
                            <option <?php if(!empty($_GET['short'])){ echo ($_GET['short'] =="last_year")?'selected="selected"':''; } ?> value="last_year">Last Year</option>

                        </select>
                    </div>
                    <div id="date_section" style="<?php echo $dis; ?>">
                    <div class="col-sm-3">
                         <p class="f-500 m-b-15 c-black">Start Date</p>
                        <div class="input-group form-group">
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-calendar"></i>
                            </span>
                            <div class="dtp-container fg-line">
                                <input type="text" style="padding-left: 10px;" id="start_date" class="form-control date-picker" name="start_date" value="<?php echo isset($_GET['start_date'])?$_GET['start_date']:''; ?>" placeholder="select start date">
                            </div>
                             <div style="position: absolute;" id="error_start" class="error"></div>
                        </div>

                    </div>
                        <div class="col-sm-3">
                            <p class="f-500 m-b-15 c-black">End Date</p>
                            <div class="input-group form-group">
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-calendar"></i>
                            </span>
                            <div class="dtp-container fg-line">
                                <input type="text" style="padding-left: 10px;" id="end_date" class="form-control date-picker" name="end_date" value="<?php echo isset($_GET['end_date'])?$_GET['end_date']:''; ?>" placeholder="select end date">
                            </div>
                                <div style="position: absolute;" id="error_end" class="error"></div>
                        </div></div>
                        <div class="col-sm-3">
                            <p class="f-500 m-b-25 c-black"></p>
                            <button type="button" id="click_search" class="btn btn-info btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-search"></i></button>
                        </div>
                    </div>


                </div>
                    <?php if(!empty($_GET['short']))
                            $get_data = "?short=".$_GET['short'];
                          else if(!empty($_GET['start_date']) && !empty($_GET['end_date']))
                            $get_data= "?start_date=".$_GET['start_date']."&end_date=".$_GET['end_date'];
                          else
                            $get_data = "?short=30";
                            

                     ?>
                    <div class="mini-charts">
                        <div class="new-chart-row">
                            <div class="new-chart-column">
                                <a href="<?php echo base_url('backend/dashboard/salon_listing').$get_data; ?>">
                                <div class="mini-charts-item bgm-cyan" style="cursor: pointer; ">
                                    <div class="clearfix">
                                       <!--  <div class="chart stats-bar"></div> -->
                                        <div class="count">
                                            <small>New<br> Merchant</small>
                                            <h2 ><?php echo $all_total->merchant_total; ?></h2>
                                        </div>
                                    </div>
                                 </div>
                                </a>
                            </div>
                            
                            <div class="new-chart-column">
                              <a href="<?php echo base_url('backend/dashboard/user_listing').$get_data; ?>">
                                <div class="mini-charts-item bgm-lightgreen" style="cursor: pointer;">
                                    <div class="clearfix">
                                        <!-- <div class="chart stats-bar-2"></div> -->
                                        <div class="count">
                                            <small>New<br> Users</small>
                                            <h2><?php echo $all_total->user_total; ?></h2>
                                        </div>
                                    </div>
                                </div>
                             </a>
                            </div>
                            
                            <div class="new-chart-column">
                                <div class="mini-charts-item bgm-orange" style="">
                                   <div class="clearfix">
                                        <!-- <div class="chart stats-line"></div> -->
                                        <div class="count">
                                            <small>New<br> Bookings</small>
                                            <h2 ><?php echo $all_total->total_booking; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="new-chart-column">
                                <div class="mini-charts-item" style="background-color:#009688;">
                                   <div class="clearfix">
                                        <!-- <div class="chart stats-line"></div> -->
                                        <div class="count">
                                            <small>Confirmed<br> Booking Value</small>
                                            <h2 >€ <?php echo (!empty($all_total->confirm_total_value)?price_formate($all_total->confirm_total_value):'0');  ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="new-chart-column">
                                <div class="mini-charts-item bgm-bluegray">
                                    <div class="clearfix">
                                       <!--  <div class="chart stats-line-2"></div> -->
                                        <div class="count">
                                            <small>Booking<br> Value</small>
                                            <h2>€ <?php echo price_formate((!empty($all_total->total_price)?$all_total->total_price:'0')); ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Recent Items -->
                            <div class="card" style="min-height: 430px;">
                                <div class="card-header">
                                    <h2>Revenue Earned</h2>
                                    <h5>Total Revenue: <?php echo (!empty($top_ten_revenue))?$top_ten_revenue[0]->total_price:'0'; ?></h5>
                                    <small style="font-size: 15px;">10 best performing salons by revenue</small>
                                </div>
                                
                                <div class="card-body m-t-0">
                                    <table class="table table-inner table-vmiddle">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Salon Name</th>
                                                <th>Completed Booking</th>
                                                <th style="width: 60px">Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($top_ten_revenue)){ $i=1;
                                                foreach($top_ten_revenue as $revenue){ ?>
                                            <tr>
                                                <td class="f-500 c-cyan"><?php echo $i; ?></td>
                                                <td><a href="javascript:void(0)" class="get_salondetail" id="<?php echo $revenue->merchant_id; ?>"><?php echo $revenue->merchant; ?></a></td>
                                                <td class="f-500 c-cyan text-center"><?php echo $revenue->tot_comp; ?></td>
                                                <td class="f-500 c-cyan text-center"><?php echo price_formate($revenue->orderTotal); ?></td>
                                            </tr>
                                            <?php $i++; } 
                                            } else{ ?>
                                            <tr>
                                                <td colspan="3" style="padding-top: 80px" class="text-center">No record found...!</td>
                                            </tr>

                                           <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- <div id="recent-items-chart" class="flot-chart"></div> -->
                            </div>
                             
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Booking Ratio Web Vs App</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="pie-chart" class="flot-chart-pie"></div>
                                    <div class="flc-pie hidden-xs"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Recent Items -->
                            <div class="card" style="min-height: 430px;">
                                <div class="card-header">
                                    <h2>Registered Merchant By Business Type </h2>
                                 </div>
                                
                                <div class="card-body m-t-0">
                                    <table class="table table-inner table-vmiddle">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Business Type</th>
                                                <th style="width: 60px">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($salon_by_type)){ $j=1;
                                                foreach($salon_by_type as $type){ ?>
                                            <tr>
                                                <td class="f-500 c-cyan"><?php echo $j; ?></td>
                                                <td><?php echo $type->business_type; ?></td>
                                                <td class="f-500 c-cyan text-center"><?php echo $type->salon_total; ?></td>
                                            </tr>
                                           <?php $j++; }
                                            } else{ ?>
                                            <tr>
                                                <td colspan="3" style="padding-top: 80px" class="text-center">No record found...!</td>
                                            </tr>

                                           <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- <div id="recent-items-chart" class="flot-chart"></div> -->
                            </div>
                             
                        </div>

                         <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Ratio of Male & Female users</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="pie-chart1" class="flot-chart-pie"></div>
                                    <div class="flc-pie1 hidden-xs"></div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Ratio of Male & Female users</h2>
                                    
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="donut-chart" class="flot-chart-pie"></div>
                                    <div class="flc-donut hidden-xs"></div>
                                </div>
                            </div>
                        </div> -->
                        
                    </div>
                     <div class="row">
                        <div class="col-sm-6">
                            <!-- Recent Items -->
                            <div class="card" style="min-height: 430px;">
                                <div class="card-header">
                                    <h2>Salesman data </h2>
                                 </div>
                                
                                <div class="card-body m-t-0">
                                    <table class="table table-inner table-vmiddle">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Salesman name</th>
                                                <th style="width: 60px">Total</th>
                                                <th style="width: 60px">Paid</th>
                                                <th style="width: 60px">Commission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                             $salesmanData = array();
                                             $salesmanData_paid = array();
                                                                                         
                                             if(!empty($salesman)){ $j=1;
                                                       $salessum = 0;
                                                       foreach($salesman as $sales1){ 
                                                           $salessum = $salessum+$sales1->merchantCount;
                                                          }
                                                        
                                                         //print_r($salesman);       
                                                         //print_r($sum);       
                                                
                                                $colors = array('#FF9944','#E46986','#6CC198','#F6BC51','#F3DDCF','#90624B','#3296D2','#48C3CA','#A9A2CE','#BDBEB9');
                                                
                                                foreach($salesman as $sales){ 
                                                    
                                                    $prcente = 0;
                                                    $prcente_p = 0;
                                                    
                                                    if(!empty($sales->merchantCount))
                                                    {
                                                        $prcente = round((100*$sales->merchantCount)/$salessum);        
                                                    }
                                                    if(!empty($sales->merchantCount))
                                                    {
                                                        $prcente_p = round((100*$sales->paidCount)/$salessum);        
                                                    }                                                             
                                                    $onearray = array('data'=>$prcente,'color'=>$colors[$j-1],'label'=>$sales->merchantCount.' '.$sales->first_name.' '.$sales->last_name);
                                                    $salesmanData[] = $onearray; 
                                                    $onearray_p = array('data'=>$prcente_p,'color'=>$colors[$j-1],'label'=>$sales->paidCount.' '.$sales->first_name.' '.$sales->last_name);
                                                    $salesmanData_paid[] = $onearray_p; 
                                                    ?>
                                            <tr>
                                                <td class="f-500 c-cyan"><?php echo $j; ?></td>
                                                <td><?php echo $sales->first_name." ".$sales->last_name; ?></td>
                                                <td class="f-500 c-cyan text-center"><a href="<?php echo base_url('backend/user/listing/marchant?code='.$sales->salesman_code); ?>"><?php echo $sales->merchantCount; ?></a></td>
                                                <td class="f-500 c-cyan text-center"><?php if(!empty($sales->paidCount)){ ?>
                                                    <a href="<?php echo base_url('backend/user/listing/marchant?code='.$sales->salesman_code); ?>&paid=yes"><?php echo $sales->paidCount; ?></a>
                                                    <?php }else{ 
                                                     echo '0'; } ?>
                                                </td>
                                               <td class="f-500 c-cyan text-center"><?php if(!empty($sales->paidCountValue)) echo $sales->paidCountValue; else echo '0'; ?></td>
                                            </tr>
                                           <?php $j++; } } else{ ?>
                                            <tr>
                                                <td colspan="3" style="padding-top: 80px" class="text-center">No record found...!</td>
                                            </tr>

                                           <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- <div id="recent-items-chart" class="flot-chart"></div> -->
                            </div>
                             
                        </div>

                         <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Ratio of salesman</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="pie-chart-salesman" class="flot-chart-pie"></div>
                                    <div class="flc-pie-salesman hidden-xs"></div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Ratio of Male & Female users</h2>
                                    
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="donut-chart" class="flot-chart-pie"></div>
                                    <div class="flc-donut hidden-xs"></div>
                                </div>
                            </div>
                        </div> -->
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Ratio Of Salesman Paid</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="pie-chart-salesman-paid" class="flot-chart-pie"></div>
                                    <div class="flc-pie-salesman-paid hidden-xs"></div>
                                </div>
                            </div>
                        </div>
                     
                      </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>How people got to know about Styletimer : Salon</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="pie-chart-heard-about" class="flot-chart-pie"></div>
                                    <div class="flc-pie-heard-about hidden-xs"></div>
                                </div>
                            </div>
                        </div>
                     
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>How people got to know about Styletimer : User</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="pie-chart-heard-about-user" class="flot-chart-pie"></div>
                                    <div class="flc-pie-heard-about-user hidden-xs"></div>
                                </div>
                            </div>
                        </div>
                     
                      </div>


                      <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Payment Type Ratio</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="pie-chart-payment-type" class="flot-chart-pie"></div>
                                    <div class="flc-pie-payment-type hidden-xs"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>User Registration Ratio</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    <div id="pie-chart-registration-type" class="flot-chart-pie"></div>
                                    <div class="flc-pie-registration-type hidden-xs"></div>
                                </div>
                            </div>
                        </div>
                     
                      </div>


                     <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Recent Sales</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    
                                    <div id="chartjs-line-chart " class="card " style="border:none !important;">
                              <div class="card-content">
                                
                                 <div class="caption">
                                    <p class="font-size-14">
                                        <span class="fontfamily-medium font-size-14 color333 display-ib">Bookings : </span>
                                        <span class="color666 display-ib width50 text-left ml-2" id="salebokking_count"></span>
                                    </p>
                                    <p class="font-size-14">
                                        <span class="fontfamily-medium font-size-14 color333 display-ib">Booking Value : </span>
                                        <span class="color666 display-ib width50 text-left ml-2" id="salebokking_value"></span>
                                    </p>
                                    <p class="font-size-14">
                                        <span class="fontfamily-medium font-size-14 color333 display-ib">Merchant : </span>
                                        <span class="color666 display-ib width50 text-left ml-2" id="salemerchant_count"></span>
                                    </p>
                                    <p class="font-size-14">
                                        <span class="fontfamily-medium font-size-14 color333 display-ib">User : </span>
                                        <span class="color666 display-ib width50 text-left ml-2" id="saleuser_count"> </span>
                                    </p>
                                 </div>
                                       
                                <div class="sample-chart-wrapper">
                                    <canvas id="chartjs-1"></canvas>
                                </div>
                              </div>
                           </div>
                                </div>
                            </div>
                        </div>
                     
                      </div> 

                      <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Bookings Values</h2>
                                </div>
                                
                                <div class="card-body card-padding">
                                    
                                    <div id="chartjs-line-chart " class="card " style="border:none !important;">
                              <div class="card-content">
                                                                       
                                <div class="sample-chart-wrapper">
                                    <canvas id="chartjs-2"></canvas>
                                </div>
                              </div>
                           </div>
                                </div>
                            </div>
                        </div>
                     
                      </div> 


                </div>
            </section>
            
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2> Latest Merchant - Listing</h2>
                    </div>
                    <?php //print_r($users); ?>
                    <div class="card">
                        
                        <div class="card-header">
                            <?php $this->load->view('backend/common/alert'); ?>  
                        </div>
                        
                        
                        
                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-header-css-class="idth" data-order="asc" data-type="numeric">ID</th>
                                    <th data-column-id="rowid" data-order="asc" data-type="numeric" data-visible="false"></th>
                                    <th data-column-id="first_name"  data-header-css-class="nameth" data-order="asc" data-type="string" >First name</th>
                                    <th data-column-id="last_name"  data-header-css-class="nameth" data-type="string">Last name</th>
                                    <th data-column-id="gender" data-type="string">
                                       Business Name
                                    </th>                                   
                                    <th data-column-id="email">Email</th>
                                    <th data-column-id="mobile" data-type="string">Registered</th>          
                                    <th data-column-id="status" data-type="string">Status</th>          
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                   
                                   if($latest_merchant){
                                       $i=1;
                                     foreach($latest_merchant as $user){ 
                                          $reg=date("d/m/Y H:i", strtotime($user->created_on)); ?>
                                          <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $user->id; ?></td>
                                            <td><?php echo $user->first_name; ?></td>
                                            <td><?php echo $user->last_name; ?></td>
                                            
                                            <td> <?php echo $user->business_name; ?>
                                            </td>
                                            <td title="<?php echo $user->email; ?>"><?php echo $user->email; ?></td>
                                            <!-- <td><?php //echo $user->age_group; ?></td> -->
                                            <td><?php echo $reg; ?></td>
                                            <td><?php echo $user->status; ?></td>
                                          </tr>
                                          
                                    <?php $i++; }
                                   } 
                                
                                ?>
                                
                               
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        <div class="modal fade" id="profile-client-view-modal">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
            <div class="modal-content" id="clientViewProfileHtml">            
            
            </div>
          </div>
        </div>

           

  <?php
    //print_r($ratio);
    $male=$female=0;
    $web=$app=0;
    $web_total=$app_total=0;
    $male_total=$female_total=0;
    if(!empty($ratio))
    {
      $total_mf=$ratio->male+$ratio->female;
      $total_wp=$ratio->web_ratio+$ratio->app_ratio;
      $web_total = $ratio->web_ratio; $app_total = $ratio->app_ratio;
      $male_total =  $ratio->male;  $female_total= $ratio->female;  
      if($total_mf != 0){
        $male=round(($ratio->male*100)/$total_mf);
        $female = 100;
      }
      $female=$female-$male;
       if($total_wp != 0){
        $web=round(($ratio->web_ratio*100)/$total_wp);
        $app=100;
      }
      $app=$app-$web;
    }
   
   $fb_p=$link_p=$google_p=$site_p=$outdoor_p=$tv_p=$event_p=$refer_p=$other_p=$cust_p=$salon_p=$mag_p=0;
  
    if(!empty($heard_about->total_heard)){
        $fb_p = round(($heard_about->fb*100)/$heard_about->total_heard);
        $link_p = round(($heard_about->link*100)/$heard_about->total_heard);
        $google_p = round(($heard_about->google*100)/$heard_about->total_heard);
        $site_p = round(($heard_about->site*100)/$heard_about->total_heard);
        $outdoor_p = round(($heard_about->outdoor*100)/$heard_about->total_heard);
        $tv_p = round(($heard_about->tv*100)/$heard_about->total_heard);
        $event_p = round(($heard_about->event*100)/$heard_about->total_heard);
        $refer_p = round(($heard_about->refer*100)/$heard_about->total_heard);
        $other_p = round(($heard_about->other*100)/$heard_about->total_heard);
        $cust_p = round(($heard_about->rec_customer*100)/$heard_about->total_heard);
        $salon_p = round(($heard_about->rec_salon*100)/$heard_about->total_heard);
        $mag_p = round(($heard_about->mag*100)/$heard_about->total_heard);
    }

    $ufb_p=$uinsta_p=$ugoogle_p=$utv_p=$uevent_p=$uother_p=$uoutdoor_p=$ucust_p=$usalon_p=$ucust_p=$umag_p=0;

    if(!empty($heard_about_user->total_heard)){
        $ufb_p = round(($heard_about_user->fb*100)/$heard_about_user->total_heard);
        $uinsta_p = round(($heard_about_user->insta*100)/$heard_about_user->total_heard);
        $ugoogle_p = round(($heard_about_user->google*100)/$heard_about_user->total_heard);
        $utv_p = round(($heard_about_user->tv*100)/$heard_about_user->total_heard);
        $uevent_p = round(($heard_about_user->event*100)/$heard_about_user->total_heard);
        $uother_p = round(($heard_about_user->other*100)/$heard_about_user->total_heard);
        $uoutdoor_p = round(($heard_about_user->outdoor*100)/$heard_about_user->total_heard);
        $usalon_p = round(($heard_about_user->insalon*100)/$heard_about_user->total_heard);
        $ucust_p = round(($heard_about_user->oth_cus*100)/$heard_about_user->total_heard);
        $umag_p = round(($heard_about_user->mag*100)/$heard_about_user->total_heard);
    }

    $card_p=$cash_p=$oth_p=$vouch=0;
    if(!empty($pay_type->total_paytype)){
        $card_p = round(($pay_type->card*100)/$pay_type->total_paytype);
        $cash_p = round(($pay_type->cash*100)/$pay_type->total_paytype);
        $oth_p = round(($pay_type->other*100)/$pay_type->total_paytype);
        $vouch_p = round(($pay_type->voucher*100)/$pay_type->total_paytype);
    }

    $fbp=$gmail_p=$regular_p=$apple_p=0;
    if(!empty($user_reg->total_user)){
        $fbp = round(($user_reg->fb*100)/$user_reg->total_user);
        $gmail_p = round(($user_reg->gmail*100)/$user_reg->total_user);
        $apple_p = round(($user_reg->apple*100)/$user_reg->total_user);
        $regular_p = round(($user_reg->regular*100)/$user_reg->total_user);
    }

   // echo '<pre>'; print_r($salesmanData); die;
    //$salesman = json_encode($salesman);
     ?>
  <?php $this->load->view('backend/common/footer'); ?>
  <script type="text/javascript">
     var male= '<?php echo $male; ?>';
     var female= '<?php echo $female; ?>';
     var web= '<?php echo $web; ?>';
     var app= '<?php echo $app; ?>';
     var maletotal= '<?php echo $male_total; ?>';
     var femaletotal= '<?php echo $female_total; ?>';
     var webtotal= '<?php echo $web_total; ?>';
     var apptotal= '<?php echo $app_total; ?>';
     var salesman= JSON.parse('<?php echo json_encode($salesmanData); ?>');
     var salesman_paid= JSON.parse('<?php echo json_encode($salesmanData_paid); ?>');
     
     

    // console.log(salesman);
  </script>
  <script src="<?php echo base_url('assets/frontend/'); ?>js/chart.min.js"></script>
  <script src="<?php echo base_url('assets/backend/vendors/bower_components/flot/jquery.flot.js'); ?>"></script>
  <script src="<?php echo base_url('assets/backend/vendors/bower_components/flot/jquery.flot.resize.js'); ?>"></script>
  <script src="<?php echo base_url('assets/backend/vendors/bower_components/flot/jquery.flot.pie.js'); ?>"></script>
   <script src="<?php echo base_url('assets/backend/vendors/bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js') ?>"></script>
 
  <script type="text/javascript">
      console.log("LOGIN------");
    localStorage.setItem("STATUS", "LOGGED_IN");

      $(document).ready(function(){
         $("#data-table-command").bootgrid({
                    caseSensitive: false,
                    columnSelection: false,
                    navigation:0,
                    css: {
                        icon: 'zmdi icon',
                        iconColumns: 'zmdi-view-module',
                        iconDown: 'zmdi-expand-more',
                        iconRefresh: 'zmdi-refresh',
                        iconUp: 'zmdi-expand-less'
                    }
                });
      
  });
       $(document).on('change','.on_change_short', function(){
        var limit=$(this).val();
        if(limit !=''){  
         var url=base_url+"backend/dashboard?short="+limit;
          window.location.href = url; 
          $('#date_section').hide();
        }
        else{
            $('#date_section').show();
        }
        

       });

       $(document).on('click','#click_search', function(){
            var start = $('#start_date').val();
            var end =  $('#end_date').val();
            var tokan =true;
            if(start ==""){
                $('#error_start').html('select start date');
                tokan = false;
            }
            else
                $('#error_start').html('');
            if(end ==""){
                $('#error_end').html('select end date');
                tokan = false;
            }
            else
                $('#error_end').html('');
            if(tokan == true){
                var url=base_url+"backend/dashboard?start_date="+start+"&end_date="+end;
                window.location.href = url;
            }
       });

        var pieData = [
       {data: web, color: '#03A9F4', label: webtotal+' WEB'},
       {data: app, color: '#FFEB3B', label: apptotal+' APP'},
       
    ];
    var pieData1 = [
       {data: male, color: '#8BC34A', label: maletotal+' Male'},
      {data: female, color: '#009688', label: femaletotal+' Female'},
       
    ];
     /*( [id] => 2 [fb] => 2 [link] => 0 [google] => 1 [site] => 0 [outdoor] => 0 [tv] => 4 [event] => 0 [refer] => 2 [other] => 1 [rec_customer] => 0 )*/ 
    var pieData2 = [
      {data: <?php echo $fb_p ?>, color: '#800000', label: <?php echo $heard_about->fb ?>+' Facebook/ Instagram'},
      {data: <?php echo $link_p ?>, color: '#808000', label: <?php echo $heard_about->link ?>+' LinkedIn'},
      {data: <?php echo $google_p ?>, color: '#008000', label: <?php echo $heard_about->google ?>+' Google'},
      {data: <?php echo $site_p ?>, color: '#008080', label: <?php echo $heard_about->site ?>+' Software comparison site'},
      {data: <?php echo $outdoor_p ?>, color: '#800080', label: <?php echo $heard_about->outdoor ?>+' Outdoor advertising'},
      {data: <?php echo $tv_p ?>, color: '#FF7F50', label: <?php echo $heard_about->tv ?>+' TV advertising'},
      {data: <?php echo $event_p ?>, color: '#FFA500', label: <?php echo $heard_about->event ?>+' Events'},
      {data: <?php echo $refer_p ?>, color: '#BA55D3', label: <?php echo $heard_about->refer ?>+' Referral'},
      {data: <?php echo $other_p ?>, color: '#6A5ACD', label: <?php echo $heard_about->other ?>+' Other'},
      {data: <?php echo $cust_p ?>, color: '#4682B4', label: <?php echo $heard_about->rec_customer ?>+' Recommended by a customer'},
      {data: <?php echo $salon_p ?>, color: '#2F4F4F', label: <?php echo $heard_about->rec_salon ?>+' Recommended by another salon'},
      {data: <?php echo $mag_p ?>, color: '#808080', label: <?php echo $heard_about->mag ?>+' Magazine/ print advertising'}
       
    ];

    var pieData3 = [
       {data: <?php echo $cash_p ?>, color: '#009688', label: <?php echo $pay_type->cash ?>+' Cash'},
       {data: <?php echo $card_p ?>, color: '#CCCCCC', label: <?php echo $pay_type->card ?>+' Card'},
       {data: <?php echo $oth_p ?>, color: '#5481B0', label: <?php echo $pay_type->other ?>+' Other'},
       {data: <?php echo $vouch_p ?>, color: '#807BC6', label: <?php echo $pay_type->voucher ?>+' Voucher'}
       
    ];

    var pieData4 = [
      {data: <?php echo $ufb_p ?>, color: '#800000', label: <?php echo $heard_about_user->fb ?>+' Facebook'},
      {data: <?php echo $uinsta_p ?>, color: '#808000', label: <?php echo $heard_about_user->insta ?>+' Instagram'},
      {data: <?php echo $ugoogle_p ?>, color: '#008000', label: <?php echo $heard_about_user->google ?>+' Google'},
      {data: <?php echo $utv_p ?>, color: '#FF7F50', label: <?php echo $heard_about_user->tv ?>+' TV/cinema'},
      {data: <?php echo $uevent_p ?>, color: '#FFA500', label: <?php echo $heard_about_user->event ?>+' Events'},
      {data: <?php echo $uother_p ?>, color: '#6A5ACD', label: <?php echo $heard_about_user->other ?>+' Other'},
      {data: <?php echo $uoutdoor_p ?>, color: '#800080', label: <?php echo $heard_about_user->outdoor ?>+' Outdoor advertising'},
      {data: <?php echo $usalon_p ?>, color: '#4682B4', label: <?php echo $heard_about_user->insalon ?>+' Heard about in salon'},
      {data: <?php echo $ucust_p ?>, color: '#2F4F4F', label: <?php echo $heard_about_user->oth_cus ?>+' Heard from other customer'},
      {data: <?php echo $umag_p ?>, color: '#808080', label: <?php echo $heard_about_user->mag ?>+' Magazine/print advertising'}
       
    ];

    var pieData5 = [
       {data: <?php echo $fbp ?>, color: '#6CC198', label: <?php echo $user_reg->fb ?>+' Facebook'},
       {data: <?php echo $gmail_p ?>, color: '#E46986', label: <?php echo $user_reg->gmail ?>+' Google'},
       {data: <?php echo $apple_p ?>, color: '#807BC6', label: <?php echo $user_reg->apple ?>+' Apple'},
       {data: <?php echo $regular_p ?>, color: '#FF9944', label: <?php echo $user_reg->regular ?>+' Regular'},
    ];
   //console.log(pieData5);
   //console.log(pieData);
   //console.log(pieData1);
        if($('#pie-chart')[0]){
        $.plot('#pie-chart', pieData, {
            series: {
                pie: {
                    show: true,
                    stroke: { 
                        width: 2,
                    },
                },
            },
            legend: {
                container: '.flc-pie',
                backgroundOpacity: 0.5,
                noColumns: 0,
                backgroundColor: "white",
                lineWidth: 0
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false,
                cssClass: 'flot-tooltip'
            }
            
        });
    }


     if($('#pie-chart1')[0]){
        $.plot('#pie-chart1', pieData1, {
            series: {
                pie: {
                    show: true,
                    stroke: { 
                        width: 2,
                    },
                },
            },
            legend: {
                container: '.flc-pie1',
                backgroundOpacity: 0.5,
                noColumns: 0,
                backgroundColor: "white",
                lineWidth: 0
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false,
                cssClass: 'flot-tooltip'
            }
            
        });
    }

     if($('#pie-chart-salesman')[0]){
        $.plot('#pie-chart-salesman', salesman, {
            series: {
                pie: {
                    show: true,
                    stroke: { 
                        width: 2,
                    },
                },
            },
             legend: {
                container: '.flc-pie-salesman',
                backgroundOpacity: 0.5,
                noColumns: 0,
                backgroundColor: "white",
                lineWidth: 0
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false,
                cssClass: 'flot-tooltip'
            }
            
        });
    }

    if($('#pie-chart-salesman-paid')[0]){
        $.plot('#pie-chart-salesman-paid', salesman_paid, {
            series: {
                pie: {
                    show: true,
                    stroke: { 
                        width: 2,
                    },
                },
            },
             legend: {
                container: '.flc-pie-salesman-paid',
                backgroundOpacity: 0.5,
                noColumns: 0,
                backgroundColor: "white",
                lineWidth: 0
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false,
                cssClass: 'flot-tooltip'
            }
            
        });
    }

    if($('#pie-chart-heard-about')[0]){
        $.plot('#pie-chart-heard-about', pieData2, {
            series: {
                pie: {
                    show: true,
                    stroke: { 
                        width: 2,
                    },
                },
            },
            legend: {
                container: '.flc-pie-heard-about',
                backgroundOpacity: 0.5,
                position: 'right',
                /*noColumns: 0,*/
                backgroundColor: "white",
                lineWidth: 0
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false,
                cssClass: 'flot-tooltip'
            }
            
        });
    }


    if($('#pie-chart-payment-type')[0]){
        $.plot('#pie-chart-payment-type', pieData3, {
            series: {
                pie: {
                    show: true,
                    stroke: { 
                        width: 2,
                    },
                },
            },
            legend: {
                container: '.flc-pie-payment-type',
                backgroundOpacity: 0.5,
                noColumns: 0,
                backgroundColor: "white",
                lineWidth: 0
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false,
                cssClass: 'flot-tooltip'
            }
            
        });
    }

    
    if($('#pie-chart-heard-about-user')[0]){
        $.plot('#pie-chart-heard-about-user', pieData4, {
            series: {
                pie: {
                    show: true,
                    stroke: { 
                        width: 2,
                    },
                },
            },
            legend: {
                container: '.flc-pie-heard-about-user',
                backgroundOpacity: 0.5,
                position: 'right',
                /*noColumns: 0,*/
                backgroundColor: "white",
                lineWidth: 0
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false,
                cssClass: 'flot-tooltip'
            }
            
        });
    }

    if($('#pie-chart-registration-type')[0]){
        $.plot('#pie-chart-registration-type', pieData5, {
            series: {
                pie: {
                    show: true,
                    stroke: { 
                        width: 2,
                    },
                },
            },
            legend: {
                container: '.flc-pie-registration-type',
                backgroundOpacity: 0.5,
                noColumns: 0,
                backgroundColor: "white",
                lineWidth: 0
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            tooltip: true,
            tooltipOpts: {
                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false,
                cssClass: 'flot-tooltip'
            }
            
        });
    }

getsaleboookingline();
    function getsaleboookingline(){
  surl=base_url+"backend/dashboard/getbookingrecentsale";
  //var dayfilter=$("input[name='over_all_filter']:checked").val();
  var dayfilter = $("#over_all_filter").val(); 
    var start ="";
    var end ="";
    if(dayfilter ==""){
        var start = $('#start_date').val();
        var end =  $('#end_date').val();
    }
    var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
     $.post(surl,{filter:dayfilter,start_date: start, end_date:end},function( data1 ) {
        var data1 = jQuery.parseJSON( data1 );
    new Chart(document.getElementById("chartjs-1"),{"type":"line","data":{"labels":data1.days,
            "datasets":[{"label":"SALES","data":data1.sales,"fill":false,"borderColor":"rgb(251, 153, 71)","lineTension":0,"yAxisID": 'y-axis-1',},{"label":"BOOKINGS","data":data1.booking,"fill":false,"borderColor":"rgb(1, 214, 227)","lineTension":0,"yAxisID": 'y-axis-2'},{"label":"MERCHANT","data":data1.merchant,"fill":false,"borderColor":"rgb(128, 128, 0)","lineTension":0 ,"yAxisID": 'y-axis-2'},{"label":"USER","data":data1.user,"fill":false,"borderColor":"rgb(0, 0, 128)","lineTension":0 ,"yAxisID": 'y-axis-2'}]},
            /*,{"label":"MERCHANT","data":data1.merchant,"fill":false,"borderColor":"rgb(128, 128, 0)","lineTension":0},{"label":"USER","data":data1.user,"fill":false,"borderColor":"rgb(0, 0, 128)","lineTension":0}*/
            "options":{tooltips: {
                                enabled: true,
                                mode: 'single',
                                callbacks: {
                                    label: function(tooltipItems, data,i=0) { 
                    //console.log(tooltipItems);
                    //console.log(data.datasets[i].label);
                    if(data.datasets[tooltipItems.datasetIndex].label=='SALES'){
                       return 'SALES :'+tooltipItems.yLabel+' €';
                    }
                    else if(data.datasets[tooltipItems.datasetIndex].label=='MERCHANT'){
                       return 'MERCHANT :'+tooltipItems.yLabel;
                    }
                    else if(data.datasets[tooltipItems.datasetIndex].label=='USER'){
                       return 'USER :'+tooltipItems.yLabel;
                    }else{
                         return 'BOOKING :'+tooltipItems.yLabel;
                        } 
                        i++;
                }
            }
        },
      scales: {
        yAxes: [{
            position: 'left',
            id: 'y-axis-1',
            ticks: {
            beginAtZero: true,
            callback: function(value, index, values) {
              
                if(parseInt(value) >= 1000){
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' €';
                  } else {
                    return value+' €';
                  }
              
            }
          }
        },
        {
            display: true,
            position: 'right',
            id: 'y-axis-2',
            ticks: {
            beginAtZero: true,
            callback: function(value, index, values) {
                if(parseInt(value) >= 1000){
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                  } else {
                    return value;
                  }
              
            }
          }
        }
        ]
        
      }
    }
});
    
     if(data1.sale_count){
        $("#salebokking_value").html(data1.sale_count.tot_sale+' € '+data1.sales_compare);
        $("#salebokking_count").html(data1.sale_count.tot_booking+' '+data1.totalBooking_compare);
        $("#salemerchant_count").html(data1.sale_count.tot_merchant+' '+data1.totalMerchant_compare);
        $("#saleuser_count").html(data1.sale_count.tot_user+' '+data1.totalUser_compare);
      }
      else{
        $("#salebokking_value").html('0 €'+data1.sales_compare);
        $("#salebokking_count").html('0 '+data1.totalBooking_compare);
        $("#salemerchant_count").html('0 '+data1.totalMerchant_compare);
        $("#saleuser_count").html('0 '+data1.totalUser_compare);
      }

    });

 }

getboookingvalueline();
    function getboookingvalueline(){
  surl=base_url+"backend/dashboard/getbookingvalues";
  //var dayfilter=$("input[name='over_all_filter']:checked").val();
  var dayfilter = $("#over_all_filter").val(); 
    var start ="";
    var end ="";
    if(dayfilter ==""){
        var start = $('#start_date').val();
        var end =  $('#end_date').val();
    }
    var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
     $.post(surl,{filter:dayfilter,start_date: start, end_date:end},function( data1 ) {
        var data1 = jQuery.parseJSON( data1 );
    new Chart(document.getElementById("chartjs-2"),{"type":"line","data":{"labels":data1.days,
            "datasets":[{"label":"Complete booking value","data":data1.complete_booking_value,"fill":false,"borderColor":"rgb(251, 153, 71)","lineTension":0,"yAxisID": 'y-axis-2',},{"label":"Confirmed booking value","data":data1.confirmed_booking_value,"fill":false,"borderColor":"rgb(1, 214, 227)","lineTension":0,"yAxisID": 'y-axis-2'},{"label":"Total booking value","data":data1.total_booking_value,"fill":false,"borderColor":"rgb(128, 128, 0)","lineTension":0 ,"yAxisID": 'y-axis-2'}]},
            /*,{"label":"MERCHANT","data":data1.merchant,"fill":false,"borderColor":"rgb(128, 128, 0)","lineTension":0},{"label":"USER","data":data1.user,"fill":false,"borderColor":"rgb(0, 0, 128)","lineTension":0}*/
            "options":{tooltips: {
                                enabled: true,
                                mode: 'single',
                                callbacks: {
                                label: function(tooltipItems, data,i=0) { 
                    //console.log(tooltipItems);
                    //console.log(data.datasets[i].label);
                    return data.datasets[tooltipItems.datasetIndex].label+' :'+tooltipItems.yLabel+' €';
                   /* if(data.datasets[tooltipItems.datasetIndex].label=='Complete booking value'){
                       return 'Complete booking value :'+tooltipItems.yLabel+' €';
                    }
                    else if(data.datasets[tooltipItems.datasetIndex].label=='MERCHANT'){
                       return 'MERCHANT :'+tooltipItems.yLabel;
                    }
                    else if(data.datasets[tooltipItems.datasetIndex].label=='USER'){
                       return 'USER :'+tooltipItems.yLabel;
                    }else{
                         return 'BOOKING :'+tooltipItems.yLabel;
                        } */
                        i++;
                }
            }
        },
      scales: {
        yAxes: [{
            position: 'left',
            id: 'y-axis-2',
            ticks: {
            beginAtZero: true,
            callback: function(value, index, values) {
              
                if(parseInt(value) >= 1000){
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' €';
                  } else {
                    return value+' €';
                  }
              
            }
          }
        },
        {
            display: true,
            position: 'right',
            id: 'y-axis-1',
            ticks: {
            beginAtZero: true,
            callback: function(value, index, values) {
                if(parseInt(value) >= 1000){
                    return "";
                  } else {
                    return "";
                  }
              
            }
          }
        }
        ]
        
      }
    }
});
    
   
    });

 }
 


 $(document).on('change','.on_change_short_c', function(){
                var short=$(".on_change_short_c option:selected").val();
                var idd = $("#salon_idds").val();

                    if(short ==""){
                        $('#date_section_c').show();
                    }
                    else{
                        $('#date_section_c').hide();
                        
                        get_clientBookingList();
                       getCustomerList();
                        //viewRowsalon_filter(idd,base_url+'backend/user/salon_detail_filter');
                    }

                   });
                   
             $(document).on('change','.searchNewFilter', function(){
                var short=$(".on_change_short_c option:selected").val();
                var idd = $("#salon_idds").val();

                    if(short ==""){
                        $('#date_section_c').show();
                    }
                    else{
                        $('#date_section_c').hide();
                        viewRowsalon_filter(idd,base_url+'backend/user/salon_detail_filter');
                    }

                   });
            $(document).on('click','#click_search_c', function(){
                var start = $('#start_date_c').val();
                var end =  $('#end_date_c').val();
                var idd = $("#salon_idds").val();
                var tokan =true;
                if(start ==""){
                    $('#error_start_c').html('select start date');
                    tokan = false;
                }
                else
                    $('#error_start_c').html('');
                if(end ==""){
                    $('#error_end_c').html('select end date');
                    tokan = false;
                }
                else
                    $('#error_end_c').html('');
                if(tokan == true){
                    viewRowsalon_filter(idd,base_url+'backend/user/salon_detail_filter');
                    /*get_clientBookingList();
                    getCustomerList();*/
                }
           });

               function viewRowsalon_filter(id,url){
                var short=$(".searchNewFilter option:selected").val();
                var start = $("#start_date_c").val();
                var end = $("#end_date_c").val();
                var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
                    $.ajax({
                            url: url,
                            type: "POST",
                            data:{ id : id,short : short, start_date : start, end_date: end},
                            success: function (data) {
                             var obj = jQuery.parseJSON( data );
                                if(obj.success == 1){ 
                                    if(obj.html.totalrevenew != null)
                                        $("#tot_revenue").html(obj.html.totalrevenew+' €');
                                    else
                                        $("#tot_revenue").html('0 €');
                                    
                                    $("#tot_book").html(obj.html.totalbook);
                                    $("#tot_comp").html(obj.html.totalcomplete);
                                    $("#tot_upcom").html(obj.html.totalupcoming);
                                    $("#tot_canc").html(obj.html.totalcanceled);
                                    $("#tot_noshow").html(obj.html.totalnoshow);
                                    //get_clientBookingList();
                                    //getCustomerList();
                                           
                                }
                            }
                                
                        }); 
                    
                    
                }

                function changeSalonstatus(id){
                    //var id = $(this).att('data-uid');
                        var status = $('#salon_status_change').val();
                        var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
                        $.ajax({
                        url: base_url+"backend/user/update_status_byadmin",
                        type: "POST",
                        data:{'id' : id , 'status' : status},
                        success: function (response) {
                            if(response){
                                if(status == 'active'){
                                 $("#change_salon_status").html('Inactive');
                                 $("#salon_status_change").val('inactive');
                                }
                                else{
                                 $("#change_salon_status").html('Active');
                                 $("#salon_status_change").val('active');   
                                }
                                $("#dropdownMenuButton11").trigger('click');

                            }

                         }
                         
                    });
                }
 $(document).on('click','.get_salondetail', function(){
     var id = $(this).attr('id'); 
     var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
       $.ajax({
                url: base_url+'backend/user/salon_detail',
                type: "POST",
                data:{ id : id},
                success: function (data) {
                 var obj = jQuery.parseJSON( data );
                    if(obj.success == 1){  
                       $("#clientViewProfileHtml").html(obj.html);
                        $("#profile-client-view-modal").modal('show');
                        $('#profile-client-view-modal').modal({backdrop: 'static', keyboard: false});  
                        $(".modal").css('overflow-y','auto');
                        get_clientBookingList();
                        getCustomerList();
                        $('.date-picker').datetimepicker({
                            format: 'DD/MM/YYYY'
                            });
                               
                    }
                }
                    
            }); 
        });
                function get_clientBookingList(url=''){
  
                    var lim=10;
                    var uid=$('#listingTabl').attr('data-uid');
                    var sht=$('#short_by_field').val();
                    var short=$(".on_change_short_c option:selected").val();
                    var start = $("#start_date_c").val();
                    var end = $("#end_date_c").val();
                if(url==''){
                        url=base_url+"backend/user/client_booking_list/"+uid;
                        }
                    
                    //loading();
                    var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
                    $.post(url,{limit:lim,order:sht,short:short,start_date:start,end_date:end},function( data ) {
                     
                        
                        var obj = jQuery.parseJSON( data );
                        if(obj.success=='1'){
                            //var time = $("#selctTimeFilter").val();
                           // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
                            //console.log(obj.html);
                            $("#all_listing").html(obj.html);
                            $("#pagination_clientBooking").html(obj.pagination);
                            
                            
                        }
                       // unloading();    
                    });
                    
                    }

            function getCustomerList(url=''){

                        var lim=10;
                        var order=$("#orderby").val();
                        var shortby=$("#shortby").val();
                        var sch="";
                        var uid=$('#listingTabl').attr('data-uid');
                        var short=$(".on_change_short_c option:selected").val();
                        var start = $("#start_date_c").val();
                        var end = $("#end_date_c").val();
                        //var uid=$('#listingTabl').attr('data-uid');
                        if(url==''){
                          url=base_url+"backend/user/customer_list/"+uid;
                         }
                       
                         var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status); 
                        $.post(url,{limit:lim,search:sch,orderby:order,shortby:shortby,short:short,start_date:start,end_date:end},function( data ) {
                         
                            
                          var obj = jQuery.parseJSON( data );
                                if(obj.success=='1'){
                            $("#allcust_listing").html(obj.html);
                            $("#pagination").html(obj.pagination);
                            
                          }
                         });
                        
                        }

                $(document).on('click','.nav-link', function() {
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');
                 });

                $(document).on('click',"#pagination_clientBooking .page-item a",function(){
                          var url=$(this).attr('href');
                          // console.log("======",url)
                          if(url!=undefined){
                            get_clientBookingList(url);
                            }
                            window.scrollTo(0, 350); 
                            return false;
                          });
                $(document).on('click',"#pagination .page-item a",function(){
                          var url=$(this).attr('href');
                          
                          if(url!=undefined){
                            getCustomerList(url);
                            }
                             window.scrollTo(0, 350);
                          //$("#listingTabl").focus();  
                            return false; 
                          
                          });
 
  </script>
