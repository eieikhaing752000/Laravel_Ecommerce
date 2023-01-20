@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <div class="card">
            <div class="card-header">
                <h3>Payment Method
                    <a href="{{ url('admin/payment/create') }}" class="btn btn-primary btn-sm text-white float-end">Add Payment</a>
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Payment Name</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->name }}</td>
                            <td>
                                <img src="{{asset("$payment->image")}}" style="width:70px;height:70px" alt="payment">
                            </td>
                            <td>{{ $payment->status=='0'?'Visible':'Hidden'}}</td>
                            <td>
                                <a href="{{ url('admin/payment/'.$payment->id.'/edit') }}" class="btn btn-success">Edit</a>
                                <a href="{{ url('admin/payment/'.$payment->id.'/delete') }}" onclick="return confirm('Are you sure you want to delete this payment?');" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection