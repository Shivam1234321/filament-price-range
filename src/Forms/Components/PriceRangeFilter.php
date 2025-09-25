<?php

namespace Filament\PriceRangeFilter\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class PriceRangeFilter extends Field
{
    protected string $view = 'price-range-filter::forms.components.price-range-filter';

    protected int $minValue = 0;
    protected int $maxValue = 10000;
    protected int $step = 1;
    protected string $minFieldName = 'min_price';
    protected string $maxFieldName = 'max_price';
    protected bool $showLabels = true;
    protected string $fromLabel = 'FROM';
    protected string $toLabel = 'TO';

    public static function make(string $name = 'price_range'): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->configure();

        return $static;
    }

    public function minValue(int $value): static
    {
        $this->minValue = $value;
        return $this;
    }

    public function maxValue(int $value): static
    {
        $this->maxValue = $value;
        return $this;
    }

    public function step(int $value): static
    {
        $this->step = $value;
        return $this;
    }

    public function minFieldName(string $name): static
    {
        $this->minFieldName = $name;
        return $this;
    }

    public function maxFieldName(string $name): static
    {
        $this->maxFieldName = $name;
        return $this;
    }

    public function showLabels(bool $show = true): static
    {
        $this->showLabels = $show;
        return $this;
    }

    public function fromLabel(string $label): static
    {
        $this->fromLabel = $label;
        return $this;
    }

    public function toLabel(string $label): static
    {
        $this->toLabel = $label;
        return $this;
    }

    public function getMinValue(): int
    {
        return $this->minValue;
    }

    public function getMaxValue(): int
    {
        return $this->maxValue;
    }

    public function getStep(): int
    {
        return $this->step;
    }

    public function getMinFieldName(): string
    {
        return $this->minFieldName;
    }

    public function getMaxFieldName(): string
    {
        return $this->maxFieldName;
    }

    public function getShowLabels(): bool
    {
        return $this->showLabels;
    }

    public function getFromLabel(): string
    {
        return $this->fromLabel;
    }

    public function getToLabel(): string
    {
        return $this->toLabel;
    }

    public function getState(): mixed
    {
        $state = parent::getState();
        
        if (is_array($state)) {
            return $state;
        }

        if (is_string($state)) {
            $decoded = json_decode($state, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [
            'min' => $this->getMinValue(),
            'max' => $this->getMaxValue(),
        ];
    }

    public function dehydrateStateUsing(?callable $callback): static
    {
        $this->dehydrateStateUsing = $callback ?? function ($state) {
            if (is_array($state)) {
                return json_encode($state);
            }
            return $state;
        };

        return $this;
    }

    public function hydrateStateUsing(?callable $callback): static
    {
        $this->hydrateStateUsing = $callback ?? function ($state) {
            if (is_string($state)) {
                $decoded = json_decode($state, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
            }
            return $state;
        };

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (PriceRangeFilter $component, $state) {
            if (is_null($state)) {
                $component->state([
                    'min' => $component->getMinValue(),
                    'max' => $component->getMaxValue(),
                ]);
            }
        });

        $this->dehydrateStateUsing(function ($state) {
            if (is_array($state)) {
                return json_encode($state);
            }
            return $state;
        });

        $this->hydrateStateUsing(function ($state) {
            if (is_string($state)) {
                $decoded = json_decode($state, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
            }
            return $state;
        });
    }
}
