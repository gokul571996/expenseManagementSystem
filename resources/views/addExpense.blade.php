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
            max-width: 600px;
            margin: auto;
            transition: all 0.3s ease-in-out;
        }

        h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #1a97b0;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 5px;
        }

        .save-btn {
            background-color: #1a97b0;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .save-btn:hover {
            background-color: #147c8a;
        }

        .text-danger {
            font-size: 0.9rem;
        }
    </style>

    <div class="category-container">
        <div class="category-card">
            <h2>Add Expenses</h2>
            <form action="{{ url('/expenses/save') }}" method="POST" id="expenseForm">
                @csrf

                <div class="mb-3">
                    <label for="expenseCategory" class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="expenseCategory" id="expenseCategory" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categoryList as $category)
                            <option value="{{ $category->id }}">{{ $category->categoryName }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger d-none" id="expenseCategoryNameError">Category is Required</span>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="amount" name="amount">
                    <span class="text-danger d-none" id="expenseAmountError">Amount is Required</span>
                </div>

                <div class="mb-3">
                    <label for="expenseDate" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" 
                           class="form-control" 
                           id="expenseDate" 
                           name="expenseDate" 
                           value="{{ date('Y-m-d') }}" 
                           max="{{ date('Y-m-d') }}" 
                           readonly 
                           onfocus="this.removeAttribute('readonly');" 
                           required>
                    <span class="text-danger d-none" id="expenseDateError">Date is Required</span>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="text-center">
                    <button type="button" id="submitExpense" class="save-btn">Add</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#amount').on('input', function () {
            let value = $(this).val();
            let filteredValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            $(this).val(filteredValue);
        });

        $(document).ready(function() {
            $('#expenseDate').on('keydown', function(e) {
                e.preventDefault();
            }).on('click', function() {
                this.showPicker && this.showPicker();
            });

            $('#submitExpense').on('click', function(e) {
                e.preventDefault();
                let isValid = true;
                $('.text-danger').addClass('d-none');


                if ($('#expenseCategory').val() === '') {
                    $('#expenseCategoryNameError').removeClass('d-none');
                    $('#expenseCategory').addClass('border-danger');
                    isValid = false;
                } else {
                    $('#expenseCategoryNameError').addClass('d-none');
                    $('#expenseCategory').removeClass('border-danger');
                }

                if ($('#amount').val() === '') {
                    $('#expenseAmountError').removeClass('d-none');
                    $('#amount').addClass('border-danger');
                    isValid = false;
                } else {
                    $('#expenseAmountError').addClass('d-none');
                    $('#amount').removeClass('border-danger');
                }

                if ($('#expenseDate').val() === '') {
                    $('#expenseDateError').removeClass('d-none');
                    $('#expenseDate').addClass('border-danger');
                    isValid = false;
                } else {
                    $('#expenseDateError').addClass('d-none');
                    $('#expenseDate').removeClass('border-danger');
                }

                if (isValid) {
                    $('#expenseForm').submit();
                }
            });

            $('#expenseCategory').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $(this).removeClass('border-danger');
                    $('#expenseCategoryNameError').addClass('d-none');
                }
            });

            $('#amount').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $(this).removeClass('border-danger');
                    $('#expenseAmountError').addClass('d-none');
                }
            });

            $('#expenseDate').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $(this).removeClass('border-danger');
                    $('#expenseDateError').addClass('d-none');
                }
            });
        });

        $(document).ready(function () {

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