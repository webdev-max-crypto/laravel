@extends('admin.layouts.app')

@section('content')
@php
    return redirect()->route('admin.warehouses.pending');
@endphp
@endsection
