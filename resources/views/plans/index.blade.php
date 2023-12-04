@extends('layouts.app')

@section('htmlBody')
            @section('title', __('Choose a plan'))
    
    <div class="container mt-5">
    <div class="row ">

        @foreach(config('plans') as $planName => $planDetails)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ ucfirst($planName) }} Plan</h5>
                        <p class="card-text">Pricing: ${{ $planDetails['pricing'] }}</p>
                        <p class="card-text">Subscribers: {{ $planDetails['subscribers'] }}</p>
                        <p class="card-text">Team Members: {{ $planDetails['team_members'] }}</p>
                        <p class="card-text">Domains: {{ $planDetails['domains'] }}</p>
                        <p class="card-text">Emails Limit: {{ $planDetails['emails_limit'] ?? 'Unlimited' }}</p>
                        <p class="card-text">Workspaces: {{ $planDetails['workspaces'] }}</p>
                        <h6>Features:</h6>
                        <ul>
                            @foreach($planDetails['features'] as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                        <form method="post">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $planName }}">
                            <button type="submit" class="btn btn-primary">Choose {{ ucfirst($planName) }} Plan</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Add an additional card for the Enterprise plan -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Enterprise Plan</h5>
                    <!-- Include Enterprise plan details here -->
                    <!-- ... -->
                    <form method="post">
                        @csrf
                        <input type="hidden" name="plan" value="enterprise">
                        <button type="submit" class="btn btn-primary">Choose Enterprise Plan</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div> 

@endsection
