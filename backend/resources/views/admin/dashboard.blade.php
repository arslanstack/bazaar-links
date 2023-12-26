@extends('admin.admin_app')
@section('content')
<div class="wrapper wrapper-content animated fadeIn">
	<div class="row">
		<div class="col-lg-3">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<span class="label label-success pull-right">Total</span>
					<h5>Users</h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins">{{count_total_records('users')}}</h1>
					<div class="stat-percent font-bold text-primary"><a href="{{ url('admin/users') }}"><span class="label label-primary">View</span></a></div>
					<small>Users</small>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection