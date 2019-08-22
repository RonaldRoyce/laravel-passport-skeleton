<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ _('BD') }}</a>
            <a href="#" class="simple-text logo-normal">{{ _('Black Dashboard') }}</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ _('Dashboard') }}</p>
                </a>
            </li>

	    @if (Auth::user()->role->name == 'Managers 2')
            <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="true">
                    <i class="fa fa-users"></i>
                    <span class="nav-link-text" >{{ ('User Administration') }}</span>
                </a>

                <div class="collapse show" id="laravel-examples">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ _('User Management') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('groups')  }}">
                                <i class="fa fa-object-group"></i>
                                <p>{{ _('Roles') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
	    @endif
            <li @if ($pageSlug == 'icons') class="active " @endif>
                <a href="{{ route('pages.icons') }}">
                    <i class="tim-icons icon-atom"></i>
                    <p>{{ _('Icons') }}</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#user-menu" aria-expanded="true">
                    <img src="/black/img/anime3.png" alt="Profile Photo" style="width: 30px; height: 30px;border-radius: 2.2857rem;">
                    <span class="nav-link-text" >{{ Auth::user()->name }}&nbsp;-&nbsp;{{Auth::user()->role->name}}</span>
                </a>

                <div class="collapse show" id="user-menu">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ _('User Profile') }}</p>
                            </a>
                        </li>
                        <li>
			    <a href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i><p>{{ __('Log out') }}</p></a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
