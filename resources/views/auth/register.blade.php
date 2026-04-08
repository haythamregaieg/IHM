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
           :class="isCandidat ? 'bg-gradient-to-br from-brand-600 to-brand-800 shadow-brand-500/30' : 'bg-gradient-to-br from-amber-500 to-amber-700 shadow-amber-500/30'">
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
      <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2" style="font-family:Outfit">
        <span x-text="isCandidat ? 'Rejoignez-nous ! 🚀' : 'Espace Recruteur 🏢'"></span>
      </h1>
      <p class="text-slate-500 font-medium" x-text="isCandidat ? 'Créez votre profil candidat et accédez aux meilleures opportunités.' : 'Inscrivez votre entreprise et trouvez vos futurs collaborateurs.'"></p>
    </div>

    <!-- Toggle Switch -->
    <div class="flex p-1.5 bg-white rounded-2xl shadow-sm border border-slate-100 mb-8">
      <button @click="isCandidat = true" 
              :class="isCandidat ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/20' : 'text-slate-500 hover:bg-slate-50'"
              class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-black transition-all">
        <span>👤</span> Candidat
      </button>
      <button @click="isCandidat = false" 
              :class="!isCandidat ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'text-slate-500 hover:bg-slate-50'"
              class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-black transition-all">
        <span>🏢</span> Entreprise
      </button>
    </div>

    <!-- Registration Card -->
    <div class="card card-dark p-10 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-32 h-32 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"
           :class="isCandidat ? 'bg-brand-500/10' : 'bg-amber-500/10'"></div>
      
      <div class="relative z-10">
        <!-- Info Banner for Company -->
        <template x-if="!isCandidat">
          <div class="mb-8 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex gap-4 items-start">
            <span class="text-2xl">ℹ️</span>
            <p class="text-sm font-bold text-amber-200 leading-relaxed">
              Pour garantir la qualité de notre plateforme, chaque compte entreprise est soumis à une validation manuelle par nos administrateurs.
            </p>
          </div>
        </template>

        <div id="err" class="hidden mb-6 p-4 rounded-2xl text-sm font-bold border border-red-100 bg-red-50 text-red-600 animate-shake"></div>
        
        <form @submit.prevent="submitForm" class="space-y-6">
          
          <!-- Candidat Specific Fields -->
          <template x-if="isCandidat">
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Prénom *</label>
                  <input x-model="form.prenom" type="text" class="input h-14 font-medium" required placeholder="Ex: Ahmed">
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Nom *</label>
                  <input x-model="form.nom" type="text" class="input h-14 font-medium" required placeholder="Ex: Ben Ali">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Domaine d'activité</label>
                  <select x-model="form.idDomaine" class="input h-14 font-medium appearance-none cursor-pointer">
                    <option value="">Sélectionner un domaine</option>
                    <template x-for="d in domaines" :key="d.idDomaine">
                      <option :value="d.idDomaine" x-text="d.nom"></option>
                    </template>
                  </select>
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Spécialité</label>
                  <input x-model="form.specialite" type="text" class="input h-14 font-medium" placeholder="Ex: Développeur PHP">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Niveau d'études</label>
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
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Expérience</label>
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
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Raison Sociale *</label>
                  <input x-model="form.raisonSociale" type="text" class="input h-14 font-medium" required placeholder="Nom de l'entreprise">
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Identifiant Légal *</label>
                  <input x-model="form.identifiantLegal" type="text" class="input h-14 font-medium" required placeholder="MF / SIRET / Registre">
                </div>
              </div>

              <div class="space-y-2">
                <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Description courte</label>
                <textarea x-model="form.description" class="input p-4 min-h-[100px] font-medium" placeholder="Décrivez brièvement votre activité..."></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Secteur d'activité</label>
                  <select x-model="form.idDomaine" class="input h-14 font-medium appearance-none cursor-pointer">
                    <option value="">Sélectionner un secteur</option>
                    <template x-for="d in domaines" :key="d.idDomaine">
                      <option :value="d.idDomaine" x-text="d.nom"></option>
                    </template>
                  </select>
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Téléphone</label>
                  <input x-model="form.telephone" type="tel" class="input h-14 font-medium" placeholder="+216 71 000 000">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Pays</label>
                  <input x-model="form.pays" type="text" class="input h-14 font-medium" value="Tunisie">
                </div>
                <div class="space-y-2">
                  <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Ville</label>
                  <input x-model="form.ville" type="text" class="input h-14 font-medium" placeholder="Ex: Tunis">
                </div>
              </div>

              <div class="space-y-2">
                <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Site Web</label>
                <input x-model="form.siteWeb" type="url" class="input h-14 font-medium" placeholder="https://www.votre-entreprise.tn">
              </div>
            </div>
          </template>

          <!-- Common Fields -->
          <div class="space-y-2">
            <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Adresse Email *</label>
            <input x-model="form.email" type="email" class="input h-14 font-medium" required placeholder="votre@email.com">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Mot de passe *</label>
              <input x-model="form.motDePasse" type="password" class="input h-14 font-medium" required minlength="8" placeholder="••••••••">
            </div>
            <div class="space-y-2">
              <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Confirmation *</label>
              <input x-model="form.motDePasse_confirmation" type="password" class="input h-14 font-medium" required placeholder="••••••••">
            </div>
          </div>

          <div class="space-y-2" x-show="isCandidat">
            <label class="label text-xs font-black uppercase tracking-widest text-slate-400">Localisation</label>
            <input x-model="form.localisation" type="text" class="input h-14 font-medium" placeholder="Ex: Tunis, Sfax...">
          </div>

          <div class="flex items-start gap-3 py-2">
            <input type="checkbox" id="terms" required class="mt-1 w-5 h-5 rounded-lg border-slate-200 text-brand-600 focus:ring-brand-500 cursor-pointer">
            <label for="terms" class="text-sm font-medium text-slate-500 leading-relaxed cursor-pointer">
              J'accepte les <a href="#" class="font-bold text-brand-600 hover:underline">Conditions d'Utilisation</a> et la <a href="#" class="font-bold text-brand-600 hover:underline">Politique de Confidentialité</a>.
            </label>
          </div>

          <button type="submit" id="r-btn" 
                  :class="isCandidat ? 'btn-primary shadow-brand-500/30' : 'btn-gold shadow-amber-500/30'"
                  class="btn btn-lg w-full justify-center h-14 text-lg shadow-xl mt-4">
            <span x-text="isCandidat ? 'Créer mon compte' : 'Soumettre ma demande'"></span> <span class="ml-2">→</span>
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
function registerApp() {
  return {
    isCandidat: true,
    domaines: [],
    form: {
      prenom: '', nom: '', email: '', idDomaine: '', specialite: '', niveauEtude: '',
      niveauExperience: '', localisation: '', raisonSociale: '', identifiantLegal: '',
      description: '', telephone: '', pays: 'Tunisie', ville: '', siteWeb: '',
      motDePasse: '', motDePasse_confirmation: ''
    },
    async init() {
      const res = await API.get('/domaines');
      if (res?.ok) this.domaines = await res.json();
      
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
