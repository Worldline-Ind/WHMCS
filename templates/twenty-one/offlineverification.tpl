<br>
<h3>Offline Verification</h3>
<form action="" id="form"  class="form-inline" method="POST">

      Merchant Ref No:    <input type="text" name="token"  placeholder="Merchant Ref No." required/>
      Date:    <input type="date" name="date" placeholder="dd-mm-YYYY" required/>  
      <input type="hidden" name="mrctCode" value="{$data.merchantCode}"/>          
      <input type="hidden" name="currency" value="INR"/>          
         &nbsp; &nbsp; &nbsp;   <button id="btnSubmit" type="submit" class="btn btn-primary" name="submit" value="Submit" >Submit</button>
      </form>
      <br>
      <br>
      <p id="mydata"></p>
      <script>
$(document).ready(function(){
  $("#btnSubmit").click(function(e){
    e.preventDefault();
    var str = $("#form").serializeArray();
 
    function formatDate (dateString) {
   var p = dateString.split(/\D/g);
  return [p[2],p[1],p[0] ].join("-");
  }
if(str[1].value !='' && str[0].value !='' && str[2].value !=''){
var dateformated = formatDate(str[2].value);
//console.log(str[1]);
    var data = {
   "merchant": {
    "identifier": str[3].value
  },
  "transaction": {
    "deviceIdentifier": "S",
    "currency": str[4].value,
     "identifier": str[1].value,        
     "dateTime": dateformated,  
    "requestType": "O"
  }
};
//console.log(data);
var myJSON = JSON.stringify(data);
    
    $.ajax({
      type: 'POST',
      url: "https://www.paynimo.com/api/paynimoV2.req",
      data: myJSON,
      beforeSend: function() {
        $("#mydata").html("");
        $("#mydata").append('Loading......');
    },
      success: function(resultData) { 
        
        var response=JSON.stringify(resultData);
        //console.log(resultData);
        $("#mydata").html("");
         $("#mydata").append('<div class="container"><div class="col-12 col-sm-6"><table class="table table-bordered"><tbody><tr><th>Status Code</th><th>'+resultData.paymentMethod.paymentTransaction.statusCode+'</th></tr><tr><th>Merchant Transaction Reference No</th><th>'+resultData.merchantTransactionIdentifier+'</th></tr><tr><th>TPSL Transaction ID</th><th>'+resultData.paymentMethod.paymentTransaction.identifier+'</th></tr><tr><th>Amount</th><th>'+resultData.paymentMethod.paymentTransaction.amount+'</th></tr><tr><th>Message</th><th>'+resultData.paymentMethod.paymentTransaction.errorMessage+'</th></tr><tr><th>Status Message</th><th>'+resultData.paymentMethod.paymentTransaction.statusMessage+'</th></tr><tr><th>Date Time</th><th>'+resultData.paymentMethod.paymentTransaction.dateTime+'</th></tr></tbody></table></div></div>');
        
      }
});
  }else{
    alert('Please Fill All Fields');
  }
  });
});
</script>