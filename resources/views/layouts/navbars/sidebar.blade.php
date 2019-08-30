<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-normal" style="text-align: center;">{{ _('Dashboard') }}</a>
        </div>
        <ul class="nav">
	    @if (Auth::user()->hasPermission('usermanagement-index') || 
		Auth::user()->hasPermission('rolemanagement-index') ||
		Auth::user()->hasPermission('rolepermissionmanagement-index'))
            <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="true">
                    <i class="fa fa-users"></i>
                    <span class="nav-link-text" >{{ ('User Administration') }}</span>
                </a>

                <div class="collapse show" id="laravel-examples">
                    <ul class="nav pl-4">
                    @if ( Auth::user()->hasPermission('permissionmanagement-index'))			
                        <li @if ($pageSlug == 'permissions-index') class="active " @endif>
                            <a href="{{ route('permissions')  }}">
                                <i class="fa fa-object-group"></i>
                                <span class="nav-link-text">{{ _('Permissions') }}</span>
                            </a>
                        </li>
                    @endif
			@if ( Auth::user()->hasPermission('rolemanagement-index'))
                        <li @if ($pageSlug == 'roles') class="active " @endif>
                            <a href="{{ route('roles')  }}">
                                <i class="fa fa-object-group"></i>
                                <span class="nav-link-text">{{ _('Roles') }}</span>
                            </a>
                        </li>
			@endif
			@if ( Auth::user()->hasPermission('rolepermissionmanagement-index'))
                        <li @if ($pageSlug == 'rolepermissions') class="active " @endif>
                            <a href="{{ route('rolepermissions')  }}">
                                <i class="fa fa-object-group"></i>
                                <span class="nav-link-text">{{ _('Role Permissions') }}</span>
                            </a>
                        </li>
			@endif
                        
               @if (Auth::user()->hasPermission('usermanagement-index'))
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <span class="nav-link-text">{{ _('User Management') }}</span>
                            </a>
                        </li>
			@endif
                    </ul>
                </div>
            </li>
	    @endif
            <li>
                <a data-toggle="collapse" href="#user-menu" aria-expanded="true">
                    <img src="/black/img/anime3.png" alt="Profile Photo" style="width: 30px; height: 30px;border-radius: 2.2857rem;">
                    <span class="nav-link-text" >{{ Auth::user()->name }}</span>
                </a>

                <div class="collapse show" id="user-menu">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <span class="nav-link-text">{{ _('User Profile') }}</span>
                            </a>
                        </li>
                        <li>
			    <a href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();"><i class="fa fa-sign-out-alt fa-black" style="color: black;"></i><span class="nav-link-text">{{ __('Log out') }}</span></a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
