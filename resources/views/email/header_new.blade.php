<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email template</title>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Fjalla+One|Open+Sans');

        @media screen and (max-width:600px){
            #emailContainer{width: 100%;}
        }
        @media only screen and (max-width: 480px){
            td[class="email-content-box"],
            td[class="email-content-box"] table {
                display: block;
                width: 100%;
                text-align: left;
            }
            td[class="email-content-box-inner"],
            td[class="email-content-box-inner"] table td {
                padding: 15px 0 !important;
            }
            td[class="box-container"],
            td[class="box-container"] h2{
                font-size: 41px !important;
            }
            table[class="banner-text"] h2,
            table[id="emailBodysection2"] td div{
                font-size: 20px !important;
            }
            td[class="email-content-box-inner"] div,
            td[class="email-content-box-inner"] div p,
            td[class="email-content-box-inner"] div h3,
            td[class="email-content-box-inner"] div h4{
                font-size: 22px !important;
            }

        }
    </style>
</head>
<body bgcolor="#fff" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

<!-- Start Email Container -->
<table border="0" cellpadding="0" cellspacing="3" id="emailContainer" style="max-width:600px;margin:0 auto;" bgcolor="#ffffff">
    <tr>
        <td align="center" valign="top" id="emailContainerCell">

            <!-- Start Email Header Area -->
            <table border="0" cellpadding="0" cellspacing="0" id="emailHeader"
                   style="table-layout: fixed;
                   max-width:100% !important;
                   width: 100% !important;
                   min-width: 100%!important;
                   padding: 15px;">
                <tr>
                    <td align="center" valign="middle">
                        {{--<span style="font-size:24px; color:white">{{env('APP_NAME')}}</span>--}}
                        <img src="{{ asset('/images/email/logo-light.png') }}" alt="Cleaning.com.au" height="80">
                    </td>
                </tr>
            </table>

