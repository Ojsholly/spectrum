<!-- partial:partials/_sidebar.html -->
<aside class="mdc-drawer mdc-drawer--dismissible mdc-drawer--open">
    <div class="mdc-drawer__header">
        <a href="{{ url('/admin/dashboard') }}" class="brand-logo">
            <img src="{{ asset('images/sp-book.png') }}" alt="logo">
            <h3 style="color: white;">SPECTRUM BOOKS</h3>
        </a>
    </div>
    <div class="mdc-drawer__content">
        <div class="user-info">
            <p class="email">{{ Auth::guard('admin')->user()->email }}</p>
        </div>
        <div class="mdc-list-group">
            <nav class="mdc-list mdc-drawer-menu">
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{ url('/admin/dashboard') }}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                            aria-hidden="true">home</i>
                        Dashboard
                    </a>
                </div>
                <div class="mdc-list-item mdc-drawer-item" tabindex="0">
                    <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-menu" tabindex="-1">
                      <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
                      Books Management
                      <i class="mdc-drawer-arrow material-icons">chevron_right</i>
                    </a>
                    <div class="mdc-expansion-panel" id="ui-sub-menu" style="display: block;">
                      <nav class="mdc-list mdc-drawer-submenu">
                        <div class="mdc-list-item mdc-drawer-item" tabindex="-1">
                          <a class="mdc-drawer-link" href="{{ url('admin/books/create-books') }}" tabindex="-1">
                            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                            aria-hidden="true">cloud_upload</i>
                            Add New Book
                          </a>
                        </div>
                        <div class="mdc-list-item mdc-drawer-item">
                            <a class="mdc-drawer-link" href="{{ url('admin/books/uploaded-books') }}">
                                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                                    aria-hidden="true">cloud_done</i>
                                Uploaded Books
                            </a>
                        </div>
                      </nav>
                    </div>
                </div>
                <div class="mdc-list-item mdc-drawer-item" tabindex="-1">
                    <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="key-management" tabindex="-1">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">lock</i>
                            Key Management
                        <i class="mdc-drawer-arrow material-icons">chevron_right</i>
                    </a>
                    <div class="mdc-expansion-panel" id="key-management">
                        <nav class="mdc-list mdc-drawer-submenu">
                            <div class="mdc-list-item mdc-drawer-item" tabindex="-1">
                            <a class="mdc-drawer-link" href="{{route('keys.index')}}" tabindex="-1">
                                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                            aria-hidden="true">view_list</i>
                                All Licenses
                            </a>
                            </div>
                            <div class="mdc-list-item mdc-drawer-item" tabindex="-1">
                            <a class="mdc-drawer-link" href="{{route('keys.create')}}" tabindex="-1">
                                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                            aria-hidden="true">build</i>
                                New License
                            </a>
                            </div>
                            <div class="mdc-list-item mdc-drawer-item">
                                <a class="mdc-drawer-link" href="{{ url('/admin/licenses/used-licenses') }}">
                                    <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                                        aria-hidden="true">track_changes</i>
                                    Used Licenses
                                </a>
                            </div>
                        </nav>
                    </div>
                  </div>

                @if(Auth::guard('admin')->user()->can('isSuperAdmin'))
                    <div class="mdc-list-item mdc-drawer-item">
                        <a class="mdc-drawer-link" href="{{ url('/admin/accounts/activate-accounts') }}">
                            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                                aria-hidden="true">check</i>
                            Activate Accounts
                        </a>
                    </div>
                    <div class="mdc-list-item mdc-drawer-item">
                        <a class="mdc-drawer-link" href="{{ url('/admin/api_accesskey_management') }}">
                            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                                aria-hidden="true">code</i>
                            API Access Keys
                        </a>
                    </div>
                    <div class="mdc-list-item mdc-drawer-item">
                        <a class="mdc-drawer-link" href="{{ url('admin/licenses/accounting-module') }}">
                            <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                                aria-hidden="true">euro_symbol</i>
                            Accounting Module
                        </a>
                    </div>
                @endif
                <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{ url('/admin/audit-logs') }}">
                        <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon"
                            aria-hidden="true">pie_chart_outlined</i>
                        Audit Logs
                    </a>
                </div>
            </nav>
        </div>
        <div class="profile-actions">
            <a href="javascript:;">Settings</a>
            <span class="divider"></span>
            <a href="{{ route('admin.logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</aside>
