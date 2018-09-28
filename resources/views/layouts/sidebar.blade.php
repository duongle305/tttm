<div class="sidebar sidebar-main">
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">
                    <a href="#" class="media-left"><img src="{{ asset('assets/images/placeholder.jpg') }}" class="img-circle img-sm" alt=""></a>
                    <div class="media-body">
                        <span class="media-heading text-semibold">Victoria Baker</span>
                        <div class="text-size-mini text-muted">
                            <i class="icon-pin text-size-small"></i> &nbsp;Santa Ana, CA
                        </div>
                    </div>

                    <div class="media-right media-middle">
                        <ul class="icons-list">
                            <li>
                                <a href="#"><i class="icon-cog3"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->


        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">
                    <!-- Main -->
                    <li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
                    <li><a href=""><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                    <li>
                        <a href="#"><i class="icon-stack2"></i> <span>Quản lý điều chuyển</span></a>
                        <ul>
                            <li><a href="{{ route('change-registers.index') }}">Danh sách đầu việc</a></li>
                            <li><a href="{{ route('local-manager-transfers.assets-temp-transfers') }}">Danh sách tài sản điều chuyển chờ nhận</a></li>
                        </ul>
                        <ul>
                            <li><a href="{{ route('local-transfers.index') }}">Điều chuyển giữa các node nội bộ</a></li>
                            <li><a href="{{ route('local-manager-transfers.create') }}">Điều chuyển giữa nhân viên quản lý</a></li>
                            <li><a href="{{ route('local-transfers.node-to-manager') }}">Điều chuyển giữa node và nhân viên quản lý</a></li>
                            <li><a href="{{ route('local-transfers.warehouse-to-manager') }}">Điều chuyển giữa kho và nhân viên quản lý</a></li>
                            <li><a href="{{ route('local-transfers.warehouse-to-warehouse') }}">Điều chuyển giữa các kho nội bộ</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="icon-stack2"></i> <span>Điều chuyển</span></a>
                        <ul>
                            <li><a href="{{ route('warehouse-to-node.create') }}">Kho sang node</a></li>
                            <li><a href="{{ route('node-to-warehouse.create') }}">Node sang kho</a></li>
                            <li><a href="{{ route('transfer-out-of-station.index') }}">Chuyển ra khỏi trạm</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /main navigation -->

    </div>
</div>