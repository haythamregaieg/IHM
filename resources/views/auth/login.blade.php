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
      <h1 class="text-4xl font-bold text-slate-900 tracking-tight mb-2" style="font-family:Outfit">Bon retour</h1>
      <p class="text-slate-500 font-medium">Connectez-vous pour accéder à votre espace personnalisé.</p>
    </div>

    <!-- Role Selector Tabs (REMOVED) -->

    <!-- Login Card -->
    <div class="card p-8 md:p-10 relative overflow-hidden border border-brand-300 bg-brand-200/75">
      <div class="absolute top-0 right-0 w-32 h-32 bg-brand-100 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"></div>
      
      <div class="relative z-10">
        <div id="err" role="alert" aria-live="assertive" class="hidden mb-6 p-4 rounded-2xl text-sm font-bold border border-red-100 bg-red-50 text-red-600 animate-shake"></div>
        
        <form id="lf" onsubmit="doLogin(event)" class="space-y-6">
          <div class="rounded-2xl border border-brand-300 bg-brand-100/70 p-4 space-y-5">
            <div class="text-[11px] font-black uppercase tracking-widest text-slate-400"></div>
          <div class="space-y-2">
            <label class="label text-xs font-bold uppercase tracking-widest text-slate-700">Adresse Email</label>
            <div class="relative group">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-500 transition-colors">✉️</span>
              <input id="l-email" type="email" class="input pl-12 h-14 font-medium" placeholder="nom@exemple.com" required autocomplete="email">
            </div>
          </div>
          
          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <label class="label text-xs font-bold uppercase tracking-widest text-slate-700">Mot de passe</label>
            </div>
            <div class="relative group">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand-500 transition-colors">🔒</span>
              <input id="l-pwd" name="password" type="password" class="input pl-12 pr-12 h-14 font-medium" placeholder="••••••••" required autocomplete="current-password">
              <button type="button" onclick="togglePwd(this)" aria-label="Afficher le mot de passe" class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-500 hover:text-brand-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/>
                </svg>
              </button>
            </div>
          </div>
          </div>

          <div class="flex justify-center">
            <button type="submit" id="l-btn" class="btn btn-primary btn-lg w-full sm:w-auto sm:min-w-[260px] justify-center h-14 text-lg shadow-xl shadow-brand-500/30">
              Se connecter <span class="ml-2">→</span>
            </button>
          </div>
        </form>

        <!-- Social login -->
        <div class="mt-8">
          <div class="flex items-center gap-4 mb-6">
            <div class="flex-1 h-px bg-slate-100"></div>
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">ou continuer avec</span>
            <div class="flex-1 h-px bg-slate-100"></div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <button type="button" onclick="alert('Connexion Google bientôt disponible.')"
                    class="flex items-center justify-center gap-2.5 px-4 py-3 rounded-2xl border-2 border-slate-100 bg-white hover:bg-slate-50 hover:border-slate-200 active:scale-95 transition-all font-bold text-sm text-slate-700 shadow-sm">
              <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              Google
            </button>
            <button type="button" onclick="alert('Connexion LinkedIn bientôt disponible.')"
                    class="flex items-center justify-center gap-2.5 px-4 py-3 rounded-2xl border-2 border-[#0A66C2]/20 bg-white hover:bg-[#0A66C2]/5 hover:border-[#0A66C2]/30 active:scale-95 transition-all font-bold text-sm text-[#0A66C2] shadow-sm">
              <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="#0A66C2">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
              LinkedIn
            </button>
          </div>
        </div>

        <div class="mt-8 pt-8 border-t border-slate-50 text-center">
          <p id="register-hint" class="text-sm font-medium text-slate-500">
            Nouveau sur JobPlatform ?
            <a href="/inscription?type=candidat" class="font-bold text-brand-600 hover:text-brand-700 ml-1 transition-colors">Créer un compte</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function togglePwd(btn) {
  const input = btn.previousElementSibling;
  input.type = input.type === 'password' ? 'text' : 'password';
  const isVisible = input.type === 'text';
  btn.setAttribute('aria-label', isVisible ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
  btn.innerHTML = isVisible
    ? '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.27-2.943-9.543-7a9.97 9.97 0 012.19-3.568m3.1-2.373A9.956 9.956 0 0112 5c4.478 0 8.27 2.943 9.543 7a9.965 9.965 0 01-4.132 5.411M15 12a3 3 0 00-3-3m0 0a2.99 2.99 0 00-2.121.879M12 9l0 0m0 0L4 20"/></svg>'
    : '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/></svg>';
}

async function doLogin(e) {
  e.preventDefault();
  const btn = document.getElementById('l-btn');
  const errEl = document.getElementById('err');
  
  btn.disabled = true;
  btn.innerHTML = '<span class="animate-spin mr-3">⏳</span> Connexion...';
  errEl.classList.add('hidden');
  
  try {
    const res = await API.post('/auth/login', {
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
    
    const type = data.type;
    API.save(data.token, type, data.user);
    toast('Heureux de vous revoir ! 🎉');
    
    const redirectParam = new URLSearchParams(location.search).get('redirect');
    const safeRedirect = redirectParam && redirectParam.startsWith('/') ? redirectParam : null;
    const storedRedirect = localStorage.getItem('login_redirect_after_auth');
    const finalRedirect = safeRedirect || (storedRedirect && storedRedirect.startsWith('/') ? storedRedirect : null);

    if (storedRedirect) localStorage.removeItem('login_redirect_after_auth');

    setTimeout(() => {
      window.location.href = finalRedirect || {admin:'/admin', candidat:'/mon-espace', entreprise:'/recruteur'}[type];
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

// Redirect if already logged in
if (API.isLogged()) {
  const _t = API.type();
  window.location.replace({admin:'/admin', candidat:'/mon-espace', entreprise:'/recruteur'}[_t] || '/');
}

// Auto-check for pending status
const urlParams = new URLSearchParams(location.search);
if (urlParams.get('msg') === 'pending') {
  const err = document.getElementById('err');
  err.innerHTML = '<span>ℹ️</span> Votre compte entreprise est en attente de validation par un administrateur.';
  err.className = 'mb-6 p-4 rounded-2xl text-sm font-bold border border-amber-100 bg-amber-50 text-amber-600 flex items-center gap-3';
  err.classList.remove('hidden');
}
</script>
@endpush
@endsection
