@extends('layouts.admin')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">User Details</h4>
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
                                        <span class="font-weight-semibold w-50">Email </span>
                                    </td>
                                    <td class="py-2 px-0">{{$data->email}}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-0">
                                        <span class="font-weight-semibold w-50">Role </span>
                                    </td>
                                    <td class="py-2 px-0">{{$data->roles->first()->title ?? ''}}</td>
                                </tr>
                                @if($data?->store)
                                <tr>
                                    <td class="py-2 px-0">
                                        <span class="font-weight-semibold w-50">Store Name </span>
                                    </td>
                                    <td class="py-2 px-0">{{$data?->store?->name}}</td>
                                </tr>
                                @endif
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
