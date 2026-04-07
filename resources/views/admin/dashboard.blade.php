@extends('layouts.app')
@section('title','Administration — JobPlatform')
@section('content')
<div class="flex min-h-[calc(100vh-64px)]" x-data="appA()" x-init="init()">
  <!-- Sidebar -->
  <div class="sidebar hidden lg:flex flex-col">
    <div class="px-2 py-4 mb-2 text-center">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-black text-xl mx-auto mb-3 shadow-lg shadow-red-200" style="background:linear-gradient(135deg,#dc2626,#991b1b)">AD</div>
      <div class="font-bold text-slate-800 text-sm" style="font-family:Outfit">Administrateur</div>
      <div class="text-[10px] uppercase tracking-widest text-slate-400 mt-1 font-bold">Gestion Système</div>
    </div>
    <template x-for="n in nav" :key="n.id">
      <button @click="tab=n.id" :class="tab===n.id?'sidebar-link active':'sidebar-link'" class="group">
        <span x-text="n.icon" class="text-lg"></span>
        <span x-text="n.label"></span>
      </button>
    </template>
    <div class="mt-auto pt-3 border-t border-slate-100">
      <button @click="API.logout()" class="sidebar-link text-red-500 hover:bg-red-50 w-full">🚪 Déconnexion</button>
    </div>
  </div>

  <!-- Main -->
  <main class="flex-1 overflow-auto bg-[#F8FAFC]">
    <div class="p-6 lg:p-10 max-w-6xl mx-auto">
      
      <!-- DASHBOARD STATS -->
      <div x-show="tab==='stats'" x-cloak>
        <h2 class="text-2xl font-black text-slate-800 mb-1" style="font-family:Outfit">Tableau de bord</h2>
        <p class="text-slate-400 text-sm mb-8 font-medium">Statistiques globales de la plateforme</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
          <div class="card p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-4">
              <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 text-xl">👥</div>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Candidats</span>
            </div>
            <div class="text-3xl font-black text-slate-800" x-text="stats.candidats||0"></div>
          </div>
          <div class="card p-6 border-l-4 border-amber-500">
            <div class="flex items-center justify-between mb-4">
              <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 text-xl">🏢</div>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Entreprises</span>
            </div>
            <div class="text-3xl font-black text-slate-800" x-text="stats.entreprises||0"></div>
          </div>
          <div class="card p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-4">
              <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-500 text-xl">📄</div>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Offres</span>
            </div>
            <div class="text-3xl font-black text-slate-800" x-text="stats.offres||0"></div>
          </div>
          <div class="card p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
              <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-500 text-xl">✅</div>
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Candidatures</span>
            </div>
            <div class="text-3xl font-black text-slate-800" x-text="stats.candidatures||0"></div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-5 text-sm uppercase tracking-wider flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-amber-500"></span> Entreprises en attente
            </h3>
            <div x-show="pendingE.length===0" class="text-center py-10 text-slate-400 text-sm">Aucune demande en attente.</div>
            <div class="space-y-3">
              <template x-for="e in pendingE" :key="e.idEntreprise">
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                  <div>
                    <div class="font-bold text-slate-800 text-sm" x-text="e.raison_sociale"></div>
                    <div class="text-xs text-slate-400" x-text="e.email"></div>
                  </div>
                  <div class="flex gap-2">
                    <button @click="viewE(e.idEntreprise)" class="btn btn-ghost btn-sm px-3">👁️</button>
                    <button @click="validerE(e.idEntreprise, 'valide')" class="btn btn-success btn-sm px-3">✓</button>
                    <button @click="validerE(e.idEntreprise, 'bloque')" class="btn btn-danger btn-sm px-3">✕</button>
                  </div>
                </div>
              </template>
            </div>
          </div>
          <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-5 text-sm uppercase tracking-wider flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-red-500"></span> Derniers Signalements
            </h3>
            <div x-show="signalements.length===0" class="text-center py-10 text-slate-400 text-sm">Aucun signalement.</div>
            <div class="space-y-3">
              <template x-for="s in signalements.slice(0,5)" :key="s.idSignalement">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors" @click="tab='signalements'">
                  <div class="flex justify-between mb-1">
                    <span class="text-[10px] font-black text-red-500 uppercase tracking-widest" x-text="s.cible_type"></span>
                    <span class="text-[10px] text-slate-400 font-bold" x-text="new Date(s.created_at).toLocaleDateString()"></span>
                  </div>
                  <div class="text-xs font-bold text-slate-800 mb-1" x-text="'Signalé par : ' + getActorName(s.signaleur, s.signaleur_type)"></div>
                  <div class="text-[10px] text-slate-500 line-clamp-1 italic" x-text="s.description"></div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>

      <!-- GESTION ENTREPRISES -->
      <div x-show="tab==='entreprises'" x-cloak>
        <h2 class="text-2xl font-black text-slate-800 mb-1" style="font-family:Outfit">Gestion des Entreprises</h2>
        <p class="text-slate-400 text-sm mb-8">Valider, bloquer ou supprimer des comptes entreprises</p>
        <div class="card overflow-hidden">
          <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
              <tr>
                <th class="px-6 py-4">Entreprise</th>
                <th class="px-6 py-4">Domaine</th>
                <th class="px-6 py-4">Statut</th>
                <th class="px-6 py-4">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <template x-for="e in entreprises" :key="e.idEntreprise">
                <tr class="hover:bg-slate-50 transition-colors">
                  <td class="px-6 py-4">
                    <div class="font-bold text-slate-800 text-sm" x-text="e.raison_sociale"></div>
                    <div class="text-xs text-slate-400" x-text="e.email"></div>
                  </td>
                  <td class="px-6 py-4 text-xs text-slate-500" x-text="e.domaine?.nom || 'N/A'"></td>
                  <td class="px-6 py-4">
                    <span class="badge" :class="{'badge-green':e.statut==='valide','badge-amber':e.statut==='en_attente','badge-red':e.statut==='bloque'}" x-text="e.statut"></span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex gap-2">
                      <button @click="viewE(e.idEntreprise)" class="btn btn-ghost btn-sm px-3">👁️ Voir</button>
                      <button x-show="e.statut!=='valide'" @click="validerE(e.idEntreprise, 'valide')" class="btn btn-success btn-sm">Valider</button>
                      <button x-show="e.statut==='valide'" @click="validerE(e.idEntreprise, 'bloque')" class="btn btn-danger btn-sm">Bloquer</button>
                      <button @click="deleteE(e.idEntreprise)" class="text-slate-300 hover:text-red-500 transition-colors">🗑</button>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>

      <!-- GESTION CANDIDATS -->
      <div x-show="tab==='candidats'" x-cloak>
        <h2 class="text-2xl font-black text-slate-800 mb-1" style="font-family:Outfit">Gestion des Candidats</h2>
        <p class="text-slate-400 text-sm mb-8">Liste des talents inscrits sur la plateforme</p>
        <div class="card overflow-hidden">
          <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
              <tr>
                <th class="px-6 py-4">Candidat</th>
                <th class="px-6 py-4">Domaine</th>
                <th class="px-6 py-4">Localisation</th>
                <th class="px-6 py-4">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <template x-for="c in candidats" :key="c.idCandidat">
                <tr class="hover:bg-slate-50 transition-colors">
                  <td class="px-6 py-4">
                    <div class="font-bold text-slate-800 text-sm" x-text="c.prenom + ' ' + c.nom"></div>
                    <div class="text-xs text-slate-400" x-text="c.email"></div>
                  </td>
                  <td class="px-6 py-4 text-xs text-slate-500" x-text="c.domaine?.nom || 'N/A'"></td>
                  <td class="px-6 py-4 text-xs text-slate-500" x-text="c.localisation || 'Non précisée'"></td>
                  <td class="px-6 py-4">
                    <button @click="deleteC(c.idCandidat)" class="text-slate-300 hover:text-red-500 transition-colors">🗑</button>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>

      <!-- GESTION OFFRES -->
      <div x-show="tab==='offres'" x-cloak>
        <h2 class="text-2xl font-black text-slate-800 mb-1" style="font-family:Outfit">Gestion des Offres</h2>
        <p class="text-slate-400 text-sm mb-8">Surveiller et modérer les annonces publiées</p>
        <div class="card overflow-hidden">
          <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
              <tr>
                <th class="px-6 py-4">Offre</th>
                <th class="px-6 py-4">Entreprise</th>
                <th class="px-6 py-4">Statut</th>
                <th class="px-6 py-4">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <template x-for="o in offres" :key="o.idOffre">
                <tr class="hover:bg-slate-50 transition-colors">
                  <td class="px-6 py-4">
                    <div class="font-bold text-slate-800 text-sm" x-text="o.titre"></div>
                    <div class="text-xs text-slate-400" x-text="o.typeContrat"></div>
                  </td>
                  <td class="px-6 py-4 text-xs text-slate-500" x-text="o.entreprise?.raison_sociale"></td>
                  <td class="px-6 py-4">
                    <span class="badge" :class="{'badge-green':o.statut==='active','badge-amber':o.statut==='expiree','badge-red':o.statut==='suspendue'}" x-text="o.statut"></span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex gap-2">
                      <button x-show="o.statut==='active'" @click="updateOffre(o.idOffre, 'suspendue')" class="btn btn-danger btn-sm">Suspendre</button>
                      <button x-show="o.statut!=='active'" @click="updateOffre(o.idOffre, 'active')" class="btn btn-success btn-sm">Activer</button>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>

      <!-- GESTION SIGNALEMENTS -->
      <div x-show="tab==='signalements'" x-cloak>
        <h2 class="text-2xl font-black text-slate-800 mb-1" style="font-family:Outfit">Signalements</h2>
        <p class="text-slate-400 text-sm mb-8">Traiter les rapports de la communauté</p>
        <div class="space-y-4">
          <template x-for="s in signalements" :key="s.idSignalement">
            <div class="card p-6 border-l-4" :class="s.statut==='en_attente'?'border-red-500':'border-slate-300'">
              <div class="flex flex-col md:flex-row justify-between gap-6">
                <div class="flex-1 space-y-4">
                  <!-- Header -->
                  <div class="flex items-center gap-3">
                    <span class="badge badge-red uppercase text-[10px]" x-text="s.cible_type"></span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="'ID: #' + s.idSignalement"></span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="new Date(s.created_at).toLocaleString()"></span>
                  </div>

                  <!-- Contenu -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                      <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Signaleur</p>
                      <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm text-xs font-black" x-text="s.signaleur_type==='candidat'?'👤':'🏢'"></div>
                        <div>
                          <p class="text-sm font-bold text-slate-800" x-text="getActorName(s.signaleur, s.signaleur_type)"></p>
                          <p class="text-[10px] text-slate-400 font-bold uppercase" x-text="s.signaleur_type"></p>
                        </div>
                      </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                      <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cible signalée</p>
                      <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm text-xs font-black">🎯</div>
                        <div>
                          <p class="text-sm font-bold text-slate-800" x-text="getCibleName(s.cible, s.cible_type)"></p>
                          <p class="text-[10px] text-slate-400 font-bold uppercase" x-text="s.cible_type + ' (ID: '+s.cible_id+')'"></p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="p-5 rounded-2xl bg-red-50/50 border border-red-100">
                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-2">Motif & Description</p>
                    <p class="text-slate-700 text-sm font-medium leading-relaxed" x-text="s.description"></p>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col justify-between items-end min-w-[180px]">
                  <span class="badge" :class="s.statut==='en_attente'?'badge-amber':s.statut==='traite'?'badge-green':'badge-slate'" x-text="s.statut==='en_attente'?'À traiter':s.statut"></span>
                  
                  <div class="flex flex-col gap-2 w-full" x-show="s.statut==='en_attente'">
                    <button @click="traiterSignalement(s.idSignalement, 'traite')" class="btn btn-success btn-sm w-full justify-center">✓ Marquer traité</button>
                    <button @click="traiterSignalement(s.idSignalement, 'rejete')" class="btn btn-ghost btn-sm w-full justify-center border-slate-200">✕ Rejeter</button>
                  </div>
                  
                  <template x-if="s.admin">
                    <div class="text-[10px] text-slate-400 font-bold">
                      Traité par : <span x-text="s.admin.prenom + ' ' + s.admin.nom"></span>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- AUTRES ONGLETS (Domaines, Régions, etc.) -->
      <div x-show="tab==='settings'" x-cloak>
        <h2 class="text-2xl font-black text-slate-800 mb-1" style="font-family:Outfit">Paramètres Système</h2>
        <p class="text-slate-400 text-sm mb-8">Gérer les domaines, catégories et régions</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-4 text-xs uppercase tracking-widest">Domaines</h3>
            <div class="flex gap-2 mb-4"><input x-model="newDom" class="input text-xs" placeholder="Nouveau..."><button @click="addDom()" class="btn btn-primary btn-sm">+</button></div>
            <div class="space-y-2 max-h-64 overflow-y-auto pr-2">
              <template x-for="d in domaines" :key="d.idDomaine">
                <div class="flex justify-between items-center p-2 rounded-lg bg-slate-50 text-xs font-medium text-slate-600">
                  <span x-text="d.nom"></span>
                  <button @click="delDom(d.idDomaine)" class="text-slate-300 hover:text-red-500">×</button>
                </div>
              </template>
            </div>
          </div>
          <!-- Categories & Regions can be added similarly -->
        </div>
      </div>

    </div>
  </main>

  <!-- Modal Détails Entreprise -->
  <div x-show="showModalE" class="modal-bg" x-cloak @click.self="showModalE=false">
    <div class="modal animate-pop-in">
      <div class="flex justify-between items-start mb-6">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg" 
               style="background:linear-gradient(135deg,#2B5CE6,#1535a0)" 
               x-text="(selectedE?.raison_sociale||'?').charAt(0).toUpperCase()"></div>
          <div>
            <h3 class="text-xl font-black text-slate-800" x-text="selectedE?.raison_sociale"></h3>
            <span class="badge" :class="{'badge-green':selectedE?.statut==='valide','badge-amber':selectedE?.statut==='en_attente','badge-red':selectedE?.statut==='bloque'}" x-text="selectedE?.statut"></span>
          </div>
        </div>
        <button @click="showModalE=false" class="text-slate-300 hover:text-slate-600 text-2xl">&times;</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
          <label class="label">Identifiant légal</label>
          <div class="text-sm font-bold text-slate-700 p-3 bg-slate-50 rounded-xl border border-slate-100" x-text="selectedE?.identifiant_legal"></div>
        </div>
        <div>
          <label class="label">Email professionnel</label>
          <div class="text-sm font-bold text-slate-700 p-3 bg-slate-50 rounded-xl border border-slate-100" x-text="selectedE?.email"></div>
        </div>
        <div>
          <label class="label">Domaine</label>
          <div class="text-sm font-bold text-slate-700 p-3 bg-slate-50 rounded-xl border border-slate-100" x-text="selectedE?.domaine?.nom || 'N/A'"></div>
        </div>
        <div>
          <label class="label">Localisation</label>
          <div class="text-sm font-bold text-slate-700 p-3 bg-slate-50 rounded-xl border border-slate-100" x-text="(selectedE?.ville || '') + (selectedE?.pays ? ', ' + selectedE.pays : '') || 'Non renseignée'"></div>
        </div>
        <div>
          <label class="label">Téléphone</label>
          <div class="text-sm font-bold text-slate-700 p-3 bg-slate-50 rounded-xl border border-slate-100" x-text="selectedE?.telephone || 'Non renseigné'"></div>
        </div>
        <div>
          <label class="label">Site Web</label>
          <a :href="selectedE?.site_web" target="_blank" class="text-sm font-bold text-blue-600 p-3 bg-blue-50 rounded-xl border border-blue-100 block truncate" x-text="selectedE?.site_web || 'Aucun'"></a>
        </div>
      </div>

      <div class="mb-8">
        <label class="label">Description</label>
        <div class="text-sm text-slate-600 p-4 bg-slate-50 rounded-xl border border-slate-100 leading-relaxed" x-text="selectedE?.description || 'Aucune description fournie.'"></div>
      </div>

      <div class="flex gap-3 justify-end pt-6 border-t border-slate-100">
        <button x-show="selectedE?.statut!=='valide'" @click="validerE(selectedE.idEntreprise, 'valide')" class="btn btn-success">Accepter l'entreprise</button>
        <button x-show="selectedE?.statut!=='bloque'" @click="validerE(selectedE.idEntreprise, 'bloque')" class="btn btn-danger">Bloquer / Refuser</button>
        <button @click="showModalE=false" class="btn btn-ghost">Fermer</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function appA(){
  return {
    tab: 'stats',
    stats: {},
    pendingE: [],
    entreprises: [],
    selectedE: null,
    showModalE: false,
    candidats: [],
    offres: [],
    signalements: [],
    domaines: [],
    newDom: '',
    nav: [
      {id:'stats', label:'Vue d\'ensemble', icon:'📊'},
      {id:'entreprises', label:'Entreprises', icon:'🏢'},
      {id:'candidats', label:'Candidats', icon:'👥'},
      {id:'offres', label:'Offres d\'emploi', icon:'📄'},
      {id:'signalements', label:'Signalements', icon:'🚩'},
      {id:'settings', label:'Paramètres', icon:'⚙️'}
    ],
    getActorName(a, type) {
      if(!a) return 'Inconnu';
      if(type === 'candidat') return (a.prenom || '') + ' ' + (a.nom || '');
      if(type === 'entreprise') return a.raison_sociale || 'Entreprise sans nom';
      return 'Utilisateur #' + (a.idCandidat || a.idEntreprise || '?');
    },
    getCibleName(c, type) {
      if(!c) return 'Élément supprimé ou introuvable';
      if(type === 'offre') return c.titre || 'Offre sans titre';
      if(type === 'candidat') return (c.prenom || '') + ' ' + (c.nom || '');
      if(type === 'entreprise') return c.raison_sociale || 'Entreprise';
      if(type === 'message') return 'Message du ' + new Date(c.created_at).toLocaleDateString();
      return type + ' #' + (c.idOffre || c.idCandidat || c.idEntreprise || c.idMessage || '?');
    },
    async init(){
      if(!API.isLogged() || API.type()!=='admin'){ window.location.href='/connexion'; return; }
      this.loadAll();
    },
    async loadAll(){
      const [s, e, c, o, sig, d] = await Promise.all([
        API.get('/admin/dashboard'),
        API.get('/admin/entreprises'),
        API.get('/admin/candidats'),
        API.get('/admin/offres'),
        API.get('/admin/signalements'),
        API.get('/domaines')
      ]);
      if(s?.ok) this.stats = await s.json();
      if(e?.ok) {
        this.entreprises = await e.json();
        this.pendingE = this.entreprises.filter(x => x.statut === 'en_attente');
      }
      if(c?.ok) this.candidats = await c.json();
      if(o?.ok) this.offres = await o.json();
      if(sig?.ok) this.signalements = await sig.json();
      if(d?.ok) this.domaines = await d.json();
    },
    async validerE(id, status){
      const r = await API.patch('/admin/entreprises/'+id+'/statut', {statut: status});
      if(r?.ok) { 
        toast('Statut mis à jour !'); 
        this.showModalE = false;
        this.loadAll(); 
      }
    },
    async viewE(id){
      const r = await API.get('/admin/entreprises/'+id);
      if(r?.ok) {
        this.selectedE = await r.json();
        this.showModalE = true;
      }
    },
    async deleteE(id){
      if(!confirm('Supprimer définitivement cette entreprise ?')) return;
      const r = await API.del('/admin/entreprises/'+id);
      if(r?.ok) { toast('Entreprise supprimée'); this.loadAll(); }
    },
    async deleteC(id){
      if(!confirm('Supprimer définitivement ce candidat ?')) return;
      const r = await API.del('/admin/candidats/'+id);
      if(r?.ok) { toast('Candidat supprimé'); this.loadAll(); }
    },
    async updateOffre(id, status){
      const r = await API.patch('/admin/offres/'+id+'/statut', {statut: status});
      if(r?.ok) { toast('Offre mise à jour !'); this.loadAll(); }
    },
    async traiterSignalement(id, status){
      const r = await API.patch('/admin/signalements/'+id, {statut: status});
      if(r?.ok) { 
        const updated = await r.json();
        const idx = this.signalements.findIndex(x => x.idSignalement === id);
        if(idx !== -1) this.signalements[idx] = updated;
        toast('Signalement traité'); 
      }
    },
    async addDom(){
      if(!this.newDom) return;
      const r = await API.post('/admin/domaines', {nom: this.newDom});
      if(r?.ok) { this.newDom=''; toast('Domaine ajouté'); this.loadAll(); }
    },
    async delDom(id){
      if(!confirm('Supprimer ce domaine ?')) return;
      const r = await API.del('/admin/domaines/'+id);
      if(r?.ok) { toast('Domaine supprimé'); this.loadAll(); }
    }
  }
}
</script>
@endpush
@endsection
