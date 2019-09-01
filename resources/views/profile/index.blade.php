@extends('layouts.app', ['titlePage' => 'Settings', 'pageSlug' => 'profile-index'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-14">
            <div class="card main-card">
                <div class="card-header card-header-slim">
			<div>
				<div style="float: left;"><h3 class="slim">Profile Settings</h3></div>
				<div class="clear: both;"></div>
			</div>
		</div>
                <div class="card-body">
                    <ul>
                         <li class="nav">
                              <div class="form-group">
                                   <label>{{ _('Username') }}:&nbsp;<span class="info-text">{{auth()->user()->name}}</span> </label>
                              </div>
                         </li>
                         <li class="nav">
                              <div class="form-group">
                                   <label>{{ _('Email address') }}:&nbsp;<span class="info-text">{{auth()->user()->email}}</span></label>
                              </div>
                         </li>
                         <li class="nav">
                              <div class="form-group">
                                   <label>{{ _('Role') }}:&nbsp;<span class="info-text"><?php if (Auth::user()->role) {
    echo Auth::user()->role->name;
} else {
    echo "Unknown";
} ?></span></label>
                              </div>
                         </li>
                         <li class="nav">
                              <div class="form-group">
                                   <table class="table table-striped table-bordered" style="width: 318px;margin:auto;">
                                        <thead class="thead-light role-permission">
                                                  <tr>
                                                       <th scope="col" class="role-permissions">Permission</th>
                                                  </tr>
                                        </thead>
                                        <tbody class="role-permission">
                                             @if (Auth::user() && Auth::user()->role && Auth::user()->role->name)
                                                  @foreach (Auth::user()->role->permissions as $permission)
                                                       <tr>
                                                                 <td class="role-name">{{$permission->permission->name}}</td>
                                                       </tr>
                                                  @endforeach
                                             @endif
                                        </tbody>
                                   </table>
                              </div>
                         </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
