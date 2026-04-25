<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password - Smart Warehouse</title>

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

/* NAV */
nav{
  position:fixed;
  top:0;left:0;right:0;
  height:66px;
  background:#fff;
  border-bottom:1px solid var(--border);
  display:flex;
  align-items:center;
  padding:0 6%;
  z-index:1000;
}

.logo{
  display:flex;
  align-items:center;
  gap:10px;
  text-decoration:none;
}

.logo-box{
  width:38px;height:38px;
  background:var(--blue);
  border-radius:9px;
  display:flex;
  align-items:center;
  justify-content:center;
}

.logo-name{
  font-weight:800;
  color:var(--ink);
  font-size:1.1rem;
}
.logo-name em{color:var(--blue);font-style:normal}

/* WRAPPER */
.wrapper{
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding-top:90px;
}

/* CARD */
.card{
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

/* LABEL */
label{
  font-size:.8rem;
  font-weight:600;
}

/* INPUT (Laravel components override friendly) */
input[type="email"]{
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

/* SUCCESS */
.success{
  background:#dcfce7;
  color:#166534;
  padding:10px;
  border-radius:10px;
  font-size:.85rem;
  margin-bottom:12px;
}

/* ERROR */
.error{
  color:red;
  font-size:.85rem;
  margin-bottom:10px;
}

</style>
</head>

<body>

<!-- NAV -->
<nav>
  <a href="/" class="logo">
    <div class="logo-box">🏭</div>
    <span class="logo-name">Smart-Multiwarehouse <em>Storage</em></span>
  </a>
</nav>

<div class="wrapper">

<div class="card">

<div class="title">Forgot Password</div>
<div class="subtitle">Enter your email to reset password</div>

@if (session('status'))
<div class="success">
    {{ session('status') }}
</div>
@endif

<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <label>Email</label>
    <x-text-input id="email"
        class="w-full"
        type="email"
        name="email"
        required autofocus />

    <x-input-error :messages="$errors->get('email')" class="error" />

    <button type="submit" style="margin-top:10px;">
        Send Reset Link
    </button>

</form>

</div>

</div>

</body>
</html>