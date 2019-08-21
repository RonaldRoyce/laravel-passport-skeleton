@extends('layouts.app', ['pageSlug' => 'oauthadmin'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-border">
                <div class="card-header">Clients</div>

                <div class="card-body">
			<passport-clients></passport-clients>
                </div>
            </div>
	    <p></p>
            <div class="card card-border">
                <div class="card-header">Authorized Clients</div>

                <div class="card-body">
			<passport-authorized-clients></passport-authorized-clients>
                </div>
            </div>
	    <p></p>
            <div class="card card-border">
                <div class="card-header">Personal Access Tokens</div>

                <div class="card-body">
			<passport-personal-access-tokens></passport-personal-access-tokens>
                </div>
            </div>

		<button class="btn btn-primary" type="button" id="apibtn">API Call</button>
        </div>
    </div>
</div>
@endsection

@push('js')
	<script>
		$(document).ready(function() {
			$('#apibtn').on('click', function() {
			      $.ajax({
				        url: '<?php echo env('APP_URL', 'http://localhost') . "/callback" ?>',
				        type:"GET",
    					success: function(data, textStatus, jqXHR)
    					{
						var token = data['access_token'];
                              			$.ajax({
                                        		url: '<?php echo env('APP_URL', 'http://localhost') . "/api/groups/add" ?>',
                                        		type:"GET",
							headers: {'Authorization': "Bearer " + token, "Content-Type": "application/json" },
    							success: function(data, textStatus, jqXHR)
    							{
        							var a = 1;
    							},
    							error: function (jqXHR, textStatus, errorThrown)
    							{
        							var b = 1;
    							}
						});
    					},
    					error: function (jqXHR, textStatus, errorThrown)
    					{
 						var b = 1;
    					}
				});
			});
		});

	</script>
@endpush


