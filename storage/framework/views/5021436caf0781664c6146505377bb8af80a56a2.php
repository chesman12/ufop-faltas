<!-- Main Header -->
<header class="main-header">

    <a href="<?php echo e(route('home')); ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><i class="fa fa-graduation-cap"></i></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><i class="fa fa-graduation-cap"></i> <?php echo explode(' ' ,config('app.name'))[0]; ?></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Exibir/Esconder Menu</span>
        </a>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?php echo e(route('logout')); ?>"><i class="fa fa-sign-out"></i> Sair</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
