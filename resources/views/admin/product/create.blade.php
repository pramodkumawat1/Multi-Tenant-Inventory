@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header border-bottom">
                        {{ isset($data) ? 'Edit' : 'Create' }} Product
                    </div>
                    <div class="card-body">
                        <form action="{{ isset($data) ? route('products.update', $data->id) : route('products.store') }}" method="POST" enctype="multipart/form-data" id="basic-form">
                            @csrf
                            @if(isset($data))
                                @method('PUT')
                            @endif

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
                                    <label for="sku" class="mt-2"> SKU <span class="text-danger">*</span></label>
                                    <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" placeholder="SKU" value="{{ old('sku', isset($data) ? $data->sku : '') }}" required>
                                    @error('sku')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="price" class="mt-2"> Price <span class="text-danger">*</span></label>
                                    <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="Price" value="{{ old('price', isset($data) ? $data->price : '') }}" required>
                                    @error('price')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="stock" class="mt-2"> Stock <span class="text-danger">*</span></label>
                                    <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" placeholder="Stock" value="{{ old('stock', isset($data) ? $data->stock : '') }}" required>
                                    @error('stock')
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