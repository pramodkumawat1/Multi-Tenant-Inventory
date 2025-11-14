@extends('layouts.admin')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Product Details</h4>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="py-2 px-0">
                                        <span class="font-weight-semibold w-50">Name </span>
                                    </td>
                                    <td class="py-2 px-0">{{$data->name}}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-0">
                                        <span class="font-weight-semibold w-50">SKU </span>
                                    </td>
                                    <td class="py-2 px-0">{{$data->sku}}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-0">
                                        <span class="font-weight-semibold w-50">Price </span>
                                    </td>
                                    <td class="py-2 px-0">{{$data->price}}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-0">
                                        <span class="font-weight-semibold w-50">Stock </span>
                                    </td>
                                    <td class="py-2 px-0">{{$data->stock}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <a class="btn btn-danger btn_back mt-3" href="{{ url()->previous() }}">
                {{ 'Back to list' }}
            </a>
        </div>
    </div>
</div>

@endsection
