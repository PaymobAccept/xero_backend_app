<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8" />

<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>Flash Checkout</title>

</head>

<body>

<div id="paymob-checkout"></div>

<script src="https://flashjs.paymob.com/v1/paymob.js"></script>



<script>

fetch("https://yourhelpgroup.com/mostafa/save-payment").then(function (response) {
      
        return response.json();

    }).then(function (json) {

        Paymob("pk_test_i0AIFuZdc6w1BWIoPBETAEI1zsLCPAuC").checkoutButton(json.client_secret,{ redirect:'https://yourhelpgroup.com/mostafa/payment-return',pluginConfig: {
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

</body>

</html>