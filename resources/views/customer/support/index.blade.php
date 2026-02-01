@extends('customer.layouts.app')

@section('content')
<h3>Help & Support</h3>
<p>If you have any issues or questions, please contact our support team.</p>

<div class="card mb-3">
    <div class="card-body">
        <h5>Contact Information</h5>
        <p>Email: <a href="mailto:support@yourapp.com">support@yourapp.com</a></p>
        <p>Phone: +1 234 567 890</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Submit a Support Ticket</h5>
        <form action="{{ route('owner.help') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Ticket</button>
        </form>
    </div>
</div>
@endsection
