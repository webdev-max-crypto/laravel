@extends('layouts.owner')

@section('content')
<div class="container mt-4">
    <h2>Owner Agreement</h2>

    <div class="card p-4 mt-3">
        <h4>Terms & Conditions</h4>
        <p class="mt-3">As an owner, you agree that:</p>

        <ul>
            <li>Your warehouse must remain active at least once every 30 days.</li>
            <li>If inactive for more than 30 days, your warehouse will be marked <strong>"Under Review"</strong>.</li>
            <li>Admin has the right to verify or reject your ownership details.</li>
            <li>You must upload accurate CNIC and property documents.</li>
            <li>False documents may result in permanent account suspension.</li>
        </ul>

        <form method="POST" action="{{ route('owner.agreement.accept') }}">
            @csrf
            <button class="btn btn-primary mt-3">I Agree & Continue</button>
        </form>
    </div>
</div>
@endsection
