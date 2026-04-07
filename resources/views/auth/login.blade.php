@extends('layouts.app')
@section('title','Connexion — JobPlatform')
@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-16 bg-[#F8FAFC]" style="background-image: radial-gradient(#E2E8F0 1.5px, transparent 1.5px); background-size: 32px 32px;">
  <div class="w-full max-w-[440px] animate-slide-up">
    <!-- Logo & Header -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-[28px] bg-gradient-to-br from-brand-600 to-brand-800 shadow-2xl shadow-brand-500/30 mb-6 group hover:scale-110 transition-transform duration-500">
        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
      </div>
      <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2" style="font-family:Outfit">Bon retour ! 👋</h1>
      <p class="text-slate-500 font-medium">Connectez-vous pour accéder à votre espace personnalisé.</p>
    </div>

    <!-- Role Selector Tabs -->
    <div class="flex p-1.5 bg-white rounded-2xl shadow-sm border border-slate-100 mb-8" id="role-tabs">
      <button onclick="switchTab('candidat')" id="tab-candidat" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-black transition-all bg-brand-600 text-white shadow-lg shadow-brand-500/20">
        <span>👤</span> Candidat
      </button>
      <button onclick="switchTab('entreprise')" id="tab-entreprise" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-black transition-all text-slate-500 hover:bg-slate-50">
        <span>🏢</span> Entreprise
      </button>
      <button onclick="switchTab('admin')" id="tab-admin" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-black transition-all text-slate-500 hover:bg-slate-50">
        <span>⚙️</span> Admin
      </button>
    </div>

    <!-- Login Card -->
    <div class="card card-dark p-10 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"></div>
      
      <div class="relative z-10">
        <div id="err" class="hidden mb-6 p-4 rounded-2xl text-sm font-bold border border-red-100 bg-red-50 text-red-600 animate-shake"></div>
        
        <form id="lf" onsubmit="doLogin(event)" class="space-y-6">
          <div class="space-y-2">
            <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Adresse Email</label>
            <div class="relative group">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-500 transition-colors">✉️</span>
              <input id="l-email" type="email" class="input pl-12 h-14 font-medium" placeholder="nom@exemple.com" required autocomplete="email">
            </div>
          </div>
          
          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Mot de passe</label>
              <a href="/forgot-password" class="text-xs font-bold text-brand-600 hover:underline">Oublié ?</a>
            </div>
            <div class="relative group">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-500 transition-colors">🔒</span>
              <input id="l-pwd" name="password" type="password" class="input pl-12 pr-12 h-14 font-medium" placeholder="••••••••" required autocomplete="current-password">
              <button type="button" onclick="togglePwd(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors">👁️</button>
            </div>
          </div>

          <div class="flex items-center gap-3 py-2">
            <input type="checkbox" id="remember" class="w-5 h-5 rounded-lg border-slate-200 text-brand-600 focus:ring-brand-500 cursor-pointer">
            <label for="remember" class="text-sm font-bold text-slate-500 cursor-pointer">Se souvenir de moi</label>
          </div>

          <button type="submit" id="l-btn" class="btn btn-primary btn-lg w-full justify-center h-14 text-lg shadow-xl shadow-brand-500/30">
            Se connecter <span class="ml-2">→</span>
          </button>
        </form>

        <div class="mt-10 pt-8 border-t border-slate-50 text-center">
          <p id="register-hint" class="text-sm font-medium text-slate-500">
            Nouveau sur JobPlatform ? 
            <a href="/inscription/candidat" class="font-black text-brand-600 hover:text-brand-700 ml-1 transition-colors">Créer un compte</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
let cType = 'candidat';

function togglePwd(btn) {
  const input = btn.previousElementSibling;
  input.type = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁️' : '🙈';
}

function switchTab(t) {
  cType = t;
  const roles = ['candidat', 'entreprise', 'admin'];
  roles.forEach(role => {
    const btn = document.getElementById('tab-' + role);
    if (role === t) {
      btn.className = "flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-black transition-all bg-brand-600 text-white shadow-lg shadow-brand-500/20";
    } else {
      btn.className = "flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-black transition-all text-slate-500 hover:bg-slate-50";
    }
  });

  const hint = document.getElementById('register-hint');
  const btn = document.getElementById('l-btn');
  
  if (t === 'candidat') {
    hint.innerHTML = 'Nouveau sur JobPlatform ? <a href="/inscription/candidat" class="font-black text-brand-600 hover:text-brand-700 ml-1 transition-colors">Créer un compte</a>';
    btn.className = "btn btn-primary btn-lg w-full justify-center h-14 text-lg shadow-xl shadow-brand-500/30";
  } else if (t === 'entreprise') {
    hint.innerHTML = 'Recruteur ? <a href="/inscription/entreprise" class="font-black text-amber-600 hover:text-amber-700 ml-1 transition-colors">Inscrire mon entreprise</a>';
    btn.className = "btn btn-gold btn-lg w-full justify-center h-14 text-lg shadow-xl shadow-amber-500/30";
  } else {
    hint.innerHTML = '';
    btn.className = "btn btn-secondary btn-lg w-full justify-center h-14 text-lg shadow-xl shadow-slate-200/50";
  }
}

async function doLogin(e) {
  e.preventDefault();
  const btn = document.getElementById('l-btn');
  const errEl = document.getElementById('err');
  
  btn.disabled = true;
  btn.innerHTML = '<span class="animate-spin mr-3">⏳</span> Connexion...';
  errEl.classList.add('hidden');
  
  const ep = {admin:'/admin/login', candidat:'/candidat/login', entreprise:'/entreprise/login'}[cType];
  
  try {
    const res = await API.post(ep, {
      email: document.getElementById('l-email').value,
      motDePasse: document.getElementById('l-pwd').value
    });
    const data = await res.json();
    
    if (!res.ok) {
      let msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Identifiants incorrects.');
      errEl.textContent = msg;
      errEl.classList.remove('hidden');
      return;
    }
    
    const key = {admin:'admin', candidat:'candidat', entreprise:'entreprise'}[cType];
    API.save(data.token, cType, data[key]);
    toast('Heureux de vous revoir ! 🎉');
    
    setTimeout(() => {
      window.location.href = {admin:'/admin', candidat:'/mon-espace', entreprise:'/recruteur'}[cType];
    }, 800);
    
  } catch (err) {
    console.error(err);
    errEl.textContent = 'Une erreur réseau est survenue. Veuillez réessayer.';
    errEl.classList.remove('hidden');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Se connecter →';
  }
}

// Auto-check for pending status
const urlParams = new URLSearchParams(location.search);
if (urlParams.get('msg') === 'pending') {
  const err = document.getElementById('err');
  err.innerHTML = '<span>ℹ️</span> Votre compte entreprise est en attente de validation par un administrateur.';
  err.className = 'mb-6 p-4 rounded-2xl text-sm font-bold border border-amber-100 bg-amber-50 text-amber-600 flex items-center gap-3';
  err.classList.remove('hidden');
}

// Auto-switch tab based on type parameter
if (urlParams.get('type')) {
  switchTab(urlParams.get('type'));
}
</script>
@endpush
@endsection
