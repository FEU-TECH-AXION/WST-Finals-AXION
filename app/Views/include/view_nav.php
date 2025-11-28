<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url(); ?>">
            <img src="<?= base_url('/public/assets/img/logoPlaceholder.png'); ?>" alt="AXION Logo" height="40" class="d-inline-block align-text-top">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Navigation Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- User Management (ITSO Personnel Only) -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('users'); ?>">User Management</a>
                </li>

                <!-- Equipment Management -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('equipments'); ?>">Equipment Management</a>
                </li>

                <!-- Borrowing Module -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('borrow'); ?>">Borrowing</a>
                </li>

                <!-- Return Module -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('returns'); ?>">Returns</a>
                </li>

                <!-- Reservation Module (Associates only) -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('reservations'); ?>">Reservations</a>
                </li>

                <!-- Reports -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('reports'); ?>">Reports</a>
                </li>

                <!-- About Page -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('about'); ?>">About</a>
                </li>
            </ul>

            <!-- Search Form -->
            <form class="d-flex me-2" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <!-- Login Button -->
            <a href="<?= base_url('login'); ?>" class="btn btn-login">Log In</a>
        </div>
    </div>
</nav>
