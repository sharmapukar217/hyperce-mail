@extends('layouts.app')

@section('htmlBody')

        @component('layouts.partials.card')
        
        @if($currentPlan)
            @section('title', __('Your Plan'))
            @slot('cardHeader', __('Your Plan'))
        @else
            @section('title', __('Choose a plan'))
            @slot('cardHeader', __('Choose a plan'))
        @endif

            @slot('cardBody')
            @if($currentPlan)
            <div class="">
                Your `{{$currentPlan->plan->plan}}` plan expires at: {{ Carbon\Carbon::parse($currentPlan->expires_at) }}
                <hr />
            </div>
            @endif

                <form action="{{ route('plans.update') }}" method="POST" class="form-horizontal">
                    @csrf
                    <x-forms.select-field name="plan_id" :label="__('Plan')" :options="$availablePlans" :value="1" />

                    <div id="services-fields"></div>

                    <x-forms.submit-button :label="__('Submit')" />
                </form>
            @endSlot
        @endcomponent
@endsection
