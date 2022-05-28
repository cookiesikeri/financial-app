<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Muli:400,300" rel="stylesheet">
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
</head>
<body>
    <div class="image-container set-full-height">
        <br><br><br><br>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6" style="visibility:hidden">
                            <address>
                                <strong>Expresstopup Nigeria</strong>
                                <br>
                                    Plot 1038 Shehu Shagari Way, Maitama Abuja.
                                <br>
                                    Email: care@expresstop.ng
                                <br>
                                <abbr title="Phone">Tel:</abbr> 081-2039-9379
                            </address>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                            <p>
                                <em id="receipt_date">Date: </em>
                            </p>
                            <p>
                                <em id="receipt_trans_id">Transaction ID #: {{$dataTransaction->transaction_id}}</em>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-9"><b id="receipt_service_id">Data Bundle</b></h4></td>
                                    <td class="col-md-1 text-center" id="receipt_amount">₦{{$dataTransaction->amount}}</td>
                                    @if ($is_vendor)
                                        <td class="col-md-1 text-center" id="receipt_total">₦{{$dataTransaction->amount}}</td>
                                    @else
                                        <td class="col-md-1 text-center" id="receipt_total">₦{{$dataTransaction->amount_paid}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>   </td>
                                    <td class="text-right"><h4><strong>Total: </strong></h4></td>
                                    @if ($is_vendor)
                                        <td class="text-center text-danger"><h4><strong id='receipt_total_purchase'>₦{{$dataTransaction->amount}}</strong></h4></td>
                                    @else
                                        <td class="text-center text-danger"><h4><strong id='receipt_total_purchase'>₦{{$dataTransaction->amount_paid}}</strong></h4></td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <h4 style="text-align:center;">
                                Thank you for your patronage. We hope to see you again...
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="container text-center">
                expresstopup &copy; {{ date('Y') }}
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
</html>
