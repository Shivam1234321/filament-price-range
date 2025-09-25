<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="() => ({
            minValue: {{ (int) data_get($getState(), 'min', $getMinValue()) }},
            maxValue: {{ (int) data_get($getState(), 'max', $getMaxValue()) }},
            min: {{ $getMinValue() }},
            max: {{ $getMaxValue() }},
            step: {{ $getStep() }},
            fromLabel: '{{ $getFromLabel() }}',
            toLabel: '{{ $getToLabel() }}',
            showLabels: {{ $getShowLabels() ? 'true' : 'false' }}
        })"
        x-init="
            $nextTick(() => {
                const root = $el;
                const element = root.querySelector('[data-price-range-filter]');
                if (element) {
                    const instance = new PriceRangeFilter(element, {
                        min: min,
                        max: max,
                        step: step,
                        fromLabel: fromLabel,
                        toLabel: toLabel
                    });
                    // Sync UI → Livewire
                    root.addEventListener('priceRangeChange', (e) => {
                        minValue = e.detail.min;
                        maxValue = e.detail.max;
                        $wire.$set('{{ $getStatePath() }}', { min: minValue, max: maxValue });
                    });
                }
                // Ensure Livewire state is initialized
                $wire.$set('{{ $getStatePath() }}', { min: minValue, max: maxValue });
            })
        "
        class="price-range-filter-container"
        data-price-range-filter
        data-min="{{ $getMinValue() }}"
        data-max="{{ $getMaxValue() }}"
        data-step="{{ $getStep() }}"
        data-from-label="{{ $getFromLabel() }}"
        data-to-label="{{ $getToLabel() }}"
    >
        @if($getShowLabels())
        <div class="price-range-display">
            <div class="price-range-value-group">
                <div class="price-range-value" x-text="minValue"></div>
                <div class="price-range-label">{{ $getFromLabel() }}</div>
            </div>
            <div class="price-range-value-group">
                <div class="price-range-value" x-text="maxValue"></div>
                <div class="price-range-label">{{ $getToLabel() }}</div>
            </div>
        </div>
        @endif

        <div class="price-range-slider-container"></div>

        <!-- Keep hidden inputs in sync (for non-Livewire form posts) -->
        <input type="hidden" name="{{ $getMinFieldName() }}" :value="minValue">
        <input type="hidden" name="{{ $getMaxFieldName() }}" :value="maxValue">
    </div>
</x-dynamic-component>
