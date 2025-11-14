<div class="side-nav">
    <ul class="side-menu">
        <li class="slide {{ request()->is('home') ? 'active' : '' }}">
            <a class="side-menu__item" data-toggle="slide" href="{{ route('home') }}">
                <span class="side-menu__label">Dashboard</span>
            </a>
        </li>
        @can('user_access')
        <li class="slide {{ request()->is('users*') ? 'active' : '' }}">
            <a class="side-menu__item" data-toggle="slide" href="{{ route('users.index') }}">
                <span class="side-menu__label">Users</span>
            </a>
        </li>
        @endcan
        @can('product_access')
        <li class="slide {{ request()->is('products*') ? 'active' : '' }}">
            <a class="side-menu__item" data-toggle="slide" href="{{ route('products.index') }}">
                <span class="side-menu__label">Products</span>
            </a>
        </li>
        @endcan
    </ul>
</div>