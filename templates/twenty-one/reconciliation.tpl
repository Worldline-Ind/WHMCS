<br>
<h3>Reconciliation</h3>
<form action="{$data.url}" method="post" enctype="multipart/form-data" id="form-payment" class="form-horizontal">

      From Date:    <input type="date" name="fromdate" placeholder="dd-mm-YYYY" required/>
     To Date:    <input type="date" name="todate" placeholder="dd-mm-YYYY" required/>  
      <input type="hidden" name="mrctCode" value="{$data.merchantCode}"/>          
         &nbsp; &nbsp; &nbsp;   <button id="btnSubmit" type="submit" class="btn btn-primary" name="submit" value="Submit" >Submit</button>
      </form>
      <br>
      <br>
      <p id="mydata">{$data.message}</p>
      