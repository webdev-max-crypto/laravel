<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Smart Warehouse</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root{
  --ink:#0d1117;
  --blue:#2563eb;
  --blue2:#1d4ed8;
  --bg:#f9fafb;
  --border:#e4e9f0;
  --slate:#64748b;
}

*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'Plus Jakarta Sans',sans-serif;
  background:var(--bg);
  color:var(--ink);
}

/* CARD */
.login-wrapper{
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:40px;
}

.login-card{
  width:100%;
  max-width:420px;
  background:#fff;
  border:1px solid var(--border);
  border-radius:16px;
  padding:28px;
  box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.title{
  font-size:1.4rem;
  font-weight:800;
  margin-bottom:5px;
}

.subtitle{
  font-size:.85rem;
  color:var(--slate);
  margin-bottom:20px;
}

label{
  font-size:.8rem;
  font-weight:600;
}

input[type="email"],
input[type="password"]{
  width:100%;
  padding:10px 12px;
  border:1px solid var(--border);
  border-radius:10px;
  margin-top:6px;
  margin-bottom:14px;
  outline:none;
}

input:focus{
  border-color:var(--blue);
}

/* BUTTON */
button{
  width:100%;
  padding:12px;
  background:var(--blue);
  color:#fff;
  border:none;
  border-radius:10px;
  font-weight:700;
  cursor:pointer;
}

button:hover{
  background:var(--blue2);
}

/* LINKS */
.links{
  display:flex;
  justify-content:space-between;
  margin-top:15px;
  font-size:.85rem;
}

.links a{
  color:var(--blue);
  text-decoration:none;
  font-weight:600;
}

/* GOOGLE */
.google-btn{
  margin-top:15px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  padding:10px;
  border:1px solid var(--border);
  border-radius:10px;
  text-decoration:none;
  color:#000;
  background:#fff;
}
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
.logo-name{
  font-weight:800;
  color:var(--ink);
  font-size:1.1rem;
}
.logo-name em{color:var(--blue);font-style:normal}
.logo{display:flex;align-items:center;gap:10px;text-decoration:none}
.logo-box{
  width:38px;height:38px;
  background:var(--blue);
  border-radius:9px;
  display:flex;align-items:center;justify-content:center;
}
.google-btn:hover{
  background:#f9fafb;
}

</style>
<body>
<nav>
  <a href="/" class="logo">
    <div class="logo-box">
      🏭
    </div>
    <span class="logo-name">Smart-Multiwarehouse <em>Storage</em></span>
  </a>
</nav>

<div class="login-wrapper">

<div class="login-card">

<div class="title">Welcome Back</div>
<div class="subtitle">Login to your Smart Warehouse account</div>

<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('login') }}">
@csrf

<!-- Email -->
<label>Email</label>
<x-text-input id="email"
    type="email"
    name="email"
    class="w-full"
    :value="old('email')"
    required autofocus />

<x-input-error :messages="$errors->get('email')" class="mt-2" />

<!-- Password -->
<label>Password</label>
<x-text-input id="password"
    type="password"
    name="password"
    class="w-full"
    required />

<x-input-error :messages="$errors->get('password')" class="mt-2" />

<!-- Remember -->
<div style="margin-top:10px;">
    <label style="font-size:.85rem;">
        <input type="checkbox" name="remember">
        Remember me
    </label>
</div>

<!-- Button -->
<button type="submit" style="margin-top:15px;">
    Log in
</button>

<!-- Links -->
<div class="links">
@if (Route::has('password.request'))
<a href="{{ route('password.request') }}">Forgot password?</a>
@endif
<a href="/register">Create account</a>
</div>

<!-- Google -->
<a href="/auth/google" class="google-btn">
<img src="https://developers.google.com/identity/images/g-logo.png" width="18">
<span>Sign in with Google</span>
</a>

</form>

</div>

</div>

