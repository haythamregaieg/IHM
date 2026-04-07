@extends('layouts.app')
@section('title',"Inscription Entreprise — JobPlatform")
@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-16 bg-[#F8FAFC]" style="background-image: radial-gradient(#E2E8F0 1.5px, transparent 1.5px); background-size: 32px 32px;">
  <div class="w-full max-w-2xl animate-slide-up">
    <!-- Header -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-[28px] bg-gradient-to-br from-amber-500 to-amber-700 shadow-2xl shadow-amber-500/30 mb-6 group hover:scale-110 transition-transform duration-500">
        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
      </div>
      <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2" style="font-family:Outfit">Espace <span class="text-amber-500">Recruteur</span> 🏢</h1>
      <p class="text-slate-500 font-medium">Inscrivez votre entreprise et trouvez vos futurs collaborateurs.</p>
    </div>

    <!-- Registration Card -->
    <div class="card card-dark p-10 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"></div>
      
      <div class="relative z-10">
        <div class="mb-8 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex gap-4 items-start">
          <span class="text-2xl">ℹ️</span>
          <p class="text-sm font-bold text-amber-200 leading-relaxed">
            Pour garantir la qualité de notre plateforme, chaque compte entreprise est soumis à une validation manuelle par nos administrateurs.
          </p>
        </div>

        <div id="err" class="hidden mb-6 p-4 rounded-2xl text-sm font-bold border border-red-100 bg-red-50 text-red-600 animate-shake"></div>
        
        <form onsubmit="doReg(event)" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Raison Sociale *</label>
              <input id="e-rs" type="text" class="input h-14 font-medium" required placeholder="Nom de l'entreprise">
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Identifiant Légal *</label>
              <input id="e-il" type="text" class="input h-14 font-medium" required placeholder="MF / SIRET / Registre">
            </div>
          </div>

          <div class="space-y-2">
            <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Description courte</label>
            <textarea id="e-desc" class="input p-4 min-h-[100px] font-medium" placeholder="Décrivez brièvement votre activité..."></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Secteur d'activité</label>
              <select id="e-dom" class="input h-14 font-medium appearance-none cursor-pointer">
                <option value="">Sélectionner un secteur</option>
              </select>
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Téléphone</label>
              <input id="e-tel" type="tel" class="input h-14 font-medium" placeholder="+216 71 000 000">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Pays</label>
              <input id="e-pays" type="text" class="input h-14 font-medium" value="Tunisie">
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Ville</label>
              <input id="e-ville" type="text" class="input h-14 font-medium" placeholder="Ex: Tunis">
            </div>
          </div>

          <div class="space-y-2">
            <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Site Web</label>
            <input id="e-site" type="url" class="input h-14 font-medium" placeholder="https://www.votre-entreprise.tn">
          </div>

          <div class="space-y-2">
            <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Email Professionnel *</label>
            <input id="e-email" type="email" class="input h-14 font-medium" required placeholder="contact@entreprise.tn">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Mot de passe *</label>
              <input id="e-pwd" name="password" type="password" class="input h-14 font-medium" required minlength="8" placeholder="••••••••" autocomplete="new-password">
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Confirmation *</label>
              <input id="e-pwdc" name="password_confirmation" type="password" class="input h-14 font-medium" required placeholder="••••••••" autocomplete="new-password">
            </div>
          </div>

          <button type="submit" id="e-btn" class="btn btn-gold btn-lg w-full justify-center h-14 text-lg shadow-xl shadow-amber-500/30 mt-4">
            Soumettre ma demande <span class="ml-2">→</span>
          </button>
        </form>

        <div class="mt-10 pt-8 border-t border-slate-50 text-center">
          <p class="text-sm font-medium text-slate-500">
            Déjà inscrit ? 
            <a href="/connexion" class="font-black text-brand-600 hover:text-brand-700 ml-1 transition-colors">Se connecter</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Load domains
(async()=>{
  const res = await API.get('/domaines');
  if(res?.ok){
    const domains = await res.json();
    const select = document.getElementById('e-dom');
    domains.forEach(d => {
      const opt = document.createElement('option');
      opt.value = d.idDomaine;
      opt.textContent = d.nom;
      select.appendChild(opt);
    });
  }
})();

async function doReg(e){
  e.preventDefault();
  const btn = document.getElementById('e-btn');
  const errEl = document.getElementById('err');
  
  btn.disabled = true;
  btn.innerHTML = '<span class="animate-spin mr-3">⏳</span> Envoi...';
  errEl.classList.add('hidden');
  
  try {
    const res = await API.post('/entreprise/register', {
      raisonSociale: document.getElementById('e-rs').value,
      identifiantLegal: document.getElementById('e-il').value,
      description: document.getElementById('e-desc').value || null,
      idDomaine: document.getElementById('e-dom').value || null,
      telephone: document.getElementById('e-tel').value || null,
      pays: document.getElementById('e-pays').value || null,
      ville: document.getElementById('e-ville').value || null,
      siteWeb: document.getElementById('e-site').value || null,
      email: document.getElementById('e-email').value,
      motDePasse: document.getElementById('e-pwd').value,
      motDePasse_confirmation: document.getElementById('e-pwdc').value
    });
    
    const data = await res.json();
    if(!res.ok){
      const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Erreur lors de l\'inscription.'];
      errEl.innerHTML = msgs.join('<br>');
      errEl.classList.remove('hidden');
      return;
    }
    
    toast('Demande envoyée ! Vous serez averti après validation.');
    setTimeout(() => window.location.href = '/connexion?msg=pending&type=entreprise', 1500);
    
  } catch(err) {
    errEl.textContent = 'Une erreur réseau est survenue. Veuillez réessayer.';
    errEl.classList.remove('hidden');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Soumettre ma demande →';
  }
}
</script>
@endpush
@endsection
