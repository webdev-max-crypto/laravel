@extends('layouts.owner')

@section('content')
<div class="container">
    <h2>Edit Warehouse: {{ $warehouse->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.warehouses.update', $warehouse->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$warehouse->name) }}" required>
        </div>

        <div class="mb-3">
            <label>Country</label>
            <select name="location" class="form-control" required>
                @foreach($countries as $code=>$name)
                    <option value="{{ $name }}" {{ old('location',$warehouse->location)==$name?'selected':'' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Size</label>
            <input type="text" name="size" class="form-control" value="{{ old('size',$warehouse->size) }}">
        </div>

        <div class="mb-3">
            <label>Contact</label>
            <input type="text" name="contact" class="form-control" value="{{ old('contact',$warehouse->contact) }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description',$warehouse->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ old('address',$warehouse->address) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Total Units</label>
            <input type="number" name="total_space" class="form-control" value="{{ old('total_space',$warehouse->total_space) }}">
        </div>

        <div class="mb-3">
            <label>Available Units</label>
            <input type="number" name="available_space" class="form-control" value="{{ old('available_space',$warehouse->available_space) }}">
        </div>

        <div class="mb-3">
            <label>Price per Month (PKR)</label>
            <input type="number" step="0.01" name="price_per_month" class="form-control" value="{{ old('price_per_month',$warehouse->price_per_month) }}">
        </div>

        <hr>
        <h5>Payment Settings</h5>
        <div class="mb-3">
            <label>Preferred Payment Method</label>
            <select name="preferred_payment_method" class="form-control" onchange="togglePaymentFields(this.value)" required>
                <option value="stripe" {{ old('preferred_payment_method',$warehouse->preferred_payment_method)=='stripe'?'selected':'' }}>Stripe</option>
                <option value="jazzcash" {{ old('preferred_payment_method',$warehouse->preferred_payment_method)=='jazzcash'?'selected':'' }}>JazzCash</option>
            </select>
        </div>

        <div class="mb-3" id="jazzcashField" style="display:none;">
            <label>JazzCash Number</label>
            <input type="text" name="jazzcash_number" class="form-control" value="{{ old('jazzcash_number',$warehouse->jazzcash_number) }}">
        </div>

        <div class="mb-3" id="stripeField">
            <label>Stripe Account ID</label>
            <input type="text" name="stripe_account_id" class="form-control" value="{{ old('stripe_account_id',$warehouse->stripe_account_id) }}">
        </div>

        <div class="mb-3">
            <label>Main Image</label>
            <input type="file" name="image" class="form-control">
            @if($warehouse->image)
                <img src="{{ asset('storage/'.$warehouse->image) }}" width="200" class="mt-2">
            @endif
        </div>

        <div class="mb-3">
            <label>Property Document</label>
            <input type="file" name="property_doc" class="form-control">
            @if($warehouse->property_doc)
                <a href="{{ asset('storage/'.$warehouse->property_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">View Existing Document</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Warehouse</button>
        <a href="{{ route('owner.warehouses.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
function togglePaymentFields(value){
    const jazz=document.getElementById('jazzcashField');
    const stripe=document.getElementById('stripeField');
    if(value==='jazzcash'){ jazz.style.display='block'; stripe.style.display='none'; }
    else{ jazz.style.display='none'; stripe.style.display='block'; }
}
document.addEventListener("DOMContentLoaded", function(){
    togglePaymentFields("{{ old('preferred_payment_method',$warehouse->preferred_payment_method) }}");
});
</script>
@endsection
