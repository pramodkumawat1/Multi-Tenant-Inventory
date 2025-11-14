@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <!-- Content -->
 
    <div class="container-xxl flex-grow-1 container-p-y">
        @if(Session::has('success'))
            @section('scripts')
                <script>swal("Good job!", "{{ Session::get('success') }}", "success");</script>
            @endsection
        @endif

        @if(Session::has('error'))
            @section('scripts')
                <script>swal("Oops...", "{{ Session::get('error') }}", "error");</script>
            @endsection
        @endif
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header border-bottom">
                        {{ isset($data) ? 'Edit' : 'Create' }} User
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($data) ? route('users.update', $data->id) : route('users.store') }}" method="POST" enctype="multipart/form-data" id="basic-form">
                            @csrf
                            @if(isset($data))
                                @method('PUT')
                            @endif

                            <h5 class="fw-bolder">{{ 'Basic Information' }}</h5>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Name <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" value="{{ old('name', isset($data) ? $data->name : '') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="email" class="mt-2"> Email <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email', isset($data) ? $data->email : '') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="password" class="mt-2"> Password  <span class="text-danger">{{ isset($data) && isset($data->id) ? '' : '*' }}</span> <i class="fe fe-info" data-toggle="tooltip" data-placement="right" title="Password must contain atleast one Lower case letter, atleast one Upper case letter, atleast one Number and atleast one Special character."></i></label>
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" minlength="8" {{ isset($data) ? '' : 'required' }}>
                                    @error('password')
                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="role" class="mt-2">Role <span class="text-danger">*</span></label>
                                    <select name="role" id="role" class="form-control role form-select @error('role') is-invalid @enderror" {{ isset($data) ? 'disabled' : '' }} required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $key => $value) 
                                            <option value="{{ $key }}" {{ (in_array($key, old('role', [])) || isset($data) && $data->roles->contains($key)) ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 d-none storeDetails">
                                    <label for="store-name" class="mt-2"> Store Name <span class="text-danger">*</span></label>
                                    <input type="text" name="store_name" id="store-name" class="form-control is_required @error('store_name') is-invalid @enderror" placeholder="Store Name" value="{{ old('store_name', isset($data) ? $data?->store?->name : '') }}" >
                                    @error('store_name')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <input class="btn btn-primary" type="submit" value="{{ isset($data) && isset($data->id) ? 'Update' : 'Save' }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts') 
<script>
    function roleFunction(roles) {
        if(roles == 2) {
            $('.storeDetails').removeClass("d-none");
            if($('#id').val() == "") {
                $('.storeDetails .is_required').attr('required',"required");
            }
        }
        else {
            $('.storeDetails').addClass("d-none");
            $('.storeDetails .is_required').removeAttr('required');
        }
    }
        
    $(document).ready(function(){
        $(document).on('change', '.role', function(){
            var roles = $(this).val();

            roleFunction(roles);
        });

        var roles = $('.role').val();
        roleFunction(roles);
    });
</script>
@endsection