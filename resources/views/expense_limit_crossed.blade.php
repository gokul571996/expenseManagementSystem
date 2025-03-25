<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Expense Limit Crossed</title>
    </head>
    <body>
        <p>Hi,</p>
        <p>You have crossed the <strong>{{ $categoryName }}</strong> expense limit.</p>
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Set Limit (₹)</th>
                    <th>Actual Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($setLimit, 2) }}</td>
                    <td>{{ number_format($actualAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <p>Please review your expenses.</p>
        <p>Thank you.</p>
    </body>
</html>