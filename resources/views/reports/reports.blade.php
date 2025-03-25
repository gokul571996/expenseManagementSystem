@extends('layout')

@section('body')
    <style>
        body {
            background-color: white;
            margin: 0;
            padding: 0;
            font-family: 'Ubuntu', sans-serif;
        }

        .category-container {
            padding: 40px 20px;
        }

        .category-card {
            background-color: #fff;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 100%;
            transition: all 0.3s ease-in-out;
        }

        h2,
        .modal-title {
            margin-bottom: 30px;
            text-align: center;
            color: #1a97b0;
        }
        table.dataTable thead th {
            background-color: #1a97b0;
            color: white;
        }

        table.dataTable tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.3s;
        }

        table.dataTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        table.dataTable th:first-child,
        table.dataTable td:first-child {
            width: 60px;
            text-align: center;
        }

        table.dataTable th:last-child,
        table.dataTable td:last-child {
            width: 100px;
            text-align: center;
        }

        .btn-delete {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .btn-delete:hover {
            color: #b02a37;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 20px;
        }

        .dataTables_filter {
            margin-bottom: 20px;
        }

        canvas {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .filter-btn {
            background-color: #1a97b0;
            color: #fff;
            border: none;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            background-color: #147d92;
            color: #fff;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <div class="category-container">
        <div class="category-card mb-4">
            <h2>1.Filter Reports</h2>
            <form method="GET" action="{{ url('/expense/reports') }}" class="row g-3">
                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categoriesList as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->categoryName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Amount Range</label>
                    <div class="d-flex">
                        <input type="number" name="amount_min" placeholder="Min" value="{{ request('amount_min') }}" class="form-control me-2">
                        <input type="number" name="amount_max" placeholder="Max" value="{{ request('amount_max') }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn mt-3 filter-btn">
                        <i class="fa fa-filter"></i> Apply Filters
                    </button>
                    <a href="{{ url('/expense/reports') }}" class="btn btn-secondary mt-3"><i class="fa fa-times"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="category-container">
        <div class="category-card">
            <h2>2.Expense Report</h2>

            <div class="text-end mb-3">
                <button onclick="exportAllWithFilters()" class="btn btn-danger">
                    <i class="fa-solid fa-file-pdf"></i> Export All
                </button>
            </div>

            <table class="table table-bordered" id="reportTable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Category Name</th>
                        <th>Expense Amount (₹)</th>
                        <th>Expense Limit (₹)</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allGroupedExpenses as $key => $expenses)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $expenses->categoryName }}</td>
                        <td>{{ $expenses->totalExpenseAmount }}</td>
                        <td>{{ $expenses->categorylimit }}</td>
                        <td>{{ \Carbon\Carbon::parse($expenses->expenseDate)->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ url('/download-expense-pdf?categoryId=' . $expenses->expenseCategoryId . '&date=' . $expenses->expenseDate) }}" target="_blank" title="Download PDF">
                                <i class="fa-solid fa-file-pdf" style="color: #d32f2f; font-size: 1.5rem;"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="category-container">
        <div class="category-card">
            <h2>3.Charts</h2>

            <h2 class="mt-5 mb-4">1. Expenses by Category</h2>
            <canvas id="barChart"></canvas>

            <h2 class="mt-5 mb-4">2. Expenses by Category</h2>
            <canvas id="doughnutChart"></canvas>

            <h2 class="mt-5 mb-4">3. Expenses Over Time by Category</h2>
            <canvas id="categoryDateLineChart"></canvas>

            <h2 class="mt-5 mb-4">4. Expenses Over Time by Category</h2>
            <canvas id="categoryDateStackedBarChart"></canvas>
            
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        $(document).ready(function () {
            $('#reportTable').DataTable({
                "lengthMenu": [5, 10, 20, 50],
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": 3 }
                ]
            });

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "2500"
            };

            @if (session('success'))
                toastr.success("{{ session('success') }}");
                setTimeout(function () {
                    location.reload();
                }, 2500);
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
                setTimeout(function () {
                    location.reload();
                }, 2500);
            @endif
        });

        const categoryDateLabels = @json($dates);
        const categories = @json($categories);
        const dataMatrix = @json($dataMatrix);

        const lineChartDataSets = categories.map((cat, index) => {
            const color = `hsl(${index * 30}, 70%, 50%)`;
            return {
                label: cat,
                data: dataMatrix[cat],
                fill: false,
                borderColor: color,
                tension: 0.3
            };
        });

        const lineCtx = document.getElementById('categoryDateLineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: categoryDateLabels,
                datasets: lineChartDataSets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    
        const stackedBarDataSets = categories.map((cat, index) => {
            const color = `hsl(${index * 30}, 70%, 50%)`;
            return {
                label: cat,
                data: dataMatrix[cat],
                backgroundColor: color
            };
        });

        const stackedCtx = document.getElementById('categoryDateStackedBarChart').getContext('2d');
        new Chart(stackedCtx, {
            type: 'bar',
            data: {
                labels: categoryDateLabels,
                datasets: stackedBarDataSets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    x: { stacked: true },
                    y: { stacked: true, beginAtZero: true }
                }
            }
        });

        const categoryLabels = @json($categoryNames);
        const categoryData = @json($categoryExpenses);

        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Expenses by Category (₹)',
                    data: categoryData,
                    backgroundColor: '#1a97b0',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 500
                        }
                    }
                }
            }
        });

   
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Expenses by Category (₹)',
                    data: categoryData,
                    backgroundColor: [
                        '#1a97b0', '#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff',
                        '#f67019', '#f53794', '#6db33f', '#f9a825', '#4a148c', '#00bcd4',
                        '#9c27b0', '#e91e63', '#03a9f4'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        function exportAllWithFilters() {
            const fromDate = $('input[name="from_date"]').val();
            const toDate = $('input[name="to_date"]').val();
            const categoryId = $('select[name="category_id"]').val();
            const amountMin = $('input[name="amount_min"]').val();
            const amountMax = $('input[name="amount_max"]').val();

            let url = '/download-expense-pdf-all?';
            if (fromDate) url += `from_date=${fromDate}&`;
            if (toDate) url += `to_date=${toDate}&`;
            if (categoryId) url += `category_id=${categoryId}&`;
            if (amountMin) url += `amount_min=${amountMin}&`;
            if (amountMax) url += `amount_max=${amountMax}&`;

            window.open(url, '_blank');
        }
        
    </script>
@endsection