<style>
  body{margin:0rem;padding:0rem;}
  .container{
    padding:0px 10px;
  }
  
  @media(min-width:992px){
    .container{max-width:960px;margin:auto;}
  }
  
  .termcondi_section2{
      padding-top:15px;
  }
  .overflowscroll{
      max-height: calc(100vh - 150px);
      overflow: auto;
  }
  .termcondi_section1{
    background:url(<?php echo base_url('assets/frontend/images/term-bg-img.png') ?>) no-repeat;
    width: 100%;
    min-height: 122px;
    background-size: 100% 100%;
    background-position: center;
    color: #fff;
    text-align: center;
    display:flex;
    align-items:center;
    justify-content:center;
  }
  .termcondi_section1 h1{
    font-size: 30px;
    font-family: 'Poppins';
    margin: 8px 0px;
  }
  section.termcondi_section2{
    padding-top:20px;
      /*padding-top:24px !important;*/
  }
  section.termcondi_section2 h1 {
      font-size: 24px;
      font-family: 'Poppins';
      color: #000;
      margin:1rem 0rem 0rem 0rem;
  }
  section.termcondi_section2.pt-50 ul {
    padding-left: 0px;
    list-style: none;
    font-family: 'Poppins';
    margin:0rem;
}
section.termcondi_section2.pt-50 ul li ul{
  padding-left: 15px;
  font-family: 'Poppins';
}
  section.termcondi_section2.pt-50 ul li {
      font-size: 16px;
      font-family: 'Poppins';
      color: #000;
  }
  section.termcondi_section2.pt-50 ul li ul li{
      font-size: 14px;
      font-family: 'Poppins';
  }
  a,p,span,strong{
      word-break: break-all;
      display: inherit;
    }
  @media(max-width:767px){
    .termcondi_section1.pt-35 .font-size-34 {
    font-size: 20px;
    margin-top: 10px;
    word-break: break-word;
    }   
    .termcondi_section2 h1{
        font-size: 20px !important;
        word-break: break-word;
    } 
    
  }
</style>
<section class="p-0 clear">
    <div class="termcondi_section1 pt-35">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-12 text-center" >
            <h1 class="font-size-34 colorwhite fontfamily-bold">
              <?php if(!empty($terms)){ echo $terms->title; }?>
            </h1>
          </div>
        </div>
      </div>
    </div>
</section>

<section class="termcondi_section2 pt-50">
  <div class="overflowscroll">
    <div class="container">
      <div class="row">
        <div class="col-12 col-sm-12">
          <?php if(!empty($terms)){
                echo $terms->text;
          }?>
        </div>
      </div>
    </div>
  </div>
</section>
