@props([
    'name' => 'semester_id',
    'id' => null,
    'selected' => null,
    'required' => false,
    'emptyLabel' => null,
    'disabledEmpty' => false,
    'class' => null,
])

@php
    $semesters = \App\Models\Semester::with('academicYear')->orderBy('id')->get();
    $options = $semesters->map(fn($s) => ['value' => $s->id, 'label' => $s->label])->toArray();
    $placeholder = $emptyLabel ?? '-- Pilih Semester --';
@endphp

<x-select :name="$name" 
          :id="$id" 
          :selected="$selected" 
          :placeholder="$placeholder" 
          :required="$required" 
          :options="$options" 
          {!! $attributes !!} />