<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('products.index') }}" class="nav-link {{ Request::is('products.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-edit"></i>
        <p>Products</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('items.index') }}" class="nav-link {{ Request::is('items.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-edit"></i>
        <p>Items</p>
    </a>
</li>
