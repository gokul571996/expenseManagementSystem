@extends('layout')
@section('body')
    <style>
        body {
            background-color: white;
            margin: 0;
            padding: 0;
            font-family: 'Ubuntu', sans-serif;
        }

        .login-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 80px);
            padding-top: 40px;
        }

        .login-card, .signup-card {
            background-color: #fff;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
            transition: all 0.3s ease-in-out;
        }

        h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #1a97b0;
        }

        .btn-login, .btn-signup {
            background-color: #1a97b0;
            border: none;
        }

        .btn-login:hover, .btn-signup:hover {
            background-color: #157f91;
        }

        .signup-link, .back-to-login {
            margin-top: 20px;
            text-align: center;
        }

        .signup-link p a, 
        .back-to-login p a {
            color: #1a97b0 !important;
            text-decoration: underline !important;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .signup-link p a:hover, 
        .back-to-login p a:hover {
            color: #157f91 !important;
            text-decoration: underline !important;
        }

        .border-danger {
            border: 1px solid red !important;
        }

        .btn-login {
            color: #fff;
            border: none;
        }           

        .btn-login:hover {
            color: #fff;
        }

        .btn-signup {
            color: #fff;
        }

        .btn-signup:hover {
            color: #fff;
        }
    </style>

    <div class="login-container">
        
        <div class="login-card"  id="loginForm">
            <h2>Login</h2>
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" id="usernameLogin" class="form-control" autofocus>
                    <span class="text-danger d-none" id="usernameErrorLogin">Username is Required</span>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="passwordLogin" class="form-control">
                    <span class="text-danger d-none" id="passwordErrorLogin">Password is Required</span>
                </div>
                <button type="submit" class="btn btn-login w-100">Login</button>
            </form>
            <div class="signup-link">
                <p>Don't have an account? <a id="showSignup">Sign Up</a></p>
            </div>
        </div>

        <div class="signup-card d-none" id="signupCard">
            <h2>Sign Up</h2>
            <form method="POST" action="{{ url('/register') }}">
                @csrf
                <div class="mb-3">
                    <label for="new_name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="new_name" id="new_name" class="form-control">
                    <span class="text-danger d-none" id="nameError">Name is Required</span>
                </div>
                <div class="mb-3">
                    <label for="new_username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="new_username" id="new_username" class="form-control">
                    <span class="text-danger d-none" id="usernameError">Username is Required</span>
                </div>
                <div class="mb-3">
                    <label for="new_email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="text" name="new_email" id="new_email" class="form-control">
                    <span class="text-danger d-none" id="emailError">Email is Required</span>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                    <span class="text-danger d-none" id="passwordError">Password is Required</span>
                </div>
                <button type="submit" class="btn btn-signup w-100">Register</button>
            </form>
            <div class="back-to-login">
                <p>Already have an account? <a id="backToLogin">Login</a></p>
            </div>
        </div>
    </div>

    <script>
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

        $('#showSignup').click(function () {
            $('.login-card').addClass('d-none');
            $('#signupCard').removeClass('d-none');

            $('#nameError').addClass('d-none');
            $('#new_name').removeClass('border-danger');
            $('#new_name').val('');

            $('#usernameError').addClass('d-none');
            $('#new_username').removeClass('border-danger');
            $('#new_username').val('');

            $('#emailError').addClass('d-none');
            $('#new_email').removeClass('border-danger');
            $('#new_email').val('');

            $('#passwordError').addClass('d-none');
            $('#new_password').removeClass('border-danger');
            $('#new_password').val('');
        });

        $('#backToLogin').click(function () {
            $('#signupCard').addClass('d-none');
            $('.login-card').removeClass('d-none');
        });

        $('#signupCard form').submit(function (e) {
            e.preventDefault();
            let isValid = true;

            if ($('#new_name').val().trim() === '') {
                $('#nameError').removeClass('d-none');
                $('#new_name').addClass('border-danger');
                isValid = false;
            } else {
                $('#nameError').addClass('d-none');
                $('#new_name').removeClass('border-danger');
            }

            if ($('#new_username').val().trim() === '') {
                $('#usernameError').removeClass('d-none');
                $('#new_username').addClass('border-danger');
                isValid = false;
            } else {
                $('#usernameError').addClass('d-none');
                $('#new_username').removeClass('border-danger');
            }

            const emailVal = $('#new_email').val().trim();
            if (emailVal === '') {
                $('#emailError').removeClass('d-none').text('Email is Required');
                $('#new_email').addClass('border-danger');
                isValid = false;
            } else if (!validateEmail(emailVal)) {
                $('#emailError').removeClass('d-none').text('Enter a valid Email');
                $('#new_email').addClass('border-danger');
                isValid = false;
            } else {
                $('#emailError').addClass('d-none');
                $('#new_email').removeClass('border-danger');
            }

            const passwordVal = $('#new_password').val().trim();
            if (passwordVal === '') {
                $('#passwordError').removeClass('d-none').text('Password is Required');
                $('#new_password').addClass('border-danger');
                isValid = false;
            } else if (!validateStrongPassword(passwordVal)) {
                $('#passwordError').removeClass('d-none').text('Password must have at least 8 characters, 1 uppercase, 1 lowercase, 1 number, and 1 special character.');
                $('#new_password').addClass('border-danger');
                isValid = false;
            } else {
                $('#passwordError').addClass('d-none');
                $('#new_password').removeClass('border-danger');
            }

            if (isValid) {
                $.ajax({
                    url: "{{ url('/register') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr.success(response.success);
                        setTimeout(function () {
                            window.location.href = "/login";
                        }, 2000);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            if (errors.new_username) {
                                $('#usernameError').removeClass('d-none').text(errors.new_username[0]);
                                $('#new_username').addClass('border-danger');
                                toastr.error(errors.new_username[0]);
                            }
                        } else {
                            toastr.error("Something went wrong. Please try again.");
                        }
                    }
                });
            }
        });

        $('input').on('input', function () {
            const inputId = $(this).attr('id');
            const errorId = '#' + inputId.replace('new_', '') + 'Error';
            if ($(this).val().trim() !== '') {
                $(errorId).addClass('d-none');
                $(this).removeClass('border-danger');
                $('#usernameErrorLogin').addClass('d-none');
                $('#passwordErrorLogin').addClass('d-none');
                $('#usernameError').text("Username is Required");
            }
        });

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validateStrongPassword(password) {
            const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?#&])[A-Za-z\d@$!%*?#&]{8,}$/;
            return re.test(password);
        }

        $('#loginForm form').submit(function (e) {
            e.preventDefault();
            let isValid = true;

            if ($('#usernameLogin').val().trim() === '') {
                $('#usernameErrorLogin').removeClass('d-none');
                $('#usernameLogin').addClass('border-danger');
                isValid = false;
            } else {
                $('#usernameLogin').removeClass('border-danger');
                $('#usernameErrorLogin').addClass('d-none');
            }

            if ($('#passwordLogin').val().trim() === '') {
                $('#passwordErrorLogin').removeClass('d-none');
                $('#passwordLogin').addClass('border-danger');
                isValid = false;
            } else {
                $('#passwordLogin').removeClass('border-danger');
                $('#passwordErrorLogin').addClass('d-none');
            }

            if (isValid) {
                this.submit();
            }
        });

    </script>
@endsection