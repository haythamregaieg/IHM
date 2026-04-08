@extends('layouts.app')
@section('title',"Détail de l'offre — JobPlatform")
@section('content')
<div class="bg-[#F8FAFC] min-h-[calc(100vh-64px)] py-8 lg:py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm font-bold mb-8 animate-slide-up">
      <a href="/offres" class="text-slate-400 hover:text-brand-600 transition-colors">Offres</a>
      <span class="text-slate-300">/</span>
      <span class="text-slate-900 truncate" id="b-title">Détail de l'offre</span>
    </nav>

    <div id="detail" class="flex flex-col items-center justify-center py-32">
      <div class="w-12 h-12 rounded-full border-4 border-slate-200 border-t-brand-600 animate-spin"></div>
      <p class="mt-4 text-slate-400 font-bold uppercase tracking-widest text-xs">Chargement de l'offre...</p></div>
    </div>
  </div>

  <!-- Modal Signalement -->
  <div id="modal-s" role="dialog" aria-modal="true" aria-labelledby="modal-s-title" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-md" onclick="if(event.target===this)closeS()">
    <div class="flex items-center justify-center min-h-full p-4">
      <div class="bg-white rounded-[40px] p-10 w-full max-w-lg shadow-2xl animate-pop-in relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-red-50 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"></div>
        
        <div class="relative z-10">
          <div class="flex justify-between items-center mb-8">
            <div>
              <h3 id="modal-s-title" class="text-3xl font-black text-slate-900 tracking-tight">Signaler <span class="text-red-600">l'offre</span></h3>
              <p class="text-slate-400 font-medium">Aidez-nous à garder JobPlatform sûr.</p>
            </div>
            <button onclick="closeS()" class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center text-3xl">&times;</button>
          </div>

          <form onsubmit="submitS(event)" class="space-y-6">
            <input type="hidden" id="s-id">
            <div class="space-y-3">
              <label class="label">Motif du signalement *</label>
              <select id="s-motif" class="input" required>
                <option value="">Sélectionnez un motif</option>
                <option value="Contenu inapproprié">Contenu inapproprié</option>
                <option value="Offre frauduleuse">Offre frauduleuse / Arnaque</option>
                <option value="Discrimination">Discrimination</option>
                <option value="Entreprise suspecte">Entreprise suspecte</option>
                <option value="Autre">Autre</option>
              </select>
            </div>
            <div class="space-y-3">
              <label class="label">Précisions supplémentaires *</label>
              <textarea id="s-desc" class="input p-6 min-h-[120px]" required placeholder="Décrivez le problème en quelques mots..."></textarea>
            </div>
            <div class="flex gap-4 pt-4">
              <button type="button" onclick="closeS()" class="btn btn-secondary flex-1 justify-center py-4 font-black">Annuler</button>
              <button type="submit" id="s-btn" class="btn bg-red-600 text-white hover:bg-red-700 flex-1 justify-center py-4 font-black rounded-2xl transition-all shadow-lg shadow-red-500/20">Envoyer le signalement ✓</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Candidature Modernisé -->
  <div id="modal-c" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-md overflow-y-auto" @click.self="closeM()">
    <div class="flex items-center justify-center min-h-full p-4">
      <div class="bg-white rounded-[40px] p-10 w-full max-w-xl shadow-2xl animate-pop-in relative overflow-hidden my-8">
        <div class="absolute top-0 right-0 w-32 h-32 bg-brand-50 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"></div>
        
        <div class="relative z-10">
          <div class="flex justify-between items-center mb-8">
            <div>
              <h3 class="text-3xl font-black text-slate-900 tracking-tight">Postuler <span class="text-brand-600">maintenant</span></h3>
              <p class="text-slate-400 font-medium">Convainquez le recruteur en quelques mots.</p>
            </div>
            <div class="flex items-center gap-3">
              <button onclick="fillUserData()" class="btn btn-secondary px-4 py-2 text-xs font-black flex items-center gap-2">
                <span>🔄</span> Charger mes données
              </button>
              <button onclick="closeM()" class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center text-3xl">&times;</button>
            </div>
          </div>

          <div id="c-err" class="hidden mb-6 p-4 rounded-2xl bg-red-50 text-red-600 text-sm font-bold border border-red-100"></div>
          
          <form id="cf" onsubmit="submitC(event)" class="space-y-8">
            <input type="hidden" id="c-id">
            
            <!-- Informations de contact -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-3">
                <label class="label">Votre Téléphone *</label>
                <input id="c-tel" type="tel" class="input" required placeholder="Ex: 55 123 456">
              </div>
              <div class="space-y-3">
                <label class="label">Votre Email de contact *</label>
                <input id="c-email" type="email" class="input" required placeholder="Ex: mon@email.com">
              </div>
              <div class="space-y-3">
                <label class="label">Votre Spécialité *</label>
                <input id="c-spec" type="text" class="input" required placeholder="Ex: Développeur PHP">
              </div>
              <div class="space-y-3">
                <label class="label">Votre Expérience *</label>
                <select id="c-exp" class="input" required>
                  <option value="">Sélectionnez votre niveau</option>
                  <option value="Débutant">Débutant (0-2 ans)</option>
                  <option value="Intermédiaire">Intermédiaire (2-5 ans)</option>
                  <option value="Senior">Senior (5+ ans)</option>
                  <option value="Expert">Expert (10+ ans)</option>
                </select>
              </div>
            </div>

            <div class="flex items-center gap-3 p-4 rounded-2xl bg-brand-50/50 border border-brand-100">
              <input type="checkbox" id="c-update-profile" class="w-5 h-5 text-brand-600 rounded-lg focus:ring-brand-500">
              <label for="c-update-profile" class="text-sm font-bold text-slate-700 cursor-pointer">Mettre à jour mon profil avec ces informations</label>
            </div>

            <div class="space-y-3">
              <label class="label text-lg">Lettre de motivation * <span class="text-xs font-bold text-slate-400">(PDF recommandé ou texte libre)</span></label>
              <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 mb-2">
                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-2xl shadow-sm text-red-500">📄</div>
                <div class="flex-1">
                  <input id="c-lm-file" type="file" accept=".pdf" class="hidden" onchange="updateLMName(this)">
                  <label for="c-lm-file" class="text-sm font-black text-brand-600 hover:text-brand-700 cursor-pointer block" id="lm-file-name">Importer ma lettre (PDF)</label>
                </div>
              </div>
              <p class="text-center text-xs font-bold text-slate-300">OU RÉDIGER ICI</p>
              <textarea id="c-lettre" class="input p-6 text-lg min-h-[150px]" minlength="50" placeholder="Décrivez vos motivations..."></textarea>
              <div class="text-right"><span id="c-count" class="text-xs font-black text-slate-300">0</span><span class="text-xs font-black text-slate-300">/50</span></div>
            </div>

            <div class="space-y-3">
              <label class="label text-lg">Votre CV (PDF) *</label>
              <div class="grid grid-cols-1 gap-4">
                <!-- Option 1: CV du profil -->
                <label class="relative flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border-2 border-transparent hover:border-brand-200 cursor-pointer transition-all has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50/50">
                  <input type="radio" name="cv_type" value="profile" class="w-5 h-5 text-brand-600 focus:ring-brand-500" checked onchange="toggleCVUpload(false)">
                  <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-2xl shadow-sm">👤</div>
                  <div class="flex-1">
                    <span class="text-sm font-black text-slate-900 block">Utiliser mon CV actuel</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Celui de mon profil</span>
                  </div>
                </label>

                <!-- Option 2: Nouveau CV -->
                <label class="relative flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border-2 border-transparent hover:border-brand-200 cursor-pointer transition-all has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50/50">
                  <input type="radio" name="cv_type" value="new" class="w-5 h-5 text-brand-600 focus:ring-brand-500" onchange="toggleCVUpload(true)">
                  <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-2xl shadow-sm">📤</div>
                  <div class="flex-1">
                    <span class="text-sm font-black text-slate-900 block">Envoyer un nouveau CV</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Fichier PDF uniquement</span>
                  </div>
                </label>

                <div id="cv-upload-zone" class="hidden animate-pop-in">
                  <input id="c-cv" type="file" accept=".pdf" class="hidden" onchange="updateCVName(this)">
                  <label for="c-cv" class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-slate-200 rounded-3xl hover:border-brand-400 transition-all cursor-pointer bg-white">
                    <span class="text-4xl mb-2">📄</span>
                    <span class="text-sm font-black text-brand-600" id="cv-file-name">Cliquez pour choisir le PDF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="flex gap-4">
              <button type="button" onclick="closeM()" class="btn btn-secondary flex-1 justify-center py-5 font-black text-lg">Annuler</button>
              <button type="submit" id="c-btn" class="btn btn-primary flex-1 justify-center py-5 font-black text-lg shadow-xl shadow-brand-500/30">Envoyer ma candidature ✓</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
const oid = location.pathname.split('/').pop();
const cc = {
  CDI: 'badge-blue',
  CDD: 'badge-blue',
  Stage: 'badge-blue',
  Freelance: 'badge-blue',
  Alternance: 'badge-slate'
};

function updateCVName(input) {
  const label = document.getElementById('cv-file-name');
  if (input.files && input.files.length > 0) {
    label.textContent = input.files[0].name;
    label.className = "text-sm font-black text-brand-600";
  }
}

function updateLMName(input) {
  const label = document.getElementById('lm-file-name');
  if (input.files && input.files.length > 0) {
    label.textContent = input.files[0].name;
    label.className = "text-sm font-black text-brand-600 block";
    // Désactiver le textarea si un fichier est fourni
    document.getElementById('c-lettre').disabled = true;
    document.getElementById('c-lettre').placeholder = "Lettre fournie en PDF";
  } else {
    label.textContent = "Importer ma lettre (PDF)";
    label.className = "text-sm font-black text-brand-600 hover:text-brand-700 cursor-pointer block";
    document.getElementById('c-lettre').disabled = false;
  }
}

function toggleCVUpload(show) {
  const zone = document.getElementById('cv-upload-zone');
  if (show) zone.classList.remove('hidden');
  else zone.classList.add('hidden');
}

(async () => {
  try {
    const res = await API.get('/offres/' + oid);
    if (!res?.ok) {
      document.getElementById('detail').innerHTML = `
        <div class="text-center py-20">
          <div class="text-6xl mb-6">🏜️</div>
          <h2 class="text-2xl font-black text-slate-900 mb-2">Offre introuvable</h2>
          <p class="text-slate-400 mb-8">Cette offre n'existe plus ou a été supprimée.</p>
          <a href="/offres" class="btn btn-primary px-10">Retour aux offres</a>
        </div>
      `;
      return;
    }
    
    const o = await res.json();
    const isC = API.isLogged() && API.type() === 'candidat';
    const publicationDate = o.datePublication ? new Date(o.datePublication).toLocaleDateString('fr-FR') : 'Non précisée';
    const limitDate = o.dateLimite ? new Date(o.dateLimite).toLocaleDateString('fr-FR') : 'Non définie';
    const isExpired = o.dateLimite ? (new Date(o.dateLimite) < new Date()) : false;
    const competences = (o.competencesRequises || '')
      .split(',')
      .map(c => c.trim())
      .filter(Boolean);
    document.getElementById('b-title').textContent = o.titre;

    document.getElementById('detail').className = "grid grid-cols-1 lg:grid-cols-3 gap-10 animate-slide-up";
    document.getElementById('detail').innerHTML = `
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-8">
        <div class="card p-8 lg:p-10 relative overflow-hidden border-slate-100 shadow-xl shadow-slate-200/40">
          <div class="absolute top-0 right-0 w-64 h-64 bg-brand-50 rounded-full -translate-y-1/2 translate-x-1/2 opacity-50 -z-0"></div>
          
          <div class="relative z-10">
            <div class="flex flex-col md:flex-row items-start gap-8 mb-8">
              <div class="w-24 h-24 rounded-[32px] flex items-center justify-center text-white font-black text-4xl shadow-2xl shadow-brand-500/20 flex-shrink-0 overflow-hidden" 
                   style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">
                ${o.entreprise?.logo_path ? `<img src="/storage/${o.entreprise.logo_path}" alt="Logo ${o.entreprise?.raison_sociale || 'entreprise'}" class="w-full h-full object-cover">` : (o.entreprise?.raison_sociale || '?').charAt(0).toUpperCase()}
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-3 mb-3">
                  <span class="badge ${cc[o.typeContrat] || 'badge-slate'} px-4 py-1.5 text-xs font-black uppercase tracking-widest">${o.typeContrat}</span>
                  <span class="text-xs font-black text-slate-300 uppercase tracking-widest">Réf: #OFF-${o.idOffre}</span>
                </div>
                <h1 class="text-3xl lg:text-4xl font-black text-slate-900 leading-tight mb-2" style="font-family:Outfit">${o.titre}</h1>
                <div class="flex items-center gap-2">
                  <span class="text-xl font-bold text-brand-600">${o.entreprise?.raison_sociale || 'Entreprise'}</span>
                  <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                  <span class="text-slate-500 font-bold">Inscrite depuis ${new Date(o.entreprise?.created_at).getFullYear()}</span>
                </div>
              </div>
            </div>

            <div class="flex flex-wrap gap-3 mb-8">
              <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black">📍 ${o.localisation}</span>
              <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-50 text-brand-700 text-sm font-black">💰 ${o.remuneration ? Number(o.remuneration).toLocaleString() + ' DT' : 'À négocier'}</span>
              <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black">🏠 ${o.mode_travail || 'Mode non précisé'}</span>
              <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-100 text-brand-700 text-sm font-black">⚡ ${o.experience || 'Expérience non précisée'}</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-7 border-y border-slate-100 mb-10">
              <div class="rounded-2xl bg-slate-50 p-4 space-y-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Localisation</p>
                <p class="font-bold text-slate-700">📍 ${o.localisation}</p>
              </div>
              <div class="rounded-2xl bg-slate-50 p-4 space-y-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Salaire</p>
                <p class="font-bold text-brand-700">${o.remuneration ? Number(o.remuneration).toLocaleString() + ' DT' : 'À négocier'}</p>
              </div>
              <div class="rounded-2xl bg-slate-50 p-4 space-y-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Vues</p>
                <p class="font-bold text-slate-700">👁️ ${o.vues || 0}</p>
              </div>
              <div class="rounded-2xl bg-slate-50 p-4 space-y-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Publiée</p>
                <p class="font-bold text-slate-700">📅 ${publicationDate}</p>
              </div>
              <div class="rounded-2xl bg-slate-50 p-4 space-y-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Expérience</p>
                <p class="font-bold text-slate-700">⚡ ${o.experience || 'Non spécifiée'}</p>
              </div>
              <div class="rounded-2xl bg-slate-50 p-4 space-y-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mode</p>
                <p class="font-bold text-slate-700">🏠 ${o.mode_travail || 'Non spécifié'}</p>
              </div>
              <div class="rounded-2xl bg-slate-50 p-4 space-y-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Études</p>
                <p class="font-bold text-slate-700">🎓 ${o.niveau_etude || 'Non spécifié'}</p>
              </div>
            </div>

            <div class="space-y-10">
              <div class="rounded-3xl border border-slate-100 bg-white p-6 lg:p-8">
                <h2 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                  <span class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center text-lg">📄</span> Description du poste
                </h2>
                <div class="text-slate-600 leading-relaxed text-base lg:text-lg whitespace-pre-line">${o.description}</div>
              </div>

              <div class="rounded-3xl border border-slate-100 bg-white p-6 lg:p-8">
                <h2 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                  <span class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center text-lg">⚡</span> Compétences clés
                </h2>
                <div class="flex flex-wrap gap-3">
                  ${(competences.length ? competences : ['Non spécifiées']).map(c => `
                    <span class="px-5 py-2.5 rounded-2xl bg-white border border-slate-100 shadow-sm text-brand-600 font-bold text-sm hover:border-brand-200 transition-all cursor-default">
                      ${c}
                    </span>
                  `).join('')}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-1">
        <div class="lg:sticky lg:top-24 space-y-8">
          <!-- Card Postuler -->
          <div class="card p-8 border-slate-100 shadow-2xl shadow-slate-200/50">
            <div class="flex items-center justify-between mb-6">
              <h3 class="font-black text-slate-900 text-xl tracking-tight">Postuler à l'offre</h3>
              ${isC ? `
                <button onclick="toggleFav(${o.idOffre},this)" id="btn-fav" class="btn btn-secondary h-10 w-10 p-0 !rounded-xl flex items-center justify-center text-lg" title="Sauvegarder l'offre" aria-label="Sauvegarder l'offre">🔖</button>
              ` : ''}
            </div>
            
            <div class="space-y-4 mb-8">
              <div class="flex items-center justify-between text-sm py-2 border-b border-slate-50">
                <span class="text-slate-400 font-medium">Type de contrat</span>
                <span class="font-black text-slate-700">${o.typeContrat}</span>
              </div>
              <div class="flex items-center justify-between text-sm py-2 border-b border-slate-50">
                <span class="text-slate-400 font-medium">Date limite</span>
                <span class="font-black ${isExpired ? 'text-red-600' : 'text-red-500'}">${limitDate}</span>
              </div>
              <div class="flex items-center justify-between text-sm py-2">
                <span class="text-slate-400 font-medium">Domaine</span>
                <span class="font-black text-slate-700">${o.domaine?.nom || 'Général'}</span>
              </div>
            </div>
            ${isExpired ? `
              <div class="p-4 rounded-2xl bg-red-50 text-red-700 text-sm font-bold text-center border border-red-100 mb-4">
                Cette offre est expirée. La candidature est fermée.
              </div>
            ` : ''}
            ${isC ? `
              ${o.dejaPostule ? `
                <div class="p-4 rounded-2xl bg-brand-50 text-brand-700 text-sm font-black text-center border border-brand-100 mb-3">
                  ✅ Vous avez déjà postulé à cette offre.
                </div>
                <button disabled class="btn btn-primary w-full justify-center h-14 text-lg opacity-50 cursor-not-allowed mb-3">✉️ Candidature envoyée</button>
              ` : `
                <div class="grid grid-cols-2 gap-3">
                  <button onclick="openPostuler(${o.idOffre})" ${isExpired ? 'disabled' : ''} class="btn btn-primary justify-center h-12 text-sm font-black shadow-lg shadow-brand-500/25 ${isExpired ? 'opacity-50 cursor-not-allowed' : ''}">✉️ Postuler</button>
                  <button onclick="openSignal(${o.idOffre})" class="btn btn-ghost justify-center h-12 text-sm font-black border-red-200 text-red-500 hover:bg-red-50 hover:text-red-700">🚩 Signaler</button>
                </div>
              `}
            ` : API.isLogged() ? `
              <div class="p-4 rounded-2xl bg-amber-50 text-amber-700 text-sm font-bold text-center border border-amber-100 mb-4">
                Seul un candidat peut postuler à cette offre.
              </div>
              <button onclick="openSignal(${o.idOffre})" class="btn btn-ghost w-full justify-center h-14 text-lg border-red-100 text-red-400 hover:bg-red-50 hover:text-red-600">🚩 Signaler l'offre</button>
            ` : `
              <div class="grid grid-cols-2 gap-3">
                <a href="/connexion?redirect=${encodeURIComponent(location.pathname)}" class="btn btn-primary justify-center h-11 text-sm">Se connecter</a>
                <a href="/inscription?type=candidat" class="btn btn-secondary justify-center h-11 text-sm">Créer un compte</a>
              </div>
            `}
          </div>

          <!-- Card Entreprise -->
          <div class="card p-8 border-slate-100 shadow-xl shadow-slate-200/30">
            <h3 class="font-black text-slate-900 mb-6 flex items-center gap-2">
              <span>🏢</span> À propos de l'entreprise
            </h3>
            <div class="flex items-center gap-4 mb-6">
              <div class="w-14 h-14 rounded-2xl bg-brand-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-brand-500/20 overflow-hidden">
                ${o.entreprise?.logo_path ? `<img src="/storage/${o.entreprise.logo_path}" alt="Logo ${o.entreprise?.raison_sociale || 'entreprise'}" class="w-full h-full object-cover">` : (o.entreprise?.raison_sociale || '?').charAt(0).toUpperCase()}
              </div>
              <div>
                <p class="font-black text-slate-900 leading-tight">${o.entreprise?.raison_sociale}</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">${o.entreprise?.ville || 'Tunisie'}</p>
              </div>
            </div>
            <p class="text-sm text-slate-500 leading-relaxed mb-6 line-clamp-4">
              ${o.entreprise?.description || 'Aucune description disponible pour cette entreprise.'}
            </p>
            <div class="space-y-3">
              ${o.entreprise?.telephone ? `
                <div class="flex items-center gap-3 text-sm font-bold text-slate-600 bg-slate-50 p-3 rounded-xl">
                  <span class="text-lg">📞</span> ${o.entreprise.telephone}
                </div>
              ` : ''}
              ${o.entreprise?.email ? `
                <div class="flex items-center gap-3 text-sm font-bold text-slate-600 bg-slate-50 p-3 rounded-xl">
                  <span class="text-lg">✉️</span> ${o.entreprise.email}
                </div>
              ` : ''}
              ${o.entreprise?.site_web || o.entreprise?.siteWeb ? `
                <a href="${o.entreprise.site_web || o.entreprise.siteWeb}" target="_blank" class="btn btn-secondary w-full justify-center py-3 text-sm font-black">🌐 Visiter le site web</a>
              ` : ''}
            </div>
          </div>
        </div>
      </div>
    `;
  } catch (e) {
    console.error(e);
  }
})();

function fillUserData() {
  const u = API.user();
  if (u) {
    document.getElementById('c-tel').value = u.telephone || '';
    document.getElementById('c-email').value = u.email || '';
    document.getElementById('c-spec').value = u.specialite || '';
    document.getElementById('c-exp').value = u.niveau_experience || '';
    toast('Données chargées depuis votre profil ! ✨', 'info');
  }
}

function openPostuler(id) {
  if (!API.isLogged() || API.type() !== 'candidat') {
    window.location.href = '/connexion?redirect=' + encodeURIComponent(location.pathname);
    return;
  }
  
  document.getElementById('c-id').value = id;
  document.getElementById('modal-c').classList.remove('hidden');
}

function closeM() {
  document.getElementById('modal-c').classList.add('hidden');
}

function openSignal(id) {
  if (!API.isLogged()) { window.location.href = '/connexion?redirect=' + encodeURIComponent(location.pathname); return; }
  document.getElementById('s-id').value = id;
  document.getElementById('modal-s').classList.remove('hidden');
}

function closeS() {
  document.getElementById('modal-s').classList.add('hidden');
}

async function submitS(e) {
  e.preventDefault();
  const btn = document.getElementById('s-btn');
  btn.disabled = true;
  btn.innerHTML = 'Envoi...';

  const type = API.type(); // 'candidat' ou 'entreprise'
  const data = {
    cible_type: 'offre',
    cible_id: document.getElementById('s-id').value,
    description: `[${document.getElementById('s-motif').value}] ${document.getElementById('s-desc').value}`
  };

  try {
    const res = await API.post(`/${type}/signalements`, data);
    if (res.ok) {
      toast('Signalement envoyé. Merci de votre aide !', 'success');
      closeS();
    } else {
      toast('Erreur lors de l\'envoi du signalement.', 'error');
    }
  } catch (err) {
    toast('Erreur réseau.', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = 'Envoyer le signalement ✓';
  }
}

document.getElementById('c-lettre')?.addEventListener('input', function () {
  const n = this.value.length;
  const countEl = document.getElementById('c-count');
  countEl.textContent = n;
  countEl.className = n >= 50 ? 'text-xs font-black text-green-500' : 'text-xs font-black text-slate-300';
});

async function toggleFav(id, btn) {
  if (!API.isLogged()) { window.location.href = '/connexion?redirect=' + encodeURIComponent(location.pathname); return; }
  try {
    const r = await API.post('/candidat/favoris/toggle/' + id);
    const d = await r.json();
    if (r.ok) {
      btn.innerHTML = d.saved ? '✅' : '🔖';
      btn.title = d.saved ? "Offre enregistrée" : "Sauvegarder l'offre";
      btn.setAttribute('aria-label', btn.title);
      toast(d.saved ? 'Offre sauvegardée !' : 'Retirée des favoris', d.saved ? 'success' : 'info');
    } else {
      console.error('Fav Toggle Error:', d);
      toast('Erreur lors de la sauvegarde.', 'error');
    }
  } catch (e) {
    console.error('Fav Catch Error:', e);
  }
}

async function submitC(e) {
  e.preventDefault();
  const btn = document.getElementById('c-btn');
  const errEl = document.getElementById('c-err');
  
  btn.disabled = true;
  btn.innerHTML = '<span class="animate-spin mr-3">⏳</span> Envoi...';
  errEl.classList.add('hidden');
  
  const fd = new FormData();
  fd.append('idOffre', document.getElementById('c-id').value);
  fd.append('telephone', document.getElementById('c-tel').value);
  fd.append('email_contact', document.getElementById('c-email').value);
  fd.append('specialite', document.getElementById('c-spec').value);
  fd.append('niveau_experience', document.getElementById('c-exp').value);
  fd.append('update_profile', document.getElementById('c-update-profile').checked ? '1' : '0');
  
  // Gestion Lettre de motivation
  const lmFile = document.getElementById('c-lm-file').files?.[0];
  if (lmFile) {
    fd.append('lettre_file', lmFile);
  } else {
    const txt = document.getElementById('c-lettre').value;
    if (txt.length < 50) {
      errEl.textContent = "La lettre de motivation doit faire au moins 50 caractères.";
      errEl.classList.remove('hidden');
      btn.disabled = false;
      btn.textContent = 'Envoyer ma candidature ✓';
      return;
    }
    fd.append('lettreMotivation', txt);
  }

  // Gestion CV
  const cvType = document.querySelector('input[name="cv_type"]:checked').value;
  if (cvType === 'new') {
    const cvFile = document.getElementById('c-cv').files?.[0];
    if (!cvFile) {
      errEl.textContent = "Veuillez sélectionner un fichier PDF pour votre nouveau CV.";
      errEl.classList.remove('hidden');
      btn.disabled = false;
      btn.textContent = 'Envoyer ma candidature ✓';
      return;
    }
    fd.append('cv', cvFile);
  } else {
    fd.append('use_profile_cv', '1');
  }
  
  try {
    const res = await API.post('/candidat/candidatures', fd, true);
    const data = await res.json();
    if (!res.ok) {
      console.error('Candidature Error Details:', data);
      errEl.textContent = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Une erreur est survenue.');
      errEl.classList.remove('hidden');
      return;
    }
    closeM();
    toast('Candidature envoyée avec succès ! 🎉');
    setTimeout(() => location.reload(), 1500);
  } catch (error) {
    console.error('Candidature Catch Error:', error);
    errEl.textContent = 'Erreur réseau.';
    errEl.classList.remove('hidden');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Envoyer ma candidature ✓';
  }
}

</script>
@endpush
@endsection
