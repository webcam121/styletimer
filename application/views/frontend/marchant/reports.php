<?php $this->load->view('frontend/common/header'); ?>
<style type="text/css">
  #add_service_popup{
  z-index:1051 !important;
  }
</style>
<div class="d-flex pt-84"> 
<?php $this->load->view('frontend/common/sidebar'); ?>
<link rel="stylesheet" href="<?php echo base_url("assets/cropp/croppie.css");?>" type="text/css" />
<div class="right-side-dashbord w-100 pl-30 pr-30">
          <div class="bgwhite border-radius4 box-shadow1 mb-60 mt-20">
            <!-- tab start -->
            <ul class="nav nav-tabs new_tab_v" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#sale_by_ervice">
                  <?php echo $this->lang->line('Sale_by_Service'); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#sale_by_staff">
                  <?php echo $this->lang->line('Sale_by_Staff'); ?> </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#sale_by_client">
                  <?php echo $this->lang->line('Sale_by_Client'); ?></a>
              </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div id="sale_by_ervice" class="tab-pane active">
                <div class="pt-10 pb-10 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
                  <!-- new filter -->
              <div class="form-group relative my-new-drop-v display-ib">
               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('current_month'); ?></button>
                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" style="max-height: none;height: auto !important;overflow-x: auto;"><!-- Select Filter -->			  
                   
                    <li class="radiobox-image">
                      <input type="radio" id="id_34_report" name="filter_service" class="filterby_service" value="day">
                      <label for="id_34_report"><?php echo $this->lang->line('today'); ?></label>
                    </li>                    
                    <li class="radiobox-image">
                      <input type="radio" id="id_d34y" name="filter_service" class="filterby_service" value="yesterday">
                      <label for="id_d34y"><?php echo $this->lang->line('yesterday'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="id_35_report" name="filter_service" class="filterby_service" value="current_week">
                      <label for="id_35_report"><?php echo $this->lang->line('current_week'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_36_report" name="filter_service" class="filterby_service" checked="checked" value="current_month">
                      <label for="id_36_report"><?php echo $this->lang->line('current_month'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_37_report" name="filter_service" class="filterby_service" value="current_year">
                      <label for="id_37_report"><?php echo $this->lang->line('current_year'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="id_d34cwtd" name="filter_service" class="filterby_service" value="cwtd">
                      <label for="id_d34cwtd"><?php echo $this->lang->line('current_week_to_day'); ?></label>
                    </li>                    
                     <li class="radiobox-image">
                      <input type="radio" id="id_d34cmtd" name="filter_service" class="filterby_service" value="cmtd">
                      <label for="id_d34cmtd"><?php echo $this->lang->line('current_month_to_day'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="id_d34cqtd" name="filter_service" class="filterby_service" value="cqtd">
                      <label for="id_d34cqtd"><?php echo $this->lang->line('current_quarter_to_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d34cytd" name="filter_service" class="filterby_service" value="cytd">
                      <label for="id_d34cytd"><?php echo $this->lang->line('current_year_to_day');?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_036_report" name="filter_service" class="filterby_service" value="last_month">
                      <label for="id_036_report"><?php echo $this->lang->line('last_month'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d3430" name="filter_service" class="filterby_service" value="30">
                      <label for="id_d3430"><?php echo $this->lang->line('last_30_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="id_d3490" name="filter_service" class="filterby_service" value="90">
                      <label for="id_d3490"><?php echo $this->lang->line('last_90_day'); ?></label>
                    </li>  
                    <li class="radiobox-image">
                      <input type="radio" id="id_037_report" name="filter_service" class="filterby_service" value="last_year">
                      <label for="id_037_report"><?php echo $this->lang->line('last_year'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idreport_d38all" name="filter_service" class="filterby_service" value="all">
                      <label for="idreport_d38all"><?php echo $this->lang->line('all_time'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="id_38_report" name="filter_service" class="filterby_service" value="date">
                      <label for="id_38_report"><?php echo $this->lang->line('serach_by_date'); ?></label>
                    </li>
                    
                  </ul>
                </div>                
              </div>
              
              <div id="servicedate_custom_filter" class="display-n">
				  <div class="form-group date_pecker display-ib mr-20 filterdate" style="max-width: 140px;">
					<label class="inp">
					  <input type="text" id="service_startdate" placeholder="Start" value="" class="form-control">
					</label>
				   <span class="error" id="start_error"></span>
				  </div>

				  <div class="form-group date_pecker display-ib filterdate" style="max-width: 140px;">
					<label class="inp">
					  <input type="text" id="service_enddate"  placeholder="Ende" value="" class="form-control">
					</label>
					<span class="error" id="end_error"></span>
				  </div>
				  <div class="display-ib filterdate">
					<i id="search_filter_service" class="fas fa-search colororange mt-0 ml-2 font-size-24"></i>
				  </div>


             </div> 
              <!-- new filer end -->
              <span class="ml-auto mb-3">
              <a href="javascript:void(0);" data-id="csv" class="display-ib cursol-p m-1 export_filterreport_service"><img class="width30" src="<?php echo base_url('assets/frontend/images/csv-icon.svg'); ?>"></a>
              <a href="javascript:void(0)" data-id="excel" class="display-ib cursol-p m-1 export_filterreport_service"><img class="width30" src="<?php echo base_url('assets/frontend/images/exel-icon.svg'); ?>"></a>
              </span>
                </div>

                <div class="my-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th class="pl-4 height56v text-center">
						  <a href="javascript:void(0)" class="color333 shorting_service" id="name">Service<div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="service_name" value="desc">
                        </th>
                      <th class="text-center height56v text-center"><a href="javascript:void(0)" class="color333 shorting_service" id="complete"><?php echo $this->lang->line('Completed2'); ?> <span class="tablete-none"><?php //echo $this->lang->line('Bookings'); ?> </span>
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="service_complete" value="desc">
                        </th>
<!--
                      <th class="text-center height56v text-center"><a href="javascript:void(0)" class="color333 shorting_service" id="confirm">CONFIRMED BOOKINGS
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php //echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php // echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="service_confirm" value="desc">
                        </th>
-->
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_service" id="cancel"><?php echo ucfirst(strtolower($this->lang->line('Cancelled'))); ?> <?php /*<span class="tablete-none"><?php echo $this->lang->line('Bookings'); ?></span>*/ ?>
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="service_cancel" value="desc">
                       </th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_service" id="total"><?php echo $this->lang->line('Total'); ?> <span class="tablete-none"><?php //echo $this->lang->line('Bookings'); ?> </span>
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="service_total" value="desc">
                        </th>
                        <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_service" id="revenue"><?php echo $this->lang->line('VALUE_SERVICE'); ?>
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="service_revenue" value="desc">
                        </th>
                    </tr>
                  </thead>
                    <tbody id="allservice_listing">
                    </tbody>
                  </table>
                  <input type="hidden" id="short_by_servicefield" value="">
                   <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;" id="pagination_service">
               
                    </ul>
                  </nav>
              </div>
                <!-- end top-table-droup -->
              </div>
              <div id="sale_by_staff" class="tab-pane ">
                <div class="pt-10 pb-10 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
              <div class="form-group relative my-new-drop-v display-ib">
               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('current_month'); ?></button>
                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" style="max-height: none;height: auto !important;overflow-x: auto;"><!-- Select Filter -->
					 
                    <li class="radiobox-image">
                      <input type="radio" id="idst_34" name="filter_staff" class="filterby_staff" value="day">
                      <label for="idst_34"><?php echo $this->lang->line('today'); ?></label>
                    </li>
                      <li class="radiobox-image">
                      <input type="radio" id="idst_d34y" name="filter_staff" class="filterby_staff" value="yesterday">
                      <label for="idst_d34y"><?php echo $this->lang->line('yesterday'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="idst_135" name="filter_staff" class="filterby_staff" value="current_week">
                      <label for="idst_135"><?php echo $this->lang->line('current_week'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idst_136" name="filter_staff" class="filterby_staff" checked="checked" value="current_month">
                      <label for="idst_136"><?php echo $this->lang->line('current_month'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idst_137" name="filter_staff" class="filterby_staff" value="current_year">
                      <label for="idst_137"><?php echo $this->lang->line('current_year'); ?> </label>
                    </li>
                       <li class="radiobox-image">
                      <input type="radio" id="idst_d34cwtd" name="filter_staff" class="filterby_staff" value="cwtd">
                      <label for="idst_d34cwtd"><?php echo $this->lang->line('current_week_to_day'); ?></label>
                    </li>                    
                     <li class="radiobox-image">
                      <input type="radio" id="idst_d34cmtd" name="filter_staff" class="filterby_staff" value="cmtd">
                      <label for="idst_d34cmtd"><?php echo $this->lang->line('current_month_to_day'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="idst_d34cqtd" name="filter_staff" class="filterby_staff" value="cqtd">
                      <label for="idst_d34cqtd"><?php echo $this->lang->line('current_quarter_to_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idst_d34cytd" name="filter_staff" class="filterby_staff" value="cytd">
                      <label for="idst_d34cytd"><?php echo $this->lang->line('current_year_to_day'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="idst_1036" name="filter_staff" class="filterby_staff" value="last_month">
                      <label for="idst_1036"><?php echo $this->lang->line('last_month'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idst_d3430" name="filter_staff" class="filterby_staff" value="30">
                      <label for="idst_d3430"><?php echo $this->lang->line('last_30_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idst_d3490" name="filter_staff" class="filterby_staff" value="90">
                      <label for="idst_d3490"><?php echo $this->lang->line('last_90_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idst_1037" name="filter_staff" class="filterby_staff" value="last_year">
                      <label for="idst_1037"><?php echo $this->lang->line('last_year'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="idst_d38all" name="filter_staff" class="filterby_staff" value="all">
                      <label for="idst_d38all"><?php echo $this->lang->line('all_time'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idst_138" name="filter_staff" class="filterby_staff" value="date">
                      <label for="idst_138"><?php echo $this->lang->line('serach_by_date'); ?></label>
                    </li> 
                    
                  </ul>
                </div>                
              </div>

              <div id="staffdate_custom_filter" class="display-n d-flex">
				  <div class="form-group date_pecker display-ib mr-20 filterdate" style="max-width: 140px;">
					<label class="inp">
					  <input type="text"  placeholder="Start" class="form-control staff_datepicker" id="staff_startdate">
					</label>
				   <span class="error" id="start_error_staff"></span>
				  </div>

				  <div class="form-group date_pecker display-ib filterdate" style="max-width: 140px;">
					<label class="inp">
					  <input type="text" placeholder="Ende" class="form-control staff_datepicker" id="staff_enddate">
					</label>
					<span class="error" id="end_error_staff"></span>
				  </div>
				  <div id="staff_error" class="error"></div>

				  <div class="display-ib filterdate pt-2">
					<i id="search_filter_staff" class="fas fa-search colororange mt-0 ml-2 font-size-24"></i>
				  </div>
             </div>
              <!-- new filer end -->
                <span class="ml-auto mb-3">
              <a href="javascript:void(0);" data-id="csv" class="display-ib cursol-p m-1 export_filterreport_staff"><img class="width30" src="<?php echo base_url('assets/frontend/images/csv-icon.svg'); ?>"></a>
              <a href="javascript:void(0)" data-id="excel" class="display-ib cursol-p m-1 export_filterreport_staff"><img class="width30" src="<?php echo base_url('assets/frontend/images/exel-icon.svg'); ?>"></a>
            </span>


                  <?php /*!-- <div class="ml-auto">
                    
                    <div class="form-froup v_date_time_picker relative display-ib mr-2">
                        <input type="text" name="" placeholder="Start" class="height56v width120 form-control staff_datepicker" id="staff_startdate" value="<?php echo date("m/d/Y", strtotime("-1 months")); ?>">
                        <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue">
                    </div>
                    <div class="form-froup v_date_time_picker relative display-ib">
                        <input type="text" name="" placeholder="Ende" class="height56v widtd120 form-control staff_datepicker" id="staff_enddate" value="<?php echo date("m/d/Y") ?>">
                        <img src="<?php echo base_url('assets/frontend/images/blue-calender.svg'); ?>" class="v_time_claender_icon_blue">
                    </div>
                    <div id="staff_error" class="error"></div>
                  </div> -- */ ?>
                </div>

                <div class="my-table">
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="pl-4 height56v "> <a href="javascript:void(0)" class="color333 shorting_staff" id="first_name"><?php echo ucfirst(strtolower($this->lang->line('Employee_Name'))); ?>
                        <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_first_name" value="desc"></th>
                      </th>
                        <th class="text-center height56v text-center">
                          <a href="javascript:void(0)" class="color333 shorting_staff" id="complete"><?php echo $this->lang->line('Completed2'); ?> 
                          <!-- <span class="tablete-none"><?php echo $this->lang->line('Bookings'); ?></span> -->
                            <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_complete" value="desc"></th>
                        <th class="text-center height56v"> <a href="javascript:void(0)" class="color333 shorting_staff" id="confirm"><?php echo $this->lang->line('Confirmed'); ?> 
                        <!-- <span class="tablete-none"><?php echo $this->lang->line('Bookings'); ?></span> -->
                          <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div>
                        </a>
                            <input type="hidden" id="short_confirm" value="desc"></th>
                        </th>
                        <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_staff" id="cancel"><?php echo ucfirst(strtolower($this->lang->line('Cancelled'))); ?> 
                        <!-- <span class="tablete-none"><?php echo $this->lang->line('Bookings'); ?></span> -->
                        <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_cancel" value="desc"></th>
                      </th>
                        <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_staff" id="total"><?php echo $this->lang->line('Total'); ?> <span class="tablete-none"><?php //echo $this->lang->line('Bookings'); ?></span>
                           <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_total" value="desc">
                        </th>
                        <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_staff" id="revenue"><?php echo ucfirst(strtolower($this->lang->line('Revenue'))); ?>
                           <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_revenue" value="desc">
                        </th>
                         <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_staff" id="totalTip"><?php echo ucfirst(strtolower($this->lang->line('Tips'))); ?>
                           <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_totalTip" value="desc">
                        </th>
                        <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_staff" id="commission"><?php echo ucfirst(strtolower($this->lang->line('Commission'))); ?>
                           <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="short_commission" value="desc">
                        </th>
                      </tr>
                    </thead>
                    <tbody id="allstaff_listing">
                      
                    </tbody>
                  </table>
                  <input type="hidden" id="short_by_fieldstaff" value="">
                   <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;" id="pagination_staff">
               
                    </ul>
                  </nav>
                </div>
              </div>
              <div id="sale_by_client" class="tab-pane ">
              <div class="pt-10 pb-10 pl-30 pr-30 relative top-table-droup d-flex align-items-center">
              <div class="form-group relative my-new-drop-v display-ib">
               <!--  <span class="color999 fontfamily-medium font-size-14">Filter</span> -->
                <div class="btn-group multi_sigle_select widthfit80 mr-20 ml-2 display-ib"> 
                  <button data-toggle="dropdown" style="" class="btn btn-default dropdown-toggle mss_sl_btn" id="btn_text"><?php echo $this->lang->line('current_month'); ?></button>
                  <ul class="dropdown-menu mss_sl_btn_dm widthfit80" style="max-height: none;height: auto !important;overflow-x: auto;"> <!-- Select Filter -->					
                    <li class="radiobox-image">
                      <input type="radio" id="idcl_34" name="filter_client" class="filterby_client" value="day">
                      <label for="idcl_34"><?php echo $this->lang->line('yoday'); ?></label>
                    </li>
                      <li class="radiobox-image">
                      <input type="radio" id="idcl_d34y" name="filter_client" class="filterby_client" value="yesterday">
                      <label for="idcl_d34y"><?php echo $this->lang->line('yesterday'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idcl_35" name="filter_client" class="filterby_client" value="current_week">
                      <label for="idcl_35"><?php echo $this->lang->line('current_week'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idcl_36" name="filter_client" class="filterby_client" checked="checked" value="current_month">
                      <label for="idcl_36"><?php echo $this->lang->line('current_month'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idcl_37" name="filter_client" class="filterby_client" value="current_year">
                      <label for="idcl_37"><?php echo $this->lang->line('current_year'); ?> </label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="idcl_d34cwtd" name="filter_client" class="filterby_client" value="cwtd">
                      <label for="idcl_d34cwtd"><?php echo $this->lang->line('current_week_to_day'); ?></label>
                    </li>                    
                     <li class="radiobox-image">
                      <input type="radio" id="idcl_d34cmtd" name="filter_client" class="filterby_client" value="cmtd">
                      <label for="idcl_d34cmtd"><?php echo $this->lang->line('current_month_to_day'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="idcl_d34cqtd" name="filter_client" class="filterby_client" value="cqtd">
                      <label for="idcl_d34cqtd"><?php echo $this->lang->line('current_quarter_to_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idcl_d34cytd" name="filter_client" class="filterby_client" value="cytd">
                      <label for="idcl_d34cytd"><?php echo $this->lang->line('current_year_to_day'); ?></label>
                    </li> 
                    <li class="radiobox-image">
                      <input type="radio" id="idcl_036" name="filter_client" class="filterby_client" value="last_month">
                      <label for="idcl_036"><?php echo $this->lang->line('last_month'); ?></label>
                    </li>       
                    <li class="radiobox-image">
                      <input type="radio" id="idcl_d3430" name="filter_client" class="filterby_client" value="30">
                      <label for="idcl_d3430"><?php echo $this->lang->line('last_30_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idcl_d3490" name="filter_client" class="filterby_client" value="90">
                      <label for="idcl_d3490"><?php echo $this->lang->line('last_90_day'); ?></label>
                    </li>
                    <li class="radiobox-image">
                      <input type="radio" id="idcl_037" name="filter_client" class="filterby_client" value="last_year">
                      <label for="idcl_037"><?php echo $this->lang->line('last_year'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="idcl_d38all" name="filter_client" class="filterby_client" value="all">
                      <label for="idcl_d38all"><?php echo $this->lang->line('all_time'); ?></label>
                    </li>
                     <li class="radiobox-image">
                      <input type="radio" id="idcl_38" name="filter_client" class="filterby_client" value="date">
                      <label for="idcl_38"><?php echo $this->lang->line('serach_by_date'); ?></label>
                    </li>
                  </ul>
                </div>                
              </div>
               <div class="form-group relative my-new-drop-v mr-20 display-ib">
                  <input type="text" name="sch" placeholder="<?php echo $this->lang->line('Search_by_Name_client'); ?>" value="" class="widthfit310v cust_search" id="sch_data_cus">
                </div>
                  <div id="clientdate_custom_filter" class="display-n d-flex">
						  <div class="form-group date_pecker display-ib mr-20 filterdate" style="max-width: 140px;">
							<label class="inp">
							  <input type="text" placeholder="Ende" class=" form-control staff_datepicker" id="client_startdate" value="<?php echo date("d.m.Y", strtotime("-1 months")); ?>">
							</label>
						   <span class="error" id="start_error_client"></span>
						  </div>

						  <div class="form-group date_pecker display-ib filterdate" style="max-width: 140px;">
							<label class="inp">
							  <input type="text" placeholder="Ende" class=" form-control staff_datepicker" id="client_enddate" value="<?php echo date("d.m.Y") ?>">
							</label>
							<span class="error" id="end_error_client"></span>
						  </div>
						  <div id="client_error" class="error"></div>
						  <div class="display-ib filterdate pt-2" style="">
							<i id="search_filter_client" class="fas fa-search colororange mt-0 ml-2 font-size-24"></i>
						  </div>
						</div>
            <span class="ml-auto mb-3">
              <a href="javascript:void(0);" data-id="csv" class="display-ib cursol-p m-1 export_filterreport_client"><img class="width30" src="<?php echo base_url('assets/frontend/images/csv-icon.svg'); ?>"></a>
              <a href="javascript:void(0)" data-id="excel" class="display-ib cursol-p m-1 export_filterreport_client"><img class="width30" src="<?php echo base_url('assets/frontend/images/exel-icon.svg'); ?>"></a>
            </span>
               </div>
                <div class="my-table">
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="pl-4 height56v "> <a href="javascript:void(0)" class="color333 shorting_client" id="first_name"><?php echo ucfirst(strtolower($this->lang->line('Customer'))); ?>
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="client_first_name" value="desc">
                      </th>
                        <th class="pl-4 height56v "> <a href="javascript:void(0)" class="color333 shorting_client" id="email"><?php echo ucfirst(strtolower($this->lang->line('Email'))); ?>
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="client_email" value="desc">
                      </th>
                        <th class="text-center height56v text-center"><a href="javascript:void(0)" class="color333 shorting_client" id="complete"><?php echo $this->lang->line('Completed2'); ?> <span class="tablete-none"><?php //echo $this->lang->line('Bookings'); ?></span>
                        <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                         <input type="hidden" id="client_complete" value="desc">
                      </th>
                        <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_client" id="confirm"><?php echo $this->lang->line('Confirmed'); ?> <span class="tablete-none"><?php //echo $this->lang->line('Bookings'); ?></span>
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="client_confirm" value="desc">
                      </th>
                        <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_client" id="cancel"><?php echo ucfirst(strtolower($this->lang->line('Cancelled'))); ?> <span class="tablete-none"><?php //echo $this->lang->line('Bookings'); ?></span>
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                         <input type="hidden" id="client_cancel" value="desc">
                      </th>
                        <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_client" id="total"><?php echo $this->lang->line('Total'); ?> <span class="tablete-none"><?php //echo $this->lang->line('Bookings'); ?></span> 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="client_total" value="desc">
                      </th>
                      <th class="text-center height56v"><a href="javascript:void(0)" class="color333 shorting_client" id="revenue"><?php echo ucfirst(strtolower($this->lang->line('Revenue'))); ?> 
                         <div class="display-ib vertical-middle ml-1">
                               <img src="<?php echo base_url('assets/frontend/images/caret-arrow-up.png'); ?>" class="display-b " style="width:8px;"> 
                          <img src="<?php echo base_url('assets/frontend/images/sort-down.png'); ?>" class="display-b " style="width:8px;"> 
                        </div></a>
                        <input type="hidden" id="client_revenue" value="desc">
                      </th>
                      
                      </tr>
                    </thead>
                    <tbody id="allclient_listing">
                      
                    </tbody>
                  </table>
                  <input type="hidden" id="short_by_fieldclient" value="">
                   <nav aria-label="" class="text-right pr-40 pt-3 pb-3">
                    <ul class="pagination" style="display: inline-flex;" id="pagination_client">
               
                    </ul>
                  </nav>
                  
                </div>
              </div>
            </div>
          </div>


          <!-- dashboard right side end -->       
        </div>


        <div class="modal fade" id="add-employee-modal">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">    
            <div class="modal-content"> 
              <div class="modal-body" id="addEmployeeHtmlPopup">
                      
                 
              </div>
            </div>
          </div>
        </div>
        <!-- add employee modal end-->


        <?php $this->load->view('frontend/common/footer_script'); 
        $this->load->view('frontend/marchant/assign_service_poup');
        $this->load->view('frontend/common/crop_pic');  
        
    $pinstatus = get_pin_status($this->session->userdata('st_userid')); 
//echo current_url().'=='.$_SERVER['HTTP_REFERER'];
    if($pinstatus->pinstatus=='on'){
        $this->load->view('frontend/marchant/profile/ask_pin_popup');
      }  
  ?>
        <script type="text/javascript" src="<?php echo base_url('assets/cropp/croppie.js'); ?>"></script>

    <script type="text/javascript">
      $(function() {
        $(window).on("scroll", function() {
            if($(window).scrollTop() > 90) {
                $(".header").addClass("header_top");
            } else {
                //remove the background property so it comes transparent again (defined in your css)
               $(".header").removeClass("header_top");
            }
        });
      });
    

       $("#service_startdate").datepicker({
           uiLibrary: 'bootstrap4',
           locale: 'de-de',
         format: 'dd.mm.yyyy'
       });
       $('#service_enddate').datepicker({
           uiLibrary: 'bootstrap4',
           locale: 'de-de',
         format: 'dd.mm.yyyy'
       });
       $("#staff_startdate").datepicker({
           uiLibrary: 'bootstrap4',
           locale: 'de-de',
         format: 'dd.mm.yyyy'
       });
       $('#staff_enddate').datepicker({
           uiLibrary: 'bootstrap4',
           locale: 'de-de',
         format: 'dd.mm.yyyy'
       });
       $("#client_startdate").datepicker({
           uiLibrary: 'bootstrap4',
           locale: 'de-de',
         format: 'dd.mm.yyyy'
       });
       $('#client_enddate').datepicker({
           uiLibrary: 'bootstrap4',
           locale: 'de-de',
           format: 'dd.mm.yyyy'
       });
   
      // Tooltips
		$('[data-toggle="popover"]').popover({
			trigger: 'hover',
				'placement': 'top'
		});


$(document).ready(function(){
    
    getserviceList();
	getstaffList();
	getclientList();
	
	
  function getstaffList(url=''){
  	
    var lim=$("input[name='staff_limit']:checked").val();
    var status=$("input[name='emp_inactive']:checked").val();
    if($('#emp_inactive').prop("checked") == true)
      status='inactive';
    else
      status='active';
    
    var filter=$("input[name='filter_staff']:checked").val();
    
    if(filter==undefined)
     {
	  filter="";
	 }


    var s_date =$("#staff_startdate").val();
   	var e_date =$("#staff_enddate").val();
    var sht=$('#short_by_fieldstaff').val();

    var s_date1 = new Date($('#staff_startdate').val());
    var e_date1 = new Date($('#staff_enddate').val());

   	if(s_date1 > e_date1){
   		$("#staff_error").html('End date should be greater then start date');
   		return false;
   	}
   	$("#staff_error").html('');

   	if(url==''){
      url=base_url+"merchant/saleby_staff_list";
     }
   
    loading();
    var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
    $.post(url,{limit:lim,startdate:s_date,enddate:e_date,order:sht,status:status,filter:filter},function( data ) {
     
        
      var obj = jQuery.parseJSON( data );
            if(obj.success=='1'){
        //var time = $("#selctTimeFilter").val();
           // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
        //console.log(obj.html);
        $("#allstaff_listing").html(obj.html);
        $("#pagination_staff").html(obj.pagination);
   
      }
      unloading();  
      });
    
    }
    $(document).on('click',"#emp_inactive",function(){
      getstaffList();
    })
    $(document).on('click',".shorting_staff",function(){

      var short = $(this).attr('id');
      var chk = $("#short_"+short).val();
      if(chk == 'asc'){
       $("#short_"+short).val('desc');
       $("#short_by_fieldstaff").val(short+' desc');
      }
      else{
        $("#short_"+short).val('asc');
        $("#short_by_fieldstaff").val(short+' asc');
      }

      getstaffList();
  });

   $(document).on('change',".changestaff_limit",function(){
  		getstaffList();
   });

   //~ $('#staff_startdate').change(function(){
   		//~ getstaffList();
		//~ });
   //~ $('#staff_enddate').change(function(){
    	//~ getstaffList();
		//~ });
	$(document).on('change','.filterby_staff',function(){
	    var filter=$("input[name='filter_staff']:checked").val();
	    
	    if(filter=='date'){
			 $("#staffdate_custom_filter").removeClass('display-n');
			}
		 else{
			 $("#staffdate_custom_filter").addClass('display-n');	
			  getstaffList();
			 }
	 });
		
    $(document).on("click","#search_filter_staff",function(){
	    var s_date =$("#staff_startdate").val();
    	var e_date =$("#staff_enddate").val();
    	var token = 1;
    	//alert(s_date)
    	if(s_date==""){
			token = 0;
			$("#start_error_staff").text('Please select start date');
			}
		if(e_date==""){
			token = 0;
			$("#end_error_staff").text('Please select start date');
			}	
	   if(token==1){		   
		   $("#start_error_staff").text('');
		   $("#end_error_staff").text('');
		     getstaffList();
		   }
		else{
			return false;
			}   
	  
	 });

   $(document).on('click',"#pagination_staff .page-item a",function(){
  	 var url=$(this).attr('href');
  
	  if(url!=undefined){
	    getstaffList(url);
	    }
     	window.scrollTo(0, 350);
  		return false; 
  	});
   
   
   function getclientList(url=''){
  	
  	 var filter=$("input[name='filter_client']:checked").val();
    
    if(filter==undefined)
     {
	  filter="";
	 }
    var sch = $.trim($("#sch_data_cus").val());

    var lim=$("input[name='client_limit']:checked").val();
   	var s_date =$("#client_startdate").val();
   	var e_date =$("#client_enddate").val();
     var shtc=$('#short_by_fieldclient').val();

    var s_date1 = new Date($('#client_startdate').val());
    var e_date1 = new Date($('#client_enddate').val());
   	if(s_date1 > e_date1){
   		$("#client_error").html('End date should be greater then start date');
   		return false;
   	}
   	$("#client_error").html('');

   	if(url==''){
      url=base_url+"merchant/saleby_client_list";
     }
   
    loading();
    var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
    $.post(url,{limit:lim,startdate:s_date,enddate:e_date,order:shtc,filter:filter,search:sch},function( data ) {
     
        
      var obj = jQuery.parseJSON( data );
            if(obj.success=='1'){
        //var time = $("#selctTimeFilter").val();
           // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
        //console.log(obj.html);
        $("#allclient_listing").html(obj.html);
        $("#pagination_client").html(obj.pagination);
   
      }
      unloading();  
      });
    
    }

     $(document).on('click',".shorting_client",function(){

      var short = $(this).attr('id');
      var chk = $("#client_"+short).val();
      if(chk == 'asc'){
       $("#client_"+short).val('desc');
       $("#short_by_fieldclient").val(short+' desc');
      }
      else{
        $("#client_"+short).val('asc');
        $("#short_by_fieldclient").val(short+' asc');
      }

      getclientList();
  });


   $(document).on('change',".changeclient_limit",function(){
  		getclientList();
   });

   $(document).on('keyup',".cust_search",function(){
     getclientList();
  });

  $(document).on('change','.filterby_client',function(){
	    var filter=$("input[name='filter_client']:checked").val();
	    
	    if(filter=='date'){
			 $("#clientdate_custom_filter").removeClass('display-n');
			}
		 else{
			 $("#clientdate_custom_filter").addClass('display-n');	
			  getclientList();
			 }
	 });

   //~ $('#client_startdate').change(function(){
    	//~ getclientList();
		//~ });
   //~ $('#client_enddate').change(function(){
    	//~ getclientList();
		//~ });
		
	 $(document).on("click","#search_filter_client",function(){
	    var s_date =$("#client_startdate").val();
    	var e_date =$("#client_enddate").val();
    	var token = 1;
    	//alert(s_date)
    	if(s_date==""){
			token = 0;
			$("#start_error_client").text('Please select start date');
			}
		if(e_date==""){
			token = 0;
			$("#end_error_client").text('Please select start date');
			}	
	   if(token==1){
		   
		   $("#start_error_client").text('');
		   $("#end_error_client").text('');
		     getclientList();
		   }
		else{
			return false;
			}   
	  
	 });	

    $(document).on('click',"#pagination_client .page-item a",function(){
  	 var url=$(this).attr('href');
  
	  if(url!=undefined){
	    getclientList(url);
	    }
     	window.scrollTo(0, 350);
  		return false; 
  	});
   

   function getserviceList(url=''){
    var filter=$("input[name='filter_service']:checked").val();
    
    if(filter==undefined)
     {
	  filter="";
	 }
    
    var lim=$("input[name='service_limit']:checked").val();
   	var s_date =$("#service_startdate").val();
   	var e_date =$("#service_enddate").val();
   	 var shtc=$('#short_by_servicefield').val();

    var s_date1 = new Date($('#service_startdate').val());
    var e_date1 = new Date($('#service_enddate').val());
   	if(s_date1 > e_date1){
   		$("#service_error").html('End date should be greater then start date');
   		return false;
   	}
   	$("#service_error").html('');

   	if(url==''){
      url=base_url+"merchant/saleby_service_list";
     }
   
    loading();
    var status = localStorage.getItem('STATUS');
			if(status=='LOGGED_OUT'){
				console.log('STATUS='+status);
				console.log(base_url)
				window.location.href = base_url;
				$(window.location)[0].replace(base_url);
				$(location).attr('href',base_url);
			}
			console.log('STATUS='+status);
    $.post(url,{limit:lim,startdate:s_date,enddate:e_date,order:shtc,filter:filter},function(data){
     
        
      var obj = jQuery.parseJSON( data );
            if(obj.success=='1'){
        //var time = $("#selctTimeFilter").val();
           // if(time=='')$("#selctTimeFilter").val('08:00-20:00');
        //console.log(obj.html);
        $("#allservice_listing").html(obj.html);
        $("#pagination_service").html(obj.pagination);
   
      }
      unloading();  
      });
    
    }

 $(document).on('click',".shorting_service",function(){

      var short1 = $(this).attr('id');
      var chk = $("#service_"+short1).val();
      if(chk == 'asc'){
       $("#service_"+short1).val('desc');
       $("#short_by_servicefield").val(short1+' desc');
      }
      else{
        $("#service_"+short1).val('asc');
        $("#short_by_servicefield").val(short1+' asc');
      }

      getserviceList();
  });



   $(document).on('change',".changeservice_limit",function(){
  		getserviceList();
   });
  $(document).on('change','.filterby_service',function(){
	    var filter=$("input[name='filter_service']:checked").val();
	    
	    if(filter=='date'){
			 $("#servicedate_custom_filter").removeClass('display-n');
			}
		 else{
			 $("#servicedate_custom_filter").addClass('display-n');	
			  getserviceList();
			 }
	 });

  $(document).on("click","#search_filter_service",function(){
	    var s_date =$("#service_startdate").val();
    	var e_date =$("#service_enddate").val();
    	var token = 1;
    	//alert(s_date)
    	if(s_date==""){
			token = 0;
			$("#start_error").text('Please select start date');
			}
		if(e_date==""){
			token = 0;
			$("#end_error").text('Please select start date');
			}	
	   if(token==1){
		   
		   $("#start_error").text('');
		   $("#end_error").text('');
		     getserviceList();
		   }
		else{
			return false;
			}   
	  
	 });
   //~ $('#service_startdate').change(function(){
    	//~ getserviceList();
		//~ });
   //~ $('#service_enddate').change(function(){
    	//~ getserviceList();
		//~ });

   $(document).on('click',"#pagination_service .page-item a",function(){
  	 var url=$(this).attr('href');
  
	  if(url!=undefined){
	    getserviceList(url);
	    }
     	window.scrollTo(0, 350);
  		return false; 
  	});

   $(document).on('click','.export_filterreport_service',function(){
        var type = $(this).attr('data-id');
        var start = '';
        var end = '';
        var filter=$("input[name='filter_service']:checked").val();
    
          if(filter==undefined)
           { filter=""; }
         
          if(filter =='date'){
            var start = $("#service_startdate").val();
            var end = $("#service_enddate").val();
          }
          var shortby=$('#short_by_servicefield').val();
    
    window.location.href = base_url+'merchant/service_report_export_tocsv/'+type+'?filter='+filter+'&startdate='+start+'&enddate='+end+'&order='+shortby;
      });

   $(document).on('click','.export_filterreport_staff',function(){
        var type = $(this).attr('data-id');
        var start = '';
        var end = '';
        var filter=$("input[name='filter_staff']:checked").val();
    
          if(filter==undefined)
           { filter=""; }
         var status=$("input[name='emp_inactive']:checked").val();
        if($('#emp_inactive').prop("checked") == true)
          status='inactive';
        else
          status='active';
        

          if(filter =='date'){
            var start = $("#staff_startdate").val();
            var end = $("#staff_enddate").val();
          }
          var shortby=$('#short_by_fieldstaff').val();
    
    window.location.href = base_url+'merchant/staff_report_export_tocsv/'+type+'?filter='+filter+'&startdate='+start+'&enddate='+end+'&order='+shortby+'&status='+status;
      });


   $(document).on('click','.export_filterreport_client',function(){
        var type = $(this).attr('data-id');
        var start = '';
        var end = '';
        var filter=$("input[name='filter_client']:checked").val();
    
          if(filter==undefined)
           { filter=""; }
          var search = $.trim($("#sch_data_cus").val());

          var lim=$("input[name='client_limit']:checked").val();
          if(filter =='date'){
            var start = $("#client_startdate").val();
            var end = $("#client_enddate").val();
          }
          var shortby=$('#short_by_fieldclient').val();

          
    window.location.href = base_url+'merchant/client_report_export_tocsv/'+type+'?filter='+filter+'&startdate='+start+'&enddate='+end+'&order='+shortby+'&search='+search;
      });

   $image_crop = $('#image_demo').croppie({
    enableExif: false,
    viewport: {
      width:250,
      height:250,
      type:'square' //circle
    },
    boundary:{
      width:500,
      height:400
    }
    });

   $(document).on('change','#profile_pic', function(){
    var reader = new FileReader();
    //console.log(reader);
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
      type:'canvas',
      size:'viewport'
    }).then(function(response){
    loading();
      $.ajax({
        url:"<?php echo base_url('profile/profile_imagecropp'); ?>",
        type: "POST",
        data:{"image": response},
        success:function(data)
        { var obj = $.parseJSON(data);
          $('#uploadimageModal').modal('hide');
          unloading();
          $('.modal').css('overflow-y','auto');
          // var $el = $('#profile_pic');
           //$el.wrap('<form>').closest('form').get(0).reset();
          // $el.unwrap();
         // $('#upload_image').attr('data-url','');
          //$('#profile-picture').attr('data-img',obj.status);
          $('#EmpProfile').attr('src',obj.image);
        }
      });
    })
  });

  $(document).on('click','.editEmp',function(){
    var id=$(this).attr('data-id');
    //alert(id);
       //var url = $("#"+id).data('href');
       getaddEmployeeHtml(id);
    });


   function getaddEmployeeHtml(id){
    var gurl=base_url+'merchant/dashboard_addemployee/'+id;
     $.get(gurl,function(data){
        var obj = jQuery.parseJSON( data );
       if(obj.success=='1'){
          $("#addEmployeeHtmlPopup").html(obj.html);
          $("#add-employee-modal").modal('show');
               
         }

      });
  
    }

$("#close_service_modal").click(function(){
      var html="";
      var arr=[];
      var subCat=[];
      $(".select_asn_service:checked").each(function() {
        var val=$(this).val();
        var subcatVal=$(this).attr('data-subcat');
          subCat.push(subcatVal);
          arr.push(val);
          
          var id=$(this).attr('id');
              var text=$(this).attr('data-text');
               html=html+'<span class="bge8e8e8 border-radius4 pt-1 pb-1 pl-15 pr-15 color333 font-size-14 display-ib m-1 '+id+'">'+text+'<img src="<?php echo base_url(); ?>assets/frontend/images/search-crose-small-icon.svg" class="ml-4 deselect_service" data-val="'+val+'" data-id="'+id+'" style="cursor:pointer"></span>';
            
      });
       $("#all_service_tag").append(html);
             $("#all_select_service").val(arr.join());
             $("#all_subcat").val(subCat.join());
      $("#all_service_tag").html(html);
       $("#add_service_popup").modal("hide");
       $(".modal").css('overflow-y','auto');
    
    });

$(document).on('click','#remove_image_emp',function(){
        var id= $(this).attr('data-id');
        var enid = $(this).attr('data-enid'); 

         Swal.fire({
            title: '<?php echo $this->lang->line("are_you_sure"); ?>',
            text: "<?php echo $this->lang->line("you_want_delete_image"); ?>",
            type: 'warning',
            showCancelButton: true,
            reverseButtons:true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Bestätigen'
          }).then((result) => {
          if (result.value) {
            $.ajax({
                      url: base_url+'profile/delete_profile_image',
                      type: "POST",
                      data:{id:id},
                      success: function (response) {
                        //location.reload();
                          $('#empimg_'+id).attr('src',base_url+'assets/frontend/images/user-icon-gret.svg');
                          getaddEmployeeHtml(enid);
                       }
                  });
            
            }else{
              return false;
              }
          });  

      });


});
$(document).on('click','#conf_close', function(){
      var id = $(this).attr('data-user');
      if(id != undefined){ 
         getClientProfile(id);
        }
   }); 
</script>
