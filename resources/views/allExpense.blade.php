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

        table.dataTable th.date-column, 
        table.dataTable td.date-column {
            width: 90px;
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <div class="category-container">
        <div class="category-card">
            <h2>All Expense List</h2>
            <table class="table table-bordered" id="categoryTable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Category Name</th>
                        <th>Expense Amount (₹)</th>
                        <th>Expense Limit (₹)</th>
                        <th class="date-column">Date</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allExpenses as $key => $expenses)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $expenses->categoryName }}</td>
                        <td>{{ $expenses->expenseAmount }}</td>
                        <td>{{ $expenses->categorylimit }}</td>
                        <td>{{ \Carbon\Carbon::parse($expenses->date)->format('d-m-Y') }}</td>
                        <td>{{ $expenses->description }}</td>
                        <td>
                            <form action="/allExpense/{{ $expenses->id }}" method="POST" class="deleteForm d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#categoryTable').DataTable({
                "lengthMenu": [5, 10, 20, 50],
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": 3 }
                ]
            });

            $('.deleteForm').on('submit', function (e) {
                e.preventDefault();
                const form = this;
                if (confirm('Are you sure you want to delete this  expense record?')) {
                    form.submit();
                }
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
    </script>
@endsection