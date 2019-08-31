<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent fixed-top">
    <div class="container-fluid">
        <div class="navbar-wrapper">
           {{ $page ?? '' }}
        </div>

        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item ">
                    <a href="{{ route('register') }}" class="nav-link">
                        <i class="tim-icons icon-laptop"></i> Register
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="/login" class="nav-link">
                        <i class="tim-icons icon-single-02"></i> Login Now
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
