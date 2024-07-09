@props(['disabled' => false])

<input spellcheck="false" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'font-light text-dark bg-greylight rounded-md pb-1 w-full']) !!}>
