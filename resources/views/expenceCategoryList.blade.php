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

        .add-category-btn {
            display: inline-block;
            background-color: #1a97b0;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
            float: right;
            cursor: pointer;
            transition: 0.3s;
            border: none;
        }

        .add-category-btn:hover {
            background-color: #147c8a;
            color: white;
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
        
        .custom-modal-width {
            max-width: 600px;
            margin-top: 100px;
        }

        #addCategoryModalLabel {
            text-align: center;
            width: 100%;
            color: #1a97b0;
            font-weight: bold;
        }

        #addCategoryModal .btn-close {
            filter: invert(38%) sepia(63%) saturate(2576%) hue-rotate(346deg) brightness(98%) contrast(90%);
        }

        .modal-footer {
            justify-content: center !important;
            border-top: none;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-content {
            border-radius: 5px !important;
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

        .modal-header .modal-title {
            margin-bottom: 0;
            font-size: 1.5rem;
            font-weight: bold;
            color: #1a97b0;
        }

        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 20px;
        }

        .dataTables_filter {
            margin-bottom: 20px;
        }

        .custom-edit-btn {
            background-color: #1a97b0;
            border: none;
            color: white;
            transition: 0.3s;
        }

        .custom-edit-btn:hover {
            background-color: #147c8a;
            color: white;
        }

        .action-separator {
            margin: 0 8px;
            color: #6c757d; 
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <div class="category-container">
        <div class="category-card">
            <div class="clearfix">
                <button class="add-category-btn" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fa fa-plus"></i> Add Category
                </button>
            </div>
            <h2>Category List</h2>
            <table class="table table-bordered" id="categoryTable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Category Name</th>
                        <th>Limit (₹)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryList as $key => $category)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $category->categoryName }}</td>
                        <td>{{ $category->categorylimit }}</td>
                        <td>
                            <button 
                                class="btn custom-edit-btn btn-sm edit-btn" 
                                data-id="{{ $category->id }}" 
                                data-name="{{ $category->categoryName }}" 
                                data-limit="{{ $category->categorylimit }}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editCategoryModal">
                                <i class="fa fa-edit"></i>
                            </button>

                            <span class="action-separator">|</span>

                            <form action="/category/{{ $category->id }}" method="POST" class="deleteForm d-inline">
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

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-width">
            <form action="{{ url('/category/save') }}" method="POST" id="addCategoryForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="categoryName" name="categoryName">
                            <span class="text-danger d-none" id="categoryNameError">Category Name is Required</span>
                        </div>
                        <div class="mb-3">
                            <label for="categoryLimit" class="form-label">Limit (₹) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="categoryLimit" name="categorylimit">
                            <span class="text-danger d-none" id="categoryLimitError">Limit is Required</span>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="save-btn">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-width">
            <form method="POST" id="editCategoryForm">
                @csrf
               
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title" id="editCategoryModalLabel" style="text-align: center; width: 100%;">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editCategoryId" name="id">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editCategoryName" name="categoryName">
                            <span class="text-danger d-none" id="editCategoryNameError">Category Name is Required</span>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoryLimit" class="form-label">Limit (₹) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editCategoryLimit" name="categorylimit">
                            <span class="text-danger d-none" id="editCategoryLimitError">Limit is Required</span>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="save-btn">Update Category</button>
                    </div>
                </div>
            </form>
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
                if (confirm('Are you sure you want to delete this category?')) {
                    form.submit();
                }
            });

            $('#categoryLimit').on('input', function () {
                let value = $(this).val();
                let newValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                $(this).val(newValue);
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

        $('#addCategoryForm').on('submit', function(e) {
            e.preventDefault();
            $('#categoryNameError').addClass('d-none');
            $('#categoryName').removeClass('border-danger');
            $('#categoryLimitError').addClass('d-none');
            $('#categoryLimit').removeClass('border-danger');
            let isValid = true;

            const categoryName = $('#categoryName').val().trim();
            const categoryLimit = $('#categoryLimit').val().trim();

            if (categoryName === '') {
                $('#categoryNameError').removeClass('d-none');
                $('#categoryName').addClass('border-danger');
                isValid = false;
            } else {
                $('#categoryNameError').addClass('d-none');
                $('#categoryName').removeClass('border-danger');
            }

            if (categoryLimit === '') {
                $('#categoryLimitError').removeClass('d-none');
                $('#categoryLimit').addClass('border-danger');
                isValid = false;
            } else {
                $('#categoryLimitError').addClass('d-none');
                $('#categoryLimit').removeClass('border-danger');
            }

            if (isValid) {
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $('#addCategoryForm')[0].reset();
                            $('#addCategoryModal').modal('hide');
                            setTimeout(() => { location.reload(); }, 2000);
                        } else if (response.status === 'error') {
                            toastr.error(response.message);
                            $('#categoryNameError').text(response.message).removeClass('d-none');
                            $('#categoryName').addClass('border-danger');
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while saving category.');
                    }
                });
            }
        });

        $('#categoryName').on('input', function() {
            if ($(this).val().trim() !== '') {
                $('#categoryNameError').addClass('d-none');
                $('#categoryName').removeClass('border-danger');
                $('#categoryNameError').text("Category Name is Required");
            }
        });

        $('#categoryLimit').on('input', function() {
            if ($(this).val().trim() !== '') {
                $('#categoryLimitError').addClass('d-none');
                $('#categoryLimit').removeClass('border-danger');
            }
        });

        $(document).on('click', '.edit-btn', function() {
            const categoryId = $(this).data('id');
            const categoryName = $(this).data('name');
            const categoryLimit = $(this).data('limit');

            $('#editCategoryId').val(categoryId);
            $('#editCategoryName').val(categoryName);
            $('#editCategoryLimit').val(categoryLimit);

            $('#editCategoryForm').attr('action', `/category/update/${categoryId}`);
        });

        $('#editCategoryLimit').on('input', function () {
            let value = $(this).val();
            let newValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
            $(this).val(newValue);
        });

        $('#editCategoryForm').on('submit', function(e) {
            e.preventDefault();
            $('#editCategoryNameError').addClass('d-none');
            $('#editCategoryName').removeClass('border-danger');
            $('#editCategoryLimitError').addClass('d-none');
            $('#editCategoryLimit').removeClass('border-danger');
            let isValid = true;
            const categoryName = $('#editCategoryName').val().trim();
            const categoryLimit = $('#editCategoryLimit').val().trim();
            const formAction = $(this).attr('action');

            if (categoryName === '') {
                $('#editCategoryNameError').removeClass('d-none');
                $('#editCategoryName').addClass('border-danger');
                isValid = false;
            } else {
                $('#editCategoryNameError').addClass('d-none');
                $('#editCategoryName').removeClass('border-danger');
            }

            if (categoryLimit === '') {
                $('#editCategoryLimitError').removeClass('d-none');
                $('#editCategoryLimit').addClass('border-danger');
                isValid = false;
            } else {
                $('#editCategoryLimitError').addClass('d-none');
                $('#editCategoryLimit').removeClass('border-danger');
            }

            if (isValid) {
                $.ajax({
                    url: formAction,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $('#editCategoryForm')[0].reset();
                            $('#editCategoryModal').modal('hide');
                            setTimeout(() => { location.reload(); }, 2000);
                        } else {
                            toastr.error(response.message);
                            $('#editCategoryNameError').removeClass('d-none');
                            $('#editCategoryName').addClass('border-danger');
                            $('#editCategoryNameError').text("Category name already exists!");
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while updating category.');
                    }
                });
            }
        });

        $('#editCategoryName').on('input', function () {
            if ($(this).val().trim() !== '') {
                $('#editCategoryNameError').addClass('d-none');
                $('#editCategoryName').removeClass('border-danger');
                $('#editCategoryNameError').text("Category Name is Required");
            }
        });

        $('#editCategoryLimit').on('input', function () {
            if ($(this).val().trim() !== '') {
                $('#editCategoryLimitError').addClass('d-none');
                $('#editCategoryLimit').removeClass('border-danger');
            }
        });

        $('#addCategoryModal').on('shown.bs.modal', function () {
            $('#addCategoryForm')[0].reset();
            $('#categoryName, #categoryLimit').removeClass('border-danger');
            $('#categoryNameError').addClass('d-none').text('Category Name is Required');
            $('#categoryLimitError').addClass('d-none').text('Limit is Required');
        });

        $('#editCategoryModal').on('shown.bs.modal', function () {
            $('#editCategoryName, #editCategoryLimit').removeClass('border-danger');
            $('#editCategoryNameError').addClass('d-none').text('Category Name is Required');
            $('#editCategoryLimitError').addClass('d-none').text('Limit is Required');
        });
    </script>
@endsection