<?php
// File: app/Views/templates/header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= base_url('/public/assets/img/logoPlaceholder.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= base_url('/public/assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title><?= esc($title ?? 'ITSO'); ?></title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
                <img src="<?= base_url('/public/assets/img/logoPlaceholder.png') ?>" alt="Logo" height="40" class="me-2">
                ITSO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <?php if (session()->get('logged_in')): ?>
                        <?php 
                        $role = session()->get('role');
                        $dashboardLink = match($role) {
                            'admin' => '/admin/dashboard',
                            'associate' => '/associate/dashboard',
                            default => '/user/dashboard'
                        };
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url($dashboardLink) ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm ms-2" href="<?= base_url('logout') ?>">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="<?= base_url('login') ?>">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>