<style>
    .navbar-nav .dropdown-toggle.active {
        color: #ffc107 !important;
        font-weight: bold;
    }
    .navbar-nav .dropdown-item.active {
        background-color: #0d6efd !important;
        color: white !important;
    }
</style>

<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Expense Management System</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/addExpense">Add Expense</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="expenseDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Expense
                    </a>
                    <ul class="dropdown-menu custom-dropdown" aria-labelledby="expenseDropdown">
                        <li>
                            <a class="dropdown-item" href="/expense/allExpenses">All Expenses</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item" href="/expense/reports">Reports</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item" href="/expense/category">Category</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/profile">My Profile</a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link" id="logout-link">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    $('#logout-link').click(function (e) {
        e.preventDefault();
       
        $('.navbar-nav .nav-link').removeClass('active');
        $('.navbar-nav .dropdown-toggle').removeClass('active show');
        $('.dropdown-menu').removeClass('show');

        $.ajax({
            url: "{{ route('logout') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function () {
                window.location.href = '/login';
            },
            error: function () {
                alert('Logout failed. Please try again.');
            }
        });
    });

    $(document).ready(function () {
        var currentUrl = window.location.pathname;

        $('.navbar-nav .nav-link, .navbar-nav .dropdown-item').each(function () {
            var hrefAttr = $(this).attr('href');

            if (hrefAttr && hrefAttr !== '#' && hrefAttr !== '' && hrefAttr !== 'javascript:void(0);') {
                var linkUrl = new URL(this.href, window.location.origin).pathname;

                if (linkUrl === currentUrl) {
                    $(this).addClass('active');
                    if ($(this).closest('.dropdown-menu').length) {
                        $(this).closest('.dropdown').find('.dropdown-toggle').addClass('active');
                    }
                }
            }
        });

        $('.dropdown-menu a').click(function () {
            $('.dropdown-menu').removeClass('show');
            $('.dropdown-toggle').removeClass('show');
        });
    });
</script>