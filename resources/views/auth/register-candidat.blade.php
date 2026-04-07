@extends('layouts.app')
@section('title',"Inscription Candidat — JobPlatform")
@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-16 bg-[#F8FAFC]" style="background-image: radial-gradient(#E2E8F0 1.5px, transparent 1.5px); background-size: 32px 32px;">
  <div class="w-full max-w-2xl animate-slide-up">
    <!-- Header -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-[28px] bg-gradient-to-br from-brand-600 to-brand-800 shadow-2xl shadow-brand-500/30 mb-6 group hover:scale-110 transition-transform duration-500">
        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
      </div>
      <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2" style="font-family:Outfit">Rejoignez-nous ! 🚀</h1>
      <p class="text-slate-500 font-medium">Créez votre profil candidat et accédez aux meilleures opportunités.</p>
    </div>

    <!-- Registration Card -->
    <div class="card card-dark p-10 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"></div>
      
      <div class="relative z-10">
        <div id="err" class="hidden mb-6 p-4 rounded-2xl text-sm font-bold border border-red-100 bg-red-50 text-red-600 animate-shake"></div>
        
        <form onsubmit="doRegister(event)" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Prénom *</label>
              <input id="r-prenom" type="text" class="input h-14 font-medium" required placeholder="Ex: Ahmed">
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Nom *</label>
              <input id="r-nom" type="text" class="input h-14 font-medium" required placeholder="Ex: Ben Ali">
            </div>
          </div>

          <div class="space-y-2">
            <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Adresse Email *</label>
            <input id="r-email" type="email" class="input h-14 font-medium" required placeholder="votre@email.com">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Domaine d'activité</label>
              <select id="r-domaine" class="input h-14 font-medium appearance-none cursor-pointer">
                <option value="">Sélectionner un domaine</option>
              </select>
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Spécialité</label>
              <input id="r-specialite" type="text" class="input h-14 font-medium" placeholder="Ex: Développeur PHP">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Niveau d'études</label>
              <select id="r-etude" class="input h-14 font-medium appearance-none cursor-pointer">
                <option value="">Niveau d'études</option>
                <option>Baccalauréat</option>
                <option>Licence / Bachelor</option>
                <option>Master / Ingénieur</option>
                <option>Doctorat</option>
                <option>Formation Pro</option>
              </select>
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Expérience</label>
              <select id="r-exp" class="input h-14 font-medium appearance-none cursor-pointer">
                <option value="">Expérience</option>
                <option>Débutant (0 an)</option>
                <option>Junior (1-3 ans)</option>
                <option>Confirmé (3-5 ans)</option>
                <option>Senior (5-10 ans)</option>
                <option>Expert (+10 ans)</option>
              </select>
            </div>
          </div>

          <div class="space-y-2">
            <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Localisation</label>
            <input id="r-localisation" type="text" class="input h-14 font-medium" placeholder="Ex: Tunis, Sfax...">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Mot de passe *</label>
              <input id="r-pwd" name="password" type="password" class="input h-14 font-medium" required minlength="8" placeholder="••••••••" autocomplete="new-password">
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Confirmation *</label>
              <input id="r-pwdc" name="password_confirmation" type="password" class="input h-14 font-medium" required placeholder="••••••••" autocomplete="new-password">
            </div>
          </div>

          <div class="flex items-start gap-3 py-2">
            <input type="checkbox" id="terms" required class="mt-1 w-5 h-5 rounded-lg border-slate-200 text-brand-600 focus:ring-brand-500 cursor-pointer">
            <label for="terms" class="text-sm font-medium text-slate-500 leading-relaxed cursor-pointer">
              J'accepte les <a href="#" class="font-bold text-brand-600 hover:underline">Conditions d'Utilisation</a> et la <a href="#" class="font-bold text-brand-600 hover:underline">Politique de Confidentialité</a>.
            </label>
          </div>

          <button type="submit" id="r-btn" class="btn btn-primary btn-lg w-full justify-center h-14 text-lg shadow-xl shadow-brand-500/30">
            Créer mon compte <span class="ml-2">→</span>
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
    const select = document.getElementById('r-domaine');
    domains.forEach(d => {
      const opt = document.createElement('option');
      opt.value = d.idDomaine;
      opt.textContent = d.nom;
      select.appendChild(opt);
    });
  }
})();

async function doRegister(e){
  e.preventDefault();
  const btn = document.getElementById('r-btn');
  const errEl = document.getElementById('err');
  
  const pwd = document.getElementById('r-pwd').value;
  const pwdc = document.getElementById('r-pwdc').value;
  
  if(pwd !== pwdc){
    errEl.textContent = 'Les mots de passe ne correspondent pas.';
    errEl.classList.remove('hidden');
    return;
  }
  
  btn.disabled = true;
  btn.innerHTML = '<span class="animate-spin mr-3">⏳</span> Création...';
  errEl.classList.add('hidden');
  
  try {
    const res = await API.post('/candidat/register', {
      nom: document.getElementById('r-nom').value,
      prenom: document.getElementById('r-prenom').value,
      email: document.getElementById('r-email').value,
      idDomaine: document.getElementById('r-domaine').value || null,
      specialite: document.getElementById('r-specialite').value || null,
      niveauEtude: document.getElementById('r-etude').value || null,
      niveauExperience: document.getElementById('r-exp').value || null,
      localisation: document.getElementById('r-localisation').value || null,
      motDePasse: pwd,
      motDePasse_confirmation: pwdc
    });
    
    const data = await res.json();
    if(!res.ok){
      const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Erreur lors de l\'inscription.'];
      errEl.innerHTML = msgs.join('<br>');
      errEl.classList.remove('hidden');
      return;
    }
    
    API.save(data.token, 'candidat', data.candidat);
    toast('Bienvenue sur JobPlatform ! 🎉');
    setTimeout(() => window.location.href = '/mon-espace', 800);
    
  } catch(err) {
    errEl.textContent = 'Une erreur réseau est survenue. Veuillez réessayer.';
    errEl.classList.remove('hidden');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Créer mon compte →';
  }
}
</script>
@endpush
@endsection
