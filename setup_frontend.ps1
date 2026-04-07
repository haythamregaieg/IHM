# ================================================================
#  JOBPLATFORM - SCRIPT 2/3 : FRONTEND COMPLET
#  Basé sur : Use Case + Diagramme de classes + Règles IHM
#  Usage: powershell -ExecutionPolicy Bypass -File 02_frontend.ps1
# ================================================================
$p = "C:\xampp\htdocs\job-platformm"
Set-Location $p

# Routes web
@'
<?php
use Illuminate\Support\Facades\Route;
Route::get('/',                        fn()=>view('pages.home'));
Route::get('/offres',                  fn()=>view('offres.index'));
Route::get('/offres/{id}',             fn()=>view('offres.show'));
Route::get('/connexion',               fn()=>view('auth.login'));
Route::get('/inscription/candidat',    fn()=>view('auth.register-candidat'));
Route::get('/inscription/entreprise',  fn()=>view('auth.register-entreprise'));
Route::get('/mon-espace/{tab?}',       fn()=>view('candidat.dashboard'));
Route::get('/recruteur/{tab?}',        fn()=>view('entreprise.dashboard'));
Route::get('/admin/{tab?}',            fn()=>view('admin.dashboard'));
Route::get('/{any}',                   fn()=>view('pages.home'))->where('any','.*');
'@ | Set-Content "routes\web.php" -Encoding UTF8

# Dossiers
@("resources\views\layouts","resources\views\pages","resources\views\auth","resources\views\offres","resources\views\candidat","resources\views\entreprise","resources\views\admin") | ForEach-Object { New-Item -ItemType Directory -Force $_ | Out-Null }

Write-Host "[1/6] Structure creee" -ForegroundColor Green

# ================================================================
# LAYOUT PRINCIPAL
# ================================================================
@'
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','JobPlatform')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config={theme:{extend:{fontFamily:{display:['Outfit','sans-serif'],body:['Inter','sans-serif']},colors:{brand:{50:'#f0f4ff',100:'#dce6ff',200:'#baccff',300:'#8aadff',400:'#5585ff',500:'#2B5CE6',600:'#1a44c9',700:'#1535a0',800:'#122d85',900:'#0d1f5c'},gold:{400:'#F6A623',500:'#E8950E'}},keyframes:{up:{from:{opacity:0,transform:'translateY(20px)'},to:{opacity:1,transform:'translateY(0)'}},pop:{from:{opacity:0,transform:'scale(.95)'},to:{opacity:1,transform:'scale(1)'}}},animation:{'fade-up':'up .5s ease both','pop-in':'pop .3s ease both'}}}}
</script>
<style>
*{-webkit-font-smoothing:antialiased;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#F4F7FE;color:#1e293b}
h1,h2,h3,h4,h5{font-family:'Outfit',sans-serif}
[x-cloak]{display:none!important}
.card{background:#fff;border:1px solid #e8edf5;border-radius:16px;box-shadow:0 1px 2px rgba(0,0,0,.04),0 4px 12px rgba(0,0,0,.04)}
.card-hover{transition:transform .2s,box-shadow .2s}.card-hover:hover{transform:translateY(-4px);box-shadow:0 8px 32px rgba(43,92,230,.12)}
.btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.4rem;border-radius:10px;font-weight:600;font-size:.875rem;transition:all .18s;cursor:pointer;border:none;outline:none}
.btn-primary{background:#2B5CE6;color:#fff}.btn-primary:hover{background:#1a44c9;transform:translateY(-1px);box-shadow:0 4px 16px rgba(43,92,230,.35)}
.btn-secondary{background:#f0f4ff;color:#2B5CE6;border:1.5px solid #baccff}.btn-secondary:hover{background:#dce6ff}
.btn-danger{background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca}.btn-danger:hover{background:#fee2e2}
.btn-success{background:#f0fdf4;color:#16a34a;border:1.5px solid #bbf7d0}.btn-success:hover{background:#dcfce7}
.btn-ghost{background:transparent;color:#64748b}.btn-ghost:hover{background:#f1f5f9;color:#334155}
.btn-gold{background:#F6A623;color:#fff}.btn-gold:hover{background:#E8950E;transform:translateY(-1px)}
.btn-lg{padding:.8rem 2rem;font-size:1rem}
.btn-sm{padding:.35rem .85rem;font-size:.8rem}
.input{width:100%;padding:.6rem 1rem;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.9rem;font-family:'Inter',sans-serif;outline:none;transition:border .2s,box-shadow .2s;background:#fff}
.input:focus{border-color:#2B5CE6;box-shadow:0 0 0 3px rgba(43,92,230,.1)}
textarea.input{resize:vertical;min-height:100px}
.label{display:block;font-size:.78rem;font-weight:600;color:#475569;margin-bottom:.35rem;letter-spacing:.03em;text-transform:uppercase}
.badge{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .7rem;border-radius:999px;font-size:.73rem;font-weight:700;letter-spacing:.02em}
.badge-blue{background:#dce6ff;color:#1535a0}
.badge-green{background:#dcfce7;color:#15803d}
.badge-amber{background:#fef9c3;color:#854d0e}
.badge-red{background:#fee2e2;color:#991b1b}
.badge-slate{background:#f1f5f9;color:#475569}
.badge-purple{background:#f3e8ff;color:#7e22ce}
.sidebar{background:#fff;border-right:1px solid #e8edf5;width:240px;flex-shrink:0;display:flex;flex-direction:column;padding:1.25rem;gap:.25rem}
.sidebar-link{display:flex;align-items:center;gap:.75rem;padding:.6rem .85rem;border-radius:10px;font-weight:500;font-size:.875rem;color:#64748b;transition:all .18s;cursor:pointer;border:none;background:transparent;text-align:left;width:100%}
.sidebar-link:hover{background:#f0f4ff;color:#2B5CE6}
.sidebar-link.active{background:linear-gradient(135deg,#2B5CE6,#1a44c9);color:#fff;box-shadow:0 4px 12px rgba(43,92,230,.3)}
.stat-card{background:#fff;border:1px solid #e8edf5;border-radius:14px;padding:1.25rem}
.modal-bg{position:fixed;inset:0;background:rgba(15,23,42,.55);backdrop-filter:blur(6px);z-index:100;display:flex;align-items:center;justify-content:center;padding:1rem}
.modal{background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:560px;max-height:92vh;overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,.18)}
.nav-link{font-size:.875rem;font-weight:500;color:#64748b;transition:color .15s}
.nav-link:hover{color:#2B5CE6}
.contract-cdi{background:#dcfce7;color:#15803d}
.contract-cdd{background:#dce6ff;color:#1535a0}
.contract-stage{background:#fef9c3;color:#854d0e}
.contract-freelance{background:#f3e8ff;color:#7e22ce}
.contract-alternance{background:#ffedd5;color:#9a3412}
.step-bar{display:flex;align-items:center;gap:0}
.step{display:flex;align-items:center;gap:.5rem;font-size:.8rem;font-weight:600}
.step-num{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700}
.step-active .step-num{background:#2B5CE6;color:#fff}
.step-done .step-num{background:#16a34a;color:#fff}
.step-inactive .step-num{background:#e2e8f0;color:#94a3b8}
.step-line{flex:1;height:2px;background:#e2e8f0;margin:0 .5rem}
.step-line.done{background:#16a34a}
::-webkit-scrollbar{width:6px;height:6px}::-webkit-scrollbar-track{background:#f1f5f9}::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}
</style>
@stack('styles')
</head>
<body>
<!-- NAVBAR -->
<nav class="bg-white border-b border-slate-100 sticky top-0 z-40" style="box-shadow:0 1px 0 rgba(0,0,0,.05)">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16 gap-4">
    <a href="/" class="flex items-center gap-2.5 flex-shrink-0">
      <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#2B5CE6,#1a44c9)">
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      </div>
      <span style="font-family:Outfit,sans-serif;font-weight:800;font-size:1.15rem" class="text-slate-800">Job<span style="color:#2B5CE6">Platform</span></span>
    </a>
    <div class="hidden md:flex items-center gap-6">
      <a href="/offres" class="nav-link">Offres d'emploi</a>
      <a href="/inscription/entreprise" class="nav-link">Recruteurs</a>
    </div>
    <div id="nav-auth" class="flex items-center gap-2"></div>
  </div>
</nav>
<!-- TOAST -->
<div id="toast" class="fixed top-5 right-5 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl text-white text-sm font-semibold shadow-2xl transition-all duration-400 translate-x-96 opacity-0 max-w-xs" style="min-width:220px">
  <span id="toast-icon" class="text-base"></span><span id="toast-txt"></span>
</div>
@yield('content')
<footer class="mt-20 bg-slate-900 text-slate-400">
  <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
    <div><div class="flex items-center gap-2 mb-3"><div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#2B5CE6"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><span class="font-bold text-white text-sm" style="font-family:Outfit">JobPlatform</span></div><p class="text-xs">La plateforme de recrutement #1 en Tunisie</p></div>
    <div><h4 class="text-white font-semibold text-sm mb-3">Candidats</h4><ul class="space-y-1.5 text-xs"><li><a href="/offres" class="hover:text-white">Rechercher des offres</a></li><li><a href="/inscription/candidat" class="hover:text-white">Créer un compte</a></li><li><a href="/mon-espace" class="hover:text-white">Mon espace</a></li></ul></div>
    <div><h4 class="text-white font-semibold text-sm mb-3">Recruteurs</h4><ul class="space-y-1.5 text-xs"><li><a href="/inscription/entreprise" class="hover:text-white">Inscrire mon entreprise</a></li><li><a href="/recruteur" class="hover:text-white">Espace recruteur</a></li></ul></div>
    <div><h4 class="text-white font-semibold text-sm mb-3">Contact</h4><ul class="space-y-1.5 text-xs"><li>contact@jobplatform.tn</li><li>+216 71 000 000</li><li>Tunis, Tunisie</li></ul></div>
  </div>
  <div class="border-t border-slate-800 py-4 text-center text-xs">© 2026 JobPlatform — Tous droits réservés</div>
</footer>
<script>
// ── API ─────────────────────────────────────────────────────────
window.API={
  base:'/api',
  token(){return localStorage.getItem('jp_tok')},
  type(){return localStorage.getItem('jp_type')},
  user(){try{return JSON.parse(localStorage.getItem('jp_usr')||'null')}catch{return null}},
  isLogged(){return!!this.token()},
  async req(ep,method='GET',data=null,isForm=false){
    const h={'Accept':'application/json'};
    if(!isForm)h['Content-Type']='application/json';
    if(this.token())h['Authorization']='Bearer '+this.token();
    const opts={method,headers:h};
    if(data)opts.body=isForm?data:JSON.stringify(data);
    const res=await fetch(this.base+ep,opts);
    if(res.status===401){this.logout();return null}
    return res
  },
  get(ep){return this.req(ep)},
  post(ep,d,f){return this.req(ep,'POST',d,f)},
  put(ep,d){return this.req(ep,'PUT',d)},
  patch(ep,d){return this.req(ep,'PATCH',d)},
  del(ep){return this.req(ep,'DELETE')},
  save(tok,type,user){localStorage.setItem('jp_tok',tok);localStorage.setItem('jp_type',type);localStorage.setItem('jp_usr',JSON.stringify(user))},
  logout(){
    const ep={admin:'/admin/logout',candidat:'/candidat/logout',entreprise:'/entreprise/logout'}[this.type()];
    if(ep&&this.token())this.post(ep);
    ['jp_tok','jp_type','jp_usr'].forEach(k=>localStorage.removeItem(k));
    window.location.href='/'
  }
};
// ── Toast ────────────────────────────────────────────────────────
window.toast=function(msg,type='success'){
  const el=document.getElementById('toast');
  const colors={success:'#16a34a',error:'#dc2626',info:'#2B5CE6',warn:'#d97706'};
  const icons={success:'✓',error:'✕',info:'ℹ',warn:'⚠'};
  el.style.background=colors[type]||colors.success;
  document.getElementById('toast-icon').textContent=icons[type]||'✓';
  document.getElementById('toast-txt').textContent=msg;
  el.classList.remove('translate-x-96','opacity-0');
  setTimeout(()=>el.classList.add('translate-x-96','opacity-0'),3200)
};
// ── Navbar ───────────────────────────────────────────────────────
(function(){
  const nav=document.getElementById('nav-auth');
  if(!nav)return;
  const u=API.user();const type=API.type();
  if(API.isLogged()&&u){
    const name=type==='admin'?'Admin':type==='candidat'?((u.prenom||'')+(u.nom?' '+u.nom:'')):(u.raisonSociale||'Entreprise');
    const href=type==='admin'?'/admin':type==='candidat'?'/mon-espace':'/recruteur';
    const init=name.substring(0,2).toUpperCase();
    const colors={admin:'#dc2626',candidat:'#2B5CE6',entreprise:'#F6A623'};
    nav.innerHTML=`<a href="${href}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-slate-50"><div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background:${colors[type]}">${init}</div><span class="font-semibold text-sm text-slate-700">${name.substring(0,20)}</span></a><button onclick="API.logout()" class="btn btn-ghost btn-sm">Déconnexion</button>`;
  }else{
    nav.innerHTML=`<a href="/connexion" class="btn btn-secondary btn-sm">Connexion</a><a href="/inscription/candidat" class="btn btn-primary btn-sm">S'inscrire</a>`;
  }
})();
</script>
@stack('scripts')
</body>
</html>
'@ | Set-Content "resources\views\layouts\app.blade.php" -Encoding UTF8

Write-Host "[2/6] Layout cree" -ForegroundColor Green

# ================================================================
# PAGE ACCUEIL
# ================================================================
@'
@extends('layouts.app')
@section('title','JobPlatform — Trouvez votre emploi en Tunisie')
@section('content')
<!-- HERO -->
<section style="background:linear-gradient(135deg,#0d1f5c 0%,#1535a0 50%,#2B5CE6 100%);position:relative;overflow:hidden" class="py-24">
  <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(#fff 1px,transparent 1px);background-size:28px 28px"></div>
  <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full opacity-15" style="background:#F6A623;filter:blur(80px)"></div>
  <div class="relative max-w-4xl mx-auto px-6 text-center">
    <div class="inline-flex items-center gap-2 mb-6 px-4 py-1.5 rounded-full text-sm font-semibold" style="background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.2)">
      <span class="w-2 h-2 rounded-full animate-pulse" style="background:#F6A623"></span>
      <span id="hero-count">Chargement des offres...</span>
    </div>
    <h1 style="font-family:Outfit,sans-serif;font-weight:900;font-size:clamp(2.2rem,5vw,3.8rem);color:#fff;line-height:1.1" class="mb-5 animate-fade-up">
      Trouvez le poste<br><span style="color:#F6A623">qui vous correspond</span>
    </h1>
    <p style="color:rgba(255,255,255,.75);font-size:1.1rem" class="mb-10 animate-fade-up" style="animation-delay:.15s">Des centaines d'offres d'emploi en Tunisie vous attendent.</p>
    <!-- Barre de recherche principale -->
    <div class="flex flex-col sm:flex-row gap-2 max-w-2xl mx-auto bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-2 animate-fade-up" style="animation-delay:.25s">
      <div class="flex-1 relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input id="hs-q" type="text" placeholder="Titre, compétence, domaine..." class="input pl-9 bg-white text-slate-800" style="border-radius:10px" onkeydown="if(event.key==='Enter')doSearch()">
      </div>
      <select id="hs-region" class="input bg-white text-slate-700 w-full sm:w-44" style="border-radius:10px;appearance:none">
        <option value="">📍 Toute la Tunisie</option>
      </select>
      <button onclick="doSearch()" class="btn btn-gold btn-lg whitespace-nowrap" style="border-radius:10px">Rechercher →</button>
    </div>
    <!-- Tags rapides -->
    <div class="flex flex-wrap justify-center gap-2 mt-5 text-sm animate-fade-up" style="animation-delay:.35s">
      <span style="color:rgba(255,255,255,.5)">Populaire :</span>
      <a href="/offres?typeContrat=CDI" class="px-3 py-1 rounded-full hover:bg-white/20 transition-colors" style="background:rgba(255,255,255,.1);color:#fff">CDI</a>
      <a href="/offres?typeContrat=Stage" class="px-3 py-1 rounded-full hover:bg-white/20 transition-colors" style="background:rgba(255,255,255,.1);color:#fff">Stage</a>
      <a href="/offres?typeContrat=Freelance" class="px-3 py-1 rounded-full hover:bg-white/20 transition-colors" style="background:rgba(255,255,255,.1);color:#fff">Freelance</a>
      <a href="/offres?typeContrat=Alternance" class="px-3 py-1 rounded-full hover:bg-white/20 transition-colors" style="background:rgba(255,255,255,.1);color:#fff">Alternance</a>
    </div>
  </div>
</section>

<!-- STATS RAPIDES -->
<section class="max-w-5xl mx-auto px-6 -mt-8 relative z-10">
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="stats-cards">
    @for($i=0;$i<4;$i++)<div class="card p-5 text-center animate-pulse"><div class="h-8 bg-slate-100 rounded mb-2"></div><div class="h-3 bg-slate-100 rounded w-2/3 mx-auto"></div></div>@endfor
  </div>
</section>

<!-- OFFRES RÉCENTES -->
<section class="max-w-7xl mx-auto px-6 mt-14">
  <div class="flex items-center justify-between mb-6">
    <div><h2 class="text-2xl font-extrabold text-slate-800" style="font-family:Outfit">Offres récentes</h2><p class="text-slate-500 text-sm mt-0.5">Les dernières opportunités publiées</p></div>
    <a href="/offres" class="btn btn-secondary">Voir tout →</a>
  </div>
  <div id="offres-home" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5"></div>
</section>

<!-- COMMENT ÇA MARCHE -->
<section class="max-w-5xl mx-auto px-6 mt-20">
  <div class="text-center mb-10"><h2 class="text-2xl font-extrabold text-slate-800" style="font-family:Outfit">Comment ça marche ?</h2><p class="text-slate-500 mt-1">Postulez en 3 étapes simples</p></div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card card-hover p-7 text-center"><div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#f0f4ff"><svg class="w-7 h-7" style="color:#2B5CE6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div><div class="text-5xl font-black mb-2" style="color:#e8edf5;font-family:Outfit">01</div><h3 class="font-bold text-slate-800 mb-1.5">Créez votre profil</h3><p class="text-slate-500 text-sm">Inscrivez-vous gratuitement et complétez votre profil avec votre CV et vos compétences.</p></div>
    <div class="card card-hover p-7 text-center"><div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#fef9c3"><svg class="w-7 h-7" style="color:#d97706" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div><div class="text-5xl font-black mb-2" style="color:#e8edf5;font-family:Outfit">02</div><h3 class="font-bold text-slate-800 mb-1.5">Explorez les offres</h3><p class="text-slate-500 text-sm">Recherche simple ou avancée par domaine, ville, type de contrat, rémunération.</p></div>
    <div class="card card-hover p-7 text-center"><div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#f0fdf4"><svg class="w-7 h-7" style="color:#16a34a" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div class="text-5xl font-black mb-2" style="color:#e8edf5;font-family:Outfit">03</div><h3 class="font-bold text-slate-800 mb-1.5">Postulez facilement</h3><p class="text-slate-500 text-sm">Envoyez votre candidature et suivez son statut en temps réel depuis votre espace.</p></div>
  </div>
</section>

<!-- CTA RECRUTEURS -->
<section class="max-w-7xl mx-auto px-6 my-16">
  <div class="rounded-3xl p-10 flex flex-col md:flex-row items-center justify-between gap-6" style="background:linear-gradient(135deg,#1535a0,#2B5CE6)">
    <div class="text-white"><h2 class="text-2xl font-extrabold mb-1" style="font-family:Outfit">Vous recrutez ?</h2><p style="color:rgba(255,255,255,.75)">Publiez vos offres et accédez aux meilleurs talents de Tunisie.</p></div>
    <div class="flex gap-3"><a href="/inscription/entreprise" class="btn btn-gold">Inscrire mon entreprise →</a><a href="/connexion" class="btn" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3)">Se connecter</a></div>
  </div>
</section>

@push('scripts')
<script>
(async function(){
  const [dRes,rRes]=await Promise.all([API.get('/domaines'),API.get('/regions')]);
  if(rRes?.ok){const r=await rRes.json();const s=document.getElementById('hs-region');r.forEach(x=>{const o=document.createElement('option');o.value=x.idRegion;o.textContent='📍 '+x.ville;s.appendChild(o)});}
  try{
    const res=await API.get('/offres');
    if(res?.ok){
      const data=await res.json();
      const total=data.total||0;
      document.getElementById('hero-count').textContent=total+' offres disponibles maintenant';
      // Stats
      const sc=document.getElementById('stats-cards');
      sc.innerHTML=[['offres-actives',total,'Offres actives','#2B5CE6'],['offres-all',data.total||0,'Total offres','#16a34a'],['check','✓','Inscription gratuite','#F6A623'],['secure','🔒','Candidature sécurisée','#7e22ce']].map(([,v,l,c])=>`<div class="card p-5 text-center"><div class="text-3xl font-black mb-1" style="color:${c};font-family:Outfit">${v}</div><div class="text-slate-500 text-xs font-medium">${l}</div></div>`).join('');
      const items=data.data||[];
      const cc={CDI:'contract-cdi',CDD:'contract-cdd',Stage:'contract-stage',Freelance:'contract-freelance',Alternance:'contract-alternance'};
      document.getElementById('offres-home').innerHTML=items.slice(0,6).map(o=>`
        <a href="/offres/${o.idOffre}" class="card card-hover p-6 block">
          <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-extrabold text-xl" style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">${(o.entreprise?.raisonSociale||'?').charAt(0).toUpperCase()}</div>
            <span class="badge ${cc[o.typeContrat]||'badge-slate'}">${o.typeContrat}</span>
          </div>
          <h3 class="font-bold text-slate-800 mb-0.5 leading-snug" style="font-family:Outfit">${o.titre}</h3>
          <p class="text-sm font-semibold mb-3" style="color:#2B5CE6">${o.entreprise?.raisonSociale||''}</p>
          <div class="flex flex-wrap gap-2 text-xs text-slate-400">
            <span>📍 ${o.localisation}</span>
            <span>📅 ${o.dateLimite?new Date(o.dateLimite).toLocaleDateString('fr-FR'):''}</span>
            ${o.remuneration?`<span style="color:#16a34a;font-weight:600">💰 ${Number(o.remuneration).toLocaleString()} TND</span>`:''}
          </div>
        </a>`).join('')||'<p class="col-span-3 text-center text-slate-400 py-8">Aucune offre disponible.</p>';
    }
  }catch(e){console.error(e)}
})();
function doSearch(){const q=document.getElementById('hs-q').value;const r=document.getElementById('hs-region').value;let url='/offres?';if(q)url+='q='+encodeURIComponent(q)+'&';if(r)url+='idRegion='+r;window.location.href=url}
</script>
@endpush
@endsection
'@ | Set-Content "resources\views\pages\home.blade.php" -Encoding UTF8

Write-Host "[3/6] Page accueil creee" -ForegroundColor Green

# ================================================================
# AUTH PAGES
# ================================================================
@'
@extends('layouts.app')
@section('title','Connexion — JobPlatform')
@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-14" style="background-image:radial-gradient(#e2e8f0 1px,transparent 1px);background-size:24px 24px">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg" style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">
        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      </div>
      <h1 class="text-3xl font-extrabold text-slate-800" style="font-family:Outfit">Bon retour 👋</h1>
      <p class="text-slate-500 mt-1">Connectez-vous à votre espace</p>
    </div>
    <!-- Onglets rôles -->
    <div class="flex bg-slate-100 rounded-xl p-1 mb-6" id="tabs">
      <button onclick="switchTab('candidat')" id="tab-candidat" class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all bg-white shadow-sm text-brand-600">👤 Candidat</button>
      <button onclick="switchTab('entreprise')" id="tab-entreprise" class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all text-slate-500 hover:text-slate-700">🏢 Entreprise</button>
      <button onclick="switchTab('admin')" id="tab-admin" class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all text-slate-500 hover:text-slate-700">⚙️ Admin</button>
    </div>
    <div class="card p-8 animate-pop-in">
      <div id="err" class="hidden mb-4 p-3 rounded-xl text-sm font-medium" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626"></div>
      <form id="lf" onsubmit="doLogin(event)" class="space-y-5">
        <div><label class="label">Adresse email</label><input id="l-email" type="email" class="input" placeholder="vous@exemple.com" required autocomplete="email"></div>
        <div><label class="label">Mot de passe</label><div class="relative"><input id="l-pwd" type="password" class="input pr-10" placeholder="••••••••" required><button type="button" onclick="this.previousElementSibling.type=this.previousElementSibling.type==='password'?'text':'password'" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-sm">👁</button></div></div>
        <button type="submit" id="l-btn" class="btn btn-primary btn-lg w-full justify-center">Se connecter →</button>
      </form>
      <p id="register-hint" class="text-center text-sm text-slate-500 mt-5">Pas encore de compte ? <a href="/inscription/candidat" class="font-semibold hover:underline" style="color:#2B5CE6">S'inscrire</a></p>
    </div>
  </div>
</div>
@push('scripts')
<script>
let cType='candidat';
function switchTab(t){
  cType=t;
  ['candidat','entreprise','admin'].forEach(x=>{
    const btn=document.getElementById('tab-'+x);
    if(x===t){btn.classList.add('bg-white','shadow-sm');btn.style.color='#2B5CE6';btn.classList.remove('text-slate-500')}
    else{btn.classList.remove('bg-white','shadow-sm');btn.style.color='';btn.classList.add('text-slate-500')}
  });
  const h=document.getElementById('register-hint');
  if(t==='candidat')h.innerHTML='Pas encore de compte ? <a href="/inscription/candidat" class="font-semibold hover:underline" style="color:#2B5CE6">S\'inscrire</a>';
  else if(t==='entreprise')h.innerHTML='Pas encore de compte ? <a href="/inscription/entreprise" class="font-semibold hover:underline" style="color:#2B5CE6">Inscrire mon entreprise</a>';
  else h.innerHTML='';
}
async function doLogin(e){
  e.preventDefault();
  const btn=document.getElementById('l-btn');btn.textContent='Connexion...';btn.disabled=true;
  const errEl=document.getElementById('err');errEl.classList.add('hidden');
  const ep={admin:'/admin/login',candidat:'/candidat/login',entreprise:'/entreprise/login'}[cType];
  try{
    const res=await API.post(ep,{email:document.getElementById('l-email').value,motDePasse:document.getElementById('l-pwd').value});
    const data=await res.json();
    if(!res.ok){errEl.textContent=data.message||'Identifiants incorrects.';errEl.classList.remove('hidden');return}
    const key={admin:'admin',candidat:'candidat',entreprise:'entreprise'}[cType];
    API.save(data.token,cType,data[key]);
    toast('Connexion réussie ! 🎉');
    setTimeout(()=>window.location.href={admin:'/admin',candidat:'/mon-espace',entreprise:'/recruteur'}[cType],700);
  }catch{errEl.textContent='Erreur réseau.';errEl.classList.remove('hidden')}
  finally{btn.textContent='Se connecter →';btn.disabled=false}
}
// Auto-check si msg=pending dans l URL
if(new URLSearchParams(location.search).get('msg')==='pending'){
  document.getElementById('err').textContent='Votre compte entreprise est en attente de validation par un administrateur.';
  document.getElementById('err').style.background='#fef9c3';document.getElementById('err').style.borderColor='#fde047';document.getElementById('err').style.color='#713f12';
  document.getElementById('err').classList.remove('hidden');
}
</script>
@endpush
@endsection
'@ | Set-Content "resources\views\auth\login.blade.php" -Encoding UTF8

@'
@extends('layouts.app')
@section('title',"Inscription Candidat — JobPlatform")
@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-14" style="background-image:radial-gradient(#e2e8f0 1px,transparent 1px);background-size:24px 24px">
  <div class="w-full max-w-lg">
    <div class="text-center mb-7">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg" style="background:linear-gradient(135deg,#2B5CE6,#1535a0)"><svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
      <h1 class="text-3xl font-extrabold text-slate-800" style="font-family:Outfit">Créer votre profil</h1>
      <p class="text-slate-500 mt-1">Rejoignez des milliers de candidats sur JobPlatform</p>
    </div>
    <div class="card p-8 animate-pop-in">
      <div id="err" class="hidden mb-4 p-3 rounded-xl text-sm font-medium" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626"></div>
      <form onsubmit="doRegister(event)" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div><label class="label">Prénom *</label><input id="r-prenom" type="text" class="input" required placeholder="Votre prénom"></div>
          <div><label class="label">Nom *</label><input id="r-nom" type="text" class="input" required placeholder="Votre nom"></div>
        </div>
        <div><label class="label">Email *</label><input id="r-email" type="email" class="input" required placeholder="vous@exemple.com"></div>
        <div><label class="label">Domaine d'activité</label><select id="r-domaine" class="input"><option value="">— Sélectionner —</option></select></div>
        <div><label class="label">Spécialité</label><input id="r-specialite" type="text" class="input" placeholder="Ex: Développement Web, Marketing Digital..."></div>
        <div class="grid grid-cols-2 gap-4">
          <div><label class="label">Niveau d'études</label>
            <select id="r-etude" class="input"><option value="">—</option><option>Bac</option><option>Bac+2</option><option>Bac+3 (Licence)</option><option>Bac+4</option><option>Bac+5 (Master)</option><option>Doctorat</option></select>
          </div>
          <div><label class="label">Expérience</label>
            <select id="r-exp" class="input"><option value="">—</option><option>Débutant (0 an)</option><option>Junior (1-3 ans)</option><option>Confirmé (3-5 ans)</option><option>Senior (5-10 ans)</option><option>Expert (+10 ans)</option></select>
          </div>
        </div>
        <div><label class="label">Localisation</label><input id="r-localisation" type="text" class="input" placeholder="Ex: Tunis, Sfax..."></div>
        <div><label class="label">Mot de passe *</label><input id="r-pwd" type="password" class="input" required minlength="8" placeholder="Minimum 8 caractères"></div>
        <div><label class="label">Confirmer le mot de passe *</label><input id="r-pwdc" type="password" class="input" required placeholder="Répétez le mot de passe"></div>
        <button type="submit" id="r-btn" class="btn btn-primary btn-lg w-full justify-center">Créer mon compte →</button>
      </form>
      <p class="text-center text-sm text-slate-500 mt-5">Déjà inscrit ? <a href="/connexion" class="font-semibold hover:underline" style="color:#2B5CE6">Se connecter</a></p>
    </div>
  </div>
</div>
@push('scripts')
<script>
(async()=>{const r=await API.get('/domaines');if(r?.ok){const d=await r.json();const s=document.getElementById('r-domaine');d.forEach(x=>{const o=document.createElement('option');o.value=x.idDomaine;o.textContent=x.nom;s.appendChild(o)})}})();
async function doRegister(e){
  e.preventDefault();
  const btn=document.getElementById('r-btn');btn.textContent='Inscription...';btn.disabled=true;
  const errEl=document.getElementById('err');errEl.classList.add('hidden');
  const pwd=document.getElementById('r-pwd').value,pwdc=document.getElementById('r-pwdc').value;
  if(pwd!==pwdc){errEl.textContent='Les mots de passe ne correspondent pas.';errEl.classList.remove('hidden');btn.textContent='Créer mon compte →';btn.disabled=false;return}
  try{
    const res=await API.post('/candidat/register',{nom:document.getElementById('r-nom').value,prenom:document.getElementById('r-prenom').value,email:document.getElementById('r-email').value,idDomaine:document.getElementById('r-domaine').value||null,specialite:document.getElementById('r-specialite').value||null,niveauEtude:document.getElementById('r-etude').value||null,niveauExperience:document.getElementById('r-exp').value||null,localisation:document.getElementById('r-localisation').value||null,motDePasse:pwd,motDePasse_confirmation:pwdc});
    const data=await res.json();
    if(!res.ok){const msgs=data.errors?Object.values(data.errors).flat():[data.message||'Erreur'];errEl.innerHTML=msgs.join('<br>');errEl.classList.remove('hidden');return}
    API.save(data.token,'candidat',data.candidat);toast('Compte créé avec succès ! 🎉');
    setTimeout(()=>window.location.href='/mon-espace',800);
  }catch{errEl.textContent='Erreur réseau.';errEl.classList.remove('hidden')}
  finally{btn.textContent='Créer mon compte →';btn.disabled=false}
}
</script>
@endpush
@endsection
'@ | Set-Content "resources\views\auth\register-candidat.blade.php" -Encoding UTF8

@'
@extends('layouts.app')
@section('title',"Inscription Entreprise — JobPlatform")
@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-14" style="background-image:radial-gradient(#e2e8f0 1px,transparent 1px);background-size:24px 24px">
  <div class="w-full max-w-lg">
    <div class="text-center mb-7">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg" style="background:linear-gradient(135deg,#F6A623,#d97706)"><svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
      <h1 class="text-3xl font-extrabold text-slate-800" style="font-family:Outfit">Inscrire votre entreprise</h1>
      <p class="text-slate-500 mt-1">Publiez vos offres et trouvez les meilleurs talents</p>
    </div>
    <div class="card p-8 animate-pop-in">
      <div class="p-3 rounded-xl text-sm mb-5 flex gap-2" style="background:#fef9c3;border:1px solid #fde047;color:#713f12">
        <span>ℹ️</span><span>Votre compte sera activé après validation par un administrateur.</span>
      </div>
      <div id="err" class="hidden mb-4 p-3 rounded-xl text-sm font-medium" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626"></div>
      <form onsubmit="doReg(event)" class="space-y-4">
        <div><label class="label">Raison sociale *</label><input id="e-rs" type="text" class="input" required placeholder="Nom officiel de l'entreprise"></div>
        <div><label class="label">Identifiant légal (MF/SIRET) *</label><input id="e-il" type="text" class="input" required placeholder="Ex: TN-2024-00123"></div>
        <div><label class="label">Description</label><textarea id="e-desc" class="input" rows="2" placeholder="Décrivez votre entreprise, activités..."></textarea></div>
        <div class="grid grid-cols-2 gap-4">
          <div><label class="label">Secteur d'activité</label><select id="e-dom" class="input"><option value="">— Sélectionner —</option></select></div>
          <div><label class="label">Téléphone</label><input id="e-tel" type="tel" class="input" placeholder="+216 XX XXX XXX"></div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div><label class="label">Pays</label><input id="e-pays" type="text" class="input" placeholder="Tunisie" value="Tunisie"></div>
          <div><label class="label">Ville</label><input id="e-ville" type="text" class="input" placeholder="Tunis"></div>
        </div>
        <div><label class="label">Site web</label><input id="e-site" type="url" class="input" placeholder="https://www.entreprise.tn"></div>
        <div><label class="label">Email professionnel *</label><input id="e-email" type="email" class="input" required placeholder="rh@entreprise.tn"></div>
        <div><label class="label">Mot de passe *</label><input id="e-pwd" type="password" class="input" required minlength="8" placeholder="Minimum 8 caractères"></div>
        <div><label class="label">Confirmer le mot de passe *</label><input id="e-pwdc" type="password" class="input" required></div>
        <button type="submit" id="e-btn" class="btn btn-gold btn-lg w-full justify-center">Soumettre ma demande →</button>
      </form>
      <p class="text-center text-sm text-slate-500 mt-5">Déjà inscrit ? <a href="/connexion" class="font-semibold hover:underline" style="color:#2B5CE6">Se connecter</a></p>
    </div>
  </div>
</div>
@push('scripts')
<script>
(async()=>{const r=await API.get('/domaines');if(r?.ok){const d=await r.json();const s=document.getElementById('e-dom');d.forEach(x=>{const o=document.createElement('option');o.value=x.idDomaine;o.textContent=x.nom;s.appendChild(o)})}})();
async function doReg(e){
  e.preventDefault();const btn=document.getElementById('e-btn');btn.textContent='Envoi...';btn.disabled=true;
  const errEl=document.getElementById('err');errEl.classList.add('hidden');
  try{
    const res=await API.post('/entreprise/register',{raisonSociale:document.getElementById('e-rs').value,identifiantLegal:document.getElementById('e-il').value,description:document.getElementById('e-desc').value||null,idDomaine:document.getElementById('e-dom').value||null,telephone:document.getElementById('e-tel').value||null,pays:document.getElementById('e-pays').value||null,ville:document.getElementById('e-ville').value||null,siteWeb:document.getElementById('e-site').value||null,email:document.getElementById('e-email').value,motDePasse:document.getElementById('e-pwd').value,motDePasse_confirmation:document.getElementById('e-pwdc').value});
    const data=await res.json();
    if(!res.ok){const msgs=data.errors?Object.values(data.errors).flat():[data.message||'Erreur'];errEl.innerHTML=msgs.join('<br>');errEl.classList.remove('hidden');return}
    window.location.href='/connexion?msg=pending';
  }catch{errEl.textContent='Erreur réseau.';errEl.classList.remove('hidden')}
  finally{btn.textContent='Soumettre ma demande →';btn.disabled=false}
}
</script>
@endpush
@endsection
'@ | Set-Content "resources\views\auth\register-entreprise.blade.php" -Encoding UTF8

Write-Host "[4/6] Pages auth creees" -ForegroundColor Green

# ================================================================
# OFFRES (index + show)
# ================================================================
@'
@extends('layouts.app')
@section('title',"Offres d'emploi — JobPlatform")
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
  <div class="mb-6"><h1 class="text-3xl font-extrabold text-slate-800" style="font-family:Outfit">Offres d'emploi</h1><p class="text-slate-500 mt-1 text-sm">Recherche simple ou avancée parmi nos offres</p></div>
  <div class="flex flex-col lg:flex-row gap-6">
    <!-- Sidebar filtres (recherche avancée) -->
    <aside class="w-full lg:w-72 flex-shrink-0">
      <div class="card p-6 lg:sticky lg:top-20">
        <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wide">🎯 Recherche avancée</h3>
        <div class="space-y-3">
          <div><label class="label">Mots-clés</label><input id="f-q" type="text" class="input text-sm" placeholder="Titre, compétence..." onkeydown="if(event.key==='Enter')load(1)"></div>
          <div><label class="label">Domaine</label><select id="f-dom" class="input text-sm"><option value="">Tous les domaines</option></select></div>
          <div><label class="label">Région</label><select id="f-reg" class="input text-sm"><option value="">Toute la Tunisie</option></select></div>
          <div><label class="label">Catégorie</label><select id="f-cat" class="input text-sm"><option value="">Toutes</option></select></div>
          <div><label class="label">Type de contrat</label>
            <select id="f-con" class="input text-sm"><option value="">Tous</option><option>CDI</option><option>CDD</option><option>Stage</option><option>Freelance</option><option>Alternance</option></select>
          </div>
          <div><label class="label">Rémunération min (TND)</label><input id="f-rmin" type="number" min="0" class="input text-sm" placeholder="Ex: 1000"></div>
          <button onclick="load(1)" class="btn btn-primary w-full justify-center text-sm">Filtrer les résultats</button>
          <button onclick="reset()" class="w-full py-2 text-xs text-slate-400 hover:text-slate-600 font-medium">↺ Réinitialiser</button>
        </div>
      </div>
    </aside>
    <!-- Résultats -->
    <div class="flex-1 min-w-0">
      <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
        <p id="rc" class="text-sm text-slate-500 font-medium"></p>
        <select id="f-sort" onchange="load(1)" class="input text-sm !w-auto">
          <option value="latest">Plus récentes</option>
          <option value="salary">Mieux rémunérées</option>
        </select>
      </div>
      <div id="list" class="space-y-3"></div>
      <div id="pag" class="flex justify-center gap-1.5 mt-8 flex-wrap"></div>
    </div>
  </div>
</div>
@push('scripts')
<script>
const cc={CDI:'contract-cdi',CDD:'contract-cdd',Stage:'contract-stage',Freelance:'contract-freelance',Alternance:'contract-alternance'};
async function load(page=1){
  const q=document.getElementById('f-q').value,dom=document.getElementById('f-dom').value,reg=document.getElementById('f-reg').value,cat=document.getElementById('f-cat').value,con=document.getElementById('f-con').value,rmin=document.getElementById('f-rmin').value;
  let url=`/offres?page=${page}`;
  if(q)url+='&q='+encodeURIComponent(q);
  if(dom)url+='&idDomaine='+dom;if(reg)url+='&idRegion='+reg;if(cat)url+='&idCategorie='+cat;if(con)url+='&typeContrat='+encodeURIComponent(con);if(rmin)url+='&remunMin='+rmin;
  const el=document.getElementById('list');
  el.innerHTML='<div class="text-center py-10 text-slate-400 text-sm">⏳ Chargement...</div>';
  try{
    const res=await API.get(url);if(!res?.ok)return;
    const data=await res.json();const items=data.data||[];
    document.getElementById('rc').textContent=`${data.total||0} offre(s) trouvée(s)`;
    if(!items.length){el.innerHTML='<div class="card p-12 text-center text-slate-400"><div class="text-4xl mb-3">🔍</div><p class="font-semibold">Aucune offre pour ces critères</p><p class="text-xs mt-1">Essayez d\'élargir votre recherche</p></div>';return}
    el.innerHTML=items.map(o=>`
      <a href="/offres/${o.idOffre}" class="card card-hover p-5 flex gap-4 items-start block">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-extrabold text-xl flex-shrink-0" style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">${(o.entreprise?.raisonSociale||'?').charAt(0).toUpperCase()}</div>
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2 flex-wrap">
            <div><h3 class="font-bold text-slate-800 leading-snug" style="font-family:Outfit">${o.titre}</h3><p class="text-sm font-semibold" style="color:#2B5CE6">${o.entreprise?.raisonSociale||''}</p></div>
            <span class="badge ${cc[o.typeContrat]||'badge-slate'} flex-shrink-0">${o.typeContrat}</span>
          </div>
          <p class="text-xs text-slate-400 mt-2 line-clamp-2">${o.description?.substring(0,130)||''}...</p>
          <div class="flex flex-wrap gap-3 mt-2 text-xs text-slate-400">
            <span>📍 ${o.localisation}</span>
            <span>📅 Limite: ${o.dateLimite?new Date(o.dateLimite).toLocaleDateString('fr-FR'):'N/A'}</span>
            ${o.remuneration?`<span style="color:#16a34a;font-weight:600">💰 ${Number(o.remuneration).toLocaleString()} TND/mois</span>`:''}
            ${o.domaine?`<span>🏷 ${o.domaine.nom}</span>`:''}
          </div>
        </div>
      </a>`).join('');
    const lp=data.last_page||1;
    const pg=document.getElementById('pag');
    pg.innerHTML=lp>1?Array.from({length:lp},(_,i)=>`<button onclick="load(${i+1})" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all ${i+1===page?'text-white':'bg-white border border-slate-200 text-slate-600 hover:border-blue-300'}" style="${i+1===page?'background:#2B5CE6':''}">${i+1}</button>`).join(''):'';
  }catch(e){el.innerHTML='<div class="card p-8 text-center text-red-400 text-sm">Erreur de chargement. Vérifiez votre connexion.</div>'}
}
function reset(){['f-q','f-dom','f-reg','f-cat','f-con','f-rmin'].forEach(id=>{const el=document.getElementById(id);if(el)el.value=''});load(1)}
(async()=>{
  const [d,r,c]=await Promise.all([API.get('/domaines'),API.get('/regions'),API.get('/categories')]);
  const fill=(sid,items,pk,label)=>{const s=document.getElementById(sid);items.forEach(x=>{const o=document.createElement('option');o.value=x[pk];o.textContent=x[label];s.appendChild(o)})};
  if(d?.ok)fill('f-dom',await d.json(),'idDomaine','nom');
  if(r?.ok)fill('f-reg',await r.json(),'idRegion','ville');
  if(c?.ok)fill('f-cat',await c.json(),'idCategorie','nom');
  const p=new URLSearchParams(location.search);
  ['q','idDomaine','idRegion','idCategorie','typeContrat'].forEach(k=>{
    const map={q:'f-q',idDomaine:'f-dom',idRegion:'f-reg',idCategorie:'f-cat',typeContrat:'f-con'};
    const v=p.get(k);const el=document.getElementById(map[k]);if(v&&el)el.value=v;
  });
  load(1);
})();
</script>
@endpush
@endsection
'@ | Set-Content "resources\views\offres\index.blade.php" -Encoding UTF8

@'
@extends('layouts.app')
@section('title',"Détail de l'offre — JobPlatform")
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
  <a href="/offres" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-brand-600 font-medium mb-6 transition-colors">← Retour aux offres</a>
  <div id="detail" class="flex items-center justify-center py-20 text-slate-400"><div class="w-10 h-10 rounded-full border-4 animate-spin" style="border-color:#dce6ff;border-top-color:#2B5CE6"></div></div>
  <!-- Modal candidature -->
  <div id="modal-c" class="hidden fixed inset-0 z-50" style="background:rgba(15,23,42,.6);backdrop-filter:blur(6px)" onclick="if(event.target===this)closeM()">
    <div class="flex items-center justify-center min-h-full p-4">
      <div class="bg-white rounded-2xl p-8 w-full max-w-lg shadow-2xl animate-pop-in">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-extrabold text-slate-800" style="font-family:Outfit">Postuler à l'offre</h3>
          <button onclick="closeM()" class="text-slate-400 hover:text-slate-600 text-2xl leading-none font-light">×</button>
        </div>
        <!-- Indicateur étapes -->
        <div class="step-bar mb-6">
          <div class="step step-done" id="s1"><div class="step-num">✓</div><span class="hidden sm:block text-slate-600">Informations</span></div>
          <div class="step-line done"></div>
          <div class="step step-active" id="s2"><div class="step-num">2</div><span class="hidden sm:block text-slate-600">Motivation</span></div>
          <div class="step-line"></div>
          <div class="step step-inactive" id="s3"><div class="step-num">3</div><span class="hidden sm:block text-slate-400">Envoi</span></div>
        </div>
        <div id="c-err" class="hidden mb-4 p-3 rounded-xl text-sm" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626"></div>
        <form id="cf" onsubmit="submitC(event)">
          <input type="hidden" id="c-id">
          <div class="mb-5">
            <label class="label">Lettre de motivation * <span class="text-slate-400 normal-case font-normal">(min. 50 caractères)</span></label>
            <textarea id="c-lettre" class="input" rows="7" placeholder="Cher(e) recruteur(se),&#10;&#10;Je vous soumets ma candidature pour le poste de... Expliquez votre motivation, vos compétences et ce qui vous attire dans cette offre." required minlength="50"></textarea>
            <div class="text-xs text-slate-400 mt-1 text-right"><span id="c-count">0</span>/50 caractères minimum</div>
          </div>
          <div class="mb-6">
            <label class="label">CV (optionnel)</label>
            <input id="c-cv" type="file" accept=".pdf,.doc,.docx" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100" style="file:background:#f0f4ff;file:color:#2B5CE6">
            <p id="cv-hint" class="text-xs text-slate-400 mt-1">Votre CV de profil sera utilisé si aucun fichier n'est fourni</p>
          </div>
          <div class="flex gap-3">
            <button type="button" onclick="closeM()" class="btn btn-secondary flex-1 justify-center">Annuler</button>
            <button type="submit" id="c-btn" class="btn btn-primary flex-1 justify-center">Envoyer ma candidature ✓</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
const oid=location.pathname.split('/').pop();
const cc={CDI:'contract-cdi',CDD:'contract-cdd',Stage:'contract-stage',Freelance:'contract-freelance',Alternance:'contract-alternance'};
(async()=>{
  const res=await API.get('/offres/'+oid);
  if(!res?.ok){document.getElementById('detail').innerHTML='<p class="text-red-400">Offre introuvable.</p>';return}
  const o=await res.json();
  const isC=API.isLogged()&&API.type()==='candidat';
  document.getElementById('detail').innerHTML=`
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 space-y-5">
        <div class="card p-8">
          <div class="flex items-start gap-5 mb-7 flex-wrap">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white font-black text-2xl flex-shrink-0" style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">${(o.entreprise?.raisonSociale||'?').charAt(0).toUpperCase()}</div>
            <div class="flex-1">
              <div class="flex items-start justify-between gap-3 flex-wrap">
                <div>
                  <h1 class="text-2xl font-extrabold text-slate-800 mb-1" style="font-family:Outfit">${o.titre}</h1>
                  <p class="font-bold text-base" style="color:#2B5CE6">${o.entreprise?.raisonSociale||''}</p>
                </div>
                <span class="badge text-sm px-3 py-1 ${cc[o.typeContrat]||'badge-slate'}">${o.typeContrat}</span>
              </div>
              <div class="flex flex-wrap gap-4 mt-3 text-sm text-slate-500">
                <span>📍 ${o.localisation}</span>
                ${o.remuneration?`<span style="color:#16a34a;font-weight:700">💰 ${Number(o.remuneration).toLocaleString()} TND/mois</span>`:''}
                <span>👁 ${o.vues||0} vues</span>
                <span>📅 Publiée le ${o.datePublication?new Date(o.datePublication).toLocaleDateString('fr-FR'):'N/A'}</span>
              </div>
            </div>
          </div>
          <div class="mb-6"><h2 class="font-bold text-slate-800 mb-3" style="font-family:Outfit">Description du poste</h2><div class="text-slate-600 text-sm leading-7 whitespace-pre-line">${o.description}</div></div>
          <div><h2 class="font-bold text-slate-800 mb-3" style="font-family:Outfit">Compétences requises</h2><div class="flex flex-wrap gap-2">${o.competencesRequises.split(',').map(c=>`<span class="px-3 py-1 rounded-full text-sm font-medium" style="background:#f0f4ff;color:#2B5CE6">${c.trim()}</span>`).join('')}</div></div>
        </div>
      </div>
      <div class="space-y-5">
        <div class="card p-6">
          <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wide">Détails</h3>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-slate-50"><span class="text-slate-400">Contrat</span><span class="badge ${cc[o.typeContrat]||'badge-slate'}">${o.typeContrat}</span></div>
            <div class="flex justify-between py-2 border-b border-slate-50"><span class="text-slate-400">Domaine</span><span class="font-medium text-slate-700">${o.domaine?.nom||'N/A'}</span></div>
            <div class="flex justify-between py-2 border-b border-slate-50"><span class="text-slate-400">Catégorie</span><span class="font-medium text-slate-700">${o.categorie?.nom||'N/A'}</span></div>
            <div class="flex justify-between py-2 border-b border-slate-50"><span class="text-slate-400">Région</span><span class="font-medium text-slate-700">${o.region?.ville||'N/A'}, ${o.region?.pays||''}</span></div>
            <div class="flex justify-between py-2"><span class="text-slate-400">Date limite</span><span class="font-bold text-red-500">${o.dateLimite?new Date(o.dateLimite).toLocaleDateString('fr-FR'):'N/A'}</span></div>
          </div>
          ${isC?`
            <button onclick="openPostuler(${o.idOffre})" class="btn btn-primary w-full justify-center mt-5" style="padding:.75rem 1.5rem;font-size:.95rem">✉️ Postuler maintenant</button>
            <button onclick="toggleFav(${o.idOffre},this)" id="btn-fav" class="btn btn-secondary w-full justify-center mt-2">🔖 Sauvegarder l'offre</button>
          `:API.isLogged()?'':`
            <a href="/connexion" class="btn btn-primary w-full justify-center mt-5 block text-center" style="padding:.75rem 1.5rem">Connexion pour postuler</a>
            <a href="/inscription/candidat" class="btn btn-secondary w-full justify-center mt-2 block text-center">Créer un compte gratuit</a>`}
        </div>
        ${o.entreprise?`<div class="card p-6"><h3 class="font-bold text-slate-800 mb-3 text-sm uppercase tracking-wide">Entreprise</h3><div class="flex items-center gap-3 mb-2"><div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold" style="background:#2B5CE6">${(o.entreprise.raisonSociale||'?').charAt(0)}</div><span class="font-bold text-slate-800">${o.entreprise.raisonSociale}</span></div>${o.entreprise.description?`<p class="text-xs text-slate-500 mt-2 leading-relaxed">${o.entreprise.description.substring(0,180)}...</p>`:''} ${o.entreprise.siteWeb?`<a href="${o.entreprise.siteWeb}" target="_blank" class="text-xs font-medium mt-2 block" style="color:#2B5CE6">🌐 Visiter le site web</a>`:''}</div>`:''}
      </div>
    </div>`;
})();
function openPostuler(id){
  if(!API.isLogged()||API.type()!=='candidat'){window.location.href='/connexion';return}
  document.getElementById('c-id').value=id;
  document.getElementById('modal-c').classList.remove('hidden');
}
function closeM(){document.getElementById('modal-c').classList.add('hidden')}
document.getElementById('c-lettre')?.addEventListener('input',function(){const n=this.value.length;document.getElementById('c-count').textContent=n;document.getElementById('c-count').style.color=n>=50?'#16a34a':'#94a3b8'});
async function toggleFav(id,btn){
  if(!API.isLogged()){window.location.href='/connexion';return}
  const r=await API.post('/candidat/favoris/toggle/'+id);
  if(r?.ok){const d=await r.json();btn.innerHTML=d.saved?'✅ Sauvegardé':'🔖 Sauvegarder l\'offre';toast(d.saved?'Offre sauvegardée !':'Retirée des favoris',d.saved?'success':'info')}
}
async function submitC(e){
  e.preventDefault();const btn=document.getElementById('c-btn');btn.textContent='Envoi...';btn.disabled=true;
  const errEl=document.getElementById('c-err');errEl.classList.add('hidden');
  const fd=new FormData();fd.append('idOffre',document.getElementById('c-id').value);fd.append('lettreMotivation',document.getElementById('c-lettre').value);
  const cv=document.getElementById('c-cv').files?.[0];if(cv)fd.append('cv',cv);
  try{
    const res=await API.post('/candidat/candidatures',fd,true);const data=await res.json();
    if(!res.ok){errEl.textContent=data.message||'Erreur.';errEl.classList.remove('hidden');return}
    closeM();toast('Candidature envoyée avec succès ! 🎉');document.querySelector('[onclick^="openPostuler"]')?.remove();
  }catch{errEl.textContent='Erreur réseau.';errEl.classList.remove('hidden')}
  finally{btn.textContent='Envoyer ma candidature ✓';btn.disabled=false}
}
</script>
@endpush
@endsection
'@ | Set-Content "resources\views\offres\show.blade.php" -Encoding UTF8

Write-Host "[5/6] Pages offres creees" -ForegroundColor Green


# ================================================================
# DASHBOARD CANDIDAT - Toutes les fonctions du use case
# ================================================================
@'
@extends('layouts.app')
@section('title','Mon Espace Candidat — JobPlatform')
@section('content')
<div class="flex min-h-[calc(100vh-64px)]" x-data="appC()" x-init="init()">
  <!-- Sidebar -->
  <div class="sidebar hidden lg:flex flex-col">
    <div class="px-2 py-4 mb-2">
      <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg mb-2" style="background:linear-gradient(135deg,#2B5CE6,#1535a0)" x-text="ini"></div>
      <div class="font-bold text-slate-800 text-sm leading-tight" x-text="uname" style="font-family:Outfit"></div>
      <div class="text-xs text-slate-400 mt-0.5" x-text="profil.localisation||'Candidat'"></div>
    </div>
    <template x-for="n in nav" :key="n.id">
      <button @click="tab=n.id" :class="tab===n.id?'sidebar-link active':'sidebar-link'" x-text="n.label"></button>
    </template>
    <div class="mt-auto pt-3 border-t border-slate-100">
      <button @click="API.logout()" class="sidebar-link text-red-400 hover:bg-red-50 w-full">🚪 Déconnexion</button>
    </div>
  </div>
  <!-- Main -->
  <main class="flex-1 overflow-auto">
    <!-- Mobile tabs -->
    <div class="lg:hidden bg-white border-b border-slate-100 px-3 py-2.5 flex gap-1.5 overflow-x-auto">
      <template x-for="n in nav" :key="n.id">
        <button @click="tab=n.id" :class="tab===n.id?'text-white':'bg-slate-100 text-slate-600'" :style="tab===n.id?'background:#2B5CE6':''" class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap" x-text="n.label"></button>
      </template>
    </div>
    <div class="p-6 max-w-4xl mx-auto">

      <!-- ACCUEIL -->
      <div x-show="tab==='accueil'" x-cloak>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-1" style="font-family:Outfit">Bonjour, <span x-text="profil.prenom||'Candidat'"></span> 👋</h2>
        <p class="text-slate-500 text-sm mb-6">Voici un résumé de votre activité</p>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-7">
          <div class="stat-card text-center"><div class="text-3xl font-black" style="color:#2B5CE6;font-family:Outfit" x-text="stats.candidatures_total||0"></div><div class="text-slate-400 text-xs mt-1">Candidatures</div></div>
          <div class="stat-card text-center"><div class="text-3xl font-black text-amber-500" style="font-family:Outfit" x-text="stats.en_attente||0"></div><div class="text-slate-400 text-xs mt-1">En attente</div></div>
          <div class="stat-card text-center"><div class="text-3xl font-black text-green-500" style="font-family:Outfit" x-text="stats.retenues||0"></div><div class="text-slate-400 text-xs mt-1">Retenues ✓</div></div>
          <div class="stat-card text-center"><div class="text-3xl font-black text-purple-500" style="font-family:Outfit" x-text="stats.favoris||0"></div><div class="text-slate-400 text-xs mt-1">Favoris</div></div>
        </div>
        <!-- Complétion du profil -->
        <div class="card p-5 mb-5">
          <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-slate-700 text-sm">Complétez votre profil</h3>
            <span class="text-xs font-bold" style="color:#2B5CE6" x-text="completion+'%'"></span>
          </div>
          <div class="h-2 bg-slate-100 rounded-full overflow-hidden"><div class="h-full rounded-full transition-all" style="background:linear-gradient(90deg,#2B5CE6,#1535a0)" :style="'width:'+completion+'%'"></div></div>
          <div class="flex flex-wrap gap-2 mt-3">
            <span x-show="!profil.cv_path" @click="tab='profil'" class="text-xs px-2.5 py-1 rounded-full cursor-pointer hover:bg-slate-200 transition-colors" style="background:#fef9c3;color:#854d0e">📄 Ajouter un CV</span>
            <span x-show="!profil.specialite" @click="tab='profil'" class="text-xs px-2.5 py-1 rounded-full cursor-pointer hover:bg-slate-200 transition-colors" style="background:#fee2e2;color:#991b1b">🎯 Ajouter une spécialité</span>
            <span x-show="certificats.length===0" @click="tab='certificats'" class="text-xs px-2.5 py-1 rounded-full cursor-pointer hover:bg-slate-200 transition-colors" style="background:#f0f4ff;color:#1535a0">🎓 Ajouter un certificat</span>
          </div>
        </div>
        <div class="card p-5">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-700 text-sm">Dernières candidatures</h3>
            <button @click="tab='candidatures'" class="text-xs font-semibold" style="color:#2B5CE6">Voir tout →</button>
          </div>
          <div x-show="recents.length===0" class="text-slate-400 text-sm text-center py-4">Aucune candidature. <a href="/offres" class="font-semibold" style="color:#2B5CE6">Explorer les offres →</a></div>
          <div class="space-y-2">
            <template x-for="c in recents.slice(0,4)" :key="c.idCandidature">
              <div class="flex items-center justify-between p-3 rounded-xl" style="background:#f8fafc">
                <div><div class="font-semibold text-sm text-slate-800" x-text="c.offre?.titre||'Offre'"></div><div class="text-xs text-slate-400" x-text="c.offre?.entreprise?.raisonSociale||''"></div></div>
                <span class="badge" :class="{'badge-amber':c.statut==='en_attente','badge-blue':c.statut==='en_cours','badge-green':c.statut==='retenu','badge-red':c.statut==='refuse'}" x-text="statLabel(c.statut)"></span>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- CHERCHER EMPLOI -->
      <div x-show="tab==='chercher'" x-cloak>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-5" style="font-family:Outfit">Chercher un emploi</h2>
        <div class="card p-5 mb-5">
          <div class="flex gap-2 flex-wrap">
            <input x-model="searchQ" type="text" class="input flex-1" placeholder="Titre, compétence, domaine..." @keydown.enter="searchOffres(1)">
            <select x-model="searchRegion" class="input !w-auto text-sm"><option value="">📍 Toute la Tunisie</option><template x-for="r in regions" :key="r.idRegion"><option :value="r.idRegion" x-text="r.ville"></option></template></select>
            <select x-model="searchContrat" class="input !w-auto text-sm"><option value="">Tout type</option><option>CDI</option><option>CDD</option><option>Stage</option><option>Freelance</option><option>Alternance</option></select>
            <button @click="searchOffres(1)" class="btn btn-primary">Rechercher →</button>
          </div>
        </div>
        <p class="text-sm text-slate-500 mb-3" x-text="searchResults.total ? searchResults.total+' résultat(s)' : ''"></p>
        <div x-show="searchResults.data?.length===0" class="card p-10 text-center text-slate-400"><div class="text-4xl mb-2">🔍</div><p>Aucune offre trouvée. <a href="/offres" class="font-semibold" style="color:#2B5CE6">Voir toutes les offres</a></p></div>
        <div class="space-y-3">
          <template x-for="o in (searchResults.data||[])" :key="o.idOffre">
            <div class="card card-hover p-5 flex gap-4 items-start">
              <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-bold flex-shrink-0" style="background:linear-gradient(135deg,#2B5CE6,#1535a0)" x-text="(o.entreprise?.raisonSociale||'?').charAt(0).toUpperCase()"></div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div><h3 class="font-bold text-slate-800 text-sm" x-text="o.titre"></h3><p class="text-xs font-semibold" style="color:#2B5CE6" x-text="o.entreprise?.raisonSociale||''"></p></div>
                  <span class="badge badge-blue flex-shrink-0" x-text="o.typeContrat"></span>
                </div>
                <div class="flex flex-wrap gap-3 text-xs text-slate-400 mt-1.5">
                  <span x-text="'📍 '+o.localisation"></span>
                  <span x-show="o.remuneration" x-text="'💰 '+Number(o.remuneration).toLocaleString()+' TND'" style="color:#16a34a;font-weight:600"></span>
                </div>
                <div class="flex gap-2 mt-3">
                  <a :href="'/offres/'+o.idOffre" class="btn btn-secondary btn-sm">Voir les détails</a>
                  <button @click="postulerDirect(o.idOffre)" class="btn btn-primary btn-sm">Postuler →</button>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- CANDIDATURES -->
      <div x-show="tab==='candidatures'" x-cloak>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-5" style="font-family:Outfit">Mes candidatures</h2>
        <div class="flex gap-2 mb-4 flex-wrap">
          <button @click="filtCand=''" :style="filtCand===''?'background:#2B5CE6;color:#fff':''" class="btn btn-secondary btn-sm">Toutes</button>
          <button @click="filtCand='en_attente'" :style="filtCand==='en_attente'?'background:#2B5CE6;color:#fff':''" class="btn btn-secondary btn-sm">En attente</button>
          <button @click="filtCand='retenu'" :style="filtCand==='retenu'?'background:#16a34a;color:#fff':''" class="btn btn-secondary btn-sm">Retenues ✓</button>
          <button @click="filtCand='refuse'" :style="filtCand==='refuse'?'background:#dc2626;color:#fff':''" class="btn btn-secondary btn-sm">Refusées</button>
        </div>
        <div x-show="filteredC.length===0" class="card p-12 text-center text-slate-400"><div class="text-5xl mb-2">📭</div><p class="font-semibold">Aucune candidature</p><a href="/offres" class="btn btn-primary mt-4 inline-flex">Explorer les offres</a></div>
        <div class="space-y-3">
          <template x-for="c in filteredC" :key="c.idCandidature">
            <div class="card p-5">
              <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex-1">
                  <h3 class="font-bold text-slate-800" style="font-family:Outfit" x-text="c.offre?.titre||'Offre'"></h3>
                  <p class="font-semibold text-sm" style="color:#2B5CE6" x-text="c.offre?.entreprise?.raisonSociale||''"></p>
                  <div class="flex gap-3 text-xs text-slate-400 mt-1.5">
                    <span x-text="'📍 '+(c.offre?.localisation||'')"></span>
                    <span x-text="'📅 Postulé le '+new Date(c.dateCandidature).toLocaleDateString('fr-FR')"></span>
                  </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                  <span class="badge" :class="{'badge-amber':c.statut==='en_attente','badge-blue':c.statut==='en_cours','badge-green':c.statut==='retenu','badge-red':c.statut==='refuse'}" x-text="statLabel(c.statut)"></span>
                  <button x-show="c.statut==='en_attente'" @click="retirerC(c.idCandidature)" class="btn btn-danger btn-sm">Retirer</button>
                  <a :href="'/offres/'+c.idOffre" class="btn btn-secondary btn-sm">Voir →</a>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- FAVORIS (offres sauvegardées) -->
      <div x-show="tab==='favoris'" x-cloak>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-5" style="font-family:Outfit">Offres sauvegardées</h2>
        <div x-show="favoris.length===0" class="card p-12 text-center text-slate-400"><div class="text-5xl mb-2">🔖</div><p class="font-semibold">Aucune offre sauvegardée</p><a href="/offres" class="btn btn-primary mt-4 inline-flex">Explorer les offres</a></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <template x-for="f in favoris" :key="f.id">
            <div class="card p-5">
              <div class="flex justify-between mb-2">
                <div><h3 class="font-bold text-slate-800 text-sm" style="font-family:Outfit" x-text="f.offre?.titre||'Offre'"></h3><p class="font-semibold text-xs" style="color:#2B5CE6" x-text="f.offre?.entreprise?.raisonSociale||''"></p></div>
                <button @click="retirFav(f.id)" class="text-slate-300 hover:text-red-400 text-xl leading-none transition-colors">×</button>
              </div>
              <div class="flex gap-2 text-xs text-slate-400 mb-3">
                <span x-text="'📍 '+(f.offre?.localisation||'')"></span>
                <span x-text="'📅 '+(f.offre?.dateLimite?new Date(f.offre.dateLimite).toLocaleDateString('fr-FR'):'')"></span>
              </div>
              <div class="flex gap-2">
                <a :href="'/offres/'+f.offre?.idOffre" class="btn btn-secondary btn-sm flex-1 justify-center">Voir l'offre</a>
                <button @click="postulerDirect(f.offre?.idOffre)" class="btn btn-primary btn-sm flex-1 justify-center">Postuler →</button>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- PROFIL (gérer profil) -->
      <div x-show="tab==='profil'" x-cloak>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-5" style="font-family:Outfit">Mon profil</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="card p-6">
            <h3 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wide">Informations personnelles</h3>
            <div class="space-y-4">
              <div class="grid grid-cols-2 gap-3"><div><label class="label">Prénom</label><input x-model="profil.prenom" class="input text-sm"></div><div><label class="label">Nom</label><input x-model="profil.nom" class="input text-sm"></div></div>
              <div><label class="label">Domaine</label><select x-model="profil.idDomaine" class="input text-sm"><option value="">— Sélectionner —</option><template x-for="d in domaines" :key="d.idDomaine"><option :value="d.idDomaine" x-text="d.nom"></option></template></select></div>
              <div><label class="label">Spécialité</label><input x-model="profil.specialite" class="input text-sm" placeholder="Ex: Développement Full-Stack..."></div>
              <div class="grid grid-cols-2 gap-3">
                <div><label class="label">Niveau d'études</label><select x-model="profil.niveauEtude" class="input text-sm"><option value="">—</option><option>Bac</option><option>Bac+2</option><option>Bac+3 (Licence)</option><option>Bac+5 (Master)</option><option>Doctorat</option></select></div>
                <div><label class="label">Expérience</label><select x-model="profil.niveauExperience" class="input text-sm"><option value="">—</option><option>Débutant (0 an)</option><option>Junior (1-3 ans)</option><option>Confirmé (3-5 ans)</option><option>Senior (5-10 ans)</option></select></div>
              </div>
              <div><label class="label">Localisation</label><input x-model="profil.localisation" class="input text-sm" placeholder="Ville, région..."></div>
            </div>
            <button @click="saveProfile()" class="btn btn-primary mt-5 text-sm">Sauvegarder ✓</button>
          </div>
          <div class="card p-6">
            <h3 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wide">Importer mon CV</h3>
            <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center mb-4 hover:border-brand-300 transition-colors">
              <div class="text-3xl mb-2">📄</div>
              <p x-show="profil.cv_path" class="text-xs font-semibold text-green-600 mb-2">✓ CV déjà importé</p>
              <p x-show="!profil.cv_path" class="text-xs text-slate-400 mb-2">Aucun CV importé</p>
              <input id="cv-file" type="file" accept=".pdf,.doc,.docx" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold" style="file:background:#f0f4ff;file:color:#2B5CE6">
            </div>
            <button @click="importCV()" class="btn btn-secondary text-sm w-full justify-center">Importer le CV</button>
            <div class="mt-4 pt-4 border-t border-slate-100">
              <h4 class="font-bold text-slate-700 mb-3 text-sm">Changer le mot de passe</h4>
              <div class="space-y-2"><input x-model="pwdForm.ancien" type="password" class="input text-sm" placeholder="Ancien mot de passe"><input x-model="pwdForm.nouveau" type="password" class="input text-sm" placeholder="Nouveau mot de passe (min. 8)"><input x-model="pwdForm.conf" type="password" class="input text-sm" placeholder="Confirmer le nouveau"></div>
              <button @click="changePwd()" class="btn btn-secondary text-sm mt-3 w-full justify-center">Modifier le mot de passe</button>
            </div>
          </div>
        </div>
      </div>

      <!-- CERTIFICATS -->
      <div x-show="tab==='certificats'" x-cloak>
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-2xl font-extrabold text-slate-800" style="font-family:Outfit">Mes certificats</h2>
          <button @click="showCertM=true;editCert={}" class="btn btn-primary text-sm">+ Ajouter</button>
        </div>
        <div x-show="certificats.length===0" class="card p-12 text-center text-slate-400"><div class="text-5xl mb-2">🎓</div><p class="font-semibold">Aucun certificat ajouté</p><p class="text-xs mt-1">Ajoutez vos certifications pour renforcer votre profil</p></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <template x-for="c in certificats" :key="c.id">
            <div class="card p-5">
              <div class="flex items-start justify-between mb-2">
                <div><h3 class="font-bold text-slate-800 text-sm" x-text="c.nom"></h3><p class="text-xs font-semibold mt-0.5" style="color:#2B5CE6" x-text="c.organisme"></p><p class="text-xs text-slate-400 mt-0.5" x-text="c.dateObtention?new Date(c.dateObtention).toLocaleDateString('fr-FR'):''"></p></div>
                <div class="flex gap-2"><button @click="editCert=JSON.parse(JSON.stringify(c));showCertM=true" class="text-slate-300 hover:text-blue-500 text-sm">✏️</button><button @click="delCert(c.id)" class="text-slate-300 hover:text-red-400 text-sm">🗑</button></div>
              </div>
            </div>
          </template>
        </div>
        <div x-show="showCertM" x-cloak class="modal-bg" @click.self="showCertM=false">
          <div class="modal animate-pop-in">
            <h3 class="text-xl font-extrabold mb-5 text-slate-800" style="font-family:Outfit" x-text="editCert.id?'Modifier le certificat':'Ajouter un certificat'"></h3>
            <div class="space-y-4">
              <div><label class="label">Nom du certificat *</label><input x-model="editCert.nom" class="input" placeholder="Ex: AWS Certified Developer" required></div>
              <div><label class="label">Organisme *</label><input x-model="editCert.organisme" class="input" placeholder="Ex: Amazon, Google, Coursera..."></div>
              <div><label class="label">Date d'obtention *</label><input x-model="editCert.dateObtention" type="date" class="input"></div>
            </div>
            <div class="flex gap-3 mt-6"><button @click="showCertM=false" class="btn btn-secondary flex-1 justify-center">Annuler</button><button @click="saveCert()" class="btn btn-primary flex-1 justify-center">Sauvegarder ✓</button></div>
          </div>
        </div>
      </div>

      <!-- MESSAGES -->
      <div x-show="tab==='messages'" x-cloak>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-5" style="font-family:Outfit">Messages</h2>
        <div class="card" x-show="!activConv">
          <div x-show="convs.length===0" class="p-12 text-center text-slate-400"><div class="text-5xl mb-2">💬</div><p>Aucune conversation</p></div>
          <div class="divide-y divide-slate-50">
            <template x-for="cv in convs" :key="cv.idMessage">
              <button @click="openConv(cv)" class="w-full flex gap-4 items-center p-4 hover:bg-slate-50 transition-colors text-left">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0" style="background:#F6A623" x-text="(cv.entreprise?.raisonSociale||'?').charAt(0).toUpperCase()"></div>
                <div class="flex-1 min-w-0"><div class="font-semibold text-sm text-slate-800 truncate" x-text="cv.entreprise?.raisonSociale||'Entreprise'"></div><div class="text-xs text-slate-400 truncate" x-text="cv.contenu?.substring(0,55)||''"></div></div>
                <div class="text-xs text-slate-300 flex-shrink-0" x-text="cv.dateEnvoi?new Date(cv.dateEnvoi).toLocaleDateString('fr-FR'):''"></div>
              </button>
            </template>
          </div>
        </div>
        <div x-show="activConv" class="card flex flex-col" style="height:500px">
          <div class="flex items-center gap-3 p-4 border-b border-slate-100">
            <button @click="activConv=null" class="text-slate-400 hover:text-slate-600 text-lg">←</button>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm" style="background:#F6A623" x-text="(activConv?.raisonSociale||'?').charAt(0).toUpperCase()"></div>
            <span class="font-bold text-slate-800 text-sm" x-text="activConv?.raisonSociale"></span>
          </div>
          <div class="flex-1 overflow-y-auto p-4 space-y-2" id="mc">
            <template x-for="m in msgs" :key="m.idMessage">
              <div :class="m.expediteur==='candidat'?'flex justify-end':'flex justify-start'">
                <div :style="m.expediteur==='candidat'?'background:#2B5CE6;color:#fff':'background:#f1f5f9;color:#334155'" class="px-4 py-2.5 rounded-2xl max-w-xs text-sm leading-relaxed" x-text="m.contenu"></div>
              </div>
            </template>
          </div>
          <div class="p-3 border-t border-slate-100 flex gap-2">
            <input x-model="newMsg" @keydown.enter="sendMsg()" class="input flex-1 text-sm" placeholder="Votre message...">
            <button @click="sendMsg()" class="btn btn-primary">Envoyer →</button>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>

<!-- Modal postuler rapide -->
<div id="pm" class="hidden fixed inset-0 z-50" style="background:rgba(15,23,42,.6);backdrop-filter:blur(6px)">
  <div class="flex items-center justify-center min-h-full p-4">
    <div class="bg-white rounded-2xl p-7 w-full max-w-md shadow-2xl animate-pop-in">
      <div class="flex justify-between items-center mb-5"><h3 class="text-xl font-extrabold text-slate-800" style="font-family:Outfit">Postuler rapidement</h3><button onclick="document.getElementById('pm').classList.add('hidden')" class="text-slate-400 text-2xl">×</button></div>
      <div id="pm-err" class="hidden mb-3 p-3 rounded-xl text-sm" style="background:#fef2f2;color:#dc2626"></div>
      <form onsubmit="submitQuick(event)" class="space-y-4">
        <input type="hidden" id="pm-id">
        <div><label class="label">Lettre de motivation * (min 50 car.)</label><textarea id="pm-lm" class="input" rows="6" required minlength="50" placeholder="Cher(e) recruteur(se), je vous soumets ma candidature..."></textarea></div>
        <div class="flex gap-3"><button type="button" onclick="document.getElementById('pm').classList.add('hidden')" class="btn btn-secondary flex-1 justify-center">Annuler</button><button type="submit" id="pm-btn" class="btn btn-primary flex-1 justify-center">Envoyer ✓</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function appC(){return{
  tab:'accueil',profil:{},ini:'',uname:'',stats:{},recents:[],candidatures:[],favoris:[],certificats:[],domaines:[],regions:[],convs:[],msgs:[],activConv:null,newMsg:'',showCertM:false,editCert:{},filtCand:'',searchQ:'',searchRegion:'',searchContrat:'',searchResults:{},pwdForm:{ancien:'',nouveau:'',conf:''},completion:0,
  nav:[{id:'accueil',label:'🏠 Accueil'},{id:'chercher',label:'🔍 Chercher emploi'},{id:'candidatures',label:'📄 Candidatures'},{id:'favoris',label:'🔖 Favoris'},{id:'profil',label:'👤 Mon profil'},{id:'certificats',label:'🎓 Certificats'},{id:'messages',label:'💬 Messages'}],
  get filteredC(){return this.filtCand?this.candidatures.filter(c=>c.statut===this.filtCand):this.candidatures},
  statLabel(s){return{en_attente:'En attente',en_cours:'En cours',retenu:'Retenu ✓',refuse:'Refusé ✕'}[s]||s},
  async init(){
    if(!API.isLogged()||API.type()!=='candidat'){window.location.href='/connexion';return}
    const u=API.user();if(u){this.profil={...u};this.uname=(u.prenom||'')+(u.nom?' '+u.nom:'');this.ini=((u.prenom||'?').charAt(0)+(u.nom||'?').charAt(0)).toUpperCase()}
    const tab=location.pathname.split('/').filter(Boolean)[1];if(tab)this.tab=tab;
    await Promise.all([this.loadStats(),this.loadCandidatures(),this.loadFavoris(),this.loadCerts(),this.loadDomaines(),this.loadConvs(),this.loadRegions()]);
    this.calcCompletion();
  },
  calcCompletion(){let s=0;if(this.profil.cv_path)s+=30;if(this.profil.specialite)s+=20;if(this.profil.localisation)s+=15;if(this.profil.niveauEtude)s+=15;if(this.certificats.length>0)s+=20;this.completion=s},
  async loadStats(){const r=await API.get('/candidat/dashboard');if(r?.ok)this.stats=await r.json()},
  async loadCandidatures(){const r=await API.get('/candidat/candidatures');if(r?.ok){this.candidatures=await r.json();this.recents=this.candidatures}},
  async loadFavoris(){const r=await API.get('/candidat/favoris');if(r?.ok)this.favoris=await r.json()},
  async loadCerts(){const r=await API.get('/candidat/certificats');if(r?.ok)this.certificats=await r.json()},
  async loadDomaines(){const r=await API.get('/domaines');if(r?.ok)this.domaines=await r.json()},
  async loadRegions(){const r=await API.get('/regions');if(r?.ok)this.regions=await r.json()},
  async loadConvs(){const r=await API.get('/candidat/messages');if(r?.ok)this.convs=await r.json()},
  async searchOffres(page=1){
    let url=`/offres?page=${page}`;
    if(this.searchQ)url+='&q='+encodeURIComponent(this.searchQ);
    if(this.searchRegion)url+='&idRegion='+this.searchRegion;
    if(this.searchContrat)url+='&typeContrat='+encodeURIComponent(this.searchContrat);
    const r=await API.get(url);if(r?.ok)this.searchResults=await r.json()
  },
  postulerDirect(id){document.getElementById('pm-id').value=id;document.getElementById('pm').classList.remove('hidden')},
  async retirerC(id){if(!confirm('Retirer cette candidature ?'))return;const r=await API.del('/candidat/candidatures/'+id);if(r?.ok){this.candidatures=this.candidatures.filter(c=>c.idCandidature!==id);toast('Candidature retirée.','info');await this.loadStats()}},
  async retirFav(id){const r=await API.del('/candidat/favoris/'+id);if(r?.ok){this.favoris=this.favoris.filter(f=>f.id!==id);toast('Retiré des favoris.','info');await this.loadStats()}},
  async saveProfile(){
    const fd=new FormData();
    const f=['nom','prenom','idDomaine','specialite','niveauEtude','niveauExperience','localisation'];
    f.forEach(k=>{if(this.profil[k])fd.append(k,this.profil[k])});
    const r=await API.post('/candidat/profile',fd,true);
    if(r?.ok){const u=await r.json();localStorage.setItem('jp_usr',JSON.stringify(u));toast('Profil mis à jour ! ✓');this.calcCompletion()}else toast('Erreur.','error')
  },
  async importCV(){
    const f=document.getElementById('cv-file')?.files?.[0];if(!f){toast('Sélectionnez un fichier.','warn');return}
    const fd=new FormData();fd.append('cv',f);
    const r=await API.post('/candidat/importer-cv',fd,true);
    if(r?.ok){const d=await r.json();this.profil.cv_path=d.cv_path;toast('CV importé ! ✓');this.calcCompletion()}else toast('Erreur import.','error')
  },
  async changePwd(){
    if(this.pwdForm.nouveau!==this.pwdForm.conf){toast('Mots de passe différents.','warn');return}
    const r=await API.post('/candidat/password',{ancien:this.pwdForm.ancien,motDePasse:this.pwdForm.nouveau,motDePasse_confirmation:this.pwdForm.conf});
    const d=await r.json();if(r?.ok){toast('Mot de passe modifié ! ✓');this.pwdForm={}}else toast(d.message||'Erreur.','error')
  },
  async saveCert(){
    const d={nom:this.editCert.nom,organisme:this.editCert.organisme,dateObtention:this.editCert.dateObtention};
    const r=this.editCert.id?await API.put('/candidat/certificats/'+this.editCert.id,d):await API.post('/candidat/certificats',d);
    if(r?.ok){await this.loadCerts();this.showCertM=false;toast('Certificat sauvegardé ! ✓')}else toast('Erreur.','error')
  },
  async delCert(id){if(!confirm('Supprimer ?'))return;const r=await API.del('/candidat/certificats/'+id);if(r?.ok){this.certificats=this.certificats.filter(c=>c.id!==id);toast('Supprimé.','info')}},
  async openConv(cv){this.activConv=cv.entreprise;const r=await API.get('/candidat/messages/'+cv.idEntreprise);if(r?.ok)this.msgs=await r.json();this.$nextTick(()=>{const el=document.getElementById('mc');if(el)el.scrollTop=el.scrollHeight})},
  async sendMsg(){if(!this.newMsg.trim()||!this.activConv)return;const r=await API.post('/candidat/messages',{idEntreprise:this.activConv.idEntreprise,contenu:this.newMsg});if(r?.ok){const m=await r.json();this.msgs.push(m);this.newMsg='';this.$nextTick(()=>{const el=document.getElementById('mc');if(el)el.scrollTop=el.scrollHeight})}},
}}
async function submitQuick(e){
  e.preventDefault();const btn=document.getElementById('pm-btn');btn.textContent='Envoi...';btn.disabled=true;
  const fd=new FormData();fd.append('idOffre',document.getElementById('pm-id').value);fd.append('lettreMotivation',document.getElementById('pm-lm').value);
  const r=await API.post('/candidat/candidatures',fd,true);const d=await r.json();
  if(r?.ok){document.getElementById('pm').classList.add('hidden');toast('Candidature envoyée ! 🎉')}else{document.getElementById('pm-err').textContent=d.message||'Erreur.';document.getElementById('pm-err').classList.remove('hidden')}
  btn.textContent='Envoyer ✓';btn.disabled=false;
}
</script>
@endpush
@endsection
'@ | Set-Content "resources\views\candidat\dashboard.blade.php" -Encoding UTF8


# ================================================================
# DASHBOARD ENTREPRISE
# ================================================================
@'
@extends('layouts.app')
@section('title','Espace Recruteur — JobPlatform')
@section('content')
<div class="flex min-h-[calc(100vh-64px)]" x-data="appE()" x-init="init()">
  <div class="sidebar hidden lg:flex flex-col">
    <div class="px-2 py-4 mb-2">
      <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg mb-2" style="background:linear-gradient(135deg,#F6A623,#d97706)" x-text="ini"></div>
      <div class="font-bold text-slate-800 text-sm leading-tight" x-text="cname" style="font-family:Outfit"></div>
      <div class="text-xs mt-0.5 font-semibold" style="color:#16a34a">✓ Entreprise vérifiée</div>
    </div>
    <template x-for="n in nav" :key="n.id"><button @click="tab=n.id" :class="tab===n.id?'sidebar-link active':'sidebar-link'" x-text="n.label"></button></template>
    <div class="mt-auto pt-3 border-t border-slate-100"><button @click="API.logout()" class="sidebar-link text-red-400 hover:bg-red-50 w-full">🚪 Déconnexion</button></div>
  </div>
  <main class="flex-1 overflow-auto">
    <div class="lg:hidden bg-white border-b border-slate-100 px-3 py-2.5 flex gap-1.5 overflow-x-auto">
      <template x-for="n in nav" :key="n.id"><button @click="tab=n.id" :style="tab===n.id?'background:#F6A623;color:#fff':''" class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap bg-slate-100 text-slate-600" x-text="n.label"></button></template>
    </div>
    <div class="p-6 max-w-5xl mx-auto">

      <!-- TABLEAU DE BORD -->
      <div x-show="tab==='dashboard'" x-cloak>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-1" style="font-family:Outfit">Tableau de bord</h2>
        <p class="text-slate-400 text-sm mb-6">Vue d'ensemble de votre activité de recrutement</p>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-7">
          <div class="stat-card text-center"><div class="text-3xl font-black" style="color:#F6A623;font-family:Outfit" x-text="stats.offres_total||0"></div><div class="text-slate-400 text-xs mt-1">Total offres</div></div>
          <div class="stat-card text-center"><div class="text-3xl font-black text-green-500" style="font-family:Outfit" x-text="stats.offres_actives||0"></div><div class="text-slate-400 text-xs mt-1">Actives</div></div>
          <div class="stat-card text-center"><div class="text-3xl font-black" style="color:#2B5CE6;font-family:Outfit" x-text="stats.candidatures_total||0"></div><div class="text-slate-400 text-xs mt-1">Candidatures</div></div>
          <div class="stat-card text-center"><div class="text-3xl font-black text-purple-500" style="font-family:Outfit" x-text="stats.retenues||0"></div><div class="text-slate-400 text-xs mt-1">Retenues</div></div>
        </div>
        <div class="card p-5">
          <h3 class="font-bold text-slate-700 mb-4 text-sm">Performance par offre</h3>
          <div x-show="(stats.offres_detail||[]).length===0" class="text-center text-slate-400 py-4 text-sm">Aucune offre. <button @click="tab='offres'" class="font-semibold" style="color:#F6A623">Publier une offre →</button></div>
          <div class="space-y-2">
            <template x-for="o in (stats.offres_detail||[]).slice(0,5)" :key="o.idOffre">
              <div class="flex items-center justify-between p-3 rounded-xl" style="background:#f8fafc">
                <div class="flex-1 min-w-0"><div class="font-semibold text-sm truncate" x-text="o.titre"></div><div class="text-xs text-slate-400" x-text="'Limite: '+(o.dateLimite?new Date(o.dateLimite).toLocaleDateString('fr-FR'):'N/A')"></div></div>
                <div class="flex items-center gap-3 flex-shrink-0"><span class="text-xs font-bold" style="color:#2B5CE6" x-text="(o.candidatures_count||0)+' candidature(s)'"></span><span class="badge" :class="o.statut==='active'?'badge-green':'badge-slate'" x-text="o.statut"></span></div>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- GÉRER DES OFFRES (publier, modifier, suspendre, supprimer) -->
      <div x-show="tab==='offres'" x-cloak>
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-2xl font-extrabold text-slate-800" style="font-family:Outfit">Gérer mes offres</h2>
          <button @click="openOM()" class="btn btn-gold text-sm">+ Publier une offre</button>
        </div>
        <div x-show="offres.length===0" class="card p-12 text-center text-slate-400"><div class="text-5xl mb-2">📋</div><p class="font-semibold">Aucune offre publiée</p><button @click="openOM()" class="btn btn-gold mt-4 inline-flex">Publier votre première offre</button></div>
        <div class="space-y-3">
          <template x-for="o in offres" :key="o.idOffre">
            <div class="card p-5">
              <div class="flex items-start gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                  <h3 class="font-bold text-slate-800" style="font-family:Outfit" x-text="o.titre"></h3>
                  <div class="flex flex-wrap gap-3 text-xs text-slate-400 mt-1.5">
                    <span x-text="o.typeContrat"></span><span x-text="'📍 '+o.localisation"></span>
                    <span x-text="'📅 Limite: '+(o.dateLimite?new Date(o.dateLimite).toLocaleDateString('fr-FR'):'')"></span>
                    <span class="font-bold" style="color:#2B5CE6" x-text="(o.candidatures_count||0)+' candidature(s)'"></span>
                  </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                  <span class="badge" :class="o.statut==='active'?'badge-green':o.statut==='suspendue'?'badge-amber':'badge-red'" x-text="o.statut"></span>
                  <button @click="voirC(o)" class="btn btn-secondary btn-sm">👥 Candidats</button>
                  <button @click="openOM(o)" class="btn btn-ghost btn-sm">✏️</button>
                  <button @click="suspendre(o)" class="btn btn-secondary btn-sm" x-text="o.statut==='active'?'⏸ Suspendre':'▶ Activer'"></button>
                  <button @click="delOffre(o.idOffre)" class="btn btn-danger btn-sm">🗑</button>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- CANDIDATURES REÇUES -->
      <div x-show="tab==='candidatures'" x-cloak>
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
          <div><h2 class="text-2xl font-extrabold text-slate-800" style="font-family:Outfit">Candidatures reçues</h2><p class="text-slate-400 text-sm" x-show="selOffre" x-text="'Pour : '+selOffre?.titre"></p></div>
          <button x-show="selOffre" @click="selOffre=null;tab='offres'" class="btn btn-ghost btn-sm">← Retour</button>
        </div>
        <div x-show="!selOffre" class="card p-10 text-center text-slate-400"><p class="text-sm">Sélectionnez une offre pour voir ses candidatures.</p><button @click="tab='offres'" class="btn btn-primary mt-4 inline-flex">Mes offres</button></div>
        <div x-show="selOffre">
          <div class="flex gap-2 mb-4 flex-wrap">
            <button @click="filtE=''" :style="filtE===''?'background:#2B5CE6;color:#fff':''" class="btn btn-secondary btn-sm">Tous</button>
            <button @click="filtE='en_attente'" :style="filtE==='en_attente'?'background:#d97706;color:#fff':''" class="btn btn-secondary btn-sm">En attente</button>
            <button @click="filtE='retenu'" :style="filtE==='retenu'?'background:#16a34a;color:#fff':''" class="btn btn-secondary btn-sm">Retenus ✓</button>
            <button @click="filtE='refuse'" :style="filtE==='refuse'?'background:#dc2626;color:#fff':''" class="btn btn-secondary btn-sm">Refusés ✕</button>
          </div>
          <div x-show="cands.length===0" class="card p-8 text-center text-slate-400 text-sm">Aucune candidature reçue.</div>
          <div class="space-y-4">
            <template x-for="c in filteredE" :key="c.idCandidature">
              <div class="card p-5">
                <div class="flex items-start gap-4 flex-wrap">
                  <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-bold flex-shrink-0" style="background:linear-gradient(135deg,#2B5CE6,#1535a0)" x-text="((c.candidat?.prenom||'?').charAt(0)+(c.candidat?.nom||'?').charAt(0)).toUpperCase()"></div>
                  <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-slate-800 text-sm" x-text="(c.candidat?.prenom||'')+' '+(c.candidat?.nom||'')"></h3>
                    <p class="text-xs" style="color:#2B5CE6" x-text="c.candidat?.email"></p>
                    <div class="flex gap-3 text-xs text-slate-400 mt-1">
                      <span x-text="c.candidat?.localisation?'📍 '+c.candidat.localisation:''"></span>
                      <span x-text="c.candidat?.niveauExperience||''"></span>
                      <span x-text="'📅 '+new Date(c.dateCandidature).toLocaleDateString('fr-FR')"></span>
                    </div>
                    <div class="mt-2 p-3 rounded-xl text-xs text-slate-500 leading-relaxed" style="background:#f8fafc" x-text="(c.lettreMotivation||'').substring(0,220)+'...'"></div>
                    <div class="flex gap-2 mt-3">
                      <a x-show="c.cv_path" :href="'/storage/'+c.cv_path" target="_blank" class="btn btn-secondary btn-sm">📄 Télécharger CV</a>
                    </div>
                  </div>
                  <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    <span class="badge" :class="{'badge-amber':c.statut==='en_attente','badge-blue':c.statut==='en_cours','badge-green':c.statut==='retenu','badge-red':c.statut==='refuse'}" x-text="c.statut"></span>
                    <select @change="changeStatutC(c.idCandidature,$event.target.value)" class="input text-xs !py-1.5 !w-auto">
                      <option value="">Changer statut</option>
                      <option value="en_cours">En cours</option>
                      <option value="retenu">✓ Retenu</option>
                      <option value="refuse">✕ Refusé</option>
                    </select>
                    <button @click="msgCandidat(c.candidat)" class="btn btn-secondary btn-sm">💬 Contacter</button>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- MESSAGES -->
      <div x-show="tab==='messages'" x-cloak>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-5" style="font-family:Outfit">Messages</h2>
        <div class="card" x-show="!activMsgC">
          <div x-show="convs.length===0" class="p-12 text-center text-slate-400"><div class="text-5xl mb-2">💬</div><p>Aucune conversation</p></div>
          <div class="divide-y divide-slate-50">
            <template x-for="cv in convs" :key="cv.idMessage">
              <button @click="openConv(cv)" class="w-full flex gap-4 items-center p-4 hover:bg-slate-50 transition-colors text-left">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0" style="background:#2B5CE6" x-text="(cv.candidat?.prenom||'?').charAt(0).toUpperCase()"></div>
                <div class="flex-1 min-w-0"><div class="font-semibold text-sm truncate" x-text="(cv.candidat?.prenom||'')+' '+(cv.candidat?.nom||'')"></div><div class="text-xs text-slate-400 truncate" x-text="cv.contenu?.substring(0,55)||''"></div></div>
              </button>
            </template>
          </div>
        </div>
        <div x-show="activMsgC" class="card flex flex-col" style="height:500px">
          <div class="flex items-center gap-3 p-4 border-b border-slate-100">
            <button @click="activMsgC=null" class="text-slate-400 hover:text-slate-600 text-lg">←</button>
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm" style="background:#2B5CE6" x-text="(activMsgC?.prenom||'?').charAt(0)"></div>
            <span class="font-bold text-slate-800 text-sm" x-text="(activMsgC?.prenom||'')+' '+(activMsgC?.nom||'')"></span>
          </div>
          <div class="flex-1 overflow-y-auto p-4 space-y-2" id="mce">
            <template x-for="m in msgsE" :key="m.idMessage">
              <div :class="m.expediteur==='entreprise'?'flex justify-end':'flex justify-start'">
                <div :style="m.expediteur==='entreprise'?'background:#F6A623;color:#fff':'background:#f1f5f9;color:#334155'" class="px-4 py-2.5 rounded-2xl max-w-xs text-sm" x-text="m.contenu"></div>
              </div>
            </template>
          </div>
          <div class="p-3 border-t border-slate-100 flex gap-2">
            <input x-model="newMsgE" @keydown.enter="sendMsgE()" class="input flex-1 text-sm" placeholder="Votre message...">
            <button @click="sendMsgE()" class="btn btn-gold">Envoyer →</button>
          </div>
        </div>
      </div>

      <!-- PROFIL ENTREPRISE -->
      <div x-show="tab==='profil'" x-cloak>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-5" style="font-family:Outfit">Profil de l'entreprise</h2>
        <div class="card p-7">
          <div class="space-y-4">
            <div><label class="label">Description</label><textarea x-model="eprofil.description" class="input" rows="3" placeholder="Décrivez votre entreprise, vos valeurs..."></textarea></div>
            <div class="grid grid-cols-2 gap-4"><div><label class="label">Pays</label><input x-model="eprofil.pays" class="input text-sm"></div><div><label class="label">Ville</label><input x-model="eprofil.ville" class="input text-sm"></div></div>
            <div><label class="label">Téléphone</label><input x-model="eprofil.telephone" class="input text-sm"></div>
            <div><label class="label">Site web</label><input x-model="eprofil.siteWeb" type="url" class="input text-sm" placeholder="https://..."></div>
            <div><label class="label">Logo</label><input type="file" id="logo-f" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold" style="file:background:#fef9c3;file:color:#713f12"></div>
          </div>
          <button @click="saveProfil()" class="btn btn-gold mt-5 text-sm">Sauvegarder ✓</button>
        </div>
      </div>

    </div>
  </main>
</div>

<!-- Modal offre -->
<div id="om" class="hidden fixed inset-0 z-50" style="background:rgba(15,23,42,.6);backdrop-filter:blur(6px)" onclick="if(event.target===this)closeOM()">
  <div class="flex items-center justify-center min-h-full p-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-2xl shadow-2xl max-h-[92vh] overflow-y-auto animate-pop-in">
      <div class="flex justify-between mb-6"><h3 id="om-title" class="text-xl font-extrabold text-slate-800" style="font-family:Outfit">Publier une offre</h3><button onclick="closeOM()" class="text-slate-400 text-2xl">×</button></div>
      <div id="om-err" class="hidden mb-4 p-3 rounded-xl text-sm" style="background:#fef2f2;color:#dc2626"></div>
      <form id="om-form" onsubmit="submitOM(event)" class="space-y-4">
        <input type="hidden" id="om-id">
        <div><label class="label">Titre du poste *</label><input id="om-titre" class="input" required placeholder="Ex: Développeur Laravel Senior"></div>
        <div class="grid grid-cols-2 gap-4">
          <div><label class="label">Type de contrat *</label><select id="om-con" class="input" required><option value="">—</option><option>CDI</option><option>CDD</option><option>Stage</option><option>Freelance</option><option>Alternance</option></select></div>
          <div><label class="label">Localisation *</label><input id="om-loc" class="input" required placeholder="Ex: Tunis, Sfax..."></div>
        </div>
        <div class="grid grid-cols-3 gap-3">
          <div><label class="label">Domaine *</label><select id="om-dom" class="input" required><option value="">—</option></select></div>
          <div><label class="label">Catégorie *</label><select id="om-cat" class="input" required><option value="">—</option></select></div>
          <div><label class="label">Région *</label><select id="om-reg" class="input" required><option value="">—</option></select></div>
        </div>
        <div><label class="label">Description du poste *</label><textarea id="om-desc" class="input" rows="5" required placeholder="Décrivez le poste, les missions, l'environnement de travail..."></textarea></div>
        <div><label class="label">Compétences requises * <span class="font-normal text-slate-400">(séparées par des virgules)</span></label><input id="om-comp" class="input" required placeholder="Ex: PHP, Laravel, MySQL, Git..."></div>
        <div class="grid grid-cols-2 gap-4">
          <div><label class="label">Rémunération (TND/mois)</label><input id="om-rem" type="number" min="0" class="input" placeholder="Optionnel"></div>
          <div><label class="label">Date limite de candidature *</label><input id="om-date" type="date" class="input" required></div>
        </div>
        <div class="flex gap-3 pt-2"><button type="button" onclick="closeOM()" class="btn btn-secondary flex-1 justify-center">Annuler</button><button type="submit" id="om-btn" class="btn btn-gold flex-1 justify-center">Publier l'offre ✓</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
let _d=[],_c=[],_r=[];
(async()=>{
  const[d,c,r]=await Promise.all([API.get('/domaines'),API.get('/categories'),API.get('/regions')]);
  if(d?.ok)_d=await d.json();if(c?.ok)_c=await c.json();if(r?.ok)_r=await r.json();
  const fill=(sid,items,pk)=>{const s=document.getElementById(sid);items.forEach(x=>{const o=document.createElement('option');o.value=x[pk];o.textContent=x.nom;s.appendChild(o)})};
  fill('om-dom',_d,'idDomaine');fill('om-cat',_c,'idCategorie');fill('om-reg',_r,'idRegion');
})();
let _curOM=null;
function openOM(o=null){_curOM=o;document.getElementById('om-title').textContent=o?'Modifier l\'offre':'Publier une offre';document.getElementById('om-id').value=o?.idOffre||'';if(o){['om-titre','om-con','om-loc','om-dom','om-cat','om-reg','om-desc','om-comp','om-rem','om-date'].forEach(id=>{const map={...{}}});document.getElementById('om-titre').value=o.titre||'';document.getElementById('om-con').value=o.typeContrat||'';document.getElementById('om-loc').value=o.localisation||'';document.getElementById('om-dom').value=o.idDomaine||'';document.getElementById('om-cat').value=o.idCategorie||'';document.getElementById('om-reg').value=o.idRegion||'';document.getElementById('om-desc').value=o.description||'';document.getElementById('om-comp').value=o.competencesRequises||'';document.getElementById('om-rem').value=o.remuneration||'';document.getElementById('om-date').value=o.dateLimite||''}else document.getElementById('om-form').reset();document.getElementById('om').classList.remove('hidden')}
function closeOM(){document.getElementById('om').classList.add('hidden')}
async function submitOM(e){
  e.preventDefault();const btn=document.getElementById('om-btn');btn.textContent='Envoi...';btn.disabled=true;
  const errEl=document.getElementById('om-err');errEl.classList.add('hidden');
  const id=document.getElementById('om-id').value;
  const data={titre:document.getElementById('om-titre').value,typeContrat:document.getElementById('om-con').value,localisation:document.getElementById('om-loc').value,idDomaine:document.getElementById('om-dom').value,idCategorie:document.getElementById('om-cat').value,idRegion:document.getElementById('om-reg').value,description:document.getElementById('om-desc').value,competencesRequises:document.getElementById('om-comp').value,remuneration:document.getElementById('om-rem').value||null,dateLimite:document.getElementById('om-date').value};
  const r=id?await API.put('/entreprise/offres/'+id,data):await API.post('/entreprise/offres',data);
  const d=await r.json();
  if(!r.ok){const msgs=d.errors?Object.values(d.errors).flat():[d.message||'Erreur'];errEl.innerHTML=msgs.join('<br>');errEl.classList.remove('hidden');btn.textContent='Publier l\'offre ✓';btn.disabled=false;return}
  closeOM();toast(id?'Offre mise à jour ! ✓':'Offre publiée ! 🎉');setTimeout(()=>location.reload(),800);
}
function appE(){return{
  tab:'dashboard',ini:'',cname:'',stats:{},offres:[],cands:[],selOffre:null,filtE:'',convs:[],msgsE:[],activMsgC:null,newMsgE:'',eprofil:{},
  nav:[{id:'dashboard',label:'📊 Tableau de bord'},{id:'offres',label:'📋 Gérer les offres'},{id:'candidatures',label:'👥 Candidatures'},{id:'messages',label:'💬 Messages'},{id:'profil',label:'⚙️ Mon profil'}],
  get filteredE(){return this.filtE?this.cands.filter(c=>c.statut===this.filtE):this.cands},
  async init(){
    if(!API.isLogged()||API.type()!=='entreprise'){window.location.href='/connexion';return}
    const u=API.user();if(u){this.cname=u.raisonSociale||'';this.ini=(u.raisonSociale||'?').substring(0,2).toUpperCase();this.eprofil={...u}}
    await Promise.all([this.loadStats(),this.loadOffres(),this.loadConvs()]);
  },
  async loadStats(){const r=await API.get('/entreprise/dashboard');if(r?.ok)this.stats=await r.json()},
  async loadOffres(){const r=await API.get('/entreprise/offres');if(r?.ok)this.offres=await r.json()},
  async loadConvs(){const r=await API.get('/entreprise/messages');if(r?.ok)this.convs=await r.json()},
  openOM(o=null){window.openOM(o)},
  async voirC(o){this.selOffre=o;const r=await API.get('/entreprise/offres/'+o.idOffre+'/candidatures');if(r?.ok)this.cands=await r.json();this.tab='candidatures'},
  async changeStatutC(id,statut){if(!statut)return;const r=await API.patch('/entreprise/candidatures/'+id+'/statut',{statut});if(r?.ok){const c=this.cands.find(x=>x.idCandidature===id);if(c)c.statut=statut;toast('Statut mis à jour !')}else toast('Erreur.','error')},
  async suspendre(o){const r=await API.patch('/entreprise/offres/'+o.idOffre+'/suspendre');if(r?.ok){const d=await r.json();o.statut=d.statut;toast(d.statut==='suspendue'?'Offre suspendue.':'Offre réactivée.','info')}},
  async delOffre(id){if(!confirm('Supprimer cette offre définitivement ?'))return;const r=await API.del('/entreprise/offres/'+id);if(r?.ok){this.offres=this.offres.filter(o=>o.idOffre!==id);toast('Offre supprimée.','info');await this.loadStats()}},
  msgCandidat(c){this.activMsgC=c;this.tab='messages'},
  async openConv(cv){this.activMsgC=cv.candidat;const r=await API.get('/entreprise/messages/'+cv.idCandidat);if(r?.ok)this.msgsE=await r.json();this.$nextTick(()=>{const el=document.getElementById('mce');if(el)el.scrollTop=el.scrollHeight})},
  async sendMsgE(){if(!this.newMsgE.trim()||!this.activMsgC)return;const r=await API.post('/entreprise/messages',{idCandidat:this.activMsgC.idCandidat,contenu:this.newMsgE});if(r?.ok){const m=await r.json();this.msgsE.push(m);this.newMsgE='';this.$nextTick(()=>{const el=document.getElementById('mce');if(el)el.scrollTop=el.scrollHeight})}},
  async saveProfil(){const fd=new FormData();['description','pays','ville','telephone','siteWeb'].forEach(k=>{if(this.eprofil[k])fd.append(k,this.eprofil[k])});const logo=document.getElementById('logo-f')?.files?.[0];if(logo)fd.append('logo',logo);const r=await API.post('/entreprise/profile',fd,true);if(r?.ok){const u=await r.json();localStorage.setItem('jp_usr',JSON.stringify(u));toast('Profil mis à jour ! ✓')}else toast('Erreur.','error')},
}}
</script>
@endpush
@endsection
'@ | Set-Content "resources\views\entreprise\dashboard.blade.php" -Encoding UTF8