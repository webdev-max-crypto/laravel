@extends('layouts.owner')

@section('content')
<div class="container">
    <h2 class="mb-4">Add New Warehouse</h2>

    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.warehouses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label>Name</label>
            <input type="text" id="name" name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name') }}"
            pattern="^[A-Za-z\s]+$">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Country --}}
        <div class="mb-3">
            <label>Country</label>
            <select id="country" name="location" class="form-control @error('location') is-invalid @enderror" onchange="setCountryCode(this.value)">
    @foreach($countries as $code=>$name)
        <option value="{{ $name }}">{{ $name }}</option>
    @endforeach
</select>
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Size --}}
        <div class="mb-3">
            <label>Size</label>
            <input type="text" id="size" name="size"
            class="form-control @error('size') is-invalid @enderror"
            value="{{ old('size') }}"
            pattern="^[0-9]+(\.[0-9]+)?$">
            @error('size')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Contact --}}
        <div class="mb-3">
            <label>Contact Number</label>
            <input type="text" id="contact" name="contact"
            class="form-control @error('contact') is-invalid @enderror"
            value="{{ old('contact') }}"
            pattern="^[0-9\s\-\+\(\)]+$">
            @error('contact')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        {{-- Description --}}
        <div class="mb-3">
            <label>Description</label>
            <textarea id="description" name="description"
            class="form-control @error('description') is-invalid @enderror"
            pattern="^[A-Za-z\s]+$">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Address --}}
        <div class="mb-3">
            <label>Address</label>
            <textarea id="address" name="address"
            class="form-control @error('address') is-invalid @enderror"
            pattern="^[A-Za-z0-9\s#,-]+$">{{ old('address') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Total Units --}}
        <div class="mb-3">
            <label>Total Units</label>
            <input type="number" name="total_space"
            class="form-control @error('total_space') is-invalid @enderror"
            value="{{ old('total_space') }}">
            @error('total_space')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Available Units --}}
        <div class="mb-3">
            <label>Available Units</label>
            <input type="number" name="available_space"
            class="form-control @error('available_space') is-invalid @enderror"
            value="{{ old('available_space') }}">
            @error('available_space')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Price --}}
        <div class="mb-3">
            <label>Price per Month (PKR)</label>
            <input type="number" step="0.01" name="price_per_month"
min="2500" max="5000"
class="form-control @error('price_per_month') is-invalid @enderror"
value="{{ old('price_per_month') }}">
@error('price_per_month')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
        </div>

        <hr>
        <h5>Payment Settings</h5>

        {{-- Payment Method --}}
        <div class="mb-3">
            <label>Preferred Payment Method</label>
            <select name="preferred_payment_method" class="form-control" onchange="togglePaymentFields(this.value)">
                <option value="stripe" {{ old('preferred_payment_method')=='stripe'?'selected':'' }}>Stripe</option>
                <option value="jazzcash" {{ old('preferred_payment_method')=='jazzcash'?'selected':'' }}>JazzCash</option>
            </select>
        </div>

        {{-- JazzCash --}}
        <div class="mb-3" id="jazzcashField" style="display:none;">
            <label>JazzCash Number</label>
            <input type="text" id="jazzcash" name="jazzcash_number"
            class="form-control @error('jazzcash_number') is-invalid @enderror"
            value="{{ old('jazzcash_number') }}"
            pattern="^[0-9]+$">
            @error('jazzcash_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Stripe --}}
        <div class="mb-3" id="stripeField">
            <label>Stripe Account ID</label>
            <input type="text" id="stripe" name="stripe_account_id"
            class="form-control @error('stripe_account_id') is-invalid @enderror"
            value="{{ old('stripe_account_id') }}"
            pattern="^[A-Za-z0-9_]+$">
            @error('stripe_account_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <hr>

        {{-- Image --}}
        <div class="mb-3">
            <label>Main Image</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Document --}}
        <div class="mb-3">
            <label>Property Document</label>
            <input type="file" name="property_doc" class="form-control @error('property_doc') is-invalid @enderror">
            @error('property_doc')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Submit for Approval</button>
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
    togglePaymentFields("{{ old('preferred_payment_method','stripe') }}");

    function validateField(field, message) {
        const value = field.value;
        const pattern = new RegExp(field.getAttribute("pattern"));
        let errorDiv = field.nextElementSibling;

        if (!pattern.test(value) && value !== "") {
            field.classList.add("is-invalid");

            if (!errorDiv || !errorDiv.classList.contains("live-error")) {
                errorDiv = document.createElement("div");
                errorDiv.className = "invalid-feedback live-error";
                field.after(errorDiv);
            }

            errorDiv.innerText = message;
        } else {
            field.classList.remove("is-invalid");
            if (errorDiv && errorDiv.classList.contains("live-error")) {
                errorDiv.remove();
            }
        }
    }

    document.querySelector('[name="price_per_month"]').addEventListener("input", function () {
    let value = parseFloat(this.value);

    if (value < 2500 || value > 5000) {
        this.classList.add("is-invalid");
    } else {
        this.classList.remove("is-invalid");
    }
});

    document.getElementById("name").addEventListener("input", function(){
        validateField(this, "Only alphabets allowed");
    });

    document.getElementById("size").addEventListener("input", function(){
        validateField(this, "Only numbers allowed");
    });
    document.getElementById("contact").addEventListener("input", function(){
        validateField(this, "Invalid contact format");
    });

    document.getElementById("description").addEventListener("input", function(){
        validateField(this, "Only alphabets allowed");
    });

    document.getElementById("address").addEventListener("input", function(){
        validateField(this, "Invalid address format");
    });

    document.getElementById("jazzcash").addEventListener("input", function(){
        validateField(this, "Only numbers allowed");
    });

    document.getElementById("stripe").addEventListener("input", function(){
        validateField(this, "Only letters & numbers allowed");
    });
});
</script>
@endsection