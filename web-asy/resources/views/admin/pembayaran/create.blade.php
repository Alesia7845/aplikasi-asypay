@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4>Tambah Pembayaran</h4>
    <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.pembayaran.form')
    </form>
</div>
@endsection
