@extends('layouts.app', ['titlePage' => 'User Profile', 'pageSlug' => 'profile-index'])

@section('content')
    <div class="row">
        <div class="col-md-12">
             <table class="table table-bordered" style="width: 100%;margin: auto;height: calc(100vh - 148px);">
                    <tr>
                         <td style="width: 50%;vertical-align: top;height: 1px;">

                         <div class="card" style="height: 100%;">
                <div class="card-header">
                    <h5 class="title">{{ _('User Information') }}</h5>
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

                    </td>
 
                    </tr>
              </table>
       
    </div>
@endsection
