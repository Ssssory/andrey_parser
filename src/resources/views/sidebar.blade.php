<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img src="/logo.png" alt="Detpars Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light" style="color: #343a40">ddd </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{-- <img src="/img/user2-160x160.jpg" class="img-circle elevation-2" alt="Img"> --}}
            </div>
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../../index.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dashboard v1</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../../index2.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dashboard v2</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../../index3.html" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dashboard v3</p>
                                    </a>
                                </li>
                            </ul> --}}
                    {{-- </li>
                            <li class="nav-item">
                            <a href="/admin/users/new" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Новый пользователь
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li> --}}
                </li>
                <li class="nav-item">
                    <a href="/admin/users" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Все пользователи
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                </li>
                <!--li class="nav-item">
                            <a href="/list/poslovnabazasrbije" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Poslovnabazasrbije
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li-->
                <li class="nav-item">
                    <a href="/rent/forzida" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Forzida
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/admin/settings/telegram" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            telegram setings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                </li>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>