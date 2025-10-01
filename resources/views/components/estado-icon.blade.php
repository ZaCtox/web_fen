@props(['estado'])

@switch($estado)
    @case('pendiente')
        <img src="{{ asset('icons/clock.svg') }}" alt="Pendiente" class="inline w-7 h-7">
        @break

    @case('en_revision')
        <img src="{{ asset('icons/revision.svg') }}" alt="En Revisión" class="inline w-8 h-8">
        @break

    @case('resuelta')
        <img src="{{ asset('icons/check.svg') }}" alt="Resuelta" class="inline w-8 h-8">
        @break

    @case('no_resuelta')
        <img src="{{ asset('icons/no_resuelta.svg') }}" alt="No Resuelta" class="inline w-7 h-7">
        @break
@endswitch
