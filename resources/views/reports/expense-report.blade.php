<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Expense Report PDF</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; color: #333; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            table, th, td { border: 1px solid #aaa; }
            th, td { padding: 8px; text-align: left; }
            th { background-color: #1a97b0; color: white; }
            h2 { color: #1a97b0; text-align: center; }
        </style>
    </head>
    <body>
        <h2>Expense Report for {{ $category->categoryName }} <br> ({{ \Carbon\Carbon::parse($date)->format('d-m-Y') }})</h2>
        <div style="margin-bottom: 30px;">
            <h3 style="border-bottom: 2px solid #1a97b0; padding-bottom: 5px; display: inline-block;">Summary</h3>
            <p><strong>Total Expense Amount:</strong> {{ number_format($totalExpenseAmount, 2) }} Rs</p>
            <p><strong>Total Expense Limit:</strong> {{ number_format($totalExpenseLimit, 2) }} Rs</p>
            <p><strong>Total Balance:</strong> {{ number_format($totalBalance, 2) }} Rs</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Amount (₹)</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $key => $expense)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ number_format($expense->expenseAmount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                        <td>{{ $expense->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>