<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    html{overflow:auto !important;}
    .font-size-16{font-size:16px;}
    .colororange{color:#FF9944;}
    .colore99999940{color:#99999950;}
    .rating { 
	  border: none;
	  float: left;
	}

	.rating > input { display: none; } 
	.rating > label:before { 
	  margin: 5px;
	  font-size: 24px;
	  font-family: FontAwesome;
	  display: inline-block;
	  content: "\f005";
	}

	.rating > .half:before { 
	  content: "\f089";
	  position: absolute;
	}

	.rating > label { 
	  color: #ddd; 
	 float: right; 
	}

	/***** CSS Magic to Highlight Stars on Hover *****/

	.rating > input:checked ~ label, /* show gold star when clicked */
	.rating:not(:checked) > label:hover, /* hover current star */
	.rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

	.rating > input:checked + label:hover, /* hover current star when changing rating */
	.rating > input:checked ~ label:hover,
	.rating > label:hover ~ input:checked ~ label, /* lighten current selection */
	.rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 
	.width7{ width:6%!important; }
</style>
<?php $this->load->view('backend/common/header'); ?>


<?php $this->load->view('backend/common/sidebar'); ?>       
            
            
            <section id="content">
                <div class="container">
                    <div class="block-header">
                        <h2>Reviews - Listing</h2>
                    </div>
                    
                    <div class="card">
						
						<div class="card-header">
							<?php $this->load->view('backend/common/alert'); ?>  
						</div>

                         <?php if(empty($_GET['short']))
                                $dis='';
                              else
                                $dis="display: none;";
                        ?>
                        <div class="row">
						   <div class="col-md-6">
								<div id="date_section" style="<?php echo $dis; ?>">
									<div class="col-sm-5">
									<!-- <p class="f-500 m-b-15 c-black">Start Date</p> -->
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
										<div class="col-sm-5">
										<!--  <p class="f-500 m-b-15 c-black">End Date</p> -->
										<div class="input-group form-group">
										<span class="input-group-addon">
											<i class="zmdi zmdi-calendar"></i>
										</span>
										<div class="dtp-container fg-line">
											<input type="text" style="padding-left: 10px;" id="end_date" class="form-control date-picker" name="end_date" value="<?php echo isset($_GET['end_date'])?$_GET['end_date']:''; ?>" placeholder="select end date">
										</div>
											<div style="position: absolute;" id="error_end" class="error"></div>
										</div></div>
										<div class="col-sm-2">
										<!-- <p class="f-500 c-black"></p> -->
										<button type="button" id="click_search" class="btn btn-info btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-search"></i></button>
									</div>
								</div>
							</div>
						</div>
                       
                            
                        <table id="data-table-command" class="table table-striped table-vmiddle">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-order="asc" data-type="numeric" data-header-css-class="width7">ID</th>
                                    <th data-column-id="rowid" data-order="asc" data-type="numeric" data-visible="false"></th>
                                    <th data-column-id="first_name" data-order="asc" data-type="string">Salon Name</th>
                                    <th data-column-id="last_name" data-type="string">Customer Name</th>
                                    <th data-column-id="Services" data-type="string">Services</th>
                                    <th data-column-id="gender" data-type="string" data-header-css-class="width7">Rate</th>
                                    <th data-column-id="email">Review</th>
                                    <th data-column-id="age_group" data-type="string">Created On</th>
                                    <th data-column-id="commands" data-formatter="commands" data-sortable="false">Action</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
								<?php 
								   
								   if($allreviews){
									   $i=1;
								     foreach($allreviews as $row){ ?>										  
										  <tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $row->id; ?></td>
											<td><?php echo $row->business_name; ?></td>
											<td>
                                              <?php 
                                                if($row->anonymous =="1")
                                                    echo 'Anonymous';
                                                else
                                                    echo $row->first_name.' '.$row->last_name; ?>

                                                </td>
                                             <td><?php echo get_servicename_with_sapce($row->booking_id); ?></td>   
                                                
											<td>    
                                                <!-- 
            <i class="fas fa-star colororange mr-1 font-size-16"></i>
            <i class="fas fa-star colororange mr-1 font-size-16"></i>
            <i class="fas fa-star colororange mr-1 font-size-16"></i>
            <i class="fas fa-star colororange mr-1 font-size-16"></i>
            <i class="fas fa-star colore99999940 mr-1 font-size-16"></i> -->
                                                <?php echo $row->rate; ?>
                                            </td>
											<td><?php echo $row->review; ?></td>
											<td><?php echo date('Y-m-d H:i',strtotime($row->created_on)); ?></td>			
										  </tr>
										  
									<?php $i++; }  } 
								
								?>
								
                               
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
 <div class="modal fade" id="editReviewModal">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">    
    <div class="modal-content relative" id="editReviewHtml">   
     
            
        </div> 
    </div>
  </div>
</div>
  
 
  <script defer src="<?php echo base_url('assets/frontend/js/fa/fontawesome-all.js'); ?>"></script>       
  <?php $this->load->view('backend/common/footer'); ?>
 <!-- Data Table -->
        <script type="text/javascript">
            $(document).ready(function(){
				
		
				
                 $(document).on('click','.export_filterreport',function(){
                        //var search = $("input[name=search-field]").val();
                   var search = $(".search-field").val();
                   var short = $('.on_change_short').val();
                   var start ="";
                   var end ="";
                   if(short ==""){
                     var start = $('#start_date').val();
                     var end =  $('#end_date').val();
                   }

                  window.location.href = base_url+'backend/booking/list_export/excel?search='+search+'&short='+short+'&start_date='+start+'&end_date='+end;
                  });


                 $(document).on('change','.on_change_short', function(){
                var limit=$(this).val();
                if(limit !=''){  
                 var url=base_url+"backend/booking/listing?short="+limit;
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
                        var url=base_url+"backend/reviews/listing?start_date="+start+"&end_date="+end;
                        window.location.href = url;
                    }
               });
                //~ //Basic Example
                //~ $("#data-table-basic").bootgrid({
                    //~ css: {
                        //~ icon: 'zmdi icon',
                        //~ iconColumns: 'zmdi-view-module',
                        //~ iconDown: 'zmdi-expand-more',
                        //~ iconRefresh: 'zmdi-refresh',
                        //~ iconUp: 'zmdi-expand-less'
                    //~ },
                //~ });
                
                //~ //Selection
                //~ $("#data-table-selection").bootgrid({
                    //~ css: {
                        //~ icon: 'zmdi icon',
                        //~ iconColumns: 'zmdi-view-module',
                        //~ iconDown: 'zmdi-expand-more',
                        //~ iconRefresh: 'zmdi-refresh',
                        //~ iconUp: 'zmdi-expand-less'
                    //~ },
                    //~ selection: true,
                    //~ multiSelect: true,
                    //~ rowSelect: true,
                    //~ keepSelection: true
                //~ });
                
                //Command Buttons
                $("#data-table-command").bootgrid({
                    caseSensitive: false,
                    columnSelection: false,
                    css: {
                        icon: 'zmdi icon',
                        iconColumns: 'zmdi-view-module',
                        iconDown: 'zmdi-expand-more',
                        iconRefresh: 'zmdi-refresh',
                        iconUp: 'zmdi-expand-less'
                    },
                    formatters: {
                        "commands": function(column, row) {
							 //console.log(column);
							 console.log(row);
                            return "<button type=\"button\" class=\"btn btn-icon command-edit\" onclick=\"editReview(" + row.rowid + ",'"+base_url+"/backend/reviews/get_edit_review_form/')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + 
                                "<button type=\"button\" class=\"btn btn-icon command-delete\" onclick=\"DeleteRow(" + row.rowid + ",'backend/reviews/delete/','review')\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
                        }
                    }
                });
                
            
            });
             function editReview(id,url){
				 // $("#editReviewModal").modal('show');
                //alert(id+" "+url);
                //var short=$(".on_change_short_c option:selected").val();
                //alert(short);
                //$('#profile-client-view-modal').modal('show');
                //return false;
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
                            data:{ id : id},
                            success: function (data) {
                             var obj = jQuery.parseJSON( data );
                                if(obj.success == 1){  
                                    $("#editReviewHtml").html(obj.html);
                                    $("#editReviewModal").modal('show');
                                        
                                }
                            }
                                
                        }); 
                    
                    
                }
        </script>
