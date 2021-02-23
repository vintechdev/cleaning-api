<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email template</title>

    <style>
        body,
        body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif,
            'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            position: relative;
        }

        body {
            -webkit-text-size-adjust: none;
            background-color: #ebf0f4;
            color: #718096;
            height: 100%;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }

        p,
        ul,
        ol,
        blockquote {
            line-height: 1.4;
            text-align: left;
        }

        a {
            color: #3869d4;
        }

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
<body bgcolor="#ebf0f4" leftmargin="0" marginwidth="0" topmargin="40" bottommargin="40" marginheight="0" offset="0"
      style="background-color: #ebf0f4; font-size: 16px; color: #718096;">

<table border="0" cellspacing="0" cellpadding="0" width="100%" style="width: 100%;">
    <tr>
        <td style="height: 40px;">&nbsp;</td>
    </tr>
    <tr>
        <td>

            <!-- Start Email Container -->
            <table border="0" cellpadding="0" cellspacing="3" id="emailContainer" bgcolor="#ffffff"
                   style="width: 100%; max-width:570px; margin:0 auto; background-color: #ffffff;
                    border-color: #e8e5ef; border-radius: 2px; border-width: 1px;
                    box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015)">
                <tr>
                    <td align="center" valign="top" id="emailContainerCell" style="padding-left: 15px; padding-right: 15px;">

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

