<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
        .ReadMsgBody {
            width: 100%;
            background-color: #ffffff;
        }

        .ExternalClass {
            width: 100%;
            background-color: #ffffff;
        }

        body {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
        }

        @media only screen and (max-width: 640px) {
            .deviceWidth {
                width: 440px !important;
                padding: 0;
            }

            .center {
                text-align: center !important;
            }
        }

        @media only screen and (max-width: 479px) {
            .deviceWidth {
                width: 280px !important;
                padding: 0;
            }

            .center {
                text-align: center !important;
            }
        }

        .bgdeviceWidth {
            margin: 0 auto;
            background: url([OriginalImage]);
            /*background-color:[ColorCode];*/
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
        }

       
</style>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!--
        This template is provided for free of charge by Email on Acid, LLC.

        Every email client is different. See exactly how your email looks in the most popular inboxes, so you can fix any problems before you hit send.
        https://www.emailonacid.com/
        -->
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://fonts.googleapis.com/css?family=Barlow+Condensed:300,400,500,600,700&display=swap" rel="stylesheet">
    <style type="text/css">
    
        
    </style>
    <!--[if gte mso 9]>
        <style type="text/css">
       .pt-120{
            padding-top:230px !important;
        }
        
        </style>
    <![endif]-->

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="font-family: Georgia, Times, serif">
    <table width="50%" border="0" cellspacing="0" cellpadding="0" class="full_wrap">
        <tr>
            <td align="center" valign="top">
                <table align="center" width="700" border="0" cellspacing="0" cellpadding="0" class="main_table" style="width:737px; table-layout:fixed;">
                    <tr>
                        <td align="center" valign="top">
                            <table width="700" border="0" align="center" class="wrapper">
                                <!--HEADER SECTION-->
                                <tr>
                                    <td align="left" valign="top" bgcolor="#3e4244" style="padding:5px 20px;">
                                        <table border="0" align="left" class="wrapper">
                                            <tr>
                                                <td align="center" valign="top" style="padding:10px 0px;">
                                                    <img src="[LogoOriginalImage]" width="153" alt="Logo" height="64" style="display:block; max-width:153px;" border="0" />
                                                </td>
                                            </tr>
                                        </table>
                                        <!--<div style="display:none;">
                                            Hej, [UserFullName]!
                                                        
                                        </div>-->
                                        <table width="700" border="0" align="left" class="wrapper">
                                            <tr>
                                                <td align="center" valign="top" style="padding-top:45px;">
                                                    <table width="380" border="0" style="color:#ffffff;">
                                                        <tr>
                                                            <!--<td align="center" valign="top" style="font-weight:900;">OM SVØMMEHALLEN</td>
                                                            <td align="center" valign="top" style="font-weight:900;">ÅBNINGSTIDER</td>
                                                            <td align="center" valign="top" style="font-weight:900;">NYHEDER</td>-->
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                       
                                    </td>
                                </tr>
                                <!--//HEADER SECTION-->
                                <!--BODY SECTION-->
                                <tr>
                                    <td align="center" valign="top" style="padding:22px 15px;" bgcolor="#f4f4f4">
                                        <table width="100%" border="0" align="center">
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table width="100%" border="0">
                                                        <tr>

                                                            <td align="center" valign="top" style="font-size:32px;padding-top:45px;color:#263238;">Hey, <?php echo $detail['name']?>!</td>
                                                            <td></td>
                                                        </tr>
                                                       
                                                        <tr>
                                                            <td align="center" valign="top" style="color:#263238; font-size:18px;padding-top:25px;">
                                                                <p>please check Custom URL below </p>
                                                                <p><?php echo $detail['url'];?></p>
                                                            </td>
                                                        </tr>
                                                       
                                                    </table>
                                                </td>
                                            </tr>
                                           
                                        </table>
                                    </td>
                                </tr>
                                <!--//BODY SECTION-->
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>