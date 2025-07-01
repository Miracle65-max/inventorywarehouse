<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="icon">📊</span> Dashboard
        </a></li>
        <li><a href="{{ route('items.index') }}" class="{{ request()->routeIs('items.*') ? 'active' : '' }}">
            <span class="icon">📦</span> Product Management
        </a></li>
        @if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin']))
        <li><a href="{{ route('stock-movements.index') }}" class="{{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
            <span class="icon">🔄</span> Stock Movements
        </a></li>
        @endif
        <li><a href="{{ route('sales-orders.index') }}" class="{{ request()->routeIs('sales-orders.*') ? 'active' : '' }}">
            <span class="icon">🛒</span> Sales & Orders
        </a></li>
        <li><a href="{{ route('suppliers.index') }}" class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <span class="icon">🏢</span> Supplier Management
        </a></li>
        <li><a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="icon">📈</span> Reports
        </a></li>
        <li><a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <span class="icon">🔔</span> Notifications
        </a></li>
        @if(auth()->check() && auth()->user()->role === 'user')
        <li><a href="{{ route('warehouse-management.index') }}" class="{{ request()->routeIs('warehouse-management.*') ? 'active' : '' }}">
            <span class="icon">🏭</span> Warehouse Management
        </a></li>
        @endif
        @if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin']))
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link settings-toggle">
                <span class="icon">⚙️</span> Settings
                <span class="float-right">
                    <i class="fas fa-angle-left right"></i>
                </span>
            </a>
            <ul class="nav nav-treeview settings-menu" style="display: none;">
                <li class="nav-item">
                    <a href="{{ route('inventory-status.index') }}" class="nav-link {{ request()->routeIs('inventory-status.*') ? 'active' : '' }}">
                        <span class="icon">📋</span> Inventory Status
                    </a>
                </li>
                @if(auth()->user()->role === 'super_admin')
                    <li class="nav-item">
                        <a href="{{ route('user-profile.show') }}" class="nav-link {{ request()->routeIs('user-profile.*') ? 'active' : '' }}">
                            <span class="icon">👤</span> My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('audit-trails.index') }}" class="nav-link {{ request()->routeIs('audit-trails.*') ? 'active' : '' }}">
                            <span class="icon">🔍</span> Audit Trails
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user-management.index') }}" class="nav-link {{ request()->routeIs('user-management.*') ? 'active' : '' }}">
                            <span class="icon">👥</span> User Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('profile-management.index') }}" class="nav-link {{ request()->routeIs('profile-management.*') ? 'active' : '' }}">
                            <span class="icon">👥</span> Profile Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('recently-deleted.index') }}" class="nav-link {{ request()->routeIs('recently-deleted.*') ? 'active' : '' }}">
                            <span class="icon">🗑️</span> Recently Deleted
                        </a>
                    </li>
                @elseif(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('user-management.index') }}" class="nav-link {{ request()->routeIs('user-management.*') ? 'active' : '' }}">
                            <span class="icon">👥</span> User Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user-profile.show') }}" class="nav-link {{ request()->routeIs('user-profile.*') ? 'active' : '' }}">
                            <span class="icon">👤</span> My Profile
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        @endif
        <li class="nav-divider"></li>
        <li><a href="{{ route('logout') }}" class="nav-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="icon">🚪</span> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="redirect_to_login" value="1">
        </form>
        </li>
    </ul>
</div>
<style>
.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar-menu li a {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
}
.sidebar-menu li a:hover {
    background-color: #f8f9fa;
    color: #231f20;
}
.sidebar-menu li a.active {
    background-color: #146434;
    color: white;
}
.sidebar-menu .icon {
    margin-right: 10px;
    font-size: 16px;
    width: 20px;
    text-align: center;
}
.nav-divider {
    height: 1px;
    background-color: #e9ecef;
    margin: 10px 0;
}
.has-treeview .settings-toggle {
    cursor: pointer;
    justify-content: space-between;
}
.nav-treeview {
    background-color: #f8f9fa;
    padding-left: 0;
}
.nav-treeview .nav-item .nav-link {
    padding-left: 30px;
    font-size: 14px;
}
.nav-treeview .nav-item .nav-link:hover {
    background-color: #146434;
}
.float-right {
    margin-left: auto;
}
.right {
    transition: transform 0.3s ease;
}
.settings-menu.show .right {
    transform: rotate(-90deg);
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const settingsToggle = document.querySelector('.settings-toggle');
    const settingsMenu = document.querySelector('.settings-menu');
    const rightIcon = document.querySelector('.right');
    
    // Function to open the settings menu
    function openSettingsMenu() {
        settingsMenu.style.display = 'block';
        settingsMenu.classList.add('show');
        if (rightIcon) rightIcon.style.transform = 'rotate(-90deg)';
        // Save menu state
        localStorage.setItem('settingsMenuOpen', 'true');
    }
    
    // Function to close the settings menu
    function closeSettingsMenu() {
        settingsMenu.style.display = 'none';
        settingsMenu.classList.remove('show');
        if (rightIcon) rightIcon.style.transform = 'rotate(0)';
        // Save menu state
        localStorage.setItem('settingsMenuOpen', 'false');
    }
    
    if (settingsToggle && settingsMenu) {
        // Check if menu should be open from previous state
        if (localStorage.getItem('settingsMenuOpen') === 'true') {
            openSettingsMenu();
        }
        
        // Toggle menu when clicking the settings button
        settingsToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent the click from bubbling up
            
            if (settingsMenu.style.display === 'none' || settingsMenu.style.display === '') {
                openSettingsMenu();
            } else {
                closeSettingsMenu();
            }
        });
        
        // Prevent clicks inside the menu from closing it
        settingsMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!settingsToggle.contains(e.target) && !settingsMenu.contains(e.target)) {
                closeSettingsMenu();
            }
        });
        
        // Check if we're on a settings page
        const currentPage = window.location.pathname.split('/').pop();
        const settingsPages = [
            'user-profile',
            'audit-trails',
            'user-management',
            'recently-deleted',
            'inventory-status'
        ];
        
        // If we're on a settings page, open the menu
        if (settingsPages.some(page => currentPage.includes(page))) {
            openSettingsMenu();
        }
    }
});
</script>