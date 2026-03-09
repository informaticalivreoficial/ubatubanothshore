<?php

namespace App\Livewire\Dashboard\Properties;

use App\Models\Property;
use App\Models\PropertySeason;
use Livewire\Component;

class Seasons extends Component
{
    public Property $property;

    public $label;
    public $start_date;
    public $end_date;
    public $price_per_day;

    public $seasonId = null;

    protected function rules()
    {
        return [
            'label' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price_per_day' => 'required|numeric|min:0'
        ];
    }

    public function mount(Property $property)
    {
        $this->property = $property;
    }

    public function save()
    {
        $this->validate();

        PropertySeason::updateOrCreate(
            ['id' => $this->seasonId],
            [
                'property_id' => $this->property->id,
                'label' => $this->label,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'price_per_day' => $this->price_per_day
            ]
        );

        $this->resetForm();
    }

    public function edit($id)
    {
        $season = PropertySeason::findOrFail($id);

        $this->seasonId = $season->id;
        $this->label = $season->label;
        $this->start_date = $season->start_date->format('Y-m-d');
        $this->end_date = $season->end_date->format('Y-m-d');
        $this->price_per_day = $season->price_per_day;
    }

    public function delete($id)
    {
        PropertySeason::find($id)?->delete();
    }

    public function resetForm()
    {
        $this->reset([
            'seasonId',
            'label',
            'start_date',
            'end_date',
            'price_per_day'
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.properties.seasons', [
            'seasons' => $this->property->seasons()->orderBy('start_date')->get()
        ]);
    }
}
