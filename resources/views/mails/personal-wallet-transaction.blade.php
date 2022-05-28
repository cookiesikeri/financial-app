<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wallet Transaction</title>
</head>
<body>
    <div style="width: 600px;max-width: 700px;margin: 0 auto">
        <table style="width: 100%;border: 1px solid #cccccc;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">
            <tbody>
                <tr style="height: 200px;color: white;background-color:#0d3e65;text-align: center;">
                    <td colspan="2">
                        @if ($status == 1 || $status == 2 || $status == 3)
                            <h2>Wallet funding failed for</h2>
                        @else
                            <h2>Your account wallet was successfully credited with</h2>
                        @endif
                        <h1>NGN {{ number_format($amount_credited, 2) }}</h1>
                    </td>
                </tr>
                <tr style="color: gray;text-align: center;">
                    <td colspan="2">
                        <b>Transaction Summary</b>
                    </td>
                </tr>
                <tr style="height: 50px;">
                    <td style="padding: 15px;">
                        Reference
                    </td>
                    <td style="color: #555555">
                        <b>{{ $payment_reference }}</b>
                    </td>
                </tr>
                <tr style="height: 50px;">
                    <td style="padding: 15px;">Date</td>
                    <td style="color: #555555">
                        <b>{{ date('Y-m-d H:i:s') }}</b>
                    </td>
                </tr>
                <tr style="height: 50px;">
                    <td style="padding: 15px;">Status</td>
                    <td style="color: #555555">
                        <b>
                            @if ($status == 1)
                                <span style="color: #ff0000">Payment verification request failed</span>
                            @elseif ($status == 2)
                                <span style="color: #ff0000">Payment verification failed</span>
                            @elseif ($status == 3)
                                <span style="color: #ff0000">Payment reference not found</span>
                            @else
                                <span style="color:#00ff00">Successful</span>
                            @endif
                        </b>
                    </td>
                </tr>
                <tr style="height: 50px;">
                    <td style="padding: 15px;">Description</td>
                    <td style="color: #555555">
                        <b>
                            {{ $description }}
                        </b>
                    </td>
                </tr>
                <tr style="height: 50px;">
                    <td style="padding: 15px;">Wallet balance</td>
                    <td style="color: #555555">
                        <b>
                            {{ number_format($new_wallet_balance, 2) }}
                        </b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
