@extends('layouts.app')

@section('heading')
    {{ __('Email Services') }}
@stop

@section('content')

    @component('layouts.partials.card')
        @slot('cardHeader', __('Edit Email Service'))

        @slot('cardBody')
            <form action="{{ route('email_services.update', $emailService->id) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <x-forms.text-field name="name" :label="__('Name')" :value="$emailService->name" />

                @include('email_services.options.' . strtolower($emailServiceType->name), ['settings' => $emailService->settings])

                <x-forms.submit-button :label="__('Update')" />
            </form>
        @endSlot
    @endcomponent

@stop
