<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>All Expenses Report</title>
        <style>
            body { font-family: sans-serif; margin: 0; padding: 20px; }
            h2 { text-align: center; color: #1a97b0; }

            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 50px;
            }

            table, th, td { border: 1px solid #ccc; }
            th, td { padding: 8px; text-align: left; }
            th { background-color: #1a97b0; color: white; }

            th.date-col, td.date-col {
                width: 100px;
                white-space: nowrap;
            }

            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }

            @page {
                margin: 30mm 20mm 20mm 20mm;
            }
        </style>
    </head>
    <body>
        <h2>All Expenses Report</h2>
        <div style="margin-bottom: 30px;">
            <h3 style="border-bottom: 2px solid #1a97b0; padding-bottom: 5px; display: inline-block;">Summary</h3>
            <p><strong>Total Expense Amount:</strong> {{ number_format($totalExpenseAmount, 2) }} ₹</p>
            <p><strong>Total Expense Limit:</strong> {{ number_format($totalExpenseLimit, 2) }} ₹</p>
            <p><strong>Total Balance:</strong> {{ number_format($totalBalance, 2) }} ₹</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Category Name</th>
                    <th>Expense Amount (₹)</th>
                    <th>Expense Limit (₹)</th>
                    <th class="date-col">Date</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($allGroupedExpenses) && count($allGroupedExpenses) > 0)
                    @foreach($allGroupedExpenses as $key => $expense)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $expense->categoryName }}</td>
                            <td>{{ $expense->totalExpenseAmount }}</td>
                            <td>{{ $expense->categorylimit }}</td>
                            <td class="date-col">{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center;">No Data Found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </body>
</html>