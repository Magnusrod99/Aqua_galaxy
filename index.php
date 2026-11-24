<?php
require_once __DIR__ . '/config/app.php';

// Aqua Galaxy – homepage
$site_name    = 'Aqua Galaxy';
$phone_link   = '+918806048011';      // used in tel: links
$phone_label  = '+91 88060 48011';    // shown on the page
$whatsapp_num = '918806048011';       // country code + number, no + or spaces
$year         = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Aqua Galaxy – Ornamental Fish, Aquarium Products & Ladies’ Tailoring</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--navy:#06264d;--deep:#041a38;--blue:#0a6cf0;--cyan:#22b8e8;--bg:#f4f9ff;--card:#fff;--tint:#e4f0fd;--text:#0c2342;--muted:#52667f;--line:#d6e4f5;--head-bg:rgba(255,255,255,.92);
box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
@media (prefers-color-scheme:dark){:root:not([data-theme="light"]){--bg:#071426;--card:#0d2038;--tint:#10294a;--text:#e8f1fc;--muted:#9db2cc;--line:#1c3556;--head-bg:rgba(7,20,38,.92);--navy:#e8f1fc}}
:root[data-theme="dark"]{--bg:#071426;--card:#0d2038;--tint:#10294a;--text:#e8f1fc;--muted:#9db2cc;--line:#1c3556;--head-bg:rgba(7,20,38,.92);--navy:#e8f1fc}
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth;scroll-padding-top:80px}
body{margin:0;background:var(--bg);color:var(--text);font:400 16px/1.6 "Plus Jakarta Sans",system-ui,-apple-system,"Segoe UI",sans-serif}
a{color:inherit;text-decoration:none}
:focus-visible{outline:3px solid var(--cyan);outline-offset:3px;border-radius:6px}
.wrap{max-width:1200px;margin:0 auto;padding:0 20px}
svg{max-width:100%}

/* header */
header{position:sticky;top:0;z-index:50;background:var(--head-bg);backdrop-filter:blur(10px);border-bottom:1px solid var(--line)}
.bar{display:flex;align-items:center;justify-content:space-between;height:68px;gap:16px}
.logo{display:flex;align-items:center;gap:10px;font-weight:800;font-size:22px;color:var(--navy)}
.logo span{color:var(--blue)}
nav ul{display:flex;gap:6px;list-style:none;margin:0;padding:0}
nav a{display:block;padding:8px 14px;border-radius:8px;font-weight:600;font-size:15px;color:var(--text)}
nav a:hover{background:var(--tint)}
nav a.on{color:var(--blue);box-shadow:inset 0 -2px 0 var(--blue);border-radius:0}
.call{display:inline-flex;align-items:center;gap:8px;background:var(--blue);color:#fff;padding:10px 18px;border-radius:999px;font-weight:700;font-size:14px;white-space:nowrap}
.call:hover{background:#0857c4}
.burger{display:none;background:none;border:1px solid var(--line);border-radius:10px;width:42px;height:42px;cursor:pointer;color:var(--text)}
.burger svg{display:block;margin:auto}

/* hero */
.hero{position:relative;color:#fff;overflow:hidden;background:radial-gradient(120% 90% at 80% 20%,#0f7aa8 0%,#0a4a85 40%,#04203f 100%)}
.hero .wrap{display:grid;grid-template-columns:1.05fr 1fr;align-items:center;gap:20px;min-height:520px;position:relative;z-index:2}
.pill{display:inline-flex;align-items:center;gap:8px;border:1px solid rgba(255,255,255,.4);padding:6px 14px;border-radius:999px;font-size:14px;font-weight:600;background:rgba(255,255,255,.08)}
h1{font-size:clamp(34px,5.2vw,60px);line-height:1.08;margin:18px 0 16px;font-weight:800;letter-spacing:-.02em}
h1 em{font-style:normal;color:#4cc9ff}
.hero p{font-size:18px;max-width:480px;margin:0 0 28px;color:#dcecfb}
.btns{display:flex;gap:12px;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 26px;border-radius:999px;font-weight:700;font-size:15px;border:2px solid transparent;cursor:pointer;transition:transform .15s,background .15s}
.btn:hover{transform:translateY(-2px)}
.btn.p{background:var(--blue);color:#fff}.btn.p:hover{background:#0857c4}
.btn.o{border-color:#fff;color:#fff}.btn.o:hover{background:rgba(255,255,255,.14)}
.scene{position:relative;height:100%;min-height:380px}
.scene svg{position:absolute;inset:0;width:100%;height:100%}
.bub{fill:none;stroke:rgba(255,255,255,.55);stroke-width:1.5}
@media (prefers-reduced-motion:no-preference){
.sway{animation:sway 6s ease-in-out infinite alternate;transform-box:fill-box;transform-origin:center}
.sway2{animation:sway 8s ease-in-out infinite alternate-reverse;transform-box:fill-box;transform-origin:center}
.rise{animation:rise 7s ease-in infinite}.rise:nth-child(2){animation-duration:9s;animation-delay:-3s}.rise:nth-child(3){animation-duration:11s;animation-delay:-6s}.rise:nth-child(4){animation-duration:8s;animation-delay:-2s}
.tail{transform-origin:392px 185px;animation:tail .7s ease-in-out infinite alternate}
.swim{animation:swim 24s linear infinite}.s2{animation-duration:30s;animation-delay:-11s}.s3{animation-duration:19s;animation-delay:-5s}
.wig{transform-box:fill-box;transform-origin:85% 50%;animation:wig .55s ease-in-out infinite alternate}
.weed{transform-box:fill-box;transform-origin:50% 100%;animation:weed 5s ease-in-out infinite alternate}.weed.b{animation-delay:-2.500s;animation-duration:6.500s}
.gs{animation:gs 26s linear infinite}.g2{animation-duration:18s;animation-delay:-6s}.g3{animation-duration:22s;animation-delay:-14s}
.bobf{animation:bob 4s ease-in-out infinite alternate}.wv{animation:bob 5s ease-in-out infinite alternate-reverse}
.rays{position:absolute;inset:0 -80px;z-index:1;pointer-events:none;background:repeating-linear-gradient(105deg,transparent 0 80px,rgba(255,255,255,.08) 80px 140px,transparent 140px 250px);-webkit-mask-image:linear-gradient(to bottom,#000,transparent 85%);mask-image:linear-gradient(to bottom,#000,transparent 85%);animation:rays 14s ease-in-out infinite alternate}
.rays::after{content:"";position:absolute;inset:0;background:radial-gradient(ellipse 120px 50px at 20% 30%,rgba(255,255,255,.12),transparent),radial-gradient(ellipse 160px 60px at 70% 20%,rgba(255,255,255,.1),transparent);animation:shimmer 7s ease-in-out infinite alternate}
.hero .wrap>div:first-child>*{animation:up .9s cubic-bezier(.2,.7,.2,1) both}
.hero .wrap>div:first-child>:nth-child(2){animation-delay:.12s}.hero .wrap>div:first-child>:nth-child(3){animation-delay:.28s}.hero .wrap>div:first-child>:nth-child(4){animation-delay:.44s}
.scene{animation:fadein 1.600s ease both .2s}
.rv{opacity:0;transform:translateY(26px);transition:opacity .8s ease,transform .8s cubic-bezier(.2,.7,.2,1)}.rv.in{opacity:1;transform:none}
}
.bubbles{position:absolute;inset:0;z-index:1;pointer-events:none;overflow:hidden}
.bubbles i{position:absolute;bottom:-20px;border-radius:50%;background:radial-gradient(circle at 30% 30%,rgba(255,255,255,.75),rgba(255,255,255,.12) 55%,rgba(255,255,255,.04));border:1px solid rgba(255,255,255,.35);animation:bubble linear infinite}
@keyframes sway{to{transform:translate(-22px,12px) rotate(-2deg)}}
@keyframes tail{from{transform:rotate(-9deg)}to{transform:rotate(9deg)}}
@keyframes swim{from{transform:translateX(260px)}to{transform:translateX(-420px)}}
@keyframes wig{from{transform:rotate(-5deg)}to{transform:rotate(5deg)}}
@keyframes weed{from{transform:rotate(-3deg) skewX(-3deg)}to{transform:rotate(3deg) skewX(3deg)}}
@keyframes gs{from{transform:translateX(150px)}to{transform:translateX(-1450px)}}
@keyframes bob{to{transform:translateY(-6px)}}
@keyframes rays{from{transform:translateX(-40px);opacity:.6}to{transform:translateX(40px);opacity:1}}
@keyframes shimmer{from{opacity:.3;transform:translateX(-30px)}to{opacity:1;transform:translateX(30px)}}
@keyframes up{from{opacity:0;transform:translateY(22px)}}
@keyframes fadein{from{opacity:0}}
@keyframes bubble{0%{transform:translate(0,0) scale(.6);opacity:0}10%{opacity:1}50%{transform:translate(14px,-260px) scale(1)}100%{transform:translate(-10px,-560px) scale(1.1);opacity:0}}
@keyframes rise{from{transform:translateY(0);opacity:0}15%{opacity:1}to{transform:translateY(-110px);opacity:0}}
.weeds{position:absolute;left:0;right:0;bottom:0;height:70px;z-index:1;opacity:.9}

/* about */
.about{display:grid;grid-template-columns:1fr 1.4fr 1.3fr;gap:32px;align-items:center;padding:56px 0}
.eyebrow{color:var(--blue);font-weight:700;font-size:14px;margin:0 0 6px}
h2{font-size:clamp(26px,3.4vw,38px);line-height:1.15;margin:0 0 12px;color:var(--navy);letter-spacing:-.01em}
.about p{color:var(--muted);margin:0}
.wave{width:100%;height:auto}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.stat{background:var(--tint);border:1px solid var(--line);border-radius:18px;padding:20px 10px;text-align:center}
.stat svg{width:34px;height:34px;color:var(--blue);margin-bottom:6px}
.stat b{display:block;font-size:15px;color:var(--navy)}
.stat small{color:var(--muted);font-size:13px}

/* offers */
.offers{padding:24px 0 56px}
.head{text-align:center;margin-bottom:32px}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.card{position:relative;border-radius:22px;overflow:hidden;min-height:300px;color:#fff;display:flex;flex-direction:column;justify-content:flex-end;padding:28px;isolation:isolate;transition:transform .2s,box-shadow .2s}
.card:hover{transform:translateY(-5px);box-shadow:0 18px 36px rgba(6,38,77,.28)}
.card::before{content:"";position:absolute;inset:0;z-index:-2}
.card::after{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(to top,rgba(3,22,48,.92) 12%,rgba(3,22,48,.15) 75%)}
.c1::before{background:radial-gradient(circle at 20% 30%,#ff8a3d 0,#d9541f 18%,transparent 40%),linear-gradient(160deg,#0f8bb5,#073d73)}
.c2::before{background:radial-gradient(circle at 25% 70%,#3d9a4a 0,transparent 35%),radial-gradient(circle at 80% 60%,#2f8f55 0,transparent 30%),linear-gradient(160deg,#0f7f9c,#08365e)}
.c3::before{background:linear-gradient(115deg,#f3b3c0 0 30%,#e98c6c 30% 52%,#a24a8a 52% 74%,#1a5ea8 74%)}
.ico{width:56px;height:56px;border-radius:50%;background:#fff;color:var(--blue);display:grid;place-items:center;margin-bottom:14px;box-shadow:0 6px 16px rgba(0,0,0,.25)}
.ico svg{width:28px;height:28px}
.card h3{margin:0 0 6px;font-size:22px}
.card p{margin:0 0 14px;color:#dbe9f8;font-size:15px}
.card .lk{font-weight:700;font-size:15px;text-decoration:underline;text-underline-offset:4px}

/* gallery */
.glimpse{border-radius:26px;overflow:hidden;position:relative;color:#fff;text-align:center;padding:56px 24px;background:radial-gradient(80% 140% at 50% 120%,#1e9bd1,#0a4a85 50%,#041f40);isolation:isolate}
.glimpse svg.bg{position:absolute;inset:0;width:100%;height:100%;z-index:-1;opacity:.9}
.glimpse h2{color:#fff;font-size:clamp(24px,3vw,32px)}
.glimpse p{margin:0 0 20px;color:#dcecfb}
.btn.w{background:#fff;color:var(--deep);padding:11px 24px}

/* contact */
.contact{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:48px 0;flex-wrap:wrap}
.contact p{margin:0;color:var(--muted)}

/* footer */
footer{background:var(--deep);color:#cfe0f5;padding:36px 0 20px}
.ft{display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap}
footer .logo{color:#fff}
footer ul{display:flex;gap:22px;list-style:none;margin:0;padding:0;flex-wrap:wrap}
footer ul a{font-size:14px}footer ul a:hover{color:#fff}
.soc{display:flex;gap:10px}
.soc a{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.1);display:grid;place-items:center}
.soc a:hover{background:var(--blue)}
.fb{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;border-top:1px solid rgba(255,255,255,.12);margin-top:24px;padding-top:16px;font-size:13px;color:#93abc8}

/* responsive */
@media (max-width:980px){
 .about{grid-template-columns:1fr;text-align:center}
 .about .wave{display:none}
 .cards{grid-template-columns:1fr 1fr}
 .cards .card:last-child{grid-column:1/-1}
}
@media (max-width:820px){
 .burger{display:block}
 .call span{display:none}.call{padding:11px 13px}
 nav{position:absolute;left:0;right:0;top:68px;background:var(--card);border-bottom:1px solid var(--line);display:none;padding:10px 20px 16px}
 nav.open{display:block}
 nav ul{flex-direction:column}
 nav a.on{box-shadow:none;background:var(--tint);border-radius:8px}
 .hero .wrap{grid-template-columns:1fr;padding-top:40px;min-height:0}
 .scene{height:300px;min-height:300px}
 .hero p{font-size:16px}
}
@media (max-width:600px){
 .cards{grid-template-columns:1fr}
 .stats{gap:8px}.stat{padding:16px 6px}
 .ft,.fb,.contact{justify-content:center;text-align:center}
 .contact .btn{width:100%;justify-content:center}
 .btns .btn{flex:1;justify-content:center}
}
html{-webkit-text-size-adjust:100%}body{overflow-x:hidden;-webkit-tap-highlight-color:transparent}
.dock{display:none}
@media (max-width:820px){
 .bar{height:60px}nav{top:60px;max-height:calc(100vh - 60px);overflow:auto}
 nav a{padding:14px 12px;font-size:16px}
 .burger{width:44px;height:44px}
 .logo{font-size:19px}
 .call{display:none}
 .hero .wrap{padding-top:32px;padding-bottom:12px}
 .scene{height:260px;min-height:260px;margin:0 -20px}
 .rays{display:none}
 .about{padding:40px 0}
 .offers{padding-bottom:40px}
 .card{min-height:240px;padding:22px}
 .glimpse{padding:44px 18px}
 .contact{padding:36px 0 40px}
 footer{padding-bottom:calc(92px + env(safe-area-inset-bottom,0px))}
 footer ul{justify-content:center;gap:10px 18px}
 footer ul a{display:block;padding:6px 2px}
 .dock{display:flex;position:fixed;left:12px;right:12px;bottom:calc(12px + env(safe-area-inset-bottom,0px));z-index:60;gap:10px}
 .dock a{flex:1;display:flex;align-items:center;justify-content:center;gap:8px;min-height:50px;border-radius:999px;font-weight:700;font-size:15px;color:#fff;box-shadow:0 8px 24px rgba(4,26,56,.35)}
 .dock .d1{background:var(--blue)}.dock .d2{background:#16a34a}
}
@media (max-width:420px){.btns .btn{padding:13px 14px;font-size:14px}.stat b{font-size:14px}.stat small{font-size:12px}h1{font-size:32px}.cards{gap:16px}}
@media (hover:none){.card:hover,.btn:hover{transform:none;box-shadow:none}}
</style>
</head>
<body>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
 <defs>
  <symbol id="fish" viewBox="0 0 64 32"><path d="M2 16c10-14 28-14 40 0-12 14-30 14-40 0zM40 16l22-13c-3 8-3 18 0 26z" fill="currentColor"/><circle cx="14" cy="13" r="2.2" fill="#fff"/></symbol>
  <symbol id="drop" viewBox="0 0 24 24"><path d="M12 2l9 4v6c0 5-4 9-9 10-5-1-9-5-9-10V6z" fill="currentColor"/><path d="M8 12l3 3 5-6" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></symbol>
  <symbol id="dress" viewBox="0 0 24 24"><path d="M9 2l3 3 3-3 2 5-2 3 4 12H6l4-12-3-3z" fill="currentColor"/></symbol>
  <symbol id="box" viewBox="0 0 24 24"><path d="M12 2l9 5v10l-9 5-9-5V7z M3 7l9 5 9-5 M12 12v10" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></symbol>
 </defs>
</svg>

<header>
 <div class="wrap bar">
  <a class="logo" href="#top" aria-label="Aqua Galaxy home">
   <svg width="40" height="22" style="color:var(--blue)"><use href="#fish"/></svg>Aqua <span>Galaxy</span>
  </a>
  <nav id="nav" aria-label="Main">
   <ul>
    <li><a class="on" href="<?= BASE_URL ?>/">Home</a></li>
    <li><a href="<?= BASE_URL ?>/fish.php">Fish</a></li>
    <li><a href="<?= BASE_URL ?>/products.php">Products</a></li>
    <li><a href="<?= BASE_URL ?>/tailoring.php">Tailoring</a></li>
    <li><a href="<?= BASE_URL ?>/gallery.php">Gallery</a></li>
    <li><a href="<?= BASE_URL ?>/contact.php">Contact</a></li>
   </ul>
  </nav>
  <a class="call" href="tel:<?= htmlspecialchars($phone_link) ?>" aria-label="Call <?= htmlspecialchars($phone_label) ?>">
   <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15 15 0 006.6 6.6l2.2-2.2a1 1 0 011-.25c1.100.35 2.300.55 3.600.55a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.500a1 1 0 011 1c0 1.300.2 2.500.6 3.600a1 1 0 01-.25 1z"/></svg>
   <span><?= htmlspecialchars($phone_label) ?></span>
  </a>
  <button class="burger" id="bg" aria-label="Open menu" aria-expanded="false" aria-controls="nav">
   <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
  </button>
 </div>
</header>

<main id="top">
 <section class="hero">
  <div class="rays" aria-hidden="true"></div><div class="bubbles" id="bubbles" aria-hidden="true"></div>
  <div class="wrap">
   <div>
    <span class="pill"><svg width="18" height="12" viewBox="0 0 64 32"><use href="#fish"/></svg>Welcome to Aqua Galaxy</span>
    <h1>Discover a World of <em>Beauty</em> &amp; Creativity.</h1>
    <p>Explore beautiful ornamental fish, aquarium essentials and creative ladies’ tailoring — all in one place.</p>
    <div class="btns">
     <a class="btn p" href="<?= BASE_URL ?>/fish.php">Explore Fish <span aria-hidden="true">→</span></a>
     <a class="btn o" href="<?= BASE_URL ?>/contact.php">Contact Us</a>
    </div>
   </div>
   <div class="scene" aria-hidden="true">
    <svg viewBox="0 0 520 400" preserveAspectRatio="xMidYMid meet">
     <g class="bub"><circle class="rise" cx="60" cy="150" r="9"/><circle class="rise" cx="90" cy="90" r="6"/><circle class="rise" cx="40" cy="250" r="5"/><circle class="rise" cx="470" cy="300" r="7"/></g>
     <g class="sway">
      <path d="M120 190c40-70 130-80 190-20 20 20 50 20 90-10-10 20-10 40 0 60-40-30-70-30-90-10-60 60-150 50-190-20z" fill="#ff7a1a"/>
      <path d="M170 150c30-30 80-30 110-5-40 0-80 20-110 5z" fill="#ffb347"/>
      <path class="tail" d="M390 160c40-30 80-50 110-40-20 30-30 70-110 90z" fill="#ff9a3c" opacity=".85"/>
      <circle cx="150" cy="185" r="9" fill="#fff"/><circle cx="152" cy="185" r="4.500" fill="#111"/>
     </g>
     <g class="sway2">
      <path d="M300 300l50-100 60 40z" fill="#d9e6ee" opacity=".9"/>
      <path d="M340 205c25-25 60-20 80 5-20 25-55 30-80-5z" fill="#c6d4dc"/>
      <path d="M355 205l10 70M372 203l8 66M388 206l4 60" stroke="#1b2a36" stroke-width="5" stroke-linecap="round"/>
     </g>
     <g class="swim"><g class="wig"><use href="#fish" x="300" y="300" width="54" height="27" style="color:#e8352f"/></g></g><g class="swim s2"><g class="wig"><use href="#fish" x="300" y="335" width="46" height="23" style="color:#2a86ff"/></g></g><g class="swim s3"><g class="wig"><use href="#fish" x="300" y="60" width="44" height="22" style="color:#e8352f"/></g></g>
     <path class="weed" d="M0 400c60-90 40-140 90-170-10 80 30 100 20 170z" fill="#1d8a4e" opacity=".8"/><path class="weed b" d="M440 400c-20-80 30-110 20-160 50 30 60 90 60 160z" fill="#1d8a4e" opacity=".8"/>
    </svg>
   </div>
  </div>
  <svg class="weeds" viewBox="0 0 1200 70" preserveAspectRatio="none" aria-hidden="true"><path d="M0 70V40c60 20 120-20 200 0s140 20 220-10 160 10 240 10 150-30 230-10 160 20 310-10v50z" fill="#06264d" opacity=".55"/></svg>
 </section>

 <div class="wrap">
  <section class="about" aria-labelledby="ab">
   <svg class="wave" viewBox="0 0 220 120" aria-hidden="true"><path class="wv" d="M0 100c40-70 100-80 160-50 20 10 40 5 60-10v80H0z" fill="#bfe3fa"/><path d="M0 110c50-50 110-50 170-20" stroke="#fff" stroke-width="6" fill="none" opacity=".8"/><use class="bobf" href="#fish" x="90" y="20" width="44" height="22" style="color:#1c6fc2"/><use class="bobf" style="color:#2b8ae6;animation-delay:-2s" href="#fish" x="30" y="60" width="36" height="18"/></svg>
   <div>
    <p class="eyebrow">About Aqua Galaxy</p>
    <h2 id="ab">More Than Just a Store</h2>
    <p>Aqua Galaxy brings together the beauty of aquatic life and the creativity of tailoring. Whether you’re exploring ornamental fish and aquarium products or looking for ladies’ tailoring, there’s something here for you.</p>
   </div>
   <div class="stats">
    <div class="stat"><svg><use href="#fish"/></svg><b>Premium</b><small>Fish Collection</small></div>
    <div class="stat"><svg><use href="#drop"/></svg><b>Quality</b><small>Products</small></div>
    <div class="stat"><svg><use href="#dress"/></svg><b>Expert</b><small>Tailoring</small></div>
   </div>
  </section>

  <section class="offers" id="offers" aria-labelledby="of">
   <div class="head"><p class="eyebrow">Explore</p><h2 id="of">Discover What We Offer</h2></div>
   <div class="cards">
    <a class="card c1" href="<?= BASE_URL ?>/fish.php"><div class="ico"><svg><use href="#fish"/></svg></div><h3>Ornamental Fish</h3><p>Explore our collection of beautiful ornamental fish and aquatic life.</p><span class="lk">Explore Fish →</span></a>
    <a class="card c2" href="<?= BASE_URL ?>/products.php"><div class="ico"><svg><use href="#box"/></svg></div><h3>Aquarium Products</h3><p>Discover aquarium essentials and products for your setup.</p><span class="lk">View Products →</span></a>
    <a class="card c3" href="<?= BASE_URL ?>/tailoring.php"><div class="ico"><svg><use href="#dress"/></svg></div><h3>Ladies’ Tailoring</h3><p>Explore our tailoring section and discover creative clothing options.</p><span class="lk">Explore Tailoring →</span></a>
   </div>
  </section>

  <section class="glimpse" id="gallery">
   <svg class="bg" viewBox="0 0 1200 260" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><g class="gs"><g class="wig"><use href="#fish" x="1100" y="90" width="150" height="75" style="color:#e9eef2"/></g></g><g class="gs g2"><g class="wig"><use href="#fish" x="1100" y="50" width="50" height="25" style="color:#ff8a2a"/></g></g><g class="gs g3"><g class="wig"><use href="#fish" x="1100" y="170" width="60" height="30" style="color:#ff8a2a"/></g></g><path d="M0 260c40-80 20-110 70-140-5 70 25 90 20 140zM1080 260c-10-70 30-100 20-150 50 30 70 80 100 150z" fill="#1d8a4e" opacity=".7"/></svg>
   <svg width="36" height="30" viewBox="0 0 24 20" fill="none" stroke="#fff" stroke-width="1.6" aria-hidden="true"><rect x="2" y="2" width="20" height="16" rx="2"/><circle cx="8" cy="8" r="2"/><path d="M3 16l6-5 4 3 4-4 4 4"/></svg>
   <h2>A Glimpse of Aqua Galaxy</h2>
   <p>Take a look at our world through photographs in our gallery.</p>
   <a class="btn w" href="<?= BASE_URL ?>/gallery.php">View Gallery →</a>
  </section>

  <section class="contact" id="contact">
   <div><h2>We’d Love to Hear From You</h2><p>Have a question or want to know more about Aqua Galaxy? Get in touch with us.</p></div>
   <a class="btn p" href="<?= BASE_URL ?>/contact.php">Get in Touch →</a>
  </section>
 </div>
</main>

<footer>
 <div class="wrap">
  <div class="ft">
   <a class="logo" href="#top"><svg width="40" height="22" style="color:#4cc9ff"><use href="#fish"/></svg>Aqua <span style="color:#4cc9ff">Galaxy</span></a>
   <ul><li><a href="<?= BASE_URL ?>/">Home</a></li><li><a href="<?= BASE_URL ?>/fish.php">Fish</a></li><li><a href="<?= BASE_URL ?>/products.php">Products</a></li><li><a href="<?= BASE_URL ?>/tailoring.php">Tailoring</a></li><li><a href="<?= BASE_URL ?>/gallery.php">Gallery</a></li><li><a href="<?= BASE_URL ?>/contact.php">Contact</a></li></ul>
   <div class="soc">
    <a href="#" aria-label="Facebook"><svg width="16" height="16" viewBox="0 0 24 24" fill="#fff"><path d="M14 8V6c0-1 .3-1.500 1.600-1.500H18V1h-3.200C11.300 1 10 3 10 5.600V8H7v3.500h3V23h4V11.500h3.200L18 8z"/></svg></a>
    <a href="#" aria-label="Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.500" cy="6.500" r="1" fill="#fff"/></svg></a>
    <a href="#" aria-label="YouTube"><svg width="18" height="16" viewBox="0 0 24 24" fill="#fff"><path d="M22 8c0-1.500-1-2.500-2.500-2.700C17.500 5 12 5 12 5s-5.500 0-7.500.3C3 5.500 2 6.500 2 8c-.2 2.700-.2 5.300 0 8 0 1.500 1 2.500 2.500 2.700C6.500 19 12 19 12 19s5.500 0 7.500-.3c1.500-.2 2.500-1.200 2.500-2.700.2-2.700.2-5.300 0-8zM10 15V9l5 3z"/></svg></a>
   </div>
  </div>
  <div class="fb"><span>© <?= $year ?> <?= htmlspecialchars($site_name) ?>. All rights reserved.</span><span>Aquatic Beauty • Creative Style • One Destination</span></div>
 </div>
</footer>
<div class="dock">
 <a class="d1" href="tel:<?= htmlspecialchars($phone_link) ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.6 10.8a15 15 0 006.6 6.6l2.2-2.2a1 1 0 011-.25c1.100.35 2.300.55 3.600.55a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.500a1 1 0 011 1c0 1.300.2 2.500.6 3.600a1 1 0 01-.25 1z"/></svg>Call us</a>
 <a class="d2" href="https://wa.me/<?= htmlspecialchars($whatsapp_num) ?>" rel="noopener"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 00-8.600 15L2 22l5.200-1.400A10 10 0 1012 2zm5.200 14.200c-.2.600-1.300 1.200-1.800 1.200-.5.100-1 .2-3.300-.7-2.800-1.200-4.600-4-4.700-4.200-.1-.2-1.100-1.500-1.100-2.800s.7-2 1-2.300c.2-.3.500-.3.700-.3h.5c.2 0 .4 0 .6.500l.8 2c.1.200.1.400 0 .5l-.4.600-.3.400c-.1.100-.3.300-.1.600.2.300.8 1.300 1.700 2.100 1.200 1 2.100 1.300 2.400 1.500.3.100.5.100.6-.1l.9-1c.2-.3.400-.2.600-.1l1.900.9c.3.100.5.200.5.300.1.200.1.800-.1 1.300z"/></svg>WhatsApp</a>
</div>

<script>
(function(){
 var b=document.getElementById('bg'),n=document.getElementById('nav');
 function t(o){n.classList.toggle('open',o);b.setAttribute('aria-expanded',o);}
 b.addEventListener('click',function(){t(!n.classList.contains('open'))});
 n.addEventListener('click',function(e){if(e.target.tagName==='A')t(false)});
 document.addEventListener('click',function(e){if(!n.contains(e.target)&&!b.contains(e.target))t(false)});
 document.addEventListener('keydown',function(e){if(e.key==='Escape')t(false)});
 if(window.matchMedia('(prefers-reduced-motion: reduce)').matches)return;
 var bx=document.getElementById('bubbles');
 var N=window.innerWidth<700?10:22;for(var i=0;i<N;i++){var s=document.createElement('i'),z=4+Math.random()*12;s.style.cssText='left:'+(Math.random()*100)+'%;width:'+z+'px;height:'+z+'px;animation-duration:'+(7+Math.random()*9)+'s;animation-delay:-'+(Math.random()*14)+'s';bx.appendChild(s);}
 var els=document.querySelectorAll('.head,.stat,.card,.glimpse,.contact');
 if('IntersectionObserver' in window){var io=new IntersectionObserver(function(en){en.forEach(function(x){if(x.isIntersecting){var el=x.target;setTimeout(function(){el.classList.add('in');setTimeout(function(){el.classList.remove('rv','in')},1000)},el.dataset.d||0);io.unobserve(el)}})},{threshold:.15});
 els.forEach(function(el,i){el.classList.add('rv');el.dataset.d=(i%3)*120;io.observe(el)});}
})();
</script>
</body>
</html>