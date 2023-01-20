@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <div class="card">
            <div class="card-header">
                <h3>Edit Payment Method
                    <a href="{{ url('admin/payment/') }}" class="btn btn-danger btn-sm text-white float-end">Back</a>
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/payment/'.$payment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="">Payment Name</label>
                        <input type="text" name="name" value="{{ $payment->name }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">Image</label>
                        <input type="file" name="image" class="form-control">
                        <img src="{{ asset("$payment->image") }}" style="width:50px;height:50px" alt="Payment">
                    </div>
                    <div class="mb-3">
                        <label for="">Status</label><br/>
                        <input type="checkbox" name="status" {{ $payment->status == '1' ? 'checked':'' }} style="width: 30px;height:30px"/>Checked=Hidden,UnChecked=Visible
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection