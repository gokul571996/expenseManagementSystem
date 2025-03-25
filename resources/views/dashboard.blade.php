@extends('layout')
@section('body')
    <style>
        .card {
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
            margin-bottom: 16px;
            transition: transform 0.2s ease;
            color: #fff;
            padding: 12px 15px 8px;
            position: relative;
            overflow: hidden;
            text-align: center;
            min-height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card:hover { transform: translateY(-3px); }
        .category-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
            text-shadow: 0.5px 0.5px rgba(0,0,0,0.06);
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            padding: 0 3px;
        }
        .label-text {
            font-size: 10px;
            opacity: 0.8;
            margin-bottom: 0px;
        }
        .amount-text {
            font-size: 15px;
            font-weight: 700;
            position: relative;
            z-index: 2;
            text-shadow: 0.5px 0.5px rgba(0,0,0,0.08);
            margin-bottom: 0px;
        }
        .balance-container {
            position: relative;
            display: inline-block;
        }
        .balance-container i {
            position: absolute;
            top: -2px;
            right: -6px;
            font-size: 30px;
            opacity: 0.05;
            z-index: 1;
        }
        .dashboard-title {
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: left;
            font-size: 40px;
            letter-spacing: 1px;
        }
        .dashboard-title .dashboard-sub {
            font-size: 16px;
            color: #555;
            font-weight: 400;
        }
        .expense-table-heading {
            font-size: 18px;
            font-weight: 500;
            color: #444;
            margin-top: 40px;
            margin-bottom: 20px;
            text-align: left;
        }
        table.expense-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.03);
        }
        table.expense-table th {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: #fff;
            font-weight: 700;
            text-align: left;
            padding: 10px;
            font-size: 13px;
        }
        table.expense-table td {
            padding: 9px 12px;
            font-size: 13px;
            color: #333;
        }
        table.expense-table tr:nth-child(even) { background-color: #f0f7ff; }
        table.expense-table tr:hover { background-color: #e0f0ff; }

        .gradient-purple-blue { background: linear-gradient(135deg, #667eea, #764ba2); }
        .gradient-pink-orange { background: linear-gradient(135deg, #ff5858, #f857a6); }
        .gradient-orange-yellow { background: linear-gradient(135deg, #f7971e, #ffd200); }
        .gradient-blue-cyan { background: linear-gradient(135deg, #36d1dc, #5b86e5); }
        .gradient-red-orange { background: linear-gradient(135deg, #ff416c, #ff4b2b); }
        .gradient-purple-pink { background: linear-gradient(135deg, #bc4e9c, #f80759); }
        .gradient-teal-green { background: linear-gradient(135deg, #11998e, #38ef7d); }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <div class="container mt-4">
        <h2 class="dashboard-title">
            DASHBOARD <span class="dashboard-sub">(today's)</span>
        </h2>
        <div class="row">
            @foreach($categoryList as $index => $category)
                @php
                    $gradients = [
                        'gradient-purple-blue',
                        'gradient-pink-orange',
                        'gradient-orange-yellow',
                        'gradient-blue-cyan',
                        'gradient-red-orange',
                        'gradient-purple-pink',
                        'gradient-teal-green'
                    ];
                    $colorClass = $gradients[$index % count($gradients)];
                    $spent = $categoryExpenses[$category->id]->total ?? 0;
                    $balance = $category->categorylimit - $spent;
                @endphp
                <div class="col-md-4 col-lg-3">
                    <div class="card {{ $colorClass }}">
                        <h3 class="category-title">{{ $category->categoryName }}</h3>
                        <div class="details-row">
                            <div>
                                <div class="label-text">Spent</div>
                                <div class="amount-text">₹{{ number_format($spent, 2) }}</div>
                            </div>
                            <div>
                                <div class="label-text">Balance</div>
                                <div class="balance-container">
                                    <div class="amount-text">₹{{ number_format($balance, 2) }}</div>
                                    <i class="fa-solid fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h3 class="expense-table-heading">Expense Table</h3>
        <table class="expense-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Category Name</th>
                    <th>Expense Limit (₹)</th>
                    <th>Expense Amount (₹)</th>
                    <th>Balance (₹)</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($categoryList) && count($categoryList) > 0)
                    @foreach($categoryList as $index => $category)
                        @php
                            $spent = $categoryExpenses[$category->id]->total ?? 0;
                            $balance = $category->categorylimit - $spent;
                            $date = $categoryExpenses[$category->id]->latest_date ?? '-';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->categoryName }}</td>
                            <td>{{ number_format($category->categorylimit, 2) }}</td>
                            <td>{{ number_format($spent, 2) }}</td>
                            <td>{{ number_format($balance, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center;">No Data Found.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <h3 class="expense-table-heading">Expenses by Category</h3>
        <canvas id="expensesByCategoryChart" height="100"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('expensesByCategoryChart').getContext('2d');
        const expensesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($categoryList->pluck('categoryName')),
                datasets: [{
                    label: 'Expenses by Category (₹)',
                    data: @json($categoryList->map(fn($cat) => $categoryExpenses[$cat->id]->total ?? 0)),
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000"
        };

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
@endsection