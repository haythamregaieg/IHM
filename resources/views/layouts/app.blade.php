<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','JobPlatform')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
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
</style>
@stack('styles')
</head>
<body>
<!-- NAVBAR -->
<nav class="bg-white/95 backdrop-blur border-b border-slate-200 sticky top-0 z-40 shadow-sm transition-colors duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-17 min-h-[68px] gap-4">
    <a href="/" class="flex items-center gap-2.5 flex-shrink-0">
      <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#2B5CE6,#1a44c9)">
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      </div>
      <span style="font-family:Outfit,sans-serif;font-weight:700;font-size:1.15rem" class="text-slate-800">Job<span style="color:#2B5CE6">Platform</span></span>
    </a>
    <div id="nav-auth" class="flex items-center gap-2 md:gap-3"></div>
  </div>
</nav>
<!-- TOAST -->
<div id="toast" class="fixed top-5 right-5 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl text-white text-sm font-semibold shadow-2xl transition-all duration-400 translate-x-96 opacity-0 max-w-xs" style="min-width:220px">
  <span id="toast-icon" class="text-base"></span><span id="toast-txt"></span>
</div>
@yield('content')
<footer class="mt-20 bg-slate-900 text-slate-400 border-t border-slate-800 transition-colors duration-300">
  <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
    <div><div class="flex items-center gap-2 mb-3"><div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#2B5CE6"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><span class="font-bold text-white text-sm" style="font-family:Outfit">JobPlatform</span></div><p class="text-xs">La plateforme de recrutement #1 en Tunisie</p></div>
    <div><h4 class="text-white font-semibold text-sm mb-3">Candidats</h4><ul class="space-y-1.5 text-xs"><li><a href="/offres" class="hover:text-white">Rechercher des offres</a></li><li><a href="/inscription?type=candidat" class="hover:text-white">Créer un compte</a></li><li><a href="/mon-espace" class="hover:text-white">Mon espace</a></li></ul></div>
    <div><h4 class="text-white font-semibold text-sm mb-3">Entreprises</h4><ul class="space-y-1.5 text-xs"><li><a href="/inscription?type=entreprise" class="hover:text-white">Inscrire mon entreprise</a></li><li><a href="/recruteur" class="hover:text-white">Espace entreprises</a></li></ul></div>
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
// ── Notifications ────────────────────────────────────────────────────
window._notifShown = {};
window._notifList  = JSON.parse(localStorage.getItem('jp_notif_list') || '[]');

window._notifBadgeUpdate = function() {
  const badge = document.getElementById('notif-badge');
  if (!badge) return;
  const n = window._notifList.length;
  badge.textContent = n > 99 ? '99+' : n;
  badge.style.display = n > 0 ? 'flex' : 'none';
};

window.renderNotifDropdown = function() {
  const list  = document.getElementById('notif-list');
  const empty = document.getElementById('notif-empty');
  if (!list || !empty) return;
  const notifs = window._notifList;
  if (!notifs.length) {
    list.innerHTML = '';
    empty.style.display = 'block';
  } else {
    empty.style.display = 'none';
    list.innerHTML = notifs.map(n => `
      <a href="${n.href}" onclick="window.dismissNotif('${n.id}')" class="flex items-start gap-3 px-5 py-4 hover:bg-slate-50 transition-all group">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg flex-shrink-0" style="background:${n.color}18">${n.icon}</div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold text-slate-800 leading-tight truncate">${n.text}</p>
          <p class="text-xs text-slate-400 font-medium mt-0.5 truncate">${n.sub}</p>
          ${n.time ? `<p class="text-[10px] text-slate-300 font-bold mt-1 uppercase tracking-wide">${new Date(n.time).toLocaleString('fr-FR',{day:'2-digit',month:'short',hour:'2-digit',minute:'2-digit'})}</p>` : ''}
        </div>
        <button onclick="event.preventDefault();event.stopPropagation();window.dismissNotif('${n.id}')" class="opacity-0 group-hover:opacity-100 text-slate-300 hover:text-red-400 flex-shrink-0 text-xl leading-none transition-opacity">×</button>
      </a>`).join('');
  }
};

window.dismissNotif = function(id) {
  window._notifList = window._notifList.filter(n => n.id !== id);
  localStorage.setItem('jp_notif_list', JSON.stringify(window._notifList));
  window._notifBadgeUpdate();
  window.renderNotifDropdown();
};

window.clearNotifs = function() {
  window._notifList = [];
  localStorage.setItem('jp_notif_list', '[]');
  window._notifBadgeUpdate();
  window.renderNotifDropdown();
};

window.toggleNotifDropdown = function(e) {
  e && e.stopPropagation();
  const dd = document.getElementById('notif-dropdown');
  if (!dd) return;
  const open = dd.style.display !== 'none';
  dd.style.display = open ? 'none' : 'block';
  if (!open) window.renderNotifDropdown();
};

document.addEventListener('click', function(e) {
  const dd   = document.getElementById('notif-dropdown');
  const bell = document.getElementById('notif-bell');
  if (dd && bell && !bell.closest('#notif-wrap').contains(e.target)) {
    dd.style.display = 'none';
  }
});

window.checkNavNotifs = async function() {
  if (!API.isLogged()) return;
  const type = API.type();

  // ── Messages non lus ────────────────────────────────────────────
  const convEp = type === 'candidat' ? '/candidat/messages'
               : type === 'entreprise' ? '/entreprise/messages' : null;
  if (convEp) {
    try {
      const r = await API.get(convEp);
      if (r?.ok) {
        const convs = await r.json();
        
        // Supprimer les notifs de messages qui sont maintenant lus sur le serveur
        const unreadIds = convs.filter(c => {
          const fromOther = type === 'candidat' ? c.expediteur === 'entreprise' : c.expediteur === 'candidat';
          return !c.lu && fromOther;
        }).map(c => 'msg_' + c.idMessage);

        window._notifList = window._notifList.filter(n => !n.id.startsWith('msg_') || unreadIds.includes(n.id));

        convs.forEach(c => {
          const fromOther = type === 'candidat' ? c.expediteur === 'entreprise' : c.expediteur === 'candidat';
          if (!c.lu && fromOther) {
            const sender = type === 'candidat'
              ? (c.entreprise?.raison_sociale || 'Entreprise')
              : ((c.candidat?.prenom || '') + ' ' + (c.candidat?.nom || '')).trim();
            const id = 'msg_' + c.idMessage;
            if (!window._notifList.find(n => n.id === id)) {
              window._notifList.unshift({
                id, icon: '💬',
                color: type === 'entreprise' ? '#F6A623' : '#2B5CE6',
                text: `Message de ${sender || 'Inconnu'}`,
                sub: (c.contenu || '').substring(0, 60),
                href: type === 'candidat' ? `/mon-espace?tab=messages&id=${c.idEntreprise}` : `/recruteur?tab=messages&id=${c.idCandidat}`,
                time: c.dateEnvoi
              });
            }
          }
        });
      }
    } catch(e) {}
  }

  // ── Changements de statut candidature (candidat) ─────────────────
  if (type === 'candidat') {
    try {
      const r = await API.get('/candidat/candidatures');
      if (r?.ok) {
        const cands = await r.json();
        const stored = JSON.parse(localStorage.getItem('jp_cand_statuts') || 'null');
        const newStatuts = {};
        if (stored) {
          cands.forEach(c => {
            newStatuts[c.idCandidature] = c.statut;
            if (stored[c.idCandidature] !== undefined && stored[c.idCandidature] !== c.statut) {
              const key = 'stat_' + c.idCandidature + '_' + c.statut;
              const labels = {retenu:'Retenue ✅', refuse:'Refusée ❌', en_cours:"En cours d'examen 👁", en_attente:'En attente ⏳'};
              const label = labels[c.statut] || c.statut;
              const titre = (c.offre?.titre || 'votre candidature').substring(0, 40);
              if (!window._notifList.find(n => n.id === key)) {
                window._notifList.unshift({
                  id: key,
                  icon: c.statut === 'retenu' ? '✅' : c.statut === 'refuse' ? '❌' : '📋',
                  color: c.statut === 'retenu' ? '#16a34a' : c.statut === 'refuse' ? '#dc2626' : '#2B5CE6',
                  text: titre,
                  sub: `Statut : ${label}`,
                  href: '/mon-espace',
                  time: null
                });
              }
              if (!window._notifShown[key]) {
                window._notifShown[key] = true;
                toast(`"${titre}" — ${label}`, c.statut === 'retenu' ? 'success' : c.statut === 'refuse' ? 'error' : 'info');
              }
            }
          });
        } else {
          cands.forEach(c => { newStatuts[c.idCandidature] = c.statut; });
        }
        localStorage.setItem('jp_cand_statuts', JSON.stringify(newStatuts));
      }
    } catch(e) {}
  }

  window._notifList = window._notifList.slice(0, 30);
  localStorage.setItem('jp_notif_list', JSON.stringify(window._notifList));
  window._notifBadgeUpdate();
  if (document.getElementById('notif-dropdown')?.style.display !== 'none') {
    window.renderNotifDropdown();
  }
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
    const avatar = imgPath ? `<img src="/storage/${imgPath}" class="w-full h-full object-cover">` : init;

    nav.innerHTML=`
      <div id="notif-wrap" class="relative flex-shrink-0">
        <button onclick="window.toggleNotifDropdown(event)" id="notif-bell" title="Notifications"
                class="relative p-2 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all flex items-center justify-center">
          <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
          <span id="notif-badge" style="display:none;background:#ef4444"
                class="absolute -top-1 -right-1 w-5 h-5 text-white text-[10px] font-black rounded-full items-center justify-center leading-none">0</span>
        </button>

        <!-- Dropdown notifications -->
        <div id="notif-dropdown" style="display:none"
             class="absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-[200]">
          <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
            <span class="font-black text-slate-900 text-sm tracking-tight">Notifications</span>
            <button onclick="window.clearNotifs()" class="text-xs font-bold text-slate-400 hover:text-red-500 transition-colors">Tout effacer</button>
          </div>
          <div id="notif-list" class="max-h-[360px] overflow-y-auto divide-y divide-slate-50"></div>
          <div id="notif-empty" class="py-10 text-center">
            <div class="text-3xl mb-2">🔔</div>
            <p class="text-sm font-bold text-slate-400">Aucune notification</p>
            <p class="text-xs text-slate-300 mt-1">Vous êtes à jour !</p>
          </div>
          <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 text-center">
            <a href="${href}" onclick="document.getElementById('notif-dropdown').style.display='none'"
               class="text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors">Voir mon espace →</a>
          </div>
        </div>
      </div>
      <a href="${href}" class="flex items-center gap-2 px-3 py-2 rounded-xl border border-transparent hover:bg-slate-50 hover:border-slate-200 transition-all">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold overflow-hidden shadow-sm" style="background:${colors[type]}">${avatar}</div>
        <span class="font-semibold text-sm text-slate-700">${name.substring(0,20)}</span>
      </a>
      <button onclick="API.logout()" class="btn btn-ghost btn-sm border border-slate-200 hover:border-slate-300">Déconnexion</button>`;

    // Show badge for stored notifs immediately, then poll
    window._notifBadgeUpdate();
    window.checkNavNotifs();
    setInterval(window.checkNavNotifs, 30000);
  }else{
    nav.innerHTML=`<a href="/connexion" class="btn btn-secondary btn-sm border border-slate-200">Connexion</a><a href="/inscription" class="btn btn-primary btn-sm">S'inscrire</a>`;
  }
})();
</script>
@stack('scripts')
</body>
</html>
