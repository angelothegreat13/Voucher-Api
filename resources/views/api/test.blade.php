@extends('layout')
@section('content')

<div class="card">
    <div class="card-header">
        <h1 class="text-center">This is a Test Area</h1>
    </div>
</div>

@php
    print_r($voucherCodes);
@endphp

@endsection

@section('extra-scripts')
<script type="text/javascript">

console.log("Hello world");

</script>
@endsection

