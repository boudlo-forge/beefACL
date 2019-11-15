@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-center">
                        <span class="headerLeft col-md-4">{{ __('Users') }}{{ $searchTerms ?? ', All' }}</span>
                        <span class="headerMiddle col-md-4">{{ $users->links() }}</span>
                        <span class="headerRight col-md-4"><a class="btn btn-primary btn-sm" href="/user/new">{{ __('New user') }}</a></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list userList">
                        <div class="listHeader row justify-content-left">
                            <div class="attributeName col-md-1">{{ __('ID') }}</div>
                            <div class="attributeName col-md-2">{{ __('Name') }}</div>
                            <div class="attributeName col-md-2">{{ __('Admin Roles') }}</div>
                            <div class="attributeName col-md-1">{{ __('Status') }}</div>
                            <div class="attributeName col-md-2"></div>
                        </div>
                        @forelse($users as $user)
                            <div class="listItem row justify-content-left">
                                <div class="attributeValue col-md-1">{{ $user->id }}</div>
                                <div class="attributeValue col-md-2"><a href="/user/{{ $user->id }}">{{ $user->name }}</a></div>
                                <div class="attributeValue col-md-2">{{ $user->access_flags }}</div>
                                <div class="attributeValue col-md-1">{{ $statuses[$user->status_id] }}</div>
                                <div class="listItemControls col-md-2">
                                    <a class="btn btn-sm btn-outline-primary" href="/user/{{ $user->id }}">VIEW</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-sm btn-primary" href="/user/{{ $user->id }}/edit">EDIT</a>
                                </div>
                            </div>
                        @empty
                            <div class="listItem row">
                                <div class="noResults col-md-12">No users</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
