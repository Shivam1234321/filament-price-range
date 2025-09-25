<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            minValue: @entangle($getStatePath() . '.min'),
            maxValue: @entangle($getStatePath() . '.max'),
            min: {{ $getMinValue() }},
            max: {{ $getMaxValue() }},
            step: {{ $getStep() }},
            fromLabel: '{{ $getFromLabel() }}',
            toLabel: '{{ $getToLabel() }}',
            showLabels: {{ $getShowLabels() ? 'true' : 'false' }}
        }"
        x-init="
            $nextTick(() => {
                const element = $el.querySelector('[data-price-range-filter]');
                if (element) {
                    new PriceRangeFilter(element, {
                        min: min,
                        max: max,
                        step: step,
                        fromLabel: fromLabel,
                        toLabel: toLabel
                    });
                }
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

        <!-- Hidden inputs for form submission -->
        <input type="hidden" name="{{ $getMinFieldName() }}" x-model="minValue">
        <input type="hidden" name="{{ $getMaxFieldName() }}" x-model="maxValue">
    </div>
</x-dynamic-component>
