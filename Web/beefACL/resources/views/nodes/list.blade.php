@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-center">
                        <span class="headerLeft col-md-4">{{ __('Nodes') }}{{ $searchTerms ?? ', All' }}</span>
                        <span class="headerMiddle col-md-4">{{ $nodes->links() }}</span>
                        <span class="headerRight col-md-4"><a class="btn btn-primary btn-sm" href="/node/new">{{ __('New Node') }}</a></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list nodeList">
                        <div class="listHeader row justify-content-left">
                            <div class="attributeName col-md-1">{{ __('ID') }}</div>
                            <div class="attributeName col-md-2">{{ __('Name') }}</div>
                            <div class="attributeName col-md-2">{{ __('Category') }}</div>
                            <div class="attributeName col-md-2">{{ __('MAC') }}</div>
                            <div class="attributeName col-md-2">{{ __('Config') }}</div>
                            <div class="attributeName col-md-1">{{ __('Status') }}</div>
                            <div class="attributeName col-md-2"></div>
                        </div>
                        @forelse($nodes as $node)
                            <div class="listItem row justify-content-left">
                                <div class="attributeValue col-md-1">{{ $node->id }}</div>
                                <div class="attributeValue col-md-2"><a href="/node/{{ $node->id }}">{{ $node->name }}</a></div>
                                <div class="attributeValue col-md-2">{{ is_object($node->access_category) ? $node->access_category->name : 'None' }}</div>
                                <div class="attributeValue col-md-2">{{ $node->mac_address }}</div>
                                <div class="attributeValue col-md-2">{{ $node->config_flags }}</div>
                                <div class="attributeValue col-md-1">{{ $statuses[$node->status_id] }}</div>
                                <div class="listItemControls col-md-2">
                                    <a class="btn btn-sm btn-primary" href="/node/{{ $node->id }}/edit">EDIT</a>
                                </div>
                            </div>
                        @empty
                            <div class="listItem row">
                                <div class="noResults col-md-12">No Nodes</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
