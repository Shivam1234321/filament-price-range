<x-filament-tables::filters.filter
    :form="$getForm()"
    :is-active="$isActive"
    :max-height="$getMaxHeight()"
    :width="$getWidth()"
    :wire:key="$this->getId() . '.filters.' . $getName()"
>
    <x-slot name="trigger">
        <x-filament-tables::filters.trigger
            :active="$isActive"
            :active-count="$getActiveCount()"
            :badge="$getBadge()"
            :badge-color="$getBadgeColor()"
            :badge-size="$getBadgeSize()"
            :icon="$getIcon()"
            :icon-color="$getIconColor()"
            :icon-position="$getIconPosition()"
            :icon-size="$getIconSize()"
            :indicators-count="$getIndicatorsCount()"
            :label="$getLabel()"
            :should-open="$shouldOpen"
            :size="$getSize()"
            :tooltip="$getTooltip()"
        />
    </x-slot>

    <div class="p-4">
        <div class="space-y-4">
            <div class="price-range-filter-container">
                <div class="price-range-display">
                    <div class="price-range-value-group">
                        <div class="price-range-value price-range-from-value">{{ $getForm()->getState()['min'] ?? $getMinValue() }}</div>
                        <div class="price-range-label">{{ $getFromLabel() }}</div>
                    </div>
                    <div class="price-range-value-group">
                        <div class="price-range-value price-range-to-value">{{ $getForm()->getState()['max'] ?? $getMaxValue() }}</div>
                        <div class="price-range-label">{{ $getToLabel() }}</div>
                    </div>
                </div>

                <div 
                    class="price-range-slider-container"
                    data-price-range-filter
                    data-min="{{ $getMinValue() }}"
                    data-max="{{ $getMaxValue() }}"
                    data-step="{{ $getStep() }}"
                    data-from-label="{{ $getFromLabel() }}"
                    data-to-label="{{ $getToLabel() }}"
                ></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-filament::input.wrapper>
                    <x-filament::input
                        type="number"
                        :min="$getMinValue()"
                        :max="$getMaxValue()"
                        :step="$getStep()"
                        wire:model.live="filters.{{ $getName() }}.min"
                        placeholder="Min"
                    />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input
                        type="number"
                        :min="$getMinValue()"
                        :max="$getMaxValue()"
                        :step="$getStep()"
                        wire:model.live="filters.{{ $getName() }}.max"
                        placeholder="Max"
                    />
                </x-filament::input.wrapper>
            </div>
        </div>
    </div>
</x-filament-tables::filters.filter>
