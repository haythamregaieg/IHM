@extends('layouts.app')
@section('title','Mon Espace Candidat — JobPlatform')
@section('content')
<div class="flex min-h-[calc(100vh-64px)] bg-[#F8FAFC]" x-data="appC()" x-init="init()">
  <!-- Sidebar Modernisée -->
  <aside class="sidebar hidden lg:flex flex-col bg-white border-r border-slate-100 shadow-sm w-72 flex-shrink-0">
    <div class="px-6 py-10 text-center">
      <div class="relative inline-block mb-4">
        <div class="w-24 h-24 rounded-[32px] flex items-center justify-center text-white font-black text-3xl shadow-2xl shadow-brand-500/20 transition-transform hover:scale-105 duration-500 overflow-hidden" 
             style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">
          <template x-if="profil.photo_path">
            <img :src="'/storage/'+profil.photo_path" :alt="(profil.prenom || '') + ' ' + (profil.nom || '')" class="w-full h-full object-cover">
          </template>
          <template x-if="!profil.photo_path">
            <span x-text="ini"></span>
          </template>
        </div>
        <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-green-500 border-4 border-white rounded-full shadow-sm"></div>
      </div>
      <h2 class="font-bold text-slate-900 text-xl leading-tight mb-1" x-text="uname" style="font-family:Outfit"></h2>
      <p class="text-slate-400 text-xs font-bold uppercase tracking-widest" x-text="profil.specialite || 'Candidat'"></p>
    </div>

    <nav class="flex-1 px-4 space-y-1.5">
      <template x-for="n in nav" :key="n.id">
        <button @click="tab=n.id" 
                :class="tab===n.id ? 'sidebar-link active shadow-lg shadow-brand-500/5' : 'sidebar-link group'">
          <div class="relative">
            <span x-text="n.icon" class="text-xl transition-transform group-hover:scale-110"></span>
            <template x-if="n.badge && unreadMessages > 0">
              <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
            </template>
          </div>
          <span x-text="n.label" class="font-bold"></span>
        </button>
      </template>
    </nav>

    <div class="p-6 border-t border-slate-50">
      <button @click="API.logout()" class="flex items-center gap-3 w-full px-5 py-4 rounded-2xl text-red-500 font-bold text-sm hover:bg-red-50 transition-all active:scale-95">
        <span class="text-lg">🚪</span> Déconnexion
      </button>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 overflow-x-hidden">
    <!-- Mobile Navigation -->
    <div class="lg:hidden sticky top-0 z-40 glass border-b border-slate-100 px-4 py-3 flex gap-3 overflow-x-auto no-scrollbar scroll-smooth">
      <template x-for="n in nav" :key="n.id">
        <button @click="tab=n.id" 
                :class="tab===n.id ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/30 scale-105' : 'bg-white text-slate-600 border border-slate-100'" 
                class="px-5 py-2.5 rounded-2xl text-xs font-black whitespace-nowrap transition-all flex items-center gap-2">
          <div class="relative flex items-center gap-2">
            <span x-text="n.icon"></span>
            <template x-if="n.badge && unreadMessages > 0">
              <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
            </template>
          </div>
          <span x-text="n.label"></span>
        </button>
      </template>
    </div>

    <div class="p-6 lg:p-12 max-w-6xl mx-auto animate-slide-up">

      <!-- Alerte Score de Visibilité -->
      <template x-if="completion < 100">
        <div class="bg-gradient-to-r from-brand-600 to-brand-800 rounded-[32px] p-8 text-white shadow-2xl shadow-brand-500/20 mb-10 relative overflow-hidden">
          <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
          <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-3 text-center md:text-left">
              <h3 class="text-2xl font-black tracking-tight">Boostez votre visibilité ! 🚀</h3>
              <p class="text-brand-100 font-medium max-w-md">Complétez votre profil à 100% pour apparaître en priorité auprès des recruteurs.</p>
            </div>
            <div class="flex flex-col items-center gap-4">
              <div class="relative w-24 h-24">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                  <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="3" />
                  <path :style="'stroke-dasharray: ' + completion + ', 100'" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" class="transition-all duration-1000" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center font-black text-xl" x-text="completion + '%'"></div>
              </div>
              <button @click="tab='profil'" class="px-6 py-2.5 bg-white text-brand-600 rounded-xl font-black text-xs hover:bg-brand-50 transition-all shadow-xl shadow-black/10">Compléter maintenant</button>
            </div>
          </div>
        </div>
      </template>

      <!-- SECTION : ACCUEIL -->
      <div x-show="tab==='accueil'" x-cloak class="space-y-10">
        <div>
          <h1 class="text-3xl lg:text-5xl font-black text-slate-900 mb-3 tracking-tight">Bonjour, <span class="text-brand-600" x-text="profil.prenom"></span> ! 👋</h1>
          <p class="text-slate-500 font-medium text-lg">Prêt à franchir une nouvelle étape dans votre carrière ?</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="card p-6 border-l-4 border-brand-500 group hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-brand-50 flex items-center justify-center text-brand-600 text-2xl group-hover:scale-110 transition-transform">📄</div>
              <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Postulées</span>
            </div>
            <div class="text-3xl font-black text-slate-900" x-text="stats.candidatures_total || 0"></div>
          </div>
          <div class="card p-6 border-l-4 border-amber-500 group hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 text-2xl group-hover:scale-110 transition-transform">⏳</div>
              <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">En attente</span>
            </div>
            <div class="text-3xl font-black text-slate-900" x-text="stats.en_attente || 0"></div>
          </div>
          <div class="card p-6 border-l-4 border-green-500 group hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 text-2xl group-hover:scale-110 transition-transform">✨</div>
              <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Retenues</span>
            </div>
            <div class="text-3xl font-black text-slate-900" x-text="stats.retenues || 0"></div>
          </div>
          <div class="card p-6 border-l-4 border-purple-500 group hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 text-2xl group-hover:scale-110 transition-transform">🔖</div>
              <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Favoris</span>
            </div>
            <div class="text-3xl font-black text-slate-900" x-text="stats.favoris || 0"></div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Guide de complétion -->
          <div class="lg:col-span-2 space-y-8">
            <div class="card p-8 bg-gradient-to-br from-brand-600 to-brand-900 text-white relative overflow-hidden group">
              <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
              <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                  <div>
                    <h3 class="text-2xl font-black mb-1">Score de visibilité</h3>
                    <p class="text-brand-100 text-sm font-medium">Complétez votre profil pour attirer les recruteurs.</p>
                  </div>
                  <div class="text-4xl font-black bg-white/20 px-4 py-2 rounded-2xl backdrop-blur-md" x-text="completion+'%'"></div>
                </div>
                <div class="h-4 bg-white/20 rounded-full mb-8 p-1">
                  <div class="h-full bg-white rounded-full transition-all duration-1000 shadow-sm" :style="'width:'+completion+'%'"></div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <template x-if="!profil.cv_path">
                    <button @click="tab='profil'" class="px-5 py-2.5 bg-white text-brand-600 hover:bg-brand-50 rounded-xl text-xs font-black transition-all shadow-lg shadow-black/10">📄 Ajouter mon CV (+30%)</button>
                  </template>
                  <template x-if="!profil.specialite">
                    <button @click="tab='profil'" class="px-5 py-2.5 bg-white/20 hover:bg-white/30 border border-white/30 rounded-xl text-xs font-black transition-all">🎯 Spécialité (+20%)</button>
                  </template>
                  <template x-if="certificats.length===0">
                    <button @click="tab='certificats'" class="px-5 py-2.5 bg-white/20 hover:bg-white/30 border border-white/30 rounded-xl text-xs font-black transition-all">🎓 Diplômes (+20%)</button>
                  </template>
                </div>
              </div>
            </div>

            <!-- Activités Récentes -->
            <div class="card p-8">
              <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-900">Activités récentes</h3>
                <button @click="tab='candidatures'" class="text-sm font-black text-brand-600 hover:text-brand-700 transition-colors">Voir tout →</button>
              </div>
              <div x-show="recents.length===0" class="text-center py-16">
                <div class="text-6xl mb-6 grayscale opacity-50">🔍</div>
                <h4 class="text-lg font-black text-slate-900 mb-2">Aucune activité</h4>
                <p class="text-slate-400 font-medium mb-8">Commencez par rechercher des offres qui vous correspondent.</p>
                <button @click="tab='chercher'" class="btn btn-primary px-8">Trouver mon premier job</button>
              </div>
              <div class="space-y-4">
                <template x-for="c in recents.slice(0,5)" :key="c.idCandidature">
                  <div class="group flex items-center justify-between p-5 rounded-[24px] bg-slate-50/50 border border-slate-100 hover:border-brand-200 hover:bg-white hover:shadow-xl hover:shadow-brand-500/5 transition-all cursor-pointer">
                    <div class="flex items-center gap-5">
                      <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-brand-600 font-black text-xl shadow-sm group-hover:scale-110 transition-transform border border-slate-50 overflow-hidden">
                        <template x-if="c.offre?.entreprise?.logo_path">
                          <img :src="'/storage/'+c.offre.entreprise.logo_path" :alt="'Logo ' + (c.offre?.entreprise?.raison_sociale || 'entreprise')" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!c.offre?.entreprise?.logo_path">
                          <span x-text="(c.offre?.entreprise?.raison_sociale || '?').charAt(0).toUpperCase()"></span>
                        </template>
                      </div>
                      <div>
                        <div class="font-black text-slate-900 group-hover:text-brand-600 transition-colors leading-tight mb-1" x-text="c.offre?.titre"></div>
                        <div class="flex items-center gap-2">
                          <span class="text-xs font-bold text-slate-400" x-text="c.offre?.entreprise?.raison_sociale"></span>
                          <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                          <span class="text-xs font-bold text-slate-400" x-text="new Date(c.dateCandidature).toLocaleDateString('fr-FR')"></span>
                        </div>
                      </div>
                    </div>
                    <span class="badge" :class="{
                      'badge-amber':c.statut==='en_attente',
                      'badge-blue':c.statut==='en_cours',
                      'badge-green':c.statut==='retenu',
                      'badge-red':c.statut==='refuse'
                    }" x-text="statLabel(c.statut)"></span>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <!-- Sidebar Droite -->
          <div class="space-y-8">
            <div class="card p-8 bg-[#FFF9E5] border-amber-100 relative overflow-hidden">
              <div class="relative z-10">
                <div class="w-12 h-12 bg-amber-400 rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-lg shadow-amber-200">🏆</div>
                <h4 class="text-xl font-black text-slate-900 mb-2">Devenez Premium</h4>
                <p class="text-sm font-medium text-slate-600 mb-8 leading-relaxed">Apparaissez en priorité auprès des recruteurs et boostez vos chances de 300%.</p>
                <button class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-slate-800 transition-all active:scale-95">Bientôt disponible</button>
              </div>
            </div>
            
            <div class="card p-8">
              <h4 class="font-black text-slate-900 mb-6 flex items-center gap-2">
                <span>⚡</span> Suggestions
              </h4>
              <div class="space-y-6">
                <div class="flex gap-4">
                  <div class="w-2 h-2 rounded-full bg-brand-500 mt-2"></div>
                  <p class="text-sm font-medium text-slate-600">Améliorez votre lettre de motivation pour chaque offre.</p>
                </div>
                <div class="flex gap-4">
                  <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                  <p class="text-sm font-medium text-slate-600">Ajoutez au moins 3 compétences clés à votre profil.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION : CHERCHER -->
      <div x-show="tab==='chercher'" x-cloak class="space-y-10">
        <div>
          <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mb-3 tracking-tight">Trouver mon futur <span class="text-brand-600">job</span></h2>
          <p class="text-slate-500 font-medium text-lg">Des opportunités sélectionnées pour vous en Tunisie.</p>
        </div>

        <div class="card p-4 glass border-white/50 flex flex-col md:flex-row gap-4 shadow-2xl shadow-brand-500/5">
          <div class="flex-[1.5] relative">
            <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input x-model="searchQ" type="text" class="input border-transparent bg-slate-50/50 pl-14 focus:bg-white h-14 text-lg font-medium" placeholder="Titre, compétence..." @keydown.enter="searchOffres(1)">
          </div>
          <div class="flex-1 relative">
            <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <select x-model="searchDomaine" class="input border-transparent bg-slate-50/50 pl-14 h-14 appearance-none cursor-pointer font-bold text-slate-700">
              <option value="">Tous les domaines</option>
              <template x-for="d in domaines" :key="d.idDomaine">
                <option :value="d.idDomaine" x-text="d.nom"></option>
              </template>
            </select>
          </div>
          <div class="flex-1 relative">
            <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <select x-model="searchRegion" class="input border-transparent bg-slate-50/50 pl-14 h-14 appearance-none cursor-pointer font-bold text-slate-700">
              <option value="">Toute la Tunisie</option>
              <template x-for="r in regions" :key="r.idRegion">
                <option :value="r.idRegion" x-text="r.ville"></option>
              </template>
            </select>
          </div>
          <button @click="searchOffres(1)" class="btn btn-primary px-10 h-14 text-lg">Rechercher</button>
        </div>

        <!-- Résultats de recherche -->
        <div x-show="searchResults.data?.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <template x-for="o in searchResults.data" :key="o.idOffre">
            <div class="card card-hover p-8 flex flex-col h-full group border-slate-100">
              <div class="flex justify-between items-start mb-6">
                <div class="w-16 h-16 rounded-[24px] flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-brand-500/10 group-hover:scale-110 transition-transform duration-500 overflow-hidden" 
                     style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">
                  <template x-if="o.entreprise?.logo_path">
                    <img :src="'/storage/'+o.entreprise.logo_path" :alt="'Logo ' + (o.entreprise?.raison_sociale || 'entreprise')" class="w-full h-full object-cover">
                  </template>
                  <template x-if="!o.entreprise?.logo_path">
                    <span x-text="(o.entreprise?.raison_sociale || '?').charAt(0).toUpperCase()"></span>
                  </template>
                </div>
                <div class="flex flex-col items-end gap-2">
                  <span class="badge badge-blue px-4 py-1.5" x-text="o.typeContrat"></span>
                  <template x-if="o.remuneration">
                    <span class="text-green-600 font-black text-sm" x-text="Number(o.remuneration).toLocaleString() + ' DT'"></span>
                  </template>
                </div>
              </div>
              
              <h3 class="text-2xl font-black text-slate-900 mb-2 leading-tight group-hover:text-brand-600 transition-colors" x-text="o.titre"></h3>
              <p class="font-bold text-slate-500 mb-6 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                <span x-text="o.entreprise?.raison_sociale"></span>
              </p>
              
              <div class="flex flex-wrap gap-4 mt-auto pt-6 border-t border-slate-50">
                <div class="flex items-center gap-2 text-sm text-slate-500 font-bold bg-slate-50 px-3 py-1.5 rounded-xl">
                  <span>📍</span> <span x-text="(o.region?.ville || '') + (o.localisation ? ' - ' + o.localisation : '')"></span>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500 font-bold bg-slate-50 px-3 py-1.5 rounded-xl">
                  <span>💼</span> <span x-text="o.domaine?.nom || 'Général'"></span>
                </div>
              </div>
              
              <div class="mt-8">
                <a :href="'/offres/'+o.idOffre" class="btn btn-secondary w-full justify-center py-3.5">Voir les détails</a>
              </div>
            </div>
          </template>
        </div>

        <!-- Empty State Search -->
        <div x-show="initialSearchDone && (!searchResults.data || searchResults.data.length === 0)" class="text-center py-24 bg-white rounded-[48px] border-2 border-dashed border-slate-100">
          <div class="text-7xl mb-8 grayscale opacity-30">🏜️</div>
          <h3 class="text-2xl font-black text-slate-900 mb-3">Aucun résultat trouvé</h3>
          <p class="text-slate-400 font-medium mb-10 max-w-md mx-auto">Nous n'avons pas trouvé d'offres correspondant à vos critères. Essayez d'élargir votre recherche.</p>
          <button @click="searchQ='';searchRegion='';searchDomaine='';searchOffres(1)" class="btn btn-secondary px-8">Réinitialiser les filtres</button>
        </div>
        
        <!-- Loading State Initial -->
        <div x-show="!initialSearchDone" class="text-center py-24 bg-white rounded-[48px] border-2 border-dashed border-slate-100">
          <div class="animate-bounce text-7xl mb-8">🔍</div>
          <h3 class="text-2xl font-black text-slate-900 mb-3">Préparation de vos recommandations...</h3>
          <p class="text-slate-400 font-medium mb-10 max-w-md mx-auto">Nous recherchons les meilleures opportunités basées sur votre profil.</p>
        </div>
      </div>

      <!-- SECTION : CANDIDATURES -->
      <div x-show="tab==='candidatures'" x-cloak class="space-y-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Mes <span class="text-brand-600">candidatures</span></h2>
            <p class="text-slate-500 font-medium">Suivez l'état de vos demandes en temps réel.</p>
          </div>
          <div class="flex bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 overflow-x-auto no-scrollbar">
            <button @click="filtCand=''" :class="filtCand===''?'bg-brand-600 text-white shadow-lg shadow-brand-500/20':'text-slate-500 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-xs font-black transition-all whitespace-nowrap">Tous</button>
            <button @click="filtCand='en_attente'" :class="filtCand==='en_attente'?'bg-amber-500 text-white shadow-lg shadow-amber-500/20':'text-slate-500 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-xs font-black transition-all whitespace-nowrap">En attente</button>
            <button @click="filtCand='retenu'" :class="filtCand==='retenu'?'bg-green-500 text-white shadow-lg shadow-green-500/20':'text-slate-500 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-xs font-black transition-all whitespace-nowrap">Retenus ✓</button>
            <button @click="filtCand='refuse'" :class="filtCand==='refuse'?'bg-red-500 text-white shadow-lg shadow-red-500/20':'text-slate-500 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-xs font-black transition-all whitespace-nowrap">Refusés ✕</button>
          </div>
        </div>

        <div x-show="filteredC.length === 0" class="text-center py-24 bg-white rounded-[48px] border border-slate-100">
          <div class="text-7xl mb-8 grayscale opacity-30">📄</div>
          <h3 class="text-2xl font-black text-slate-900 mb-3">Aucune candidature</h3>
          <p class="text-slate-400 font-medium mb-10">Vous n'avez pas encore postulé à des offres avec ce statut.</p>
          <button @click="tab='chercher'" class="btn btn-primary px-10">Explorer les offres</button>
        </div>

        <div class="grid grid-cols-1 gap-6">
          <template x-for="c in filteredC" :key="c.idCandidature">
            <div class="card p-8 flex flex-col md:flex-row md:items-center justify-between gap-8 group hover:border-brand-100 transition-all">
              <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-[24px] flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-brand-500/10 group-hover:scale-110 transition-transform duration-500 overflow-hidden" 
                     style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">
                  <template x-if="c.offre?.entreprise?.logo_path">
                    <img :src="'/storage/'+c.offre.entreprise.logo_path" class="w-full h-full object-cover">
                  </template>
                  <template x-if="!c.offre?.entreprise?.logo_path">
                    <span x-text="(c.offre?.entreprise?.raison_sociale || '?').charAt(0).toUpperCase()"></span>
                  </template>
                </div>
                <div>
                  <h3 class="text-xl font-black text-slate-900 mb-1 group-hover:text-brand-600 transition-colors" x-text="c.offre?.titre"></h3>
                  <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-bold text-slate-500" x-text="c.offre?.entreprise?.raison_sociale"></span>
                    <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                    <span class="text-sm font-bold text-slate-400" x-text="'Postulé le ' + new Date(c.dateCandidature).toLocaleDateString('fr-FR')"></span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-4">
                <span class="badge" :class="{
                  'badge-amber':c.statut==='en_attente',
                  'badge-blue':c.statut==='en_cours',
                  'badge-green':c.statut==='retenu',
                  'badge-red':c.statut==='refuse'
                }" x-text="statLabel(c.statut)"></span>
                <div class="flex gap-2">
                  <button @click="retirerC(c.idCandidature)" class="p-3 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-2xl transition-all" title="Retirer ma candidature">🗑</button>
                  <a :href="'/offres/'+c.idOffre" class="btn btn-secondary btn-sm px-5">Voir l'offre</a>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- SECTION : PROFIL -->
      <div x-show="tab==='profil'" x-cloak class="space-y-10">
        <div>
          <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Mon <span class="text-brand-600">profil</span></h2>
          <p class="text-slate-500 font-medium">Gérez vos informations personnelles et votre CV.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2 space-y-8">
            <div class="card p-10 border border-brand-300 bg-brand-200/75">
              <h3 class="text-xl font-black mb-8 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-brand-100 flex items-center justify-center text-lg">👤</span> Informations générales
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                  <label class="label">Prénom</label>
                  <input x-model="profil.prenom" class="input" placeholder="Votre prénom">
                </div>
                <div class="space-y-2">
                  <label class="label">Nom</label>
                  <input x-model="profil.nom" class="input" placeholder="Votre nom">
                </div>
                <div class="space-y-2">
                  <label class="label">Domaine d'activité</label>
                  <select x-model="profil.idDomaine" class="input">
                    <option value="">Sélectionnez un domaine</option>
                    <template x-for="d in domaines" :key="d.idDomaine">
                      <option :value="d.idDomaine" x-text="d.nom"></option>
                    </template>
                  </select>
                </div>
                <div class="space-y-2">
                  <label class="label">Spécialité / Titre du profil</label>
                  <input x-model="profil.specialite" class="input" placeholder="Ex: Développeur Fullstack PHP">
                </div>
                <div class="space-y-2">
                  <label class="label">Niveau d'études</label>
                  <select x-model="profil.niveau_etudes" class="input">
                    <option value="">Niveau d'études</option>
                    <option>Baccalauréat</option>
                    <option>Licence / Bachelor</option>
                    <option>Master / Ingénieur</option>
                    <option>Doctorat</option>
                    <option>Formation Pro</option>
                  </select>
                </div>
                <div class="space-y-2">
                  <label class="label">Expérience</label>
                  <select x-model="profil.niveau_experience" class="input">
                    <option value="">Niveau d'expérience</option>
                    <option>Débutant (0-1 an)</option>
                    <option>Junior (1-3 ans)</option>
                    <option>Intermédiaire (3-5 ans)</option>
                    <option>Senior (5-10 ans)</option>
                    <option>Expert (10+ ans)</option>
                  </select>
                </div>
                <div class="space-y-2">
                  <label class="label">Téléphone</label>
                  <input type="tel" x-model="profil.telephone" class="input" placeholder="Ex: 55 123 456">
                </div>
                <div class="space-y-2">
                  <label class="label">Région</label>
                  <select x-model="profileRegion" class="input">
                    <option value="">Sélectionnez une région</option>
                    <template x-for="r in regions" :key="r.idRegion">
                      <option :value="r.ville" x-text="r.ville"></option>
                    </template>
                  </select>
                </div>
              </div>
              <button @click="saveProfile()" class="btn btn-primary mt-10 px-10 h-14 text-lg w-full md:w-auto">Sauvegarder les modifications</button>
            </div>

            <div class="card p-10">
              <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-lg">📄</span> Mon Curriculum Vitae (CV)
              </h3>
              <div class="flex flex-col md:flex-row items-center gap-8 p-8 rounded-[32px] bg-slate-50/50 border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 rounded-[24px] bg-white flex items-center justify-center text-4xl shadow-sm border border-slate-100">📄</div>
                <div class="flex-1 text-center md:text-left">
                  <template x-if="profil.cv_path">
                    <div>
                      <p class="font-black text-slate-900 text-lg mb-1">Votre CV est en ligne ! ✨</p>
                      <p class="text-slate-500 text-sm font-medium mb-4">Dernière mise à jour : Récemment</p>
                      <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        <a :href="'/storage/'+profil.cv_path" target="_blank" class="btn btn-secondary btn-sm px-6">Voir mon CV</a>
                        <label class="btn btn-ghost btn-sm px-6 cursor-pointer">
                          Remplacer le fichier
                          <input type="file" id="cv-file" class="hidden" @change="importCV()">
                        </label>
                      </div>
                    </div>
                  </template>
                  <template x-if="!profil.cv_path">
                    <div>
                      <p class="font-black text-slate-900 text-lg mb-1">Vous n'avez pas encore de CV</p>
                      <p class="text-slate-500 text-sm font-medium mb-6">Un CV à jour augmente vos chances d'être recruté par 5.</p>
                      <label class="btn btn-primary px-8 cursor-pointer">
                        Importer mon CV (PDF)
                        <input type="file" id="cv-file" class="hidden" @change="importCV()">
                      </label>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>

          <div class="space-y-8">
            <div class="card p-8">
              <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                <span class="text-brand-500">🖼️</span> Photo de profil
              </h3>
              <div class="flex flex-col items-center gap-6 p-8 rounded-[32px] bg-slate-50/50 border-2 border-dashed border-slate-200">
                <div class="w-32 h-32 rounded-[32px] bg-white flex items-center justify-center text-5xl shadow-xl border border-slate-100 overflow-hidden">
                  <template x-if="profil.photo_path">
                    <img :src="'/storage/'+profil.photo_path" :alt="(profil.prenom || '') + ' ' + (profil.nom || '')" class="w-full h-full object-cover">
                  </template>
                  <template x-if="!profil.photo_path">
                    <span x-text="ini"></span>
                  </template>
                </div>
                <label class="btn btn-secondary w-full justify-center py-3.5 cursor-pointer">
                  Changer la photo
                  <input type="file" id="photo-f" accept="image/*" class="hidden" @change="uploadPhoto($event)">
                </label>
              </div>
            </div>

            <div class="card p-8">
              <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                <span class="text-brand-500">🔒</span> Sécurité
              </h3>
              <div class="space-y-5">
                <div>
                  <label class="label text-xs">Ancien mot de passe</label>
                  <input type="password" x-model="pwdForm.ancien" class="input text-sm h-11" placeholder="••••••••">
                </div>
                <div>
                  <label class="label text-xs">Nouveau mot de passe</label>
                  <input type="password" x-model="pwdForm.nouveau" class="input text-sm h-11" placeholder="••••••••">
                </div>
                <div>
                  <label class="label text-xs">Confirmer le nouveau</label>
                  <input type="password" x-model="pwdForm.conf" class="input text-sm h-11" placeholder="••••••••">
                </div>
                <button @click="changePwd()" class="btn btn-secondary w-full py-3.5 mt-2">Mettre à jour</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION : FAVORIS -->
      <div x-show="tab==='favoris'" x-cloak class="space-y-10">
        <div>
          <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Mes <span class="text-brand-600">favoris</span></h2>
          <p class="text-slate-500 font-medium">Retrouvez les offres que vous avez mises de côté.</p>
        </div>

        <div x-show="favoris.length === 0" class="text-center py-24 bg-white rounded-[48px] border border-slate-100">
          <div class="text-7xl mb-8 grayscale opacity-30">🔖</div>
          <h3 class="text-2xl font-black text-slate-900 mb-3">Aucun favori</h3>
          <p class="text-slate-400 font-medium mb-10">Enregistrez des offres pour les retrouver facilement plus tard.</p>
          <button @click="tab='chercher'" class="btn btn-primary px-10">Parcourir les offres</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <template x-for="f in favoris" :key="f.id">
            <div class="card card-hover p-8 flex flex-col h-full group border-slate-100">
              <div class="flex justify-between items-start mb-6">
                <div class="w-16 h-16 rounded-[24px] flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-brand-500/10 group-hover:scale-110 transition-transform duration-500 overflow-hidden" 
                     style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">
                  <template x-if="f.offre?.entreprise?.logo_path">
                    <img :src="'/storage/'+f.offre.entreprise.logo_path" :alt="'Logo ' + (f.offre?.entreprise?.raison_sociale || 'entreprise')" class="w-full h-full object-cover">
                  </template>
                  <template x-if="!f.offre?.entreprise?.logo_path">
                    <span x-text="(f.offre?.entreprise?.raison_sociale || '?').charAt(0).toUpperCase()"></span>
                  </template>
                </div>
                <div class="flex flex-col items-end gap-2">
                  <span class="badge badge-blue px-4 py-1.5" x-text="f.offre?.typeContrat"></span>
                  <button @click="toggleFavDashboard(f.idOffre)" class="text-red-500 text-2xl hover:scale-110 transition-transform">❤️</button>
                </div>
              </div>
              
              <h3 class="text-2xl font-black text-slate-900 mb-2 leading-tight group-hover:text-brand-600 transition-colors" x-text="f.offre?.titre"></h3>
              <p class="font-bold text-slate-500 mb-6 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                <span x-text="f.offre?.entreprise?.raison_sociale"></span>
              </p>
              
              <div class="flex flex-wrap gap-4 mt-auto pt-6 border-t border-slate-50">
                <div class="flex items-center gap-2 text-sm text-slate-500 font-bold bg-slate-50 px-3 py-1.5 rounded-xl">
                  <span>📍</span> <span x-text="f.offre?.localisation"></span>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500 font-bold bg-slate-50 px-3 py-1.5 rounded-xl">
                  <span>💼</span> <span x-text="f.offre?.domaine?.nom || 'Général'"></span>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-4 mt-8">
                <a :href="'/offres/'+f.idOffre" class="btn btn-secondary justify-center py-3.5">Détails</a>
                <template x-if="f.offre?.dejaPostule">
                  <button disabled class="btn btn-primary justify-center py-3.5 opacity-50 cursor-not-allowed text-xs">Déjà postulé</button>
                </template>
                <template x-if="!f.offre?.dejaPostule">
                  <button @click="postulerDirect(f.idOffre)" class="btn btn-primary justify-center py-3.5">Postuler</button>
                </template>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- SECTION : PARCOURS (CERTIFICATS & EXPERIENCES) -->
      <div x-show="tab==='certificats'" x-cloak class="space-y-12 pb-20">
        <!-- Diplômes Section -->
        <div class="space-y-8">
          <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
              <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Mes <span class="text-brand-600">certifications</span></h2>
              <p class="text-slate-500 font-medium">Validez vos compétences avec des diplômes et certificats.</p>
            </div>
            <button @click="showCertM=true;editCert={}" class="btn btn-primary px-8 shadow-lg shadow-brand-500/20">+ Ajouter un diplôme</button>
          </div>

          <div x-show="certificats.length === 0" class="text-center py-16 bg-white rounded-[40px] border-2 border-dashed border-slate-100">
            <div class="text-6xl mb-6 grayscale opacity-30">🎓</div>
            <p class="text-slate-400 font-bold">Aucun diplôme renseigné.</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <template x-for="c in certificats" :key="c.id">
              <div class="card p-8 group hover:border-brand-100 transition-all flex justify-between items-start">
                <div class="flex gap-6">
                  <div class="w-16 h-16 rounded-[24px] bg-brand-50 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">🎓</div>
                  <div>
                    <h3 class="text-xl font-black text-slate-900 mb-1 group-hover:text-brand-600 transition-colors" x-text="c.nom"></h3>
                    <p class="font-bold text-brand-600 mb-2" x-text="c.organisme"></p>
                    <div class="flex items-center gap-2 text-slate-400 text-sm font-bold">
                      <span>📅 Obtenu le :</span>
                      <span x-text="new Date(c.dateObtention).toLocaleDateString('fr-FR')"></span>
                    </div>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button @click="editCert=JSON.parse(JSON.stringify(c));showCertM=true" class="p-2.5 text-slate-300 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">✏️</button>
                  <button @click="delCert(c.id)" class="p-2.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">🗑</button>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- Expériences Section -->
        <div class="space-y-8 pt-12 border-t border-slate-100">
          <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
              <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Mon <span class="text-brand-600">expérience</span></h2>
              <p class="text-slate-500 font-medium">Mettez en avant votre parcours professionnel.</p>
            </div>
            <button @click="showExpM=true;editExp={}" class="btn btn-primary px-8 shadow-lg shadow-brand-500/20">+ Ajouter une expérience</button>
          </div>

          <div x-show="experiences.length === 0" class="text-center py-16 bg-white rounded-[40px] border-2 border-dashed border-slate-100">
            <div class="text-6xl mb-6 grayscale opacity-30">💼</div>
            <p class="text-slate-400 font-bold">Aucune expérience renseignée.</p>
          </div>

          <div class="grid grid-cols-1 gap-6">
            <template x-for="e in experiences" :key="e.id">
              <div class="card p-8 group hover:border-brand-100 transition-all flex justify-between items-start">
                <div class="flex gap-6">
                  <div class="w-16 h-16 rounded-[24px] bg-amber-50 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">💼</div>
                  <div>
                    <h3 class="text-xl font-black text-slate-900 mb-1 group-hover:text-brand-600 transition-colors" x-text="e.poste"></h3>
                    <p class="font-bold text-amber-600 mb-2" x-text="e.entreprise"></p>
                    <div class="flex flex-wrap items-center gap-4 text-slate-400 text-sm font-bold">
                      <div class="flex items-center gap-2">
                        <span>📅</span>
                        <span x-text="new Date(e.dateDebut).toLocaleDateString('fr-FR', {month:'long', year:'numeric'})"></span>
                        <span>-</span>
                        <span x-text="e.dateFin ? new Date(e.dateFin).toLocaleDateString('fr-FR', {month:'long', year:'numeric'}) : 'Présent'"></span>
                      </div>
                    </div>
                    <p class="mt-4 text-slate-500 text-sm leading-relaxed max-w-2xl" x-text="e.description"></p>
                  </div>
                </div>
                <div class="flex gap-2">
                  <button @click="editExp=JSON.parse(JSON.stringify(e));showExpM=true" class="p-2.5 text-slate-300 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-all">✏️</button>
                  <button @click="delExp(e.id)" class="p-2.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">🗑</button>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- Modal Certificat (Corrigé centrage) -->
        <div x-show="showCertM" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" @click.self="showCertM=false">
          <div class="bg-white rounded-[40px] p-10 w-full max-w-lg shadow-2xl animate-pop-in relative overflow-hidden">
            <div class="flex justify-between items-center mb-8">
              <h3 class="text-2xl font-black text-slate-900 tracking-tight" x-text="editCert.id ? 'Modifier le diplôme' : 'Ajouter un diplôme'"></h3>
              <button @click="showCertM=false" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center text-3xl">&times;</button>
            </div>
            <div class="space-y-6">
              <div class="space-y-2">
                <label class="label">Nom du diplôme *</label>
                <input x-model="editCert.nom" class="input" placeholder="Ex: Master en Informatique...">
              </div>
              <div class="space-y-2">
                <label class="label">Établissement *</label>
                <input x-model="editCert.organisme" class="input" placeholder="Ex: Université de Tunis...">
              </div>
              <div class="space-y-2">
                <label class="label">Date d'obtention *</label>
                <input x-model="editCert.dateObtention" type="date" class="input">
              </div>
            </div>
            <div class="flex gap-4 mt-10">
              <button @click="showCertM=false" class="btn btn-secondary flex-1 justify-center py-4 font-black">Annuler</button>
              <button @click="saveCert()" class="btn btn-primary flex-1 justify-center py-4 shadow-xl shadow-brand-500/20 font-black">Sauvegarder ✓</button>
            </div>
          </div>
        </div>

        <!-- Modal Expérience -->
        <div x-show="showExpM" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" @click.self="showExpM=false">
          <div class="bg-white rounded-[40px] p-10 w-full max-w-xl shadow-2xl animate-pop-in relative overflow-hidden max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center mb-8">
              <h3 class="text-2xl font-black text-slate-900 tracking-tight" x-text="editExp.id ? 'Modifier l\'expérience' : 'Ajouter une expérience'"></h3>
              <button @click="showExpM=false" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center text-3xl">&times;</button>
            </div>
            <div class="space-y-6 overflow-y-auto no-scrollbar pr-2">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="label">Poste occupé *</label>
                  <input x-model="editExp.poste" class="input" placeholder="Ex: Développeur PHP Junior">
                </div>
                <div class="space-y-2">
                  <label class="label">Entreprise *</label>
                  <input x-model="editExp.entreprise" class="input" placeholder="Ex: Google, Start-up...">
                </div>
                <div class="space-y-2">
                  <label class="label">Date de début *</label>
                  <input x-model="editExp.dateDebut" type="date" class="input">
                </div>
                <div class="space-y-2">
                  <label class="label">Date de fin (Laisser vide si actuel)</label>
                  <input x-model="editExp.dateFin" type="date" class="input">
                </div>
              </div>
              <div class="space-y-2">
                <label class="label">Description des missions</label>
                <textarea x-model="editExp.description" class="input min-h-[120px] p-6" placeholder="Quelles étaient vos responsabilités ?"></textarea>
              </div>
            </div>
            <div class="flex gap-4 mt-10 pt-6 border-t border-slate-50">
              <button @click="showExpM=false" class="btn btn-secondary flex-1 justify-center py-4 font-black">Annuler</button>
              <button @click="saveExp()" class="btn btn-primary flex-1 justify-center py-4 shadow-xl shadow-brand-500/20 font-black">Sauvegarder ✓</button>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION : MESSAGES -->
      <div x-show="tab==='messages'" x-cloak class="space-y-10">
        <div>
          <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Mes <span class="text-brand-600">messages</span></h2>
          <p class="text-slate-500 font-medium">Discutez directement avec les recruteurs.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-[700px]">
          <!-- Liste des conversations -->
          <div class="card p-0 overflow-hidden flex flex-col bg-white">
            <div class="p-6 border-b border-slate-50 bg-slate-50/50">
              <h3 class="font-black text-slate-900">Conversations</h3>
            </div>
            <div class="flex-1 overflow-y-auto divide-y divide-slate-50 no-scrollbar">
              <template x-for="cv in convs" :key="cv.idMessage">
                <button @click="openConv(cv)" 
                        :class="activConv?.idEntreprise === cv.idEntreprise ? 'bg-brand-50 border-l-4 border-brand-500' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                        class="w-full flex gap-4 items-center p-6 transition-all text-left group">
                  <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-black text-xl flex-shrink-0 shadow-sm transition-transform group-hover:scale-105 overflow-hidden" 
                       style="background:linear-gradient(135deg,#F6A623,#d97706)">
                    <template x-if="cv.entreprise?.logo_path">
                      <img :src="'/storage/'+cv.entreprise.logo_path" :alt="'Logo ' + (cv.entreprise?.raison_sociale || 'entreprise')" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!cv.entreprise?.logo_path">
                      <span x-text="(cv.entreprise?.raison_sociale || '?').charAt(0).toUpperCase()"></span>
                    </template>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="font-black text-slate-900 text-sm truncate mb-1" x-text="cv.entreprise?.raison_sociale || 'Entreprise'"></div>
                    <div class="text-xs text-slate-400 font-medium truncate" x-text="cv.contenu"></div>
                  </div>
                </button>
              </template>
              <div x-show="convs.length === 0" class="p-12 text-center text-slate-300">
                <div class="text-4xl mb-4">💬</div>
                <p class="text-sm font-bold">Aucun message</p>
              </div>
            </div>
          </div>

          <!-- Fenêtre de chat -->
          <div class="lg:col-span-2 card p-0 overflow-hidden flex flex-col bg-white">
            <template x-if="!activConv">
              <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                <div class="w-24 h-24 rounded-[32px] bg-slate-50 flex items-center justify-center text-5xl mb-8">📨</div>
                <h3 class="text-2xl font-black text-slate-900 mb-3">Sélectionnez une conversation</h3>
                <p class="text-slate-400 font-medium max-w-xs">Cliquez sur une entreprise pour commencer à discuter ou voir vos échanges passés.</p>
              </div>
            </template>
            <template x-if="activConv">
              <div class="flex flex-col h-full">
                <div class="flex items-center gap-4 p-6 border-b border-slate-50 bg-slate-50/30">
                  <button @click="activConv=null" class="lg:hidden text-slate-400 hover:text-slate-600 text-2xl">←</button>
                  <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-sm overflow-hidden" 
                       style="background:linear-gradient(135deg,#F6A623,#d97706)">
                    <template x-if="activConv?.logo_path">
                      <img :src="'/storage/'+activConv.logo_path" :alt="'Logo ' + (activConv?.raison_sociale || 'entreprise')" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!activConv?.logo_path">
                      <span x-text="(activConv?.raison_sociale || '?').charAt(0).toUpperCase()"></span>
                    </template>
                  </div>
                  <div>
                    <span class="font-black text-slate-900 block leading-tight" x-text="activConv?.raison_sociale"></span>
                    <span class="text-[10px] font-black text-green-500 uppercase tracking-widest">En ligne</span>
                  </div>
                </div>
                <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-[#FCFDFF]" id="chat-box">
                  <template x-for="m in msgs" :key="m.idMessage">
                    <div :class="m.expediteur==='candidat'?'flex justify-end':'flex justify-start'">
                      <div class="max-w-[80%] space-y-1">
                        <div :class="m.expediteur==='candidat' ? 'bg-brand-600 text-white rounded-t-[20px] rounded-bl-[20px] shadow-lg shadow-brand-500/10' : 'bg-white text-slate-700 rounded-t-[20px] rounded-br-[20px] border border-slate-100 shadow-sm'" 
                             class="px-6 py-4 text-sm font-medium leading-relaxed" x-text="m.contenu"></div>
                        <div :class="m.expediteur==='candidat' ? 'text-right' : 'text-left'" class="text-[10px] font-black text-slate-300 uppercase tracking-widest px-1" x-text="new Date(m.dateEnvoi).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></div>
                      </div>
                    </div>
                  </template>
                </div>
                <div class="p-6 border-t border-slate-50 bg-white">
                  <div class="flex gap-4 p-2 bg-slate-50 rounded-[24px] border border-slate-100">
                    <input x-model="newMsg" @keydown.enter="sendMsg()" class="flex-1 bg-transparent border-none focus:ring-0 px-6 py-3 font-medium text-slate-700" placeholder="Écrivez votre message ici...">
                    <button @click="sendMsg()" class="w-12 h-12 rounded-2xl bg-brand-600 text-white flex items-center justify-center hover:bg-brand-700 transition-all shadow-lg shadow-brand-500/30 active:scale-90">
                      <svg class="w-5 h-5 translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>

<!-- Modal postuler rapide -->
<div id="pm" role="dialog" aria-modal="true" aria-labelledby="pm-title" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-md" x-data="{}" @click.self="document.getElementById('pm').classList.add('hidden')">
  <div class="flex items-center justify-center min-h-full p-4">
    <div class="bg-white rounded-[40px] p-10 w-full max-w-xl shadow-2xl animate-pop-in relative overflow-hidden">
      <div class="absolute top-0 right-0 w-32 h-32 bg-brand-50 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"></div>
      
      <div class="relative z-10">
        <div class="flex justify-between items-center mb-10">
          <div>
            <h3 id="pm-title" class="text-3xl font-black text-slate-900 tracking-tight">Postuler <span class="text-brand-600">vite</span></h3>
            <p class="text-slate-400 font-medium">Laissez un message convaincant au recruteur.</p>
          </div>
          <button onclick="document.getElementById('pm').classList.add('hidden')" class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center text-3xl">&times;</button>
        </div>

        <div id="pm-err" class="hidden mb-6 p-4 rounded-2xl bg-red-50 text-red-600 text-sm font-bold border border-red-100"></div>
        
        <form onsubmit="submitQuick(event)" class="space-y-8">
          <input type="hidden" id="pm-id">
          <div class="space-y-3">
            <label class="label text-lg">Pourquoi vous ? * <span class="text-xs font-bold text-slate-400">(min 50 car.)</span></label>
            <textarea id="pm-lm" class="input p-6 text-lg min-h-[200px]" required minlength="50" placeholder="Décrivez vos motivations, vos compétences clés pour ce poste..."></textarea>
          </div>
          <div class="flex gap-4">
            <button type="button" onclick="document.getElementById('pm').classList.add('hidden')" class="btn btn-secondary flex-1 justify-center py-5 font-black text-lg">Annuler</button>
            <button type="submit" id="pm-btn" class="btn btn-primary flex-1 justify-center py-5 font-black text-lg shadow-xl shadow-brand-500/30">Envoyer ma candidature ✓</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function appC(){
  return {
    tab: 'accueil',
    profil: {},
    ini: '',
    uname: '',
    stats: {},
    recents: [],
    candidatures: [],
    favoris: [],
    certificats: [],
    experiences: [],
    domaines: [],
    regions: [],
    convs: [],
    msgs: [],
    activConv: null,
    newMsg: '',
    showCertM: false,
    editCert: {},
    showExpM: false,
    editExp: {},
    filtCand: '',
    searchQ: '',
    searchRegion: '',
    searchDomaine: '',
    searchContrat: '',
    searchResults: {},
    initialSearchDone: false,
    profileRegion: '',
    pwdForm: {ancien:'', nouveau:'', conf:''},
    completion: 0,
    nav: [
      {id:'accueil', label:'Accueil', icon:'🏠'},
      {id:'chercher', label:'Chercher', icon:'🔍'},
      {id:'candidatures', label:'Demandes', icon:'📄'},
      {id:'certificats', label:'Parcours', icon:'🎓'},
      {id:'favoris', label:'Favoris', icon:'🔖'},
      {id:'messages', label:'Messages', icon:'💬', badge: true},
      {id:'profil', label:'Profil', icon:'👤'}
    ],
    unreadMessages: 0,
    get filteredC(){
      return this.filtCand ? this.candidatures.filter(c => c.statut === this.filtCand) : this.candidatures;
    },
    statLabel(s){
      return {en_attente:'En attente', en_cours:'En cours', retenu:'Retenu ✓', refuse:'Refusé ✕'}[s] || s;
    },
    async init(){
      if(!API.isLogged() || API.type() !== 'candidat'){
        window.location.href = '/connexion';
        return;
      }
      
      // Load initial data from localStorage
      const u = API.user();
      if(u){
        this.profil = {...u};
        this.profileRegion = u.localisation || '';
        this.updateHeader(u);
      }

      // Sync tab with URL if needed
      const urlTab = location.pathname.split('/').filter(Boolean)[1];
      if(urlTab && this.nav.find(n => n.id === urlTab)) this.tab = urlTab;

      await this.loadAll();
      
      // Fetch fresh user data from API
      const resMe = await API.get('/candidat/me');
      if(resMe?.ok){
        const freshUser = await resMe.json();
        this.profil = {...freshUser};
        this.profileRegion = freshUser.localisation || '';
        this.updateHeader(freshUser);
        localStorage.setItem('jp_usr', JSON.stringify(freshUser));
      }

      this.calcCompletion();
      
      // Initial search based on profile (Prioritize Domain over Specialty)
      if(this.profil.idDomaine){
        this.searchDomaine = this.profil.idDomaine;
        this.searchOffres(1);
      } else if(this.profil.specialite){
        this.searchQ = this.profil.specialite;
        this.searchOffres(1);
      } else {
        this.searchOffres(1); // Load everything if no preference
      }
      
      // Check for unread messages periodically
      this.checkUnread();
      setInterval(() => this.checkUnread(), 30000); // Every 30 seconds
    },
    updateHeader(u){
      this.uname = (u.prenom || '') + (u.nom ? ' ' + u.nom : '');
      this.ini = ((u.prenom || '?').charAt(0) + (u.nom || '?').charAt(0)).toUpperCase();
    },
    async checkUnread(){
      const r = await API.get('/candidat/messages/unread-count');
      if(r?.ok){
        const d = await r.json();
        if (d.count > this.unreadMessages && this.unreadMessages !== 0) {
          toast('Vous avez reçu un nouveau message ! 💬');
        }
        this.unreadMessages = d.count;
      }
    },
    async loadAll(){
      const [st, cd, fav, cert, exp, dom, reg, cvs] = await Promise.all([
        API.get('/candidat/dashboard'),
        API.get('/candidat/candidatures'),
        API.get('/candidat/favoris'),
        API.get('/candidat/certificats'),
        API.get('/candidat/experiences'),
        API.get('/domaines'),
        API.get('/regions'),
        API.get('/candidat/messages')
      ]);

      if(st?.ok) this.stats = await st.json();
      if(cd?.ok) {
        this.candidatures = await cd.json();
        this.recents = [...this.candidatures].sort((a,b) => new Date(b.dateCandidature) - new Date(a.dateCandidature));
      }
      if(fav?.ok) this.favoris = await fav.json();
      if(cert?.ok) this.certificats = await cert.json();
      if(exp?.ok) this.experiences = await exp.json();
      if(dom?.ok) this.domaines = await dom.json();
      if(reg?.ok) this.regions = await reg.json();
      if(cvs?.ok) this.convs = await cvs.json();
    },
    calcCompletion(){
      let s = 10; // Base score
      if(this.profil.cv_path) s += 30;
      if(this.profil.specialite) s += 20;
      if(this.profil.localisation) s += 10;
      if(this.profil.niveau_etudes) s += 10;
      if(this.certificats.length > 0) s += 10;
      if(this.experiences.length > 0) s += 10;
      this.completion = Math.min(s, 100);
    },
    async searchOffres(page=1){
      let url = `/offres?page=${page}`;
      if(this.searchQ) url += '&q=' + encodeURIComponent(this.searchQ);
      if(this.searchRegion) url += '&idRegion=' + this.searchRegion;
      if(this.searchDomaine) url += '&idDomaine=' + this.searchDomaine;
      const r = await API.get(url);
      if(r?.ok) {
        this.searchResults = await r.json();
        this.initialSearchDone = true;
      }
    },
    postulerDirect(id){
      document.getElementById('pm-id').value = id;
      document.getElementById('pm').classList.remove('hidden');
    },
    async retirerC(id){
      if(!confirm('Voulez-vous retirer votre candidature ?')) return;
      const r = await API.del('/candidat/candidatures/' + id);
      if(r?.ok){
        toast('Candidature retirée.', 'info');
        this.loadAll();
      }
    },
    async saveProfile(){
      this.profil.localisation = this.profileRegion || '';
      const r = await API.post('/candidat/profile', this.profil);
      if(r?.ok){
        const u = await r.json();
        localStorage.setItem('jp_usr', JSON.stringify(u));
        this.profil = {...u};
        this.profileRegion = u.localisation || '';
        this.updateHeader(u);
        toast('Profil mis à jour ! ✓');
        this.calcCompletion();
      } else toast('Erreur lors de la sauvegarde.', 'error');
    },
    async importCV(){
      const file = document.getElementById('cv-file')?.files?.[0];
      if(!file) return;
      
      const fd = new FormData();
      fd.append('cv', file);
      
      const r = await API.post('/candidat/importer-cv', fd, true);
      if(r?.ok){
        const d = await r.json();
        this.profil.cv_path = d.cv_path;
        toast('CV importé avec succès ! 🎉');
        this.calcCompletion();
      } else toast('Erreur lors de l\'import.', 'error');
    },
    async uploadPhoto(event){
      const file = event.target.files[0];
      if(!file) return;
      
      const fd = new FormData();
      fd.append('photo', file);
      
      const r = await API.post('/candidat/profile', fd, true);
      if(r?.ok){
        const u = await r.json();
        this.profil = {...u};
        localStorage.setItem('jp_usr', JSON.stringify(u));
        this.updateHeader(u);
        toast('Photo de profil mise à jour ! ✓');
      } else toast('Erreur lors de l\'envoi de la photo.', 'error');
    },
    async changePwd(){
      if(this.pwdForm.nouveau !== this.pwdForm.conf){
        toast('Les mots de passe ne correspondent pas.', 'warn');
        return;
      }
      const r = await API.post('/candidat/password', {
        ancien: this.pwdForm.ancien,
        motDePasse: this.pwdForm.nouveau,
        motDePasse_confirmation: this.pwdForm.conf
      });
      if(r?.ok){
        toast('Mot de passe modifié ! ✓');
        this.pwdForm = {ancien:'', nouveau:'', conf:''};
      } else {
        const d = await r.json();
        toast(d.message || 'Erreur.', 'error');
      }
    },
    async saveCert(){
      if(!this.editCert.nom || !this.editCert.organisme || !this.editCert.dateObtention){
        toast('Veuillez remplir tous les champs obligatoires.', 'warn');
        return;
      }
      const r = this.editCert.id 
        ? await API.put('/candidat/certificats/' + this.editCert.id, this.editCert)
        : await API.post('/candidat/certificats', this.editCert);
        
      if(r?.ok){
        await this.loadAll();
        this.showCertM = false;
        toast('Certification enregistrée !');
      } else toast('Erreur lors de l\'enregistrement.', 'error');
    },
    async delCert(id){
      if(!confirm('Supprimer ce certificat ?')) return;
      const r = await API.del('/candidat/certificats/' + id);
      if(r?.ok){
        this.certificats = this.certificats.filter(c => c.id !== id);
        toast('Certificat supprimé.', 'info');
        this.calcCompletion();
      }
    },
    async saveExp(){
      if(!this.editExp.poste || !this.editExp.entreprise || !this.editExp.dateDebut){
        toast('Veuillez remplir tous les champs obligatoires.', 'warn');
        return;
      }
      const r = this.editExp.id 
        ? await API.put('/candidat/experiences/' + this.editExp.id, this.editExp)
        : await API.post('/candidat/experiences', this.editExp);
        
      if(r?.ok){
        await this.loadAll();
        this.showExpM = false;
        toast('Expérience enregistrée !');
        this.calcCompletion();
      } else toast('Erreur lors de l\'enregistrement.', 'error');
    },
    async delExp(id){
      if(!confirm('Supprimer cette expérience ?')) return;
      const r = await API.del('/candidat/experiences/' + id);
      if(r?.ok){
        this.experiences = this.experiences.filter(e => e.id !== id);
        toast('Expérience supprimée.', 'info');
        this.calcCompletion();
      }
    },
    async toggleFavDashboard(idOffre){
      const r = await API.post('/candidat/favoris/toggle/' + idOffre);
      if(r?.ok){
        await this.loadAll();
        toast('Liste des favoris mise à jour.');
      }
    },
    async openConv(cv){
      this.activConv = cv.entreprise;
      const r = await API.get('/candidat/messages/' + (cv.idEntreprise || cv.entreprise?.idEntreprise));
      if(r?.ok){
        this.msgs = await r.json();
        this.checkUnread(); // Refresh count after reading
        this.$nextTick(() => {
          const el = document.getElementById('chat-box');
          if(el) el.scrollTop = el.scrollHeight;
        });
      }
    },
    async sendMsg(){
      const idEnt = this.activConv?.idEntreprise;
      if(!this.newMsg.trim() || !idEnt) return;
      const r = await API.post('/candidat/messages', {
        idEntreprise: idEnt,
        contenu: this.newMsg
      });
      if(r?.ok){
        const m = await r.json();
        this.msgs.push(m);
        this.newMsg = '';
        this.$nextTick(() => {
          const el = document.getElementById('chat-box');
          if(el) el.scrollTop = el.scrollHeight;
        });
      }
    }
  }
}

async function submitQuick(e){
  e.preventDefault();
  const btn = document.getElementById('pm-btn');
  const err = document.getElementById('pm-err');
  
  btn.disabled = true;
  btn.innerHTML = '<span class="animate-spin mr-2">⏳</span> Envoi...';
  err.classList.add('hidden');
  
  const fd = new FormData();
  fd.append('idOffre', document.getElementById('pm-id').value);
  fd.append('lettreMotivation', document.getElementById('pm-lm').value);
  
  const r = await API.post('/candidat/candidatures', fd, true);
  const d = await r.json();
  
  if(r?.ok){
    document.getElementById('pm').classList.add('hidden');
    toast('Candidature envoyée avec succès ! 🎉');
    // Reload data if we are on dashboard
    if(window.appC) {
      // Logic to refresh dashboard stats/list if possible
    }
    setTimeout(() => location.reload(), 1500);
  } else {
    err.textContent = d.message || 'Une erreur est survenue.';
    err.classList.remove('hidden');
    btn.disabled = false;
    btn.textContent = 'Envoyer ma candidature ✓';
  }
}
</script>
@endpush
@endsection
