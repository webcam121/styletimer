<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>styletimer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="author" content="" />
  <link rel="stylesheet" type="text/css" href="">
  <link rel="shortcut icon" type="image/png"  href="images/favicon.png" />
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">


  <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins&display=swap');
    *,body{
      font-family: 'Poppins', sans-serif;
      vertical-align: top;
      margin: 0rem;
    }
    table tr td,
    table tr td p{
      vertical-align: top;
      margin: 0rem;

    }
    table{
      border:1px solid #666;
      padding: 20px;
    }

</style>
</head>
<body class="" style="padding-top: 70px;">
    <table width="500" style="width:500px;background: #fff;font-family: 'Poppins';margin:0 auto;" cellspacing="4">
      <tr>
        <td style="text-align: center;" colspan="2">
          <span style="font-size: 16px; color:#FF9944; font-weight: 500; display:block; margin-bottom: 20px; text-align: center;">Receipt</span>
        </td>
      </tr>
      <tr>
        <td style="" colspan="2">
         
          <?php
             if($main[0]->booking_type == "guest"){
                  $us_name = $main[0]->fullname;
                }
                else{
                  $us_name = $booking_detail[0]->first_name.' '.$booking_detail[0]->last_name;
                }

                 if($booking_detail[0]->profile_pic !='')
                    $img=base_url('assets/uploads/users/').$booking_detail[0]->id.'/icon_'.$booking_detail[0]->profile_pic;
                 else
                    $img=base_url('assets/frontend/images/user-icon-profile.svg');

                   $ser_name=$service_nm=''; 
                    foreach($booking_detail as $row){ 
                     $sub_name=get_subservicename($row->service_id); 

                     if($sub_name == $row->service_name)
                          $service_nm=$row->service_name;
                     else
                         $service_nm=$sub_name.' - '.$row->service_name; 

                          $ser_name=$ser_name.','.$service_nm;
                     }
           ?>
          <img src="<?php echo $img; ?>" style="width: 40px;height: 40px;border-radius: 50% ;display: inline-block;margin-right: 10px;">
          <div class="" style="display: inline-block;vertical-align: top;"> 
            <p style="color: #00b3bf;font-size: 1rem;margin:0rem 0rem 0px 0rem;"><?php echo ucfirst($us_name); ?></p>
           <?php if($main[0]->booking_type != "guest"){ ?>
            <p style="color: #666666;font-size: 14px;margin:0rem;"><?php echo $booking_detail[0]->address." <br>".$booking_detail[0]->zip." ".$booking_detail[0]->city; ?></p>
            <?php } ?>
          </div>
        </td>
      </tr>
      <tr>
        <td style="" colspan="">
          <span style="color:#999;font-size: 0.75rem;margin:0px;">Total Duration</span>
          <p style="color: #FF9944;font-size: 0.875rem;font-weight: 500;margin:0px;"><?php echo $main[0]->total_minutes.' Mins'; ?></p>
        </td>
        <td style="text-align: right;" colspan="">
          <span style="border-radius: 0.25rem;border: 1px solid #00b3bf;background: #DBF4F6;color: #00b3bf;height: 32px;padding-top:10px;width: 75px;display: inline-block;text-align: center;float: right;"><?php echo $main[0]->total_price.' €'; ?></span>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <h5 style="color: #333;font-size: 18px;font-weight: 500;margin: 0.625rem 0rem 20px 0rem;">Booking Information</h5>
          <span style="color:#999;font-size: 0.75rem;margin:0rem;">Booked via</span>
          <p style="font-size: 0.875rem;font-weight: 500;margin:0rem;"><?php if($main[0]->book_by=='0') echo 'Web'; else echo 'App'; ?></p>

          
          <span style="color:#999;font-size: 0.75rem;margin:1rem 0rem 0rem 0rem;display: block;">Booking Date</span>
          <p style="font-size: 0.875rem;font-weight: 500;margin:0rem;"><?php echo date("d F Y, H : i",strtotime($main[0]->booking_time)); ?></p>

          <span style="color:#999;font-size: 0.75rem;margin:1rem 0rem 0rem 0rem;display: block;">Completed Date</span>
          <p style="font-size: 0.875rem;font-weight: 500;margin:0rem;"><?php echo date("d F Y, H : i",strtotime($main[0]->updated_on)); ?></p>

          <span style="color:#999;font-size: 0.75rem;margin:1rem 0rem 0rem 0rem;display: block;">Booking ID</span>
          <p style="font-size: 0.875rem;font-weight: 500;margin:0rem;"><?php echo $main[0]->book_id; ?></p>

          <span style="color:#999;font-size: 0.75rem;margin:1rem 0rem 0rem 0rem;display: block;">Service</span>
          <p style="font-size: 0.875rem;font-weight: 500;margin:0rem;"><?php echo ltrim($ser_name, ','); ?></p>

          <span style="color:#999;font-size: 0.75rem;margin:1rem 0rem 0rem 0rem;display: block;">Salon name</span>
          <p style="font-size: 0.875rem;font-weight: 500;margin:0rem;"><?php echo $main[0]->business_name; ?></p>
          <span style="color:#666;font-size: 0.75rem;margin:0rem;display: block;"><?php echo $main[0]->address." <br/>".$main[0]->zip." ".$main[0]->city; ?></span>
          
          <span style="color:#999;font-size: 0.75rem;margin:1rem 0rem 0rem 0rem;display: block;">Customer email address</span>
          <p style="font-size: 0.875rem;font-weight: 500;margin:0rem;"> 
                  <?php if($main[0]->booking_type != "guest") 
                          echo $booking_detail[0]->email;
                        else
                          echo $main[0]->email;
                        ?>
                        </p>
        </td>
      </tr>
    </table>
</body>
</html>
