<?php echo view('common.header');?>
<script src="https://flashjs.paymob.com/v1/paymob.js"></script>
<style>
    .borderless tr {
    border: none;
}
</style>
<!------ Include the above in your HEAD tag ---------->
</br></br>

<!--<form method="post" action="{{URL('/save-payment')}}">-->
<div class="container">
    <div class="panel panel-default mb-5">
        <div class="panel-heading">
            <div class="row">
              <div class="col-md-10 col-md-offset-2">
                <div class="col-md-4 pull-left">
                    <strong>INVOICE: {{$data['invoiceNo']}}</strong><br>
                    Created: {{$data['date']}} <br>
                    Due: {{$data['dueDate']}}
                </div>
                <div class="col-md-4 center">
                    <img src="http://www.blog.menut.ro/assets/img/download.png" alt="logo" class="" style="max-width: 100px;">
                </div>
                <div class="col-md-4 pull-right">
                    STATUS: {{$data['status']}}
                </div>
              </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-6 mb-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        From:
                    </div>
                    <div class="panel-body">
                      <address>
                        <strong>Test Company srl</strong> <br>
                        Adress: Aleea Moldovita, Nr. 6, Bl. Em3, Sc. B, Et. 1, Ap. 28, Sect. 4, Bucuresti  <br>
                        Phone: 0773990706 <br>
                        Email: office@breakingpoint.ro <br>
                        
                        </address>
                    </div>
                </div>                
            </div>
            <div class="col-md-6 mb-3 text-right">
              <div class="panel panel-default">
                  <div class="panel-heading">
                    To:
                  </div>
                  <div class="panel-body">
                    <address>
                    <strong>{{$data['contactName']}} <br></strong>
                    Adress:  <br>
                    Phone : <br>
                    Bank:  <br>
                    </address>
                  </div>
                </div>
            </div>
          <!--<table class="table">-->
          <!--  <thead>-->
          <!--    <tr>-->
          <!--      <th>No.     </th>-->
          <!--      <th>Product </th>-->
          <!--      <th>Qty.    </th>-->
          <!--      <th>Price   </th>-->
          <!--      <th class="text-right">Amount  </th>-->
          <!--    </tr>-->
          <!--  </thead>-->
          <!--  <tbody>-->
          <!--    <tr>-->
          <!--      <td>1       </td>-->
          <!--      <td>nimic   </td>-->
          <!--      <td>12      </td>-->
          <!--      <td>2,000   </td>-->
          <!--      <td class="text-right">24,000  </td>-->
          <!--    </tr>-->
          <!--    <tr class="border-bottom">-->
          <!--      <td>2       </td>-->
          <!--      <td>altceva </td>-->
          <!--      <td>7       </td>-->
          <!--      <td>5,000   </td>-->
          <!--      <td class="text-right">35,000  </td>-->
          <!--    </tr>-->
          <!--  </tbody>-->
          <!--</table>-->
          <div class="row justify-content-end">
            <div class="col-md-6">
              Currency: GBP <br>
             
            </div>
            <div class="col-md-6">
              <table class="table borderless">
                <tbody>
                  <tr>
                    <th scope="row" class="text-right">Sub Total</th>
                    <th class="text-right">{{$data['subTotal']}}</th>
                  </tr>
                  <tr>
                    <th scope="row" class="text-right">Total Tax</th>
                    <th class="text-right">{{$data['totalTax']}}</th>
                  </tr>
                  <tr>
                    <th scope="row" class="text-right">TOTAL</th>
                    <th class="text-right">{{$data['total']}}</th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="panel-footer" style="height: 10rem;">
            <div class="col-md-6 col-md-offset-3">
                <!--<div class="col-md-12">-->
                <!--   <input type="submit" class="form-control" value="Pay Now"/>-->
                <!--</div>-->
                <div id="paymob-checkout"></div>

                <!--<div class="col-md-6">-->
                <!--    Client Signature-->
                <!--</div>-->
            </div>
        </div>
    </div>
</div>
</form>
<script>
    var invoiceNo = "<?php echo $data['invoiceNo'];?>";
    var uniqueId = "<?php echo $uniqueId;?>";
    var url = "<?php echo URL('/save-payment')?>";
    var returnUrl = "<?php echo URL('/payment-return')?>";
    var paymob_key = "<?php echo Config::get('global.paymob_key') ?>";
 fetch(url+"?invoiceNo="+invoiceNo+"&uniqueId="+uniqueId).then(function (response) {
      
        return response.json();

    }).then(function (json) {

        Paymob(paymob_key).checkoutButton(json.client_secret,{ redirect:returnUrl,pluginConfig: {
successMessage: "Payment Successful!",
errorMessage: "Payment Error!",
declinedMessage: "Payment Declined!",
platform: "shopify",
anyKey: "Any Value",
// all pluginConfig object will added to url
}}).mount("#paymob-checkout");

    }).catch(function (err) {

        console.error(err);

    });

</script>