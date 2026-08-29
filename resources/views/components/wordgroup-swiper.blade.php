@props([
    'title' => '',
    'wordgroups' => [],
    'id' => 'wordgroup-swiper',
])

<div {{ $attributes->merge(['class' => 'card wordgroup-swiper']) }} id="{{ $id }}" data-wordgroup-swiper>
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h4 data-wordgroup-title>{{ $title }}</h4>
        </div>
    </div>

    <div class="card-body position-relative">
        <button type="button" class="slider-nav-btn prev" data-wordgroup-prev aria-label="Previous wordgroup">
            <i class="fa fa-chevron-left"></i>
        </button>

        <div class="swiper slider" data-wordgroup-slider>
            <div class="swiper-wrapper">
                @foreach ($wordgroups as $wordgroup)
                    @php
                        $wordgroupId = is_array($wordgroup) ? ($wordgroup['id'] ?? null) : ($wordgroup->id ?? null);
                        $wordgroupText = is_array($wordgroup) ? ($wordgroup['text'] ?? '') : ($wordgroup->text ?? '');
                    @endphp
                    <div class="swiper-slide">
                        <h4 class="arabic-text ar-title word-group text-center" data-wordgroup-id="{{ $wordgroupId }}">
                            {{ $wordgroupText }}
                        </h4>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="button" class="slider-nav-btn next" data-wordgroup-next aria-label="Next wordgroup">
            <i class="fa fa-chevron-right"></i>
        </button>
    </div>
</div>
