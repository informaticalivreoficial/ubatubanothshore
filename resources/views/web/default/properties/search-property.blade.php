@extends("web.$configuracoes->template.master.master")

@section('content')
    <livewire:web.property-filter />
    <livewire:web.property-list />
@endsection

@section('css')
    
@endsection

@section('js')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('galleryRoot', () => ({
                open: false,
                images: [],
                current: 0,
                scale: 1,
                translateX: 0,
                translateY: 0,
                startX: 0,
                startY: 0,
                touchStartX: 0,
                touchEndX: 0,

                init() {
                    // Clique em qualquer botão da galeria
                    document.addEventListener('click', (e) => {
                        const btn = e.target.closest('.open-gallery-btn');
                        if (!btn) return;
                        this.openGallery(btn);
                    });

                    // Teclas
                    document.addEventListener('keydown', (e) => {
                        if (!this.open) return;
                        if (e.key === 'Escape') this.close();
                        if (e.key === 'ArrowRight') this.next();
                        if (e.key === 'ArrowLeft') this.prev();
                    });
                },

                openGallery(btn) {
                    const imgs = JSON.parse(btn.getAttribute('data-images'));
                    if (!imgs?.length) return;
                    this.images = imgs;
                    this.current = 0;
                    this.resetZoom();
                    this.open = true;
                },

                close() {
                    this.open = false;
                    this.resetZoom();
                },

                next() {
                    this.current = (this.current + 1) % this.images.length;
                    this.resetZoom();
                },

                prev() {
                    this.current = (this.current - 1 + this.images.length) % this.images.length;
                    this.resetZoom();
                },

                currentImage() {
                    return this.images[this.current] ?? '';
                },

                toggleZoom() {
                    if (this.scale > 1) {
                        this.resetZoom();
                    } else {
                        this.scale = 2;
                    }
                },

                resetZoom() {
                    this.scale = 1;
                    this.translateX = 0;
                    this.translateY = 0;
                },

                imageStyle() {
                    return `
                        transform: scale(${this.scale}) translate(${this.translateX}px, ${this.translateY}px);
                        transition: transform 0.2s ease-out;
                        cursor: ${this.scale > 1 ? 'move' : 'zoom-in'};
                    `;
                },

                onWheel(e) {
                    if (!this.open) return;
                    e.preventDefault();
                    const delta = e.deltaY < 0 ? 0.2 : -0.2;
                    this.scale = Math.min(Math.max(this.scale + delta, 1), 3);
                },

                onTouchStart(e) {
                    if (e.touches.length === 1) {
                        this.startX = e.touches[0].clientX - this.translateX;
                        this.startY = e.touches[0].clientY - this.translateY;
                        this.touchStartX = e.touches[0].clientX;
                    }
                },

                onTouchMove(e) {
                    if (e.touches.length === 1 && this.scale > 1) {
                        e.preventDefault();
                        this.translateX = e.touches[0].clientX - this.startX;
                        this.translateY = e.touches[0].clientY - this.startY;
                    }
                },

                onTouchEnd(e) {
                    this.touchEndX = e.changedTouches[0].clientX;
                    const diff = this.touchStartX - this.touchEndX;
                    if (Math.abs(diff) > 80 && this.scale === 1) {
                        diff > 0 ? this.next() : this.prev();
                    }
                }
            }));
        });
    </script>
@endsection