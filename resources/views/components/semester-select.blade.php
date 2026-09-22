@props([
    'name' => 'semester_id',
    'id' => null,
    'selected' => null,
    'required' => false,
    'emptyLabel' => null,
    'disabledEmpty' => false,
    'class' => null,
    'semesters' => null,
])

@php
    $semestersCollection = $semesters ?? \App\Models\Semester::with('academicYear')->orderBy('id')->get();
    $options = $semestersCollection->map(fn($s) => ['value' => $s->id, 'label' => $s->label])->toArray();
    $placeholder = $emptyLabel ?? ($semestersCollection->isEmpty() ? 'Belum ada semester yang tersedia' : '-- Pilih Semester --');
@endphp

<x-select :name="$name" 
          :id="$id" 
          :selected="$selected" 
          :placeholder="$placeholder" 
          :required="$required" 
          :options="$options" 
          {!! $attributes !!} />