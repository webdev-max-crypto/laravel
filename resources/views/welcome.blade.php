<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Warehouse — Smart Warehouse Storage Pakistan</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    :root{
      --ink:#0d1117;--ink2:#1c2b3a;
      --blue:#2563eb;--blue2:#1d4ed8;
      --sky:#eff6ff;--sky2:#dbeafe;
      --gold:#f59e0b;--emerald:#10b981;
      --slate:#64748b;--border:#e4e9f0;
      --bg:#f9fafb;--white:#ffffff;
    }
    html{scroll-behavior:smooth}
    body{font-family:'Plus Jakarta Sans',sans-serif;color:var(--ink);background:var(--white);overflow-x:hidden}

    /* NAV */
    nav{position:fixed;top:0;left:0;right:0;z-index:200;height:66px;background:rgba(255,255,255,0.96);backdrop-filter:blur(16px);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 6%}
    .logo{display:flex;align-items:center;gap:10px;text-decoration:none}
    .logo-box{width:38px;height:38px;background:var(--blue);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .logo-name{font-size:1.2rem;font-weight:800;color:var(--ink);letter-spacing:-0.4px}
    .logo-name em{font-style:normal;color:var(--blue)}
    .nav-links{display:flex;gap:28px;list-style:none}
    .nav-links a{font-size:.875rem;font-weight:600;color:var(--slate);text-decoration:none;transition:color .18s}
    .nav-links a:hover{color:var(--blue)}
    .nav-btns{display:flex;gap:8px}
    .n-login{padding:8px 18px;border:1.5px solid var(--border);color:var(--ink);background:transparent;border-radius:8px;font-size:.85rem;font-weight:700;text-decoration:none;transition:all .18s}
    .n-login:hover{border-color:var(--ink)}
    .n-signup{padding:8px 18px;background:var(--blue);color:#fff;border:none;border-radius:8px;font-size:.85rem;font-weight:700;text-decoration:none;transition:background .18s}
    .n-signup:hover{background:var(--blue2)}

    /* HERO */
    .hero{padding:100px 6% 72px;display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;background:var(--white);min-height:100vh;position:relative}
    .hero-bg{position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 70% 40%,#eff6ff 0%,transparent 70%);pointer-events:none;z-index:0}
    .hero-left{position:relative;z-index:1}
    .hero-pill{display:inline-flex;align-items:center;gap:7px;background:var(--sky);color:var(--blue);border:1px solid var(--sky2);border-radius:100px;padding:5px 14px;font-size:.78rem;font-weight:700;margin-bottom:22px;letter-spacing:.3px}
    .dot{width:6px;height:6px;background:var(--blue);border-radius:50%;animation:blink 2s infinite;display:inline-block}
    @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
    .hero h1{font-size:clamp(2.2rem,3.8vw,3.4rem);font-weight:800;line-height:1.12;letter-spacing:-1px;color:var(--ink);margin-bottom:18px}
    .hero h1 .hl{color:var(--blue)}
    .hero-sub{font-size:1rem;color:var(--slate);line-height:1.75;max-width:440px;margin-bottom:34px}
    .hero-ctas{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:44px}
    .cta-main{padding:13px 28px;background:var(--blue);color:#fff;border-radius:10px;font-size:.95rem;font-weight:700;text-decoration:none;transition:all .2s;font-family:inherit}
    .cta-main:hover{background:var(--blue2);transform:translateY(-1px)}
    .cta-ghost{padding:13px 22px;color:var(--ink);font-size:.9rem;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:5px;transition:gap .2s}
    .cta-ghost:hover{gap:9px}
    .hero-nums{display:flex;gap:36px;padding-top:28px;border-top:1px solid var(--border)}
    .num-n{font-size:1.9rem;font-weight:800;color:var(--ink);line-height:1}
    .num-l{font-size:.76rem;color:var(--slate);font-weight:600;margin-top:3px}
    .hero-right{position:relative;z-index:1}
    .hero-img-wrap{border-radius:18px;overflow:hidden;box-shadow:0 24px 64px rgba(13,17,23,.14);cursor:pointer;transition:transform .2s}
    .hero-img-wrap:hover{transform:scale(1.01)}
    .hero-img-wrap img{width:100%;height:420px;object-fit:cover;display:block}
    .float-card{position:absolute;background:#fff;border-radius:12px;border:1px solid var(--border);padding:12px 16px;box-shadow:0 8px 28px rgba(0,0,0,.1)}
    .float-card.tl{top:-16px;left:-16px}
    .float-card.br{bottom:20px;right:-16px}
    .fc-icon{font-size:20px;margin-bottom:5px}
    .fc-title{font-size:.85rem;font-weight:700;color:var(--ink)}
    .fc-sub{font-size:.72rem;color:var(--slate);margin-top:1px}

    /* TRUST */
    .trust{background:var(--ink2);padding:18px 6%;display:flex;align-items:center;justify-content:center;gap:40px;flex-wrap:wrap}
    .trust-i{display:flex;align-items:center;gap:8px;color:rgba(255,255,255,.65);font-size:.82rem;font-weight:600}
    .trust-i .chk{color:var(--gold)}

    /* SECTIONS */
    section{padding:84px 6%}
    .tag{display:inline-block;font-size:.7rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--blue);margin-bottom:12px}
    .sec-h{font-size:clamp(1.7rem,2.6vw,2.4rem);font-weight:800;color:var(--ink);letter-spacing:-.5px;margin-bottom:12px}
    .sec-p{font-size:.95rem;color:var(--slate);line-height:1.75;max-width:500px}
    .center{text-align:center}.center .sec-p{margin:0 auto}

    /* HOW */
    .how-bg{background:var(--bg)}
    .steps{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:52px}
    .step{background:#fff;border:1px solid var(--border);border-radius:14px;padding:28px 20px;text-align:center;position:relative;transition:box-shadow .2s}
    .step:hover{box-shadow:0 12px 36px rgba(37,99,235,.08)}
    .step-n{width:48px;height:48px;background:var(--sky);border:2px solid var(--blue);border-radius:50%;color:var(--blue);font-size:1.1rem;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 18px}
    .step-h{font-size:.95rem;font-weight:700;color:var(--ink);margin-bottom:7px}
    .step-p{font-size:.82rem;color:var(--slate);line-height:1.6}
    .step-arrow{position:absolute;top:38px;right:-10px;font-size:1.1rem;color:#cbd5e1;z-index:1}

    /* PANELS */
    .panels-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:52px}
    .p-card{border:1.5px solid var(--border);border-radius:16px;padding:30px;transition:all .22s;position:relative;background:#fff}
    .p-card:hover{border-color:var(--blue);box-shadow:0 16px 48px rgba(37,99,235,.08);transform:translateY(-3px)}
    .p-card.hot{background:var(--ink2);border-color:var(--ink2)}
    .p-ico{width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:18px}
    .ico-a{background:rgba(245,158,11,.12)}.ico-o{background:rgba(255,255,255,.1)}.ico-c{background:rgba(16,185,129,.1)}
    .p-lbl{position:absolute;top:18px;right:18px;font-size:.68rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:3px 10px;border-radius:100px}
    .lbl-a{background:rgba(245,158,11,.12);color:#92670a}
    .lbl-o{background:rgba(255,255,255,.12);color:rgba(255,255,255,.7)}
    .lbl-c{background:rgba(16,185,129,.1);color:#047857}
    .p-h{font-size:1.1rem;font-weight:800;color:var(--ink);margin-bottom:10px}
    .p-card.hot .p-h{color:#fff}
    .p-d{font-size:.85rem;color:var(--slate);line-height:1.65;margin-bottom:20px}
    .p-card.hot .p-d{color:rgba(255,255,255,.6)}
    .p-ul{list-style:none;display:flex;flex-direction:column;gap:7px}
    .p-ul li{font-size:.82rem;color:var(--slate);display:flex;align-items:flex-start;gap:7px}
    .p-card.hot .p-ul li{color:rgba(255,255,255,.65)}
    .p-ul li::before{content:'✓';color:var(--emerald);font-weight:700;flex-shrink:0}

    /* GALLERY */
    .gallery-bg{background:var(--bg)}
    .gallery-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:52px}
    .g-item{border-radius:14px;overflow:hidden;cursor:pointer;aspect-ratio:4/3;position:relative}
    .g-item img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .35s}
    .g-item:hover img{transform:scale(1.06)}
    .g-overlay{position:absolute;inset:0;background:rgba(13,17,23,0);display:flex;align-items:center;justify-content:center;transition:background .25s}
    .g-item:hover .g-overlay{background:rgba(13,17,23,.38)}
    .g-icon{font-size:1.8rem;opacity:0;transition:opacity .25s;color:#fff}
    .g-item:hover .g-icon{opacity:1}

    /* LIGHTBOX */
    .lightbox{display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,.9);align-items:center;justify-content:center}
    .lightbox.open{display:flex}
    .lb-wrap{position:relative;max-width:92vw;max-height:92vh}
    .lb-wrap img{max-width:90vw;max-height:86vh;border-radius:10px;object-fit:contain;display:block;box-shadow:0 0 80px rgba(0,0,0,.5)}
    .lb-close{position:absolute;top:-14px;right:-14px;width:34px;height:34px;background:#fff;border-radius:50%;border:none;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--ink);box-shadow:0 2px 10px rgba(0,0,0,.2)}
    .lb-close:hover{background:#f0f0f0}

    /* PRICING */
    .price-wrap{margin-top:52px;background:var(--sky);border:1.5px solid var(--sky2);border-radius:20px;padding:48px;display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center}
    .big-price{font-size:3.8rem;font-weight:800;color:var(--ink);line-height:1;margin:10px 0 4px}
    .big-price span{font-size:1.5rem;color:var(--slate)}
    .price-note{font-size:.82rem;color:var(--slate);margin-bottom:22px}
    .price-body{font-size:.93rem;color:var(--slate);line-height:1.72;margin-bottom:28px}
    .price-btn{display:inline-block;padding:13px 28px;background:var(--blue);color:#fff;border-radius:10px;font-size:.92rem;font-weight:700;text-decoration:none;font-family:inherit;transition:background .2s}
    .price-btn:hover{background:var(--blue2)}
    .price-items{display:flex;flex-direction:column;gap:12px}
    .pr-row{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px}
    .pr-ic{font-size:22px;flex-shrink:0}
    .pr-t{font-size:.9rem;font-weight:700;color:var(--ink)}
    .pr-s{font-size:.78rem;color:var(--slate);margin-top:2px}

    /* SECURITY */
    .sec-bg{background:var(--ink2)}
    .sec-bg .tag{color:var(--gold)}
    .sec-bg .sec-h{color:#fff}
    .sec-bg .sec-p{color:rgba(255,255,255,.6)}
    .sec-row{display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center}
    .sec-list{display:flex;flex-direction:column;gap:18px;margin-top:32px}
    .sec-item{display:flex;gap:14px;align-items:flex-start}
    .si-ico{width:40px;height:40px;background:rgba(255,255,255,.07);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
    .si-t{font-size:.9rem;font-weight:700;color:#fff;margin-bottom:3px}
    .si-d{font-size:.8rem;color:rgba(255,255,255,.5);line-height:1.5}
    .sec-vis{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:18px;padding:30px}
    .refund-box{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.2);border-radius:12px;padding:22px;text-align:center;margin-bottom:18px}
    .rb-icon{font-size:32px;margin-bottom:8px}
    .rb-title{font-size:1rem;font-weight:800;color:var(--emerald)}
    .rb-sub{font-size:.78rem;color:rgba(255,255,255,.5);margin-top:5px;line-height:1.5}
    .flow-list{display:flex;flex-direction:column;gap:8px}
    .flow-row{display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.05);border-radius:9px;padding:11px 14px;font-size:.82rem}
    .fi{font-size:16px;flex-shrink:0}
    .fl{color:rgba(255,255,255,.65);flex:1}
    .ftag{font-size:.7rem;font-weight:700;padding:3px 9px;border-radius:100px}
    .ftg{background:rgba(16,185,129,.15);color:var(--emerald)}
    .fty{background:rgba(245,158,11,.15);color:var(--gold)}
    .ftb{background:rgba(37,99,235,.2);color:#93c5fd}

    /* TESTIMONIALS */
    .test-bg{background:var(--bg)}
    .test-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:52px}
    .test-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:26px}
    .stars{color:var(--gold);font-size:13px;margin-bottom:12px;letter-spacing:2px}
    .test-q{font-size:.88rem;color:var(--ink);line-height:1.7;margin-bottom:18px;font-style:italic}
    .test-who{display:flex;align-items:center;gap:10px}
    .ava{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:800;color:#fff;flex-shrink:0}
    .av1{background:var(--blue)}.av2{background:var(--gold);color:#7a4f00}.av3{background:var(--emerald)}
    .ava-name{font-size:.87rem;font-weight:700;color:var(--ink)}
    .ava-role{font-size:.73rem;color:var(--slate)}

    /* CTA */
    .cta-sec{background:linear-gradient(130deg,#1e40af 0%,#2563eb 100%);padding:80px 6%;text-align:center}
    .cta-sec .sec-h{color:#fff}
    .cta-sec .sec-p{color:rgba(255,255,255,.75);margin:14px auto 36px}
    .cta-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
    .cb-w{padding:13px 28px;background:#fff;color:var(--blue);border-radius:10px;font-size:.9rem;font-weight:700;text-decoration:none;font-family:inherit;transition:all .2s}
    .cb-w:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.15)}
    .cb-o{padding:13px 24px;color:#fff;border:1.5px solid rgba(255,255,255,.35);border-radius:10px;font-size:.9rem;font-weight:600;text-decoration:none;font-family:inherit;transition:all .2s}
    .cb-o:hover{background:rgba(255,255,255,.1)}

    /* FOOTER */
    footer{background:var(--ink);color:#fff;padding:56px 6% 28px}
    .ft-top{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:44px;margin-bottom:48px}
    .ft-logo{display:flex;align-items:center;gap:9px;margin-bottom:14px;text-decoration:none}
    .ft-logo-box{width:34px;height:34px;background:var(--blue);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .ft-logo-name{font-size:1.1rem;font-weight:800;color:#fff}
    .ft-desc{font-size:.82rem;color:rgba(255,255,255,.45);line-height:1.65;max-width:260px;margin-bottom:18px}
    .ft-contacts{display:flex;flex-direction:column;gap:5px;font-size:.78rem;color:rgba(255,255,255,.4)}
    .ft-col-h{font-size:.85rem;font-weight:800;color:#fff;margin-bottom:14px}
    .ft-links{list-style:none;display:flex;flex-direction:column;gap:9px}
    .ft-links a{font-size:.82rem;color:rgba(255,255,255,.45);text-decoration:none;transition:color .18s}
    .ft-links a:hover{color:#fff}
    .ft-bottom{padding-top:24px;border-top:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
    .ft-copy{font-size:.78rem;color:rgba(255,255,255,.3)}
    .ft-chips{display:flex;gap:8px}
    .ft-chip{font-size:.68rem;font-weight:600;padding:3px 10px;border-radius:100px;border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.35)}

    /* RESPONSIVE */
    @media(max-width:960px){
      .hero{grid-template-columns:1fr;min-height:auto;padding-top:90px;gap:36px}
      .hero-right{order:-1}
      .panels-grid,.test-grid{grid-template-columns:1fr}
      .steps{grid-template-columns:1fr 1fr}.step-arrow{display:none}
      .sec-row{grid-template-columns:1fr;gap:36px}
      .ft-top{grid-template-columns:1fr 1fr}
      .price-wrap{grid-template-columns:1fr}
      .gallery-grid{grid-template-columns:1fr 1fr}
    }
    @media(max-width:640px){
      .nav-links{display:none}
      .gallery-grid{grid-template-columns:1fr}
      .ft-top{grid-template-columns:1fr}
      .hero-nums{flex-wrap:wrap;gap:20px}
      .steps{grid-template-columns:1fr}
      .cta-btns{flex-direction:column;align-items:center}
    }
  </style>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="/" class="logo">
    <div class="logo-box">
      <svg viewBox="0 0 24 24" fill="none" width="20" height="20">
        <path d="M3 9.5L12 4l9 5.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z" stroke="#fff" stroke-width="1.7" fill="rgba(255,255,255,.15)"/>
        <rect x="9" y="13" width="6" height="8" rx="1" fill="#fff" opacity=".9"/>
        <path d="M3 9.5h18" stroke="rgba(255,255,255,.5)" stroke-width="1.2"/>
      </svg>
    </div>
    <span class="logo-name">Smart-Multiwarehouse Self<em>-Storage</em></span>
  </a>

  <ul class="nav-links">
    <li><a href="#how">How It Works</a></li>
    <li><a href="#panels">Panels</a></li>
    <li><a href="#gallery">Gallery</a></li>
    <li><a href="#pricing">Pricing</a></li>
    <li><a href="#security">Security</a></li>
    <li><a href="#contact">Contact</a></li>
  </ul>

  <div class="nav-btns">
    <a href="/login" class="n-login">Log In</a>
    <a href="/register" class="n-signup">Sign Up</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-left">
    <div class="hero-pill"><span class="dot"></span> Pakistan's Warehouse Platform</div>
    <h1>Smart Storage for<br>Every <span class="hl">Business</span><br>in Pakistan</h1>
    <p class="hero-sub">List, book, and manage warehouse units fully online. Admin-verified warehouses, secure PKR-only payments, and a full refund guarantee if anything goes wrong.</p>
    <div class="hero-ctas">
      <a href="/register?role=customer" class="cta-main">Find a Warehouse</a>
      <a href="#how" class="cta-ghost">How it works →</a>
    </div>
    <div class="hero-nums">
      <div><div class="num-n">500+</div><div class="num-l">Storage Units</div></div>
      <div><div class="num-n">3</div><div class="num-l">Smart Panels</div></div>
      <div><div class="num-n">100%</div><div class="num-l">PKR Only</div></div>
      <div><div class="num-n">24/7</div><div class="num-l">Security</div></div>
    </div>
  </div>
  <div class="hero-right">
    <div class="float-card tl">
      <div class="fc-icon">🏭</div>
      <div class="fc-title">Admin Verified</div>
      <div class="fc-sub">Every listing approved</div>
    </div>
    <div class="hero-img-wrap" onclick="openLb('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1400&q=90')">
      <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=900&q=80" alt="Warehouse"/>
    </div>
    <div class="float-card br">
      <div class="fc-icon">🔒</div>
      <div class="fc-title">Refund Protected</div>
      <div class="fc-sub">Money always safe</div>
    </div>
  </div>
</section>

<!-- TRUST BAR -->
<div class="trust">
  <div class="trust-i"><span class="chk">✓</span> Admin-Verified Warehouses</div>
  <div class="trust-i"><span class="chk">✓</span> PKR Payments Only</div>
  <div class="trust-i"><span class="chk">✓</span> Full Refund Guarantee</div>
  <div class="trust-i"><span class="chk">✓</span> 24/7 CCTV Security</div>
  <div class="trust-i"><span class="chk">✓</span> Instant Online Booking</div>
</div>

<!-- HOW IT WORKS -->
<section class="how-bg" id="how">
  <div class="center">
    <span class="tag">Simple Process</span>
    <h2 class="sec-h">How WarehouseIQ Works</h2>
    <p class="sec-p">From signup to moving in — everything is fast, simple, and 100% online.</p>
  </div>
  <div class="steps">
    <div class="step">
      <div class="step-n">1</div>
      <div class="step-h">Create Account</div>
      <p class="step-p">Sign up as a warehouse owner or customer. Each gets a dedicated panel built for their needs.</p>
      <span class="step-arrow">→</span>
    </div>
    <div class="step">
      <div class="step-n">2</div>
      <div class="step-h">Browse Listings</div>
      <p class="step-p">Explore admin-approved warehouses. View photos, location, and all available units.</p>
      <span class="step-arrow">→</span>
    </div>
    <div class="step">
      <div class="step-n">3</div>
      <div class="step-h">Book & Pay in PKR</div>
      <p class="step-p">Select your space and pay securely in Pakistani Rupees. No foreign currency, ever.</p>
      <span class="step-arrow">→</span>
    </div>
    <div class="step">
      <div class="step-n">4</div>
      <div class="step-h">Store Securely</div>
      <p class="step-p">Move in and access your unit. Your money is fully protected throughout your booking.</p>
    </div>
  </div>
</section>

<!-- PANELS -->
<section id="panels" style="background:#fff">
  <div class="center">
    <span class="tag">Platform Panels</span>
    <h2 class="sec-h">Three Dedicated Panels</h2>
    <p class="sec-p">Every user type gets their own powerful panel — admin, owner, and customer.</p>
  </div>
  <div class="panels-grid">
    <div class="p-card">
      <span class="p-lbl lbl-a">Admin</span>
      <div class="p-ico ico-a">🛡️</div>
      <div class="p-h">Admin Panel</div>
      <p class="p-d">The super-admin has full control of the entire platform. One secure login, complete oversight of all warehouses, owners, and customers.</p>
      <ul class="p-ul">
        <li>Single admin account — total control</li>
        <li>Approve or reject warehouse listings</li>
        <li>Manage all owners and customers</li>
        <li>Platform-wide reports & analytics</li>
        <li>Monitor all PKR transactions</li>
      </ul>
    </div>
    <div class="p-card hot">
      <span class="p-lbl lbl-o">Owner</span>
      <div class="p-ico ico-o">🏭</div>
      <div class="p-h">Owner Panel</div>
      <p class="p-d">Warehouse owners get powerful tools to list, manage, and grow their storage business across multiple locations in Pakistan.</p>
      <ul class="p-ul">
        <li>Add & manage multiple warehouses</li>
        <li>Create and price custom storage units</li>
        <li>Update availability in real-time</li>
        <li>Track bookings & revenue in PKR</li>
        <li>Go live after admin approval</li>
      </ul>
    </div>
    <div class="p-card">
      <span class="p-lbl lbl-c">Customer</span>
      <div class="p-ico ico-c">📦</div>
      <div class="p-h">Customer Panel</div>
      <p class="p-d">Customers browse admin-approved warehouses, book storage units, view full history, and enjoy guaranteed payment protection.</p>
      <ul class="p-ul">
        <li>Browse only approved warehouses</li>
        <li>Book any available unit instantly</li>
        <li>Full booking history & invoices</li>
        <li>Pay securely in PKR only</li>
        <li>Automatic refund if issue occurs</li>
      </ul>
    </div>
  </div>
</section>

<!-- GALLERY -->
<section class="gallery-bg" id="gallery">
  <div class="center">
    <span class="tag">Warehouse Gallery</span>
    <h2 class="sec-h">See Our Spaces</h2>
    <p class="sec-p">Click any photo to view it full screen.</p>
  </div>
  <div class="gallery-grid">
    <div class="g-item" onclick="openLb('https://smart-inventory-manager.com/images/Warehouse2.jpg')">
      <img src="https://smart-inventory-manager.com/images/Warehouse2.jpg" alt="W1"/>
      <div class="g-overlay"><span class="g-icon">⛶</span></div>
    </div>
    <div class="g-item" onclick="openLb('https://images.unsplash.com/photo-1553413077-190dd305871c?w=1400&q=90')">
      <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?w=700&q=80" alt="W2"/>
      <div class="g-overlay"><span class="g-icon">⛶</span></div>
    </div>
    <div class="g-item" onclick="openLb('https://www.amsc-usa.com/wp-content/uploads/2023/06/warehouse-efficiency.jpg')">
      <img src="https://www.amsc-usa.com/wp-content/uploads/2023/06/warehouse-efficiency.jpg" alt="W3"/>
      <div class="g-overlay"><span class="g-icon">⛶</span></div>
    </div>
    <div class="g-item" onclick="openLb('https://tse4.mm.bing.net/th/id/OIP.arYoqpZ3CsVpQJEC622HDwHaHa?w=512&h=512&rs=1&pid=ImgDetMain&o=7&rm=3')">
      <img src="https://tse4.mm.bing.net/th/id/OIP.arYoqpZ3CsVpQJEC622HDwHaHa?w=512&h=512&rs=1&pid=ImgDetMain&o=7&rm=3" alt="W4"/>
      <div class="g-overlay"><span class="g-icon">⛶</span></div>
    </div>
    <div class="g-item" onclick="openLb('https://tse3.mm.bing.net/th/id/OIP.8xSbCpa1Oz8NWjeqrFlhIQHaFS?rs=1&pid=ImgDetMain&o=7&rm=3')">
      <img src="https://tse3.mm.bing.net/th/id/OIP.8xSbCpa1Oz8NWjeqrFlhIQHaFS?rs=1&pid=ImgDetMain&o=7&rm=3" alt="W5"/>
      <div class="g-overlay"><span class="g-icon">⛶</span></div>
    </div>
    <div class="g-item" onclick="openLb('https://tse3.mm.bing.net/th/id/OIP.5hDjgxGpdOaVW8c203RhrAHaE7?rs=1&pid=ImgDetMain&o=7&rm=3')">
      <img src="https://tse3.mm.bing.net/th/id/OIP.5hDjgxGpdOaVW8c203RhrAHaE7?rs=1&pid=ImgDetMain&o=7&rm=3" alt="W6"/>
      <div class="g-overlay"><span class="g-icon">⛶</span></div>
    </div>
  </div>
</section>

<!-- PRICING -->
<section id="pricing" style="background:#fff">
  <div class="center">
    <span class="tag">Pricing</span>
    <h2 class="sec-h">Simple, Transparent Pricing</h2>
    <p class="sec-p">No unit tiers, no confusing packages. Price depends on the space size of the warehouse — starting from Rs 2,500/month. All in PKR only.</p>
  </div>
  <div class="price-wrap">
    <div>
      <span class="tag">Starting From</span>
      <div class="big-price"><span>Rs</span> 2,500</div>
      <div class="price-note">per month — increases with space size</div>
      <p class="price-body">Each warehouse owner sets their pricing based on the size of the storage space. The bigger the space, the higher the monthly price. All prices are in Pakistani Rupees only — no dollar charges, no currency confusion. Browse available warehouses to see exact prices per unit.</p>
      <a href="/register?role=customer" class="price-btn">Browse Warehouses</a>
    </div>
    <div class="price-items">
      <div class="pr-row"><span class="pr-ic">🇵🇰</span><div><div class="pr-t">PKR Only — No Dollars</div><div class="pr-s">All payments in Pakistani Rupees</div></div></div>
      <div class="pr-row"><span class="pr-ic">📏</span><div><div class="pr-t">Priced by Space Size</div><div class="pr-s">Larger space = higher monthly rate</div></div></div>
      <div class="pr-row"><span class="pr-ic">✅</span><div><div class="pr-t">No Hidden Charges</div><div class="pr-s">What you see is exactly what you pay</div></div></div>
      <div class="pr-row"><span class="pr-ic">🔒</span><div><div class="pr-t">Refund Guarantee</div><div class="pr-s">Full refund if anything goes wrong</div></div></div>
    </div>
  </div>
</section>

<!-- SECURITY -->
<section class="sec-bg" id="security">
  <div class="sec-row">
    <div>
      <span class="tag">Security & Money Protection</span>
      <h2 class="sec-h">Your Money Is<br>Always Safe</h2>
      <p class="sec-p">Payments are held securely and never released to the owner until your booking is confirmed. If anything goes wrong — you get your full refund back, automatically.</p>
      <div class="sec-list">
        <div class="sec-item"><div class="si-ico">🏦</div><div><div class="si-t">Secure Payment Hold</div><p class="si-d">Money is held by the platform until booking is successfully completed.</p></div></div>
        <div class="sec-item"><div class="si-ico">↩️</div><div><div class="si-t">Automatic Refund</div><p class="si-d">Any dispute or cancellation triggers an automatic full refund to the customer.</p></div></div>
        <div class="sec-item"><div class="si-ico">🎥</div><div><div class="si-t">24/7 CCTV Monitoring</div><p class="si-d">All listed warehouses maintain camera security around the clock.</p></div></div>
        <div class="sec-item"><div class="si-ico">🛡️</div><div><div class="si-t">Admin-Only Approval</div><p class="si-d">Only warehouses verified by the admin are ever shown to customers.</p></div></div>
      </div>
    </div>
    <div class="sec-vis">
      <div class="refund-box">
        <div class="rb-icon">🔒</div>
        <div class="rb-title">100% Refund Guarantee</div>
        <p class="rb-sub">If something goes wrong with your booking or unit, your full PKR payment is returned. No delay, no hassle.</p>
      </div>
      <div class="flow-list">
        <div class="flow-row"><span class="fi">💳</span><span class="fl">Customer pays in PKR</span><span class="ftag ftg">Secured</span></div>
        <div class="flow-row"><span class="fi">⏳</span><span class="fl">Platform holds payment</span><span class="ftag fty">On Hold</span></div>
        <div class="flow-row"><span class="fi">✅</span><span class="fl">Booking confirmed, owner paid</span><span class="ftag ftg">Released</span></div>
        <div class="flow-row"><span class="fi">↩️</span><span class="fl">Problem raised — refunded</span><span class="ftag ftb">Refunded</span></div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="test-bg" id="testimonials">
  <div class="center">
    <span class="tag">Reviews</span>
    <h2 class="sec-h">What Our Users Say</h2>
    <p class="sec-p">Real reviews from warehouse owners and customers across Pakistan.</p>
  </div>
  <div class="test-grid">
    <div class="test-card">
      <div class="stars">★★★★★</div>
      <p class="test-q">"Booking bohot asaan tha. Online unit select ki, payment PKR mein ki, aur same day access mil gaya. Kaafi secure platform hai!"</p>
      <div class="test-who"><div class="ava av1">AK</div><div><div class="ava-name">Ayesha Khan</div><div class="ava-role">Customer — Lahore</div></div></div>
    </div>
    <div class="test-card">
      <div class="stars">★★★★★</div>
      <p class="test-q">"Admin approval system ne mujhe trust dilaya. Customers seedha platform se aate hain aur payment secure rehti hai. Bohot acha system hai."</p>
      <div class="test-who"><div class="ava av2">AR</div><div><div class="ava-name">Ali Raza</div><div class="ava-role">Warehouse Owner — Karachi</div></div></div>
    </div>
    <div class="test-card">
      <div class="stars">★★★★★</div>
      <p class="test-q">"Mera ek issue hua tha unit ke sath, lekin refund bilkul jaldi mil gaya. Pakistan mein sab se trustworthy storage platform!"</p>
      <div class="test-who"><div class="ava av3">SM</div><div><div class="ava-name">Sara Malik</div><div class="ava-role">Customer — Islamabad</div></div></div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-sec">
  <span class="tag" style="color:rgba(255,255,255,.5)">Get Started Today</span>
  <h2 class="sec-h">Ready to Store Smarter?</h2>
  <p class="sec-p">Join warehouse owners and customers across Pakistan on the most secure, modern storage platform — 100% in PKR.</p>
  <div class="cta-btns" style="margin-top:36px">
    <a href="/register?role=customer" class="cb-w">Sign Up as Customer</a>
    <a href="/register?role=owner" class="cb-o">List Your Warehouse as Owner</a>
  </div>
</section>

<!-- FOOTER -->
<footer id="contact">
  <div class="ft-top">
    <div>
      <a href="/" class="ft-logo">
        <div class="ft-logo-box">
          <svg viewBox="0 0 24 24" fill="none" width="16" height="16">
            <path d="M3 9.5L12 4l9 5.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z" stroke="#fff" stroke-width="1.7" fill="rgba(255,255,255,.15)"/>
            <rect x="9" y="13" width="6" height="8" rx="1" fill="#fff" opacity=".9"/>
          </svg>
        </div>
        <span class="ft-logo-name">Smart multi-warehouse self storage</span>
      </a>
      <p class="ft-desc">Pakistan's smart multi-warehouse self storage platform. Secure, online, and 100% in PKR.</p>
      <div class="ft-contacts">
        <span>📧 support@smart-multiwarehouse.pk</span>
        <span>📞 +92 300 7239005</span>
       
      </div>
    </div>
    <div>
      <div class="ft-col-h">Platform</div>
      <ul class="ft-links">
        <li><a href="#how">How It Works</a></li>
        <li><a href="#panels">Admin Panel</a></li>
        <li><a href="#panels">Owner Panel</a></li>
        <li><a href="#panels">Customer Panel</a></li>
        <li><a href="#pricing">Pricing</a></li>
      </ul>
    </div>
    <div>
      <div class="ft-col-h">Services</div>
      <ul class="ft-links">
        <li><a href="#gallery">Warehouse Gallery</a></li>
        <li><a href="#security">Refund Policy</a></li>
        <li><a href="#security">Payment Security</a></li>
        <li><a href="#testimonials">Reviews</a></li>
        <li><a href="#">FAQs</a></li>
      </ul>
    </div>
    <div>
      <div class="ft-col-h">Account</div>
      <ul class="ft-links">
        <li><a href="/login">Log In</a></li>
        <li><a href="/register?role=customer">Sign Up — Customer</a></li>
        <li><a href="/register?role=owner">Sign Up — Owner</a></li>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Privacy Policy</a></li>
      </ul>
    </div>
  </div>
  <div class="ft-bottom">
    <p class="ft-copy">© 2025 Smart-MultiwarehouseStorage — All Rights Reserved. Pakistan</p>
    <div class="ft-chips">
      <span class="ft-chip">PKR Only</span>
      <span class="ft-chip">Secure Payments</span>
      <span class="ft-chip">Refund Protected</span>
    </div>
  </div>
</footer>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" onclick="closeLb()">
  <div class="lb-wrap" onclick="event.stopPropagation()">
    <button class="lb-close" onclick="closeLb()">✕</button>
    <img id="lb-img" src="" alt="Warehouse full view"/>
  </div>
</div>

<script>
  function openLb(src){
    document.getElementById('lb-img').src=src;
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow='hidden';
  }
  function closeLb(){
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow='';
  }
  document.addEventListener('keydown',function(e){if(e.key==='Escape')closeLb()});
</script>
</body>
</html>