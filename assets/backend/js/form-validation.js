// Wait for the DOM to be ready
$(function() {
  
  $('#login-submit').click(function(){
	    $("#adm-login").submit();  
  });
  
  //~ $("form[name='adm-login']").validate({
    //~ rules: {
      //~ username: {
        //~ required: true,
        //~ email: true
      //~ },
      //~ password: {
        //~ required: true,
        //~ minlength: 5
      //~ }
    //~ },
    //~ messages: {
      //~ username: "Please enter your firstname",
      //~ password: {
        //~ required: "Please provide a password",
        //~ minlength: "Your password must be at least 5 characters long"
      //~ }      
    //~ },
    //~ submitHandler: function(form) {
		
		 //~ //  var username = $("#username").val();
		  //~ // var password = $("#password").val();
		   
		   //~ //alert(username+" | "+password);
		 //~ //return false;
      //~ form.submit();
    //~ }
  //~ });
});
