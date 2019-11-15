@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $node->id ? __('Edit Node') : __('Create Node') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ $node->id ? '/node/' . $node->id : '/nodes' }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $node->id ? $node->name : old('name') }}" required autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <input id="description" type="description" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $node->id ? $node->description : old('description') }}">

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mac_address" class="col-md-4 col-form-label text-md-right">{{ __('MAC Address') }}</label>

                            <div class="col-md-6">
                                <input id="mac_address" type="mac_address" class="form-control @error('mac_address') is-invalid @enderror" name="mac_address" value="{{ $node->id ? $node->mac_address : old('mac_address') }}" required>

                                @error('mac_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="vendor_ref" class="col-md-4 col-form-label text-md-right">{{ __('Vendor Ref') }}</label>

                            <div class="col-md-6">
                                <input id="vendor_ref" class="form-control" name="vendor_ref" value="{{ $node->id ? $node->vendor_ref : old('vendor_ref') }}">
                                
                                @error('vendor_ref')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="access_category_id" class="col-md-4 col-form-label text-md-right">{{ __('Access Category ID') }}</label>

                            <div class="col-md-6">
                                {!! Form::select('access_category_id', $accessCategoryIds, (old('access_category_id') ? old('access_category_id') : $node->access_category_id), ['class' => 'form-control']) !!}
                                
                                @error('access_category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="config_flags" class="col-md-4 col-form-label text-md-right">{{ __('Config Flags') }}</label>

                            <div class="col-md-6">
                                <input id="config_flags" type="number" class="form-control" name="config_flags" value="{{ $node->id ? $node->config_flags : old('config_flags') }}">
                                
                                @error('config_flags')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="access_flags" class="col-md-4 col-form-label text-md-right">{{ __('Access Flags') }}</label>

                            <div class="col-md-6">
                                <input id="access_flags" type="number" class="form-control" name="access_flags" value="{{ $node->id ? $node->access_flags : old('access_flags') }}">

                                @error('access_flags')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status_id" class="col-md-4 col-form-label text-md-right">{{ __('Status ID') }}</label>

                            <div class="col-md-6">
                                {!! Form::select('status_id', $statuses, (old('status_id') ? old('status_id') : $node->status_id), ['class' => 'form-control']) !!}

                                @error('status_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
