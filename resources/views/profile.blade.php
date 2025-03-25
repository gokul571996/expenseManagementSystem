@extends('layout')

@section('body')
<style>

    body {     
        font-family: 'Ubuntu', sans-serif;
    }
    .update-profile-btn {
        background-color: #1a97b0;
        color: #fff;
        border: none;
        transition: background-color 0.3s ease;
    }

    .update-profile-btn:hover {
        background-color: #157f91;
        color: #fff;
    }

    .custom-modal-width {
        max-width: 600px;
        margin-top: 100px;
    }

    #updateProfileModalLabel {
        text-align: center;
        width: 100%;
        color: #1a97b0;
        font-weight: bold;
    }

    #updateProfileModal .btn-close {
        filter: invert(38%) sepia(63%) saturate(2576%) hue-rotate(346deg) brightness(98%) contrast(90%);
    }

    .modal-footer {
        justify-content: center !important;
    }

    .modal-content {
        border-radius: 5px !important;
    }

    .modal-header {
        border-bottom: none !important;
    }

    .modal-footer {
        border-top: none !important;
    }
    
</style>

<div class="container mt-5">
    <div class="d-flex justify-content-center align-items-center">
        <div class="card shadow p-4" style="max-width: 500px; border-radius: 5px;">
            <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile" width="120" class="mb-3" />
                <h4 class="fw-bold">{{ $user->name }}</h4>
            </div>
            <div class="text-start mb-4 fs-5">
                <p><strong>Full Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Username:</strong> {{ $user->username }}</p>
            </div>
            <div class="d-flex">
                <button class="btn w-100 update-profile-btn" data-bs-toggle="modal" data-bs-target="#updateProfileModal">Update Profile</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal-width">
        <form action="{{ url('/updateProfile') }}" method="POST" id="updateProfileForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProfileModalLabel">Update Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" id="name">
                        <span class="text-danger d-none" id="nameError">Name is Required</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" id="userName">
                        <span class="text-danger d-none" id="usernameError">Username is Required</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" id="email">
                        <span class="text-danger d-none" id="emailError">Email is Required</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control" id="password">
                        <span class="text-danger d-none" id="passwordError">Password is Required</span>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn update-profile-btn">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000"
        };

        @if (session('success'))
            $(document).ready(function() {
                toastr.success("{{ session('success') }}");
            });
        @endif

        @if (session('error'))
            $(document).ready(function() {
                toastr.error("{{ session('error') }}");
            });
        @endif 

        $('#updateProfileForm').on('submit', function (e) {
            e.preventDefault();

            let isValid = true;

            if ($('#name').val().trim() === '') {
                $('#nameError').removeClass('d-none');
                $('#name').addClass('border-danger');
                isValid = false;
            } else {
                $('#nameError').addClass('d-none');
                $('#name').removeClass('border-danger');
            }

            if ($('#userName').val().trim() === '') {
                $('#usernameError').removeClass('d-none');
                $('#userName').addClass('border-danger');
                isValid = false;
            } else {
                $('#usernameError').addClass('d-none');
                $('#userName').removeClass('border-danger');
            }

            const emailVal = $('#email').val().trim();
            if (emailVal === '') {
                $('#emailError').removeClass('d-none').text('Email is Required');
                $('#email').addClass('border-danger');
                isValid = false;
            } else if (!validateEmail(emailVal)) {
                $('#emailError').removeClass('d-none').text('Enter a valid Email');
                $('#email').addClass('border-danger');
                isValid = false;
            } else {
                $('#emailError').addClass('d-none');
                $('#email').removeClass('border-danger');
            }

            const passwordVal = $('#password').val().trim();
            if (passwordVal != '') {
                if (!validateStrongPassword(passwordVal)) {
                    $('#passwordError').removeClass('d-none').text('Password must have at least 8 characters, 1 uppercase, 1 lowercase, 1 number, and 1 special character.');
                    $('#password').addClass('border-danger');
                    isValid = false;
                } else {
                    $('#passwordError').addClass('d-none');
                    $('#password').removeClass('border-danger');
                }
            } 

            if (isValid) {
                $.ajax({
                    url: "{{ url('/updateProfile') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr.success(response.success);
                        setTimeout(function () {
                            window.location.href = "/profile";
                        }, 2000);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            console.log("hai");
                            if (errors.username) {
                                $('#usernameError').removeClass('d-none').text(errors.username[0]);
                                $('#userName').addClass('border-danger');
                                toastr.error(errors.username[0]);
                            } else {
                                toastr.error("Validation error occurred.");
                            }
                            
                        } else {
                            toastr.error("Something went wrong. Please try again.");
                        }
                    }
                });
            }
            
        });

        $('#name').on('input', function () {
            $('#nameError').addClass('d-none');
            $(this).removeClass('border-danger');
        });

        $('#userName').on('input', function () {
            $('#usernameError').addClass('d-none');
            $(this).removeClass('border-danger');
            $('#usernameError').text("Username is Required");
        });

        $('#email').on('input', function () {
            $('#emailError').addClass('d-none');
            $(this).removeClass('border-danger');
        });

        $('#password').on('input', function () {
            $('#passwordError').addClass('d-none');
            $(this).removeClass('border-danger');
        });

        let originalName = '';
        let originalUsername = '';
        let originalEmail = '';

        $('#updateProfileModal').on('show.bs.modal', function () {
            originalName = $('#name').val();
            originalUsername = $('#userName').val();
            originalEmail = $('#email').val();
        });

        $('#updateProfileModal').on('hidden.bs.modal', function () {
            $('#name').val(originalName).removeClass('border-danger');
            $('#userName').val(originalUsername).removeClass('border-danger');
            $('#email').val(originalEmail).removeClass('border-danger');
            $('#nameError, #usernameError, #emailError').addClass('d-none');
        });
        
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validateStrongPassword(password) {
        const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?#&])[A-Za-z\d@$!%*?#&]{8,}$/;
        return re.test(password);
    }
</script>
@endsection