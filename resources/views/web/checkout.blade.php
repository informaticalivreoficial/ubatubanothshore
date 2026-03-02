@extends('web.layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-10 px-4">

    <livewire:web.checkout-page 
        :property="$property"
        :check_in="request('check_in')"
        :check_out="request('check_out')"
        :guests="request('guests')"
    />

</div>

@endsection