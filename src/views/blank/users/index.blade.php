@extends(config('cerberus.layout'))

{{-- Web site Title --}}
@section('title')
@parent
Current Users
@stop

{{-- Content --}}
@section('content')
<h1>Current Users</h1>

<a class='btn btn-primary' href="{{ route('cerberus.users.create') }}">Create User</a>

<table class="cerberus-table">
	<thead>
		<th>User</th>
		<th>Status</th>
		<th>Options</th>
	</thead>
	<tbody>
		@foreach ($users as $user)
			<tr>
				<td>
					<a href="{{ route('cerberus.users.show', array($user->hash)) }}">{{ $user->email }}</a>
				</td>
				<td>
					{{ $user->status }}
				</td>
				<td>
					<button type="button" onClick="location.href='{{ route('cerberus.users.edit', array($user->hash)) }}'">Edit</button>
					@if ($user->status != 'Suspended')
						<button type="button" onClick="location.href='{{ route('cerberus.users.suspend', array($user->hash)) }}'">Suspend</button>
					@else
						<button type="button" onClick="location.href='{{ route('cerberus.users.unsuspend', array($user->hash)) }}'">Un-Suspend</button>
					@endif
					@if ($user->status != 'Banned')
						<button type="button" onClick="location.href='{{ route('cerberus.users.ban', array($user->hash)) }}'">Ban</button>
					@else
						<button type="button" onClick="location.href='{{ route('cerberus.users.unban', array($user->hash)) }}'">Un-Ban</button>
					@endif
					<button class="action_confirm" href="{{ route('cerberus.users.destroy', array($user->hash)) }}" data-token="{{ Session::getToken() }}" data-method="delete">Delete</button>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>

{!! $users->render() !!}

@stop
