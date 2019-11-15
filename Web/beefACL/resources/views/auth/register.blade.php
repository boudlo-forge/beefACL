@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nickname / Shed Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                <span class="label-description">This will be on your membership card etc.</span>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="legal_name" class="col-md-4 col-form-label text-md-right">{{ __('Full (legal) Name') }}</label>

                            <div class="col-md-6">
                                <input id="legal_name" type="text" class="form-control @error('legal_name') is-invalid @enderror" name="legal_name" value="{{ old('legal_name') }}" autocomplete="legal_name">

                                @error('legal_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact_me" class="col-md-4 col-form-label text-md-right">{{ __('Send me email updates') }}</label>

                            <div class="col-md-6">
                                <div>
                                        <input type="checkbox" name="contact_me" id="contact_me" value="1" {{ $user->contact_me ? 'checked' : '' }}>&nbsp;<label for="contact_me" class="checkbox-label">
                                            <strong>Contact me about events and such</strong>
                                        </label><br />
                                        <span class="label-description">Checking this box means that sometimes we may send you updates about activities and events. Otherwise your email will only be used for required notices such as your membership updates. We will never send you spam or give your email to anyone else.</span>
                                    </div><br/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address_1" class="col-md-4 col-form-label text-md-right">{{ __('Address 1') }}</label>

                            <div class="col-md-6">
                                <input id="address_1" type="text" class="form-control @error('address_1') is-invalid @enderror" name="address_1" value="{{ old('address_1') }}"  autocomplete="address_1">

                                @error('address_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address_2" class="col-md-4 col-form-label text-md-right">{{ __('Address 2') }}</label>

                            <div class="col-md-6">
                                <input id="address_2" type="text" class="form-control @error('address_2') is-invalid @enderror" name="address_2" value="{{ old('address_2') }}"  autocomplete="address_2">

                                @error('address_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address_3" class="col-md-4 col-form-label text-md-right">{{ __('Address 3') }}</label>

                            <div class="col-md-6">
                                <input id="address_3" type="text" class="form-control @error('address_3') is-invalid @enderror" name="address_3" value="{{ old('address_3') }}" autocomplete="address_3">

                                @error('address_3')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="post_code" class="col-md-4 col-form-label text-md-right">{{ __('Post Code') }}</label>

                            <div class="col-md-6">
                                <input id="post_code" type="text" class="form-control @error('postcode') is-invalid @enderror" name="post_code" value="{{ old('postcode') }}" autocomplete="post_code">

                                @error('post_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_2" class="col-md-4 col-form-label text-md-right">{{ __('Secondary Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="phone_2" type="text" class="form-control @error('phone_2') is-invalid @enderror" name="phone_2" value="{{ old('phone_2') }}" autocomplete="mobile">

                                @error('phone_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <hr />

                        <div class="form-group row">
                            <label for="risk_info" class="col-md-4 col-form-label text-md-right">{{ __('Safety & Risk Information') }}</label>

                            <div class="col-md-6">
                                @foreach($riskFlags as $key => $riskFlag)
                                    <div>
                                        <input type="checkbox" name="risk_flag_{{ $key }}" id="risk_flag_{{ $key }}" value="{{ $key }}" {{ $user->hasRiskFlag($key) ? 'checked' : '' }}>&nbsp;<label for="risk_flag_{{ $key }}" class="checkbox-label">
                                            <strong>{{ $riskFlag['name'] }}</strong>
                                        </label><br />
                                        <span class="label-description">{{ $riskFlag['description'] }} You may elaborate in the notes field below if you so wish, alternatively ask to speak to one of the facility admins in person.</span>
                                    </div><br/>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="risk_notes" class="col-md-4 col-form-label text-md-right">{{ __('Safety & Risk Notes') }}</label>

                            <div class="col-md-6">
                                <textarea name="risk_notes" id="risk_notes" rows="5" class="form-control">

                                </textarea>

                                <span class="label-description"><strong>Optional.</strong> This will be held securely as encrypted data and it will never be shared with anyone outside of our administrators. If you prefer to talk to someone in person about any relevant information please leave this blank and ask to speak to one of the admins.</span>
                            </div>
                        </div>

                        <hr />

                        <div class="form-group row">
                            <label for="emergency_contact_name_1" class="col-md-4 col-form-label text-md-right">{{ __('Name of Emergency Contact 1') }}</label>

                            <div class="col-md-6">
                                <input id="emergency_contact_name_1" type="text" class="form-control @error('emergency_contact_name_1') is-invalid @enderror" name="emergency_contact_name_1" value="{{ old('emergency_contact_name_1') }}" autocomplete="mobile">

                                @error('emergency_contact_name_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emergency_contact_phone_1" class="col-md-4 col-form-label text-md-right">{{ __('Phone for Emergency Contact 1') }}</label>

                            <div class="col-md-6">
                                <input id="emergency_contact_phone_1" type="text" class="form-control @error('emergency_contact_phone_1') is-invalid @enderror" name="emergency_contact_phone_1" value="{{ old('emergency_contact_phone_1') }}" autocomplete="mobile">

                                @error('emergency_contact_phone_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emergency_contact_name_2" class="col-md-4 col-form-label text-md-right">{{ __('Name of Emergency Contact 2') }}</label>

                            <div class="col-md-6">
                                <input id="emergency_contact_name_2" type="text" class="form-control @error('emergency_contact_name_2') is-invalid @enderror" name="emergency_contact_name_2" value="{{ old('emergency_contact_name_2') }}" autocomplete="mobile">

                                @error('emergency_contact_name_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emergency_contact_phone_2" class="col-md-4 col-form-label text-md-right">{{ __('Phone for Emergency Contact 2') }}</label>

                            <div class="col-md-6">
                                <input id="emergency_contact_phone_2" type="text" class="form-control @error('emergency_contact_phone_2') is-invalid @enderror" name="emergency_contact_phone_2" value="{{ old('emergency_contact_phone_2') }}" autocomplete="mobile">

                                @error('emergency_contact_phone_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <hr />

                        <div class="form-group row">
                            <label for="dob" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>

                            <div class="col-md-6">
                                <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob">
                                <span class="label-description">Purely optional, this will only be used to give us an idea of the age of people using the space, and perhaps to wish you a happy birthday.</span>
                                @error('dob')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <hr />

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact_me" class="col-md-4 col-form-label text-md-right">{{ __('Data Protection') }}</label>

                            <div class="col-md-6">
                                <div>
                                    <input type="checkbox" required="required" name="contact_me" id="contact_me" value="1" {{ $user->contact_me ? 'checked' : '' }}>&nbsp;<label for="contact_me" class="checkbox-label">
                                        <strong>I accept the Data Protection Policy</strong>
                                    </label><br />
                                    <span class="label-description">The Data Protection (Bailiwick of Guernsey) Law, 2017 requires Men's Shed Guernsey to obtain your explicit permission to store and use the information you have provided above. Please download and read the <a href="/docs/MensShedDataProtectionPolicyNov2019.pdf" target="_blank">Men's Shed Guernsey Data Protection Policy</a> or ask a member of the team for a paper copy.</span>
                                </div><br/>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
