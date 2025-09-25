<?php

namespace PriceRange\PriceRangeFilter\Tables\Filters;

use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class PriceRangeFilter extends Filter
{
    protected string $view = 'price-range-filter::tables.filters.price-range-filter';

    protected string $minColumn = 'min_price';
    protected string $maxColumn = 'max_price';
    protected int $minValue = 0;
    protected int $maxValue = 10000;
    protected int $step = 1;
    protected string $fromLabel = 'FROM';
    protected string $toLabel = 'TO';

    public static function make(string $name = 'price_range'): static
    {
        return parent::make($name)
            ->form([
                TextInput::make('min')
                    ->label('Minimum Price')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('max')
                    ->label('Maximum Price')
                    ->numeric()
                    ->minValue(0),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['min'],
                        fn (Builder $query, $min): Builder => $query->where($this->getMinColumn(), '>=', $min),
                    )
                    ->when(
                        $data['max'],
                        fn (Builder $query, $max): Builder => $query->where($this->getMaxColumn(), '<=', $max),
                    );
            });
    }

    public function minColumn(string $column): static
    {
        $this->minColumn = $column;
        return $this;
    }

    public function maxColumn(string $column): static
    {
        $this->maxColumn = $column;
        return $this;
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

    public function getMinColumn(): string
    {
        return $this->minColumn;
    }

    public function getMaxColumn(): string
    {
        return $this->maxColumn;
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

    public function getFromLabel(): string
    {
        return $this->fromLabel;
    }

    public function getToLabel(): string
    {
        return $this->toLabel;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->form([
            TextInput::make('min')
                ->label('Minimum Price')
                ->numeric()
                ->minValue($this->getMinValue())
                ->maxValue($this->getMaxValue())
                ->step($this->getStep()),
            TextInput::make('max')
                ->label('Maximum Price')
                ->numeric()
                ->minValue($this->getMinValue())
                ->maxValue($this->getMaxValue())
                ->step($this->getStep()),
        ]);

        $this->query(function (Builder $query, array $data): Builder {
            return $query
                ->when(
                    $data['min'],
                    fn (Builder $query, $min): Builder => $query->where($this->getMinColumn(), '>=', $min),
                )
                ->when(
                    $data['max'],
                    fn (Builder $query, $max): Builder => $query->where($this->getMaxColumn(), '<=', $max),
                );
        });
    }
}
