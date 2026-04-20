@extends('layouts.app')
@section('title',"Inscription — JobPlatform")
@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-16 bg-[#F8FAFC]" 
     style="background-image: radial-gradient(#E2E8F0 1.5px, transparent 1.5px); background-size: 32px 32px;"
     x-data="registerApp()" x-init="init()">
  <div class="w-full max-w-2xl animate-slide-up">
    <!-- Header -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-[28px] shadow-2xl mb-6 group hover:scale-110 transition-transform duration-500"
           :class="isCandidat ? 'bg-gradient-to-br from-brand-600 to-brand-800 shadow-brand-500/30' : 'bg-gradient-to-br from-slate-700 to-slate-900 shadow-slate-800/30'">
        <template x-if="isCandidat">
          <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
        </template>
        <template x-if="!isCandidat">
          <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
        </template>
      </div>
      <h1 class="text-4xl font-bold text-slate-900 tracking-tight mb-2" style="font-family:Outfit">
        <span x-text="isCandidat ? 'Créer un compte candidat' : 'Créer un compte entreprise'"></span>
      </h1>
      <p class="text-slate-500 font-medium" x-text="isCandidat ? 'Créez votre profil candidat et accédez aux meilleures opportunités.' : 'Inscrivez votre entreprise et trouvez vos futurs collaborateurs.'"></p>
    </div>

    <!-- Toggle Switch -->
    <div class="flex p-1.5 bg-white rounded-2xl shadow-sm border border-slate-100 mb-8">
      <button @click="isCandidat = true" 
              :class="isCandidat ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/20' : 'text-slate-500 hover:bg-slate-50'"
              class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold transition-all">
        <span>👤</span> Candidat
      </button>
      <button @click="isCandidat = false" 
              :class="!isCandidat ? 'bg-slate-800 text-white shadow-lg shadow-slate-800/25' : 'text-slate-500 hover:bg-slate-50'"
              class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold transition-all">
        <span>🏢</span> Entreprise
      </button>
    </div>

    <!-- Registration Card -->
    <div class="card p-8 md:p-10 relative overflow-hidden border border-brand-300 bg-brand-200/75">
      <div class="absolute top-0 right-0 w-32 h-32 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"
           :class="isCandidat ? 'bg-brand-500/10' : 'bg-slate-500/10'"></div>
      
      <div class="relative z-10">
        <!-- Info Banner for Company -->
        <template x-if="!isCandidat">
          <div class="mb-8 p-4 rounded-2xl bg-slate-300 border border-slate-500 flex gap-4 items-start">
            <span class="text-2xl">ℹ️</span>
            <p class="text-sm font-bold text-slate-700 leading-relaxed">
              Pour garantir la qualité de notre plateforme, chaque compte entreprise est soumis à une validation manuelle par nos administrateurs.
            </p>
          </div>
        </template>

        <div id="err" role="alert" aria-live="assertive" class="hidden mb-6 p-4 rounded-2xl text-sm font-bold border border-red-100 bg-red-50 text-red-600 animate-shake"></div>
        
        <form @submit.prevent="submitForm" class="space-y-6">
          
          <!-- Candidat Specific Fields -->
          <template x-if="isCandidat">
            <div class="space-y-6 rounded-2xl border border-brand-300 bg-brand-100/70 p-4 md:p-5">
              <div class="text-[11px] font-black uppercase tracking-widest text-slate-700">Informations personnelles</div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Prénom *</label>
                  <input x-model="form.prenom" type="text" class="input h-14 font-medium" required placeholder="Ex: Ahmed" autocomplete="given-name">
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Nom *</label>
                  <input x-model="form.nom" type="text" class="input h-14 font-medium" required placeholder="Ex: Ben Ali" autocomplete="family-name">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Domaine d'activité</label>
                  <select x-model="form.idDomaine" class="input h-14 font-medium appearance-none cursor-pointer">
                    <option value="">Sélectionner un domaine</option>
                    <template x-for="d in domaines" :key="d.idDomaine">
                      <option :value="d.idDomaine" x-text="d.nom"></option>
                    </template>
                  </select>
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Spécialité</label>
                  <input x-model="form.specialite" type="text" class="input h-14 font-medium" placeholder="Ex: Développeur PHP">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Niveau d'études</label>
                  <select x-model="form.niveauEtude" class="input h-14 font-medium appearance-none cursor-pointer">
                    <option value="">Niveau d'études</option>
                    <option>Baccalauréat</option>
                    <option>Licence / Bachelor</option>
                    <option>Master / Ingénieur</option>
                    <option>Doctorat</option>
                    <option>Formation Pro</option>
                  </select>
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Expérience</label>
                  <select x-model="form.niveauExperience" class="input h-14 font-medium appearance-none cursor-pointer">
                    <option value="">Expérience</option>
                    <option>Débutant (0 an)</option>
                    <option>Junior (1-3 ans)</option>
                    <option>Confirmé (3-5 ans)</option>
                    <option>Senior (5-10 ans)</option>
                    <option>Expert (+10 ans)</option>
                  </select>
                </div>
              </div>
            </div>
          </template>

          <!-- Entreprise Specific Fields -->
          <template x-if="!isCandidat">
            <div class="space-y-6 rounded-2xl border border-brand-300 bg-brand-100/70 p-4 md:p-5">
              <div class="text-[11px] font-black uppercase tracking-widest text-slate-700">Informations entreprise</div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Raison Sociale *</label>
                  <input x-model="form.raisonSociale" type="text" class="input h-14 font-medium" required placeholder="Nom de l'entreprise" autocomplete="organization">
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Identifiant Légal *</label>
                  <input x-model="form.identifiantLegal" type="text" class="input h-14 font-medium" required placeholder="MF / SIRET / Registre">
                </div>
              </div>

              <div class="space-y-2">
                <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Description courte</label>
                <textarea x-model="form.description" class="input p-4 min-h-[100px] font-medium" placeholder="Décrivez brièvement votre activité..."></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Secteur d'activité</label>
                  <select x-model="form.idDomaine" class="input h-14 font-medium appearance-none cursor-pointer">
                    <option value="">Sélectionner un secteur</option>
                    <template x-for="d in domaines" :key="d.idDomaine">
                      <option :value="d.idDomaine" x-text="d.nom"></option>
                    </template>
                  </select>
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Téléphone</label>
                  <input x-model="form.telephone" type="tel" class="input h-14 font-medium" placeholder="+216 71 000 000" autocomplete="tel">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Pays</label>
                  <input x-model="form.pays" type="text" class="input h-14 font-medium" value="Tunisie" autocomplete="country-name">
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Région</label>
                  <select x-model="form.idRegion" class="input h-14 font-medium appearance-none cursor-pointer">
                    <option value="">Sélectionner une région</option>
                    <template x-for="r in regions" :key="r.idRegion">
                      <option :value="r.idRegion" x-text="r.ville"></option>
                    </template>
                  </select>
                </div>
              </div>

              <div class="space-y-2">
                <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Ville (optionnel)</label>
                <input x-model="form.ville" type="text" class="input h-14 font-medium" placeholder="Ex: Tunis centre" autocomplete="address-level2">
              </div>

              <div class="space-y-2">
                <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Site Web</label>
                <input x-model="form.siteWeb" type="url" class="input h-14 font-medium" placeholder="https://www.votre-entreprise.tn">
              </div>
            </div>
          </template>

          <!-- Common Fields -->
          <div class="space-y-2 rounded-2xl border border-brand-300 bg-brand-100/70 p-4">
            <div class="text-[11px] font-black uppercase tracking-widest text-slate-700">Compte</div>
            <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Adresse Email *</label>
            <input x-model="form.email" type="email" class="input h-14 font-medium" required placeholder="votre@email.com" autocomplete="email">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 rounded-2xl border border-brand-300 bg-brand-100/70 p-4">
            <div class="space-y-2">
              <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Mot de passe *</label>
              <input x-model="form.motDePasse" type="password" class="input h-14 font-medium" required minlength="8" placeholder="••••••••" autocomplete="new-password">
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Confirmation *</label>
              <input x-model="form.motDePasse_confirmation" type="password" class="input h-14 font-medium" required placeholder="••••••••" autocomplete="new-password">
            </div>
          </div>
          <p class="text-xs text-slate-500 font-semibold -mt-3">Mot de passe: au moins 8 caracteres avec lettres et chiffres.</p>

          <div class="space-y-2" x-show="isCandidat">
            <label class="label text-xs font-bold uppercase tracking-widest text-slate-500">Région</label>
            <select x-model="form.localisation" class="input h-14 font-medium appearance-none cursor-pointer">
              <option value="">Sélectionner une région</option>
              <template x-for="r in regions" :key="r.idRegion">
                <option :value="r.ville" x-text="r.ville"></option>
              </template>
            </select>
          </div>

          <div class="flex items-start gap-3 py-2">
            <input x-model="termsAccepted" type="checkbox" id="terms" required class="mt-1 w-5 h-5 rounded-lg border-slate-200 text-brand-600 focus:ring-brand-500 cursor-pointer">
            <label for="terms" class="text-sm font-medium text-slate-500 leading-relaxed cursor-pointer">
              J'accepte les <a href="#" class="font-bold text-brand-600 hover:underline">Conditions d'Utilisation</a> et la <a href="#" class="font-bold text-brand-600 hover:underline">Politique de Confidentialité</a>.
            </label>
          </div>

          <button type="submit" id="r-btn" 
                  :class="isCandidat ? 'btn-primary shadow-brand-500/30' : 'bg-slate-800 text-white hover:bg-slate-900 shadow-slate-900/30'"
                  :disabled="!termsAccepted"
                  class="btn btn-lg w-full justify-center h-14 text-lg shadow-xl mt-4 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none">
            <span x-text="isCandidat ? 'Créer mon compte' : 'Soumettre ma demande'"></span> <span class="ml-2">→</span>
          </button>
        </form>

        <div class="mt-10 pt-8 border-t border-slate-50 text-center">
          <p class="text-sm font-medium text-slate-500">
            Déjà inscrit ? 
            <a href="/connexion" class="font-bold text-brand-600 hover:text-brand-700 ml-1 transition-colors">Se connecter</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function registerApp() {
  return {
    isCandidat: true,
    domaines: [],
    regions: [],
    form: {
      prenom: '', nom: '', email: '', idDomaine: '', specialite: '', niveauEtude: '',
      niveauExperience: '', localisation: '', raisonSociale: '', identifiantLegal: '', idRegion: '',
      description: '', telephone: '', pays: 'Tunisie', ville: '', siteWeb: '',
      motDePasse: '', motDePasse_confirmation: ''
    },
    termsAccepted: false,
    async init() {
      // Redirect if already logged in
      if (API.isLogged()) {
        const _t = API.type();
        window.location.replace({admin:'/admin', candidat:'/mon-espace', entreprise:'/recruteur'}[_t] || '/');
        return;
      }

      const [resDomaines, resRegions] = await Promise.all([
        API.get('/domaines'),
        API.get('/regions')
      ]);
      if (resDomaines?.ok) this.domaines = await resDomaines.json();
      if (resRegions?.ok) this.regions = await resRegions.json();

      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('type') === 'entreprise') this.isCandidat = false;
    },
    async submitForm() {
      const btn = document.getElementById('r-btn');
      const errEl = document.getElementById('err');
      
      if (this.form.motDePasse !== this.form.motDePasse_confirmation) {
        errEl.textContent = 'Les mots de passe ne correspondent pas.';
        errEl.classList.remove('hidden');
        return;
      }
      
      btn.disabled = true;
      const originalText = btn.innerHTML;
      btn.innerHTML = '<span class="animate-spin mr-3">⏳</span> Envoi...';
      errEl.classList.add('hidden');
      
      const endpoint = this.isCandidat ? '/candidat/register' : '/entreprise/register';
      
      try {
        const res = await API.post(endpoint, this.form);
        const data = await res.json();
        
        if (!res.ok) {
          const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Une erreur est survenue.'];
          errEl.innerHTML = msgs.join('<br>');
          errEl.classList.remove('hidden');
          return;
        }
        
        if (this.isCandidat) {
          API.save(data.token, 'candidat', data.candidat);
          toast('Bienvenue sur JobPlatform ! 🎉');
          setTimeout(() => window.location.href = '/mon-espace', 800);
        } else {
          toast('Demande envoyée ! Vous serez averti après validation.');
          setTimeout(() => window.location.href = '/connexion?msg=pending', 1500);
        }
      } catch (err) {
        errEl.textContent = 'Erreur réseau. Veuillez réessayer.';
        errEl.classList.remove('hidden');
      } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    }
  };
}
</script>
@endpush
@endsection
