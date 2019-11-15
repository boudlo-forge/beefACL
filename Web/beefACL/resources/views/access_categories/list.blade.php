@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-center">
                        <span class="headerLeft col-md-4">{{ __('Access Categories') }}{{ $searchTerms ?? ', All' }}</span>
                        <span class="headerMiddle col-md-4">{{ $accessCategories->links() }}</span>
                        <span class="headerRight col-md-4"><a class="btn btn-primary btn-sm" href="/access_category/new">{{ __('New Category') }}</a></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list categoryList">
                        <div class="listHeader row justify-content-left">
                            <div class="attributeName col-md-1">{{ __('ID') }}</div>
                            <div class="attributeName col-md-2">{{ __('Name') }}</div>
                            <div class="attributeName col-md-2">{{ __('Nodes') }}</div>
                            <div class="attributeName col-md-1"></div>
                        </div>
                        @forelse($accessCategories as $accessCategory)
                            <div class="listItem row justify-content-left">
                                <div class="attributeName col-md-1">{{ $accessCategory->id }}</div>
                                <div class="attributeName col-md-2">{{ $accessCategory->name }}</div>
                                <div class="attributeName col-md-2">{{ count($accessCategory->nodes) }}</div>
                                <div class="attributeName col-md-1">
                                    <a href="/access_category/{{ $accessCategory->id }}">EDIT</a>
                                </div>
                            </div>
                        @empty
                            <div class="listItem row">
                                <div class="noResults col-md-12">No Categories</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
