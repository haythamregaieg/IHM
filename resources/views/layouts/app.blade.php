<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','JobPlatform')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
tailwind.config={theme:{extend:{fontFamily:{display:['Outfit','sans-serif'],body:['Inter','sans-serif']},colors:{brand:{50:'#f0f4ff',100:'#dce6ff',200:'#baccff',300:'#8aadff',400:'#5585ff',500:'#2B5CE6',600:'#1a44c9',700:'#1535a0',800:'#122d85',900:'#0d1f5c'},gold:{400:'#F6A623',500:'#E8950E'}},keyframes:{up:{from:{opacity:0,transform:'translateY(20px)'},to:{opacity:1,transform:'translateY(0)'}},pop:{from:{opacity:0,transform:'scale(.95)'},to:{opacity:1,transform:'scale(1)'}}},animation:{'fade-up':'up .5s ease both','pop-in':'pop .3s ease both'}}}}
</script>
<style>
*{-webkit-font-smoothing:antialiased;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#F8FAFC;color:#1e293b;overflow-x:hidden}
h1,h2,h3,h4,h5{font-family:'Outfit',sans-serif;letter-spacing:-0.02em}
[x-cloak]{display:none!important}

/* Animations */
@keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.animate-slide-up { animation: slideUp 0.4s ease-out forwards; }

/* Modern Cards */
.card{background:#fff;border:1px solid #f1f5f9;border-radius:24px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.02),0 2px 4px -2px rgba(0,0,0,0.02);transition:all .3s cubic-bezier(0.4, 0, 0.2, 1)}
.card-hover:hover{transform:translateY(-4px);box-shadow:0 20px 25px -5px rgba(0,0,0,0.05),0 8px 10px -6px rgba(0,0,0,0.05);border-color:#e2e8f0}

/* Glassmorphism */
.glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3); }

/* Modern Buttons */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.6rem;padding:.7rem 1.5rem;border-radius:14px;font-weight:500;font-size:.9rem;transition:all .2s cubic-bezier(0.4, 0, 0.2, 1);cursor:pointer;border:none;outline:none;white-space:nowrap}
.btn-primary{background:linear-gradient(135deg,#2B5CE6,#3b6cf0);color:#fff;box-shadow:0 4px 12px rgba(43,92,230,0.2)}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(43,92,230,0.3);filter:brightness(1.05)}
.btn-primary:active{transform:translateY(0)}

.btn-secondary{background:#fff;color:#475569;border:1px solid #e2e8f0;box-shadow:0 1px 2px 0 rgba(0,0,0,0.05)}
.btn-secondary:hover{background:#f8fafc;border-color:#cbd5e1;color:#1e293b;transform:translateY(-1px)}

.btn-gold{background:linear-gradient(135deg,#F6A623,#fbbf24);color:#fff;box-shadow:0 4px 12px rgba(246,166,35,0.2)}
.btn-gold:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(246,166,35,0.3)}

.btn-ghost{background:transparent;color:#64748b}
.btn-ghost:hover{background:#f1f5f9;color:#1e293b}

.btn-sm{padding:.5rem 1rem;font-size:.8rem;border-radius:12px}
.btn-lg{padding:1rem 2rem;font-size:1rem;border-radius:18px}

/* Inputs */
.input{width:100%;padding:.85rem 1.25rem;border:1.5px solid #f1f5f9;border-radius:16px;font-size:.95rem;transition:all .2s ease;background:#fff;color:#1e293b}
.input:focus{border-color:#2B5CE6;box-shadow:0 0 0 4px rgba(43,92,230,0.1);background:#fff;outline:none}
.input::placeholder{color:#94a3b8}

.label{display:block;font-size:.75rem;font-weight:700;color:#64748b;margin-bottom:.5rem;letter-spacing:.025em;text-transform:uppercase}

/* Badges */
.badge{display:inline-flex;align-items:center;padding:.35rem .85rem;border-radius:12px;font-size:.75rem;font-weight:700;letter-spacing:0.01em}
.badge-blue{background:#eff6ff;color:#2B5CE6}
.badge-green{background:#f0fdf4;color:#16a34a}
.badge-amber{background:#fffbeb;color:#d97706}
.badge-red{background:#fef2f2;color:#dc2626}

/* Sidebar */
.sidebar{background:#fff;border-right:1px solid #f1f5f9;width:280px;padding:2rem 1.25rem}
.sidebar-link{display:flex;align-items:center;gap:.85rem;padding:.85rem 1.25rem;border-radius:16px;font-weight:600;font-size:.925rem;color:#64748b;transition:all .2s ease}
.sidebar-link:hover{background:#f8fafc;color:#1e293b;transform:translateX(4px)}
.sidebar-link.active{background:linear-gradient(135deg,#2B5CE6,#1a44c9);color:#fff;box-shadow:0 10px 15px -3px rgba(43,92,230,0.3)}

/* Scrollbar */
::-webkit-scrollbar{width:8px}
::-webkit-scrollbar-track{background:#f8fafc}
::-webkit-scrollbar-thumb{background:#e2e8f0;border-radius:10px}
::-webkit-scrollbar-thumb:hover{background:#cbd5e1}

/* Grid backgrounds */
.bg-grid { background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 32px 32px; }

/* Dark Forms Theme */
.card-dark { background: #0f172a!important; border-color: #1e293b!important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5)!important; }
.card-dark .label { color: #94a3b8!important; }
.card-dark .input { background: #020617!important; border-color: #1e293b!important; color: #f8fafc!important; }
.card-dark .input:focus { border-color: #2B5CE6!important; box-shadow: 0 0 0 4px rgba(43,92,230,0.2)!important; }
.card-dark h1, .card-dark h2, .card-dark h3 { color: #fff!important; }
.card-dark p { color: #94a3b8!important; }
.card-dark .border-t { border-color: #1e293b!important; }
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
      <span style="font-family:Outfit,sans-serif;font-weight:700;font-size:1.15rem" class="text-slate-800">Job<span style="color:#2B5CE6">Platform</span></span>
    </a>
    <div class="hidden md:flex items-center gap-6">
      <a href="/offres" class="nav-link">Offres d'emploi</a>
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
    <div><h4 class="text-white font-semibold text-sm mb-3">Candidats</h4><ul class="space-y-1.5 text-xs"><li><a href="/offres" class="hover:text-white">Rechercher des offres</a></li><li><a href="/inscription?type=candidat" class="hover:text-white">Créer un compte</a></li><li><a href="/mon-espace" class="hover:text-white">Mon espace</a></li></ul></div>
    <div><h4 class="text-white font-semibold text-sm mb-3">Recruteurs</h4><ul class="space-y-1.5 text-xs"><li><a href="/inscription?type=entreprise" class="hover:text-white">Inscrire mon entreprise</a></li><li><a href="/recruteur" class="hover:text-white">Espace recruteur</a></li></ul></div>
    <div><h4 class="text-white font-semibold text-sm mb-3">Contact</h4><ul class="space-y-1.5 text-xs"><li>contact@jobplatform.tn</li><li>+216 71 000 000</li><li>Tunis, Tunisie</li></ul></div>
  </div>
  <div class="border-t border-slate-800 py-4 text-center text-xs">© 2026 JobPlatform — Tous droits réservés</div>
</footer>
<script>
// ── API ─────────────────────────────────────────────────────────────
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
    if(ep && this.token()){
      fetch(this.base+ep,{method:'POST',headers:{'Authorization':'Bearer '+this.token(),'Accept':'application/json'}});
    }
    ['jp_tok','jp_type','jp_usr'].forEach(k=>localStorage.removeItem(k));
    window.location.href='/connexion';
  }
};
// ── Toast ────────────────────────────────────────────────────────────
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
// ── Navbar ───────────────────────────────────────────────────────────
(function(){
  const nav=document.getElementById('nav-auth');
  if(!nav)return;
  const u=API.user();const type=API.type();
  if(API.isLogged()&&u){
    const name=type==='admin'?'Admin':type==='candidat'?((u.prenom||'')+(u.nom?' '+u.nom:'')):(u.raison_sociale||'Entreprise');
    const href=type==='admin'?'/admin':type==='candidat'?'/mon-espace':'/recruteur';
    const init=name.substring(0,2).toUpperCase();
    const imgPath = type==='candidat' ? u.photo_path : (type==='entreprise' ? u.logo_path : null);
    const colors={admin:'#dc2626',candidat:'#2B5CE6',entreprise:'#F6A623'};
    
    const avatar = imgPath 
      ? `<img src="/storage/${imgPath}" class="w-full h-full object-cover">`
      : init;

    nav.innerHTML=`<a href="${href}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-slate-50"><div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold overflow-hidden" style="background:${colors[type]}">${avatar}</div><span class="font-semibold text-sm text-slate-700">${name.substring(0,20)}</span></a><button onclick="API.logout()" class="btn btn-ghost btn-sm">Déconnexion</button>`;
  }else{
    nav.innerHTML=`<a href="/connexion" class="btn btn-secondary btn-sm">Connexion</a><a href="/inscription" class="btn btn-primary btn-sm">S'inscrire</a>`;
  }
})();
</script>
@stack('scripts')
</body>
</html>
