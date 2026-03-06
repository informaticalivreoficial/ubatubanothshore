@extends("web.layouts.app")

@section('content')    
    <div class="max-w-8xl mx-auto px-6 py-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full">                
                <livewire:web.review-form :token="$token" />
            </div>
        </div>
    </div>    
@endsection