<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Redeem Amount</title>

  <style type="text/css">
  @import url(http://fonts.googleapis.com/css?family=Droid+Sans);

  /* Take care of image borders and formatting */

  img {
    max-width: 600px;
    outline: none;
    text-decoration: none;
    -ms-interpolation-mode: bicubic;
  }

  a {
    text-decoration: none;
    border: 0;
    outline: none;
    color: #bbbbbb;
  }

  a img {
    border: none;
  }

  /* General styling */

  td, h1, h2, h3  {
    font-family: Helvetica, Arial, sans-serif;
    font-weight: 400;
  }

  td {
    text-align: center;
  }

  body {
    -webkit-font-smoothing:antialiased;
    -webkit-text-size-adjust:none;
    width: 100%;
    height: 100%;
    color: #37302d;
    background: #ffffff;
    font-size: 16px;
  }

   table {
    border-collapse: collapse !important;
  }

  .headline {
    color: #ffffff;
    font-size: 36px;
  }

 .force-full-width {
  width: 100% !important;
 }

 .force-width-80 {
  width: 80% !important;
 }




  </style>

  <style type="text/css" media="screen">
      @media screen {
         /*Thanks Outlook 2013! http://goo.gl/XLxpyl*/
        td, h1, h2, h3 {
          font-family: 'Droid Sans', 'Helvetica Neue', 'Arial', 'sans-serif' !important;
        }
      }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class="w320"] {
        width: 320px !important;
      }

      td[class="mobile-block"] {
        width: 100% !important;
        display: block !important;
      }


    }
  </style>
</head>
<body class="body" style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
<table align="center" cellpadding="0" cellspacing="0" class="force-full-width" height="100%" >
  <tr>
    <td align="center" valign="top" bgcolor="#ffffff"  width="100%">
      <center>
        <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="600" class="w320">
          <tr>
            <td align="center" valign="top">

                <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" class="force-full-width" style="margin:0 auto;">
                  <tr>
                    <td style="font-size: 30px; text-align:center;">
                      <br>
                        
                      <br>
                      <br>
                    </td>
                  </tr>
                </table>

                <table style="margin: 0 auto;width: 100%;text-align: center;font-size: 16px; border: #50e1ed solid 4px;" cellpadding="0" cellspacing="0" class="force-full-width">
                  <tr bgcolor="#50e1ed">
                    <td>
                    <br>
                    <img src="{{url('/img/logo.png')}}" width="400" height="100" alt="AdMessage">  
                    </td>
                  </tr>
                  <tr>
                    <td class="headline" style="padding: 10px;font-size: 20px;font-weight: bold;color: #000000;">
                      Welcome to AdMessenger
                    </td>
                  </tr>
                  <hr style="background-color: #50e1ed; height: 3px; border: 0">
                  <tr>
                    <td>

                      <center>
                        <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="90%">
                          <tr>
                            <td style="color: #000000;font-size: 17px;">
                            Hi {{ ucfirst($getUserName) }},
                            <br>
                            <br>
                              You have received the redeem amount <b>${{ $getTotalAmount }}</b> from AdMessenger. <br><br>Please check your registered paypal email account.<br><br> Your registered Paypal EMail id is {{ $UserPaypalEmail }}
                            <br>
                            <br>
                            </td>
                          </tr>
                        </table>
                      </center>
                    </td>
                  </tr>
                </table>

                <table style="width:100%;text-align: center" cellpadding="0" cellspacing="0" class="force-full-width" bgcolor="#50e1ed" >
                  <tr>
                    <td style="color:#0c0c0c; font-size:12px; padding-top: 10px; font-weight: bold;">
                       © <?php echo date("Y");?> All Rights Reserved
                       <br>
                       <br>
                    </td>
                  </tr>
                </table>





            </td>
          </tr>
        </table>
    </center>
    </td>
  </tr>
</table>
</body>
</html>