<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach($images as $img)
            <div class="swiper-slide">
                <img src="{{ $img }}" class="w-full h-[450px] object-cover rounded-xl">
            </div>
        @endforeach
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
});
</script>
@endpush