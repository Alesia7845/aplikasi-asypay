@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4>Edit Pembayaran</h4>
    <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.pembayaran.form')
    </form>
</div>
@endsection
