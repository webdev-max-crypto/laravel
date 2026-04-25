


@if ($errors->any())
    <div style="color:red; margin-bottom:10px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Smart Warehouse</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root{
  --ink:#0d1117;
  --blue:#2563eb;
  --blue2:#1d4ed8;
  --bg:#f9fafb;
  --border:#e4e9f0;
  --slate:#64748b;
  --white:#fff;
}

*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'Plus Jakarta Sans',sans-serif;
  background:var(--bg);
  color:var(--ink);
}

/* NAV (same as homepage) */
nav{
  position:fixed;
  top:0;left:0;right:0;
  height:66px;
  background:#fff;
  border-bottom:1px solid var(--border);
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 6%;
  z-index:1000;
}

.logo{display:flex;align-items:center;gap:10px;text-decoration:none}
.logo-box{
  width:38px;height:38px;
  background:var(--blue);
  border-radius:9px;
  display:flex;align-items:center;justify-content:center;
}
.logo-name{
  font-weight:800;
  color:var(--ink);
  font-size:1.1rem;
}
.logo-name em{color:var(--blue);font-style:normal}

/* PAGE WRAP */
.wrapper{
  padding-top:100px;
  display:flex;
  justify-content:center;
  padding-bottom:60px;
}

/* CARD */
.card{
  width:100%;
  max-width:520px;
  background:#fff;
  border:1px solid var(--border);
  border-radius:16px;
  padding:28px;
  box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.title{
  font-size:1.4rem;
  font-weight:800;
  margin-bottom:6px;
}
.subtitle{
  font-size:.85rem;
  color:var(--slate);
  margin-bottom:20px;
}

/* INPUTS */
label{
  font-size:.8rem;
  font-weight:600;
  margin-bottom:6px;
  display:block;
}

input,select{
  width:100%;
  padding:10px 12px;
  border:1px solid var(--border);
  border-radius:10px;
  outline:none;
  font-size:.9rem;
  margin-bottom:14px;
}

input:focus,select:focus{
  border-color:var(--blue);
}

/* BUTTON */
.btn{
  width:100%;
  padding:12px;
  background:var(--blue);
  color:#fff;
  border:none;
  border-radius:10px;
  font-weight:700;
  cursor:pointer;
  transition:.2s;
}
.btn:hover{
  background:var(--blue2);
}

/* ERROR */
.error{
  background:#fee2e2;
  color:#b91c1c;
  padding:10px;
  border-radius:10px;
  font-size:.85rem;
  margin-bottom:12px;
}
.mt-4{
        display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    width:100%;
    padding:11px;
    border:1px solid #e4e9f0;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
    font-size:.9rem;
    color:#0d1117;
    background:#fff;
    transition:.2s;
   "
   onmouseover="this.style.background='#f9fafb'"
   onmouseout="this.style.background='#fff'"

}

.location-fields{
  display:block;
}

/* SUCCESS */
.success{
  background:#dcfce7;
  color:#166534;
  padding:10px;
  border-radius:10px;
  font-size:.85rem;
  margin-bottom:12px;
}

/* OWNER FIELDS */
.owner-only{display:none}
</style>
</head>

<body>

<!-- NAV -->
<nav>
  <a href="/" class="logo">
    <div class="logo-box">
      🏭
    </div>
    <span class="logo-name">Smart-Multiwarehouse <em>Storage</em></span>
  </a>
</nav>

<div class="wrapper">

<div class="card">

<div class="title">Create Account</div>
<div class="subtitle">Register as Customer, Owner or Admin</div>

@if ($errors->any())
<div class="error">
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

@if(session('success'))
<div class="success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.register') }}" enctype="multipart/form-data">
@csrf

<label>Name</label>
<input type="text" name="name" pattern="[A-Za-z\s]+" value="{{ old('name') }}" required placeholder="Your full name">

<label>Email</label>
<input type="email" name="email" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" required placeholder="Your email address">

<label>Password</label>
<input type="password" name="password" required>

<label>Confirm Password</label>
<input type="password" name="password_confirmation" required>

<label>Phone</label>
<input 
    type="text" 
    name="phone" 
    inputmode="numeric" 
    pattern="[0-9]*" 
    maxlength="15"
    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
    required
    placeholder="Your phone number"
>

<!-- ROLE -->
@php
$adminExists = \App\Models\User::where('role','admin')->exists();
@endphp

<label>Role</label>
<select name="role" id="role" required>
@if(!$adminExists)
<option value="admin">Admin</option>
@endif
<option value="owner">Owner</option>
<option value="customer" selected>Customer</option>
</select>

<!-- OWNER ONLY -->
<div class="owner-only">
<label>CNIC</label>
<input 
    type="text" 
    name="cnic" 
    id="cnic"
    maxlength="15"
    placeholder="XXXXX-XXXXXXX-X"
    oninput="formatCNIC(this)"
>
<label>CNIC Front</label>
<input type="file" name="cnic_front">

<label>CNIC Back</label>
<input type="file" name="cnic_back">

<label>Property Document just jpg,png</label>
<input type="file" name="property_document">
</div>

<div class="location-fields">

<label>Country</label>
<select id="country" name="country"></select>

<label>City</label>
<select id="city" name="city"></select>

</div>

<label>Profile Photo</label>
<input type="file" name="profile_photo">

<button type="submit" class="btn">Register</button>
<br>
 <!-- Submit -->
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}"style="color:#2563eb;font-weight:700;text-decoration:none;">
                {{ __('Already registered?') }}
            </a>
           
        </div>
        <div class="mt-4" id="googleSignup">
    <a href="/auth/google" class="flex items-center justify-center gap-2 border rounded-md px-4 py-2 bg-white" style="color:#2563eb;font-weight:700;text-decoration:none;">
        <img src="https://developers.google.com/identity/images/g-logo.png" width="20">
        <span>Sign up with Google</span>
    </a>
</div>
</form>
</div>

</div>

<script>
const role = document.getElementById('role');
const owner = document.querySelector('.owner-only');
const locationFields = document.querySelector('.location-fields');

function toggleFields() {
    if (role.value === 'owner') {
        owner.style.display = 'block';
        locationFields.style.display = 'none';
    } else {
        owner.style.display = 'none';
        locationFields.style.display = 'block';
    }
}

role.addEventListener('change', toggleFields);
toggleFields();

// Countries
let data=[];
fetch('https://countriesnow.space/api/v0.1/countries')
.then(r=>r.json())
.then(res=>{
data=res.data;
let c=document.getElementById('country');
c.innerHTML='<option>Select Country</option>';
data.forEach(i=>{
c.innerHTML+=`<option value="${i.country}">${i.country}</option>`;
});
});

document.getElementById('country').addEventListener('change',function(){
let city=document.getElementById('city');
city.innerHTML='';
let sel=data.find(i=>i.country===this.value);
if(sel){
sel.cities.forEach(x=>{
city.innerHTML+=`<option>${x}</option>`;
});
}
});



function formatCNIC(input) {
    let value = input.value.replace(/[^0-9]/g, ''); // only numbers

    if (value.length > 5) {
        value = value.substring(0, 5) + '-' + value.substring(5);
    }
    if (value.length > 13) {
        value = value.substring(0, 13) + '-' + value.substring(13);
    }

    input.value = value;
}

</script>


</body>
</html>