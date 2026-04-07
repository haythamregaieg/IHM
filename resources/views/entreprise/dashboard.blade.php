@extends('layouts.app')
@section('title','Espace Recruteur — JobPlatform')
@section('content')
<div class="flex min-h-[calc(100vh-64px)] bg-[#F8FAFC]" x-data="appE()" x-init="init()">
  <!-- Sidebar Modernisée -->
  <aside class="sidebar hidden lg:flex flex-col bg-white border-r border-slate-100 shadow-sm w-72 flex-shrink-0">
    <div class="px-6 py-10 text-center">
      <div class="relative inline-block mb-4">
        <div class="w-24 h-24 rounded-[32px] flex items-center justify-center text-white font-black text-3xl shadow-2xl shadow-amber-500/20 transition-transform hover:scale-105 duration-500" 
             style="background:linear-gradient(135deg,#F6A623,#d97706)" x-text="ini"></div>
        <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-green-500 border-4 border-white rounded-full shadow-sm"></div>
      </div>
      <h2 class="font-black text-slate-900 text-xl leading-tight mb-1" x-text="cname" style="font-family:Outfit"></h2>
      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest mt-2">
        <span>✓</span> Entreprise vérifiée
      </div>
    </div>

    <nav class="flex-1 px-4 space-y-1.5">
      <template x-for="n in nav" :key="n.id">
        <button @click="tab=n.id" 
                :class="tab===n.id ? 'sidebar-link active shadow-lg shadow-amber-500/5' : 'sidebar-link group'">
          <div class="relative flex items-center gap-3">
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
      <button @click="API.logout()" class="flex items-center gap-3 w-full px-5 py-4 rounded-2xl text-red-500 font-black text-sm hover:bg-red-50 transition-all active:scale-95">
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
                :class="tab===n.id ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30 scale-105' : 'bg-white text-slate-600 border border-slate-100'" 
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

      <!-- SECTION : TABLEAU DE BORD -->
      <div x-show="tab==='dashboard'" x-cloak class="space-y-10">
        <div>
          <h1 class="text-3xl lg:text-5xl font-black text-slate-900 mb-3 tracking-tight">Espace <span class="text-amber-500">Recruteur</span></h1>
          <p class="text-slate-500 font-medium text-lg">Gérez vos talents et vos offres d'emploi en toute simplicité.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="card p-6 border-l-4 border-amber-500 group hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 text-2xl group-hover:scale-110 transition-transform">📋</div>
              <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Offres totales</span>
            </div>
            <div class="text-3xl font-black text-slate-900" x-text="stats.offres_total || 0"></div>
          </div>
          <div class="card p-6 border-l-4 border-green-500 group hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 text-2xl group-hover:scale-110 transition-transform">⚡</div>
              <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Offres actives</span>
            </div>
            <div class="text-3xl font-black text-slate-900" x-text="stats.offres_actives || 0"></div>
          </div>
          <div class="card p-6 border-l-4 border-brand-500 group hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-brand-50 flex items-center justify-center text-brand-600 text-2xl group-hover:scale-110 transition-transform">👥</div>
              <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Candidatures</span>
            </div>
            <div class="text-3xl font-black text-slate-900" x-text="stats.candidatures_total || 0"></div>
          </div>
          <div class="card p-6 border-l-4 border-purple-500 group hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 text-2xl group-hover:scale-110 transition-transform">✨</div>
              <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Retenues</span>
            </div>
            <div class="text-3xl font-black text-slate-900" x-text="stats.candidatures_retenues || 0"></div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2 space-y-8">
            <div class="card p-8">
              <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-900">Performance des offres</h3>
                <button @click="tab='offres'" class="text-sm font-black text-amber-600 hover:text-amber-700 transition-colors">Gérer tout →</button>
              </div>
              <div x-show="(stats.offres_detail || []).length === 0" class="text-center py-16">
                <div class="text-6xl mb-6 grayscale opacity-50">📋</div>
                <h4 class="text-lg font-black text-slate-900 mb-2">Aucune offre publiée</h4>
                <p class="text-slate-400 font-medium mb-8">Publiez votre première offre pour commencer à recevoir des candidatures.</p>
                <button @click="openOM()" class="btn btn-gold px-8">Publier une offre</button>
              </div>
              <div class="space-y-4">
                <template x-for="o in (stats.offres_detail || []).slice(0,5)" :key="o.idOffre">
                  <div class="group flex items-center justify-between p-5 rounded-[24px] bg-slate-50/50 border border-slate-100 hover:border-amber-200 hover:bg-white hover:shadow-xl hover:shadow-amber-500/5 transition-all cursor-pointer">
                    <div class="flex items-center gap-5 min-w-0">
                      <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-amber-600 font-black text-lg shadow-sm border border-slate-50 flex-shrink-0" x-text="(o.titre || '?').charAt(0).toUpperCase()"></div>
                      <div class="min-w-0">
                        <div class="font-black text-slate-900 group-hover:text-amber-600 transition-colors leading-tight mb-1 truncate" x-text="o.titre"></div>
                        <div class="text-xs font-bold text-slate-400" x-text="'Limite: ' + (o.dateLimite ? new Date(o.dateLimite).toLocaleDateString('fr-FR') : 'N/A')"></div>
                      </div>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0">
                      <span class="text-xs font-black text-brand-600 bg-brand-50 px-3 py-1.5 rounded-xl" x-text="(o.candidatures_count || 0) + ' candidats'"></span>
                      <span class="badge" :class="o.statut==='active'?'badge-green':'badge-slate'" x-text="o.statut"></span>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <div class="space-y-8">
            <div class="card p-8 bg-brand-600 text-white relative overflow-hidden group">
              <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
              <div class="relative z-10">
                <h4 class="text-xl font-black mb-4">Besoin d'aide ?</h4>
                <p class="text-brand-100 text-sm font-medium mb-8 leading-relaxed">Nos experts en recrutement sont là pour vous accompagner dans la rédaction de vos offres.</p>
                <button class="w-full py-4 bg-white text-brand-600 rounded-2xl font-black text-sm hover:bg-brand-50 transition-all active:scale-95 shadow-xl shadow-black/10">Contacter le support</button>
              </div>
            </div>
            
            <div class="card p-8 border-slate-100">
              <h4 class="font-black text-slate-900 mb-6 flex items-center gap-2">
                <span class="text-amber-500">💡</span> Conseils Recruteur
              </h4>
              <div class="space-y-6">
                <div class="flex gap-4">
                  <div class="w-2 h-2 rounded-full bg-amber-500 mt-2 flex-shrink-0"></div>
                  <p class="text-sm font-medium text-slate-600 leading-relaxed">Précisez la rémunération pour attirer 40% de candidats qualifiés en plus.</p>
                </div>
                <div class="flex gap-4">
                  <div class="w-2 h-2 rounded-full bg-green-500 mt-2 flex-shrink-0"></div>
                  <p class="text-sm font-medium text-slate-600 leading-relaxed">Répondez aux candidats sous 48h pour garder un score d'attractivité élevé.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION : GÉRER LES OFFRES -->
      <div x-show="tab==='offres'" x-cloak class="space-y-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Gérer mes <span class="text-amber-500">offres</span></h2>
            <p class="text-slate-500 font-medium">Publiez, modifiez ou suspendez vos annonces en un clic.</p>
          </div>
          <button @click="openOM()" class="btn btn-gold px-10 h-14 text-lg">Publier une offre</button>
        </div>

        <div x-show="offres.length === 0" class="text-center py-24 bg-white rounded-[48px] border-2 border-dashed border-slate-100">
          <div class="text-7xl mb-8 grayscale opacity-30">📋</div>
          <h3 class="text-2xl font-black text-slate-900 mb-3">Aucune offre active</h3>
          <p class="text-slate-400 font-medium mb-10 max-w-sm mx-auto">Vous n'avez pas encore publié d'offres. Commencez dès maintenant à recruter.</p>
          <button @click="openOM()" class="btn btn-gold px-10">Créer ma première offre</button>
        </div>

        <div class="grid grid-cols-1 gap-6">
          <template x-for="o in offres" :key="o.idOffre">
            <div class="card p-8 group hover:border-amber-100 transition-all">
              <div class="flex flex-col lg:flex-row gap-8">
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-4 mb-4">
                    <h3 class="text-2xl font-black text-slate-900 group-hover:text-amber-600 transition-colors truncate" x-text="o.titre"></h3>
                    <span class="badge" :class="o.statut==='active'?'badge-green':o.statut==='suspendue'?'badge-amber':'badge-red'" x-text="o.statut"></span>
                  </div>
                  <div class="flex flex-wrap gap-6 text-sm font-bold text-slate-400">
                    <span class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl">💼 <span class="text-slate-600" x-text="o.typeContrat"></span></span>
                    <span class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl">📍 <span class="text-slate-600" x-text="(o.region?.ville || '') + (o.localisation ? ' - ' + o.localisation : '')"></span></span>
                    <span class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl">📅 Limite: <span class="text-slate-600" x-text="o.dateLimite ? new Date(o.dateLimite).toLocaleDateString('fr-FR') : 'N/A'"></span></span>
                    <span class="flex items-center gap-2 bg-purple-50 px-3 py-1.5 rounded-xl text-purple-600">⚡ Exp: <span x-text="o.experience || 'N/A'"></span></span>
                    <span class="flex items-center gap-2 bg-blue-50 px-3 py-1.5 rounded-xl text-blue-600">🏠 Mode: <span x-text="o.mode_travail || 'N/A'"></span></span>
                    <span class="flex items-center gap-2 bg-emerald-50 px-3 py-1.5 rounded-xl text-emerald-600">🎓 Étude: <span x-text="o.niveau_etude || 'N/A'"></span></span>
                    <span class="flex items-center gap-2 bg-brand-50 px-3 py-1.5 rounded-xl text-brand-600">👥 <span x-text="(o.candidatures_count || 0) + ' candidatures'"></span></span>
                  </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                  <button @click="voirC(o)" class="btn btn-primary px-6 h-12">Voir les candidats</button>
                  <button @click="openOM(o)" class="p-3 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-2xl transition-all" title="Modifier">✏️</button>
                  <button @click="suspendre(o)" class="p-3 text-slate-400 hover:text-brand-500 hover:bg-brand-50 rounded-2xl transition-all" 
                          :class="o.statut==='active' && new Date().toISOString().split('T')[0] < o.dateLimite ? 'opacity-30 cursor-not-allowed' : ''"
                          :title="o.statut==='active' ? (new Date().toISOString().split('T')[0] < o.dateLimite ? 'Désactivation impossible avant le ' + new Date(o.dateLimite).toLocaleDateString() : 'Suspendre') : 'Activer'">
                    <span x-text="o.statut==='active'?'⏸':'▶'"></span>
                  </button>
                  <button @click="delOffre(o.idOffre)" class="p-3 text-red-300 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all" title="Supprimer">🗑</button>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- SECTION : CANDIDATURES REÇUES -->
      <div x-show="tab==='candidatures'" x-cloak class="space-y-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div>
            <div class="flex items-center gap-4 mb-2">
              <h2 class="text-3xl font-black text-slate-900 tracking-tight">Candidatures <span class="text-amber-500">reçues</span></h2>
              <button x-show="selOffre" @click="selOffre=null;loadAll()" class="text-xs font-black text-slate-400 hover:text-amber-500 transition-colors uppercase tracking-widest">← Voir toutes les candidatures</button>
            </div>
            <p class="text-slate-500 font-medium" x-text="selOffre ? 'Pour : ' + selOffre.titre : 'Toutes les candidatures pour vos offres.'"></p>
          </div>
          
          <div class="flex bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 overflow-x-auto no-scrollbar">
            <button @click="filtE=''" :class="filtE===''?'bg-amber-500 text-white shadow-lg shadow-amber-500/20':'text-slate-500 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-xs font-black transition-all whitespace-nowrap">Tous</button>
            <button @click="filtE='en_attente'" :class="filtE==='en_attente'?'bg-amber-500 text-white shadow-lg shadow-amber-500/20':'text-slate-500 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-xs font-black transition-all whitespace-nowrap">En attente</button>
            <button @click="filtE='retenu'" :class="filtE==='retenu'?'bg-green-500 text-white shadow-lg shadow-green-500/20':'text-slate-500 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-xs font-black transition-all whitespace-nowrap">Retenus ✓</button>
            <button @click="filtE='refuse'" :class="filtE==='refuse'?'bg-red-500 text-white shadow-lg shadow-red-500/20':'text-slate-500 hover:bg-slate-50'" class="px-5 py-2.5 rounded-xl text-xs font-black transition-all whitespace-nowrap">Refusés ✕</button>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
          <div x-show="filteredE.length === 0" class="text-center py-20 bg-white rounded-[40px] border border-slate-100">
            <div class="text-6xl mb-6 grayscale opacity-30">👥</div>
            <p class="text-slate-400 font-bold">Aucune candidature trouvée.</p>
          </div>
          <template x-for="c in filteredE" :key="c.idCandidature">
            <div class="card p-8 group hover:border-brand-100 transition-all">
              <div class="flex flex-col lg:flex-row gap-8">
                <div class="flex items-start gap-6 flex-1">
                  <div class="w-16 h-16 rounded-[24px] flex items-center justify-center text-white font-black text-2xl shadow-xl shadow-brand-500/10 group-hover:scale-110 transition-transform duration-500" 
                       style="background:linear-gradient(135deg,#2B5CE6,#1535a0)" x-text="((c.candidat?.prenom||'?').charAt(0)+(c.candidat?.nom||'?').charAt(0)).toUpperCase()"></div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-1">
                        <h3 class="text-xl font-black text-slate-900" x-text="(c.candidat?.prenom||'')+' '+(c.candidat?.nom||'')"></h3>
                        <template x-if="c.offre">
                            <span class="text-xs font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md" x-text="'Poste: ' + c.offre.titre"></span>
                        </template>
                    </div>
                    <p class="text-sm font-bold text-brand-600 mb-3" x-text="c.candidat?.email"></p>
                    <div class="flex flex-wrap gap-4 text-xs font-black text-slate-400 mb-4">
                      <span class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl">📍 <span class="text-slate-600" x-text="c.candidat?.localisation || 'Tunisie'"></span></span>
                      <span class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl">📅 <span class="text-slate-600" x-text="new Date(c.dateCandidature).toLocaleDateString('fr-FR')"></span></span>
                      <span class="flex items-center gap-2 bg-amber-50 px-3 py-1.5 rounded-xl text-amber-600">📞 <span x-text="c.telephone || 'N/A'"></span></span>
                      <span class="flex items-center gap-2 bg-brand-50 px-3 py-1.5 rounded-xl text-brand-600">✉️ <span x-text="c.email_contact || 'N/A'"></span></span>
                      <span class="flex items-center gap-2 bg-purple-50 px-3 py-1.5 rounded-xl text-purple-600">🎓 <span x-text="c.specialite || 'N/A'"></span></span>
                      <span class="flex items-center gap-2 bg-blue-50 px-3 py-1.5 rounded-xl text-blue-600">⚡ <span x-text="c.niveau_experience || 'N/A'"></span></span>
                    </div>
                    <div class="p-6 rounded-[24px] bg-slate-50/50 border border-slate-100 text-sm text-slate-600 leading-relaxed relative overflow-hidden">
                      <div class="font-black text-[10px] text-slate-300 uppercase tracking-widest mb-2">Lettre de motivation</div>
                      <p x-text="c.lettreMotivation"></p>
                    </div>
                  </div>
                </div>
                
                <div class="flex flex-col gap-4 min-w-[200px]">
                  <span class="badge self-end" :class="{'badge-amber':c.statut==='en_attente','badge-blue':c.statut==='en_cours','badge-green':c.statut==='retenu','badge-red':c.statut==='refuse'}" x-text="statLabel(c.statut)"></span>
                  <div class="space-y-3 pt-4 border-t border-slate-50">
                    <select @change="changeStatutC(c.idCandidature,$event.target.value)" class="input text-xs font-black h-11 !rounded-xl">
                      <option value="">Modifier statut...</option>
                      <option value="en_cours">En cours d'examen</option>
                      <option value="retenu">✓ Retenir le candidat</option>
                      <option value="refuse">✕ Refuser</option>
                    </select>
                    <div class="grid grid-cols-2 gap-2">
                      <button @click="showDetail(c)" class="btn btn-secondary btn-sm justify-center h-11" title="Détails">📄 Détails</button>
                      <button @click="msgCandidat(c.candidat)" class="btn btn-primary btn-sm justify-center h-11" title="Contacter">💬 Chat</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- SECTION : MESSAGES -->
      <div x-show="tab==='messages'" x-cloak class="space-y-10">
        <div>
          <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Espace <span class="text-amber-500">Messages</span></h2>
          <p class="text-slate-500 font-medium">Communiquez directement avec vos candidats potentiels.</p>
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
                        :class="activMsgC?.idCandidat === cv.idCandidat ? 'bg-amber-50 border-l-4 border-amber-500' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                        class="w-full flex gap-4 items-center p-6 transition-all text-left group">
                  <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-black text-xl flex-shrink-0 shadow-sm transition-transform group-hover:scale-105" 
                       style="background:linear-gradient(135deg,#2B5CE6,#1535a0)" x-text="(cv.candidat?.prenom || '?').charAt(0).toUpperCase()"></div>
                  <div class="flex-1 min-w-0">
                    <div class="font-black text-slate-900 text-sm truncate mb-1" x-text="(cv.candidat?.prenom||'') + ' ' + (cv.candidat?.nom||'')"></div>
                    <div class="text-xs text-slate-400 font-medium truncate" x-text="cv.contenu"></div>
                  </div>
                </button>
              </template>
              <div x-show="convs.length === 0" class="p-12 text-center text-slate-300">
                <div class="text-4xl mb-4">💬</div>
                <p class="text-sm font-bold">Aucune conversation</p>
              </div>
            </div>
          </div>

          <!-- Fenêtre de chat -->
          <div class="lg:col-span-2 card p-0 overflow-hidden flex flex-col bg-white">
            <template x-if="!activMsgC">
              <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                <div class="w-24 h-24 rounded-[32px] bg-slate-50 flex items-center justify-center text-5xl mb-8">💌</div>
                <h3 class="text-2xl font-black text-slate-900 mb-3">Sélectionnez un candidat</h3>
                <p class="text-slate-400 font-medium max-w-xs">Répondez aux questions des candidats ou planifiez vos entretiens ici.</p>
              </div>
            </template>
            <template x-if="activMsgC">
              <div class="flex flex-col h-full">
                <div class="flex items-center gap-4 p-6 border-b border-slate-50 bg-slate-50/30">
                  <button @click="activMsgC=null" class="lg:hidden text-slate-400 hover:text-slate-600 text-2xl">←</button>
                  <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-sm" 
                       style="background:linear-gradient(135deg,#2B5CE6,#1535a0)" x-text="(activMsgC?.prenom || '?').charAt(0).toUpperCase()"></div>
                  <div>
                    <span class="font-black text-slate-900 block leading-tight" x-text="(activMsgC?.prenom||'') + ' ' + (activMsgC?.nom||'')"></span>
                    <span class="text-[10px] font-black text-green-500 uppercase tracking-widest">Candidat</span>
                  </div>
                </div>
                <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-[#FCFDFF]" id="mce">
                  <template x-for="m in msgsE" :key="m.idMessage">
                    <div :class="m.expediteur==='entreprise'?'flex justify-end':'flex justify-start'">
                      <div class="max-w-[80%] space-y-1">
                        <div :class="m.expediteur==='entreprise' ? 'bg-amber-500 text-white rounded-t-[20px] rounded-bl-[20px] shadow-lg shadow-amber-500/10' : 'bg-white text-slate-700 rounded-t-[20px] rounded-br-[20px] border border-slate-100 shadow-sm'" 
                             class="px-6 py-4 text-sm font-medium leading-relaxed" x-text="m.contenu"></div>
                        <div :class="m.expediteur==='entreprise' ? 'text-right' : 'text-left'" class="text-[10px] font-black text-slate-300 uppercase tracking-widest px-1" x-text="new Date(m.dateEnvoi).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></div>
                      </div>
                    </div>
                  </template>
                </div>
                <div class="p-6 border-t border-slate-50 bg-white">
                  <div class="flex gap-4 p-2 bg-slate-50 rounded-[24px] border border-slate-100">
                    <input x-model="newMsgE" @keydown.enter="sendMsgE()" class="flex-1 bg-transparent border-none focus:ring-0 px-6 py-3 font-medium text-slate-700" placeholder="Écrivez votre message ici...">
                    <button @click="sendMsgE()" class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/30 active:scale-90">
                      <svg class="w-5 h-5 translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- SECTION : PROFIL -->
      <div x-show="tab==='profil'" x-cloak class="space-y-10">
        <div>
          <h2 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Mon <span class="text-amber-500">profil</span> entreprise</h2>
          <p class="text-slate-500 font-medium">Présentez votre marque employeur pour attirer les meilleurs talents.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2 space-y-8">
            <div class="card card-dark p-10">
              <h3 class="text-xl font-black mb-8 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-lg">🏢</span> Informations entreprise
              </h3>
              <div class="space-y-6">
                <div class="space-y-2">
                  <label class="label">Description de l'entreprise</label>
                  <textarea x-model="eprofil.description" class="input p-6 min-h-[150px]" placeholder="Décrivez votre activité, vos valeurs, votre culture d'entreprise..."></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-2">
                    <label class="label">Pays</label>
                    <input x-model="eprofil.pays" class="input" placeholder="Ex: Tunisie">
                  </div>
                  <div class="space-y-2">
                    <label class="label">Ville</label>
                    <input x-model="eprofil.ville" class="input" placeholder="Ex: Tunis">
                  </div>
                  <div class="space-y-2">
                    <label class="label">Téléphone de contact</label>
                    <input x-model="eprofil.telephone" class="input" placeholder="Ex: +216 71 000 000">
                  </div>
                  <div class="space-y-2">
                    <label class="label">Site Web</label>
                    <input x-model="eprofil.site_web" type="url" class="input" placeholder="https://www.votre-entreprise.com">
                  </div>
                </div>
              </div>
              <button @click="saveProfil()" class="btn btn-gold mt-10 px-10 h-14 text-lg w-full md:w-auto">Mettre à jour le profil</button>
            </div>
          </div>

          <div class="space-y-8">
            <div class="card p-8">
              <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                <span class="text-amber-500">🖼️</span> Logo Entreprise
              </h3>
              <div class="flex flex-col items-center gap-6 p-8 rounded-[32px] bg-slate-50/50 border-2 border-dashed border-slate-200">
                <div class="w-32 h-32 rounded-[32px] bg-white flex items-center justify-center text-5xl shadow-xl border border-slate-100 overflow-hidden">
                  <template x-if="eprofil.logo">
                    <img :src="'/storage/'+eprofil.logo" class="w-full h-full object-cover">
                  </template>
                  <template x-if="!eprofil.logo">
                    <span x-text="ini"></span>
                  </template>
                </div>
                <label class="btn btn-secondary w-full justify-center py-3.5 cursor-pointer">
                  Changer le logo
                  <input type="file" id="logo-f" accept="image/*" class="hidden" @change="uploadLogo">
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Détails Candidature -->
      <div id="dm-c" x-show="dmOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md" @click.self="dmOpen=false">
        <div class="flex items-center justify-center min-h-full p-4">
          <div class="card card-dark p-0 w-full max-w-4xl shadow-2xl animate-pop-in relative overflow-hidden max-h-[90vh] flex flex-col">
            <div class="p-8 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center text-white font-black text-xl" 
                     x-text="((selCand?.candidat?.prenom||'?').charAt(0)+(selCand?.candidat?.nom||'?').charAt(0)).toUpperCase()"></div>
                <div>
                  <h3 class="text-xl font-black text-white" x-text="(selCand?.candidat?.prenom||'')+' '+(selCand?.candidat?.nom||'')"></h3>
                  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest" x-text="'Postulé le ' + new Date(selCand?.dateCandidature).toLocaleDateString('fr-FR')"></p>
                </div>
              </div>
              <button @click="dmOpen=false" class="w-10 h-10 rounded-xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center text-2xl">&times;</button>
            </div>

            <div class="flex-1 overflow-y-auto p-8 space-y-8 no-scrollbar">
              <!-- Grid Infos -->
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-4 rounded-2xl bg-slate-800/50 border border-slate-700">
                  <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Email de contact</p>
                  <p class="font-bold text-amber-500" x-text="selCand?.email_contact || 'N/A'"></p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-800/50 border border-slate-700">
                  <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Téléphone</p>
                  <p class="font-bold text-amber-500" x-text="selCand?.telephone || 'N/A'"></p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-800/50 border border-slate-700">
                  <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Spécialité</p>
                  <p class="font-bold text-white" x-text="selCand?.specialite || 'N/A'"></p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-800/50 border border-slate-700">
                  <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Expérience</p>
                  <p class="font-bold text-white" x-text="selCand?.niveau_experience || 'N/A'"></p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-800/50 border border-slate-700">
                  <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Localisation</p>
                  <p class="font-bold text-white" x-text="selCand?.candidat?.localisation || 'Tunisie'"></p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-800/50 border border-slate-700">
                  <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Statut actuel</p>
                  <span class="badge" :class="{'badge-amber':selCand?.statut==='en_attente','badge-blue':selCand?.statut==='en_cours','badge-green':selCand?.statut==='retenu','badge-red':selCand?.statut==='refuse'}" x-text="statLabel(selCand?.statut)"></span>
                </div>
              </div>

              <!-- Lettre de motivation -->
              <div class="space-y-3">
                <h4 class="font-black text-white flex items-center gap-2">
                  <span class="text-amber-500">📝</span> Lettre de motivation
                </h4>
                <div class="p-6 rounded-3xl bg-slate-800/30 border border-slate-700 text-slate-300 leading-relaxed whitespace-pre-line" x-html="selCand?.lettreMotivation?.startsWith('[Fichier PDF joint]') ? 'Le candidat a joint un fichier PDF pour sa lettre de motivation. <br><br> <a href=\'/storage/' + selCand.lettreMotivation.split('] ')[1] + '\' target=\'_blank\' class=\'btn btn-gold btn-sm inline-flex mt-2\'>📄 Ouvrir la lettre PDF</a>' : selCand?.lettreMotivation">
                </div>
              </div>

              <!-- Diplômes & Certifications -->
              <div class="space-y-3">
                <h4 class="font-black text-white flex items-center gap-2">
                  <span class="text-amber-500">🎓</span> Diplômes & Certifications
                </h4>
                <div class="grid grid-cols-1 gap-4">
                  <template x-for="cert in selCand?.candidat?.certificats" :key="cert.id">
                    <div class="p-4 rounded-2xl bg-slate-800/50 border border-slate-700 flex justify-between items-center">
                      <div>
                        <p class="font-bold text-white" x-text="cert.nom"></p>
                        <p class="text-xs text-slate-400" x-text="cert.organisme + ' • ' + new Date(cert.dateObtention).toLocaleDateString()"></p>
                      </div>
                      <span class="text-2xl">📜</span>
                    </div>
                  </template>
                  <template x-if="!selCand?.candidat?.certificats?.length">
                    <p class="text-slate-500 text-sm italic">Aucun diplôme renseigné.</p>
                  </template>
                </div>
              </div>

              <!-- Expériences Professionnelles -->
              <div class="space-y-3">
                <h4 class="font-black text-white flex items-center gap-2">
                  <span class="text-amber-500">💼</span> Expériences Professionnelles
                </h4>
                <div class="grid grid-cols-1 gap-4">
                  <template x-for="exp in selCand?.candidat?.experiences" :key="exp.id">
                    <div class="p-5 rounded-2xl bg-slate-800/50 border border-slate-700 space-y-2">
                      <div class="flex justify-between items-start">
                        <div>
                          <p class="font-bold text-white text-lg" x-text="exp.poste"></p>
                          <p class="font-bold text-amber-500" x-text="exp.entreprise"></p>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-900 px-2 py-1 rounded-md" 
                              x-text="new Date(exp.dateDebut).toLocaleDateString('fr-FR', {month:'short', year:'numeric'}) + ' - ' + (exp.dateFin ? new Date(exp.dateFin).toLocaleDateString('fr-FR', {month:'short', year:'numeric'}) : 'Présent')"></span>
                      </div>
                      <p class="text-sm text-slate-400 leading-relaxed" x-text="exp.description"></p>
                    </div>
                  </template>
                  <template x-if="!selCand?.candidat?.experiences?.length">
                    <p class="text-slate-500 text-sm italic">Aucune expérience renseignée.</p>
                  </template>
                </div>
              </div>

              <!-- CV Viewer -->
              <div class="space-y-3">
                <h4 class="font-black text-white flex items-center gap-2">
                  <span class="text-amber-500">📄</span> Curriculum Vitae (CV)
                </h4>
                <template x-if="selCand?.cv_path">
                  <div class="rounded-3xl border border-slate-700 overflow-hidden bg-slate-800 h-[500px]">
                    <iframe :src="selCand ? '/storage/' + selCand.cv_path : ''" class="w-full h-full border-none"></iframe>
                  </div>
                </template>
                <template x-if="!selCand?.cv_path">
                  <div class="p-12 text-center bg-slate-800/30 rounded-3xl border-2 border-dashed border-slate-700">
                    <p class="text-slate-500 font-bold">Aucun CV n'a été fourni.</p>
                  </div>
                </template>
              </div>
            </div>

            <div class="p-6 border-t border-slate-800 flex gap-4 bg-slate-900/50">
              <button @click="dmOpen=false" class="btn btn-secondary flex-1 justify-center py-4 font-black">Fermer</button>
              <a x-show="selCand?.cv_path" :href="selCand ? '/storage/' + selCand.cv_path : '#'" download class="btn btn-gold flex-1 justify-center py-4 font-black">Télécharger le CV</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Offre -->
      <div id="om" x-show="omOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md" @click.self="closeOM()">
        <div class="flex items-center justify-center min-h-full p-4">
          <div class="card card-dark p-10 w-full max-w-3xl shadow-2xl animate-pop-in relative overflow-hidden max-h-[95vh] overflow-y-auto no-scrollbar">
            <div class="absolute top-0 right-0 w-48 h-48 bg-amber-500/10 rounded-full -translate-y-1/2 translate-x-1/2 -z-0"></div>
            
            <div class="relative z-10">
              <div class="flex justify-between items-center mb-10">
                <div>
                  <h3 id="om-title" class="text-3xl font-black tracking-tight">Publier une <span class="text-amber-500">offre</span></h3>
                  <p class="font-medium">Décrivez le poste pour attirer les meilleurs candidats.</p>
                </div>
                <button @click="closeOM()" class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center text-3xl transition-colors">&times;</button>
              </div>

              <div id="om-err" class="hidden mb-6 p-4 rounded-2xl bg-red-50 text-red-600 text-sm font-bold border border-red-100"></div>
              
              <form @submit.prevent="submitOM" class="space-y-8">
                <input type="hidden" id="om-id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <div class="space-y-3">
                    <label class="label">Titre du poste *</label>
                    <input id="om-titre" class="input" required placeholder="Ex: Développeur Laravel Senior">
                  </div>
                  <div class="space-y-3">
                    <label class="label">Type de contrat *</label>
                    <select id="om-con" class="input" required>
                      <option value="">Sélectionnez un contrat</option>
                      <option>CDI</option>
                      <option>CDD</option>
                      <option>Stage</option>
                      <option>Freelance</option>
                      <option>Alternance</option>
                    </select>
                  </div>
                  <div class="space-y-3">
                    <label class="label">Domaine d'activité *</label>
                    <select id="om-dom" class="input" required><option value="">Sélectionnez un domaine</option></select>
                  </div>
                  <div class="space-y-3">
                    <label class="label">Région *</label>
                    <select id="om-reg" class="input" required><option value="">Sélectionnez une région</option></select>
                  </div>
                  <div class="space-y-3">
                    <label class="label">Localisation précise *</label>
                    <input id="om-loc" class="input" required placeholder="Ex: Tunis, Berges du Lac">
                  </div>
                  <div class="space-y-3">
                    <label class="label">Date limite *</label>
                    <input id="om-date" type="date" class="input" required>
                  </div>
                </div>

                <div class="space-y-3">
                  <label class="label">Description complète *</label>
                  <textarea id="om-desc" class="input p-6 min-h-[150px]" required placeholder="Missions, responsabilités, environnement..."></textarea>
                </div>

                <div class="space-y-3">
                  <label class="label">Compétences requises * <span class="text-xs font-bold text-slate-400">(séparées par des virgules)</span></label>
                  <input id="om-comp" class="input" required placeholder="Ex: PHP, Laravel, Docker, English...">
                </div>

                <div class="space-y-3">
                  <label class="label">Expérience demandée *</label>
                  <input id="om-exp" class="input" required placeholder="Ex: 2-3 ans, Débutant accepté...">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <div class="space-y-3">
                    <label class="label">Mode de travail *</label>
                    <select id="om-mode" class="input" required>
                      <option value="">Sélectionnez un mode</option>
                      <option>Télétravail</option>
                      <option>Sur site</option>
                      <option>Hybride</option>
                    </select>
                  </div>
                  <div class="space-y-3">
                    <label class="label">Niveau d'étude *</label>
                    <input id="om-etude" class="input" required placeholder="Ex: Bac+3, Master, Ingénieur...">
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <div class="space-y-3">
                    <label class="label">Rémunération mensuelle (DT)</label>
                    <input id="om-rem" type="number" class="input" placeholder="Ex: 2500">
                  </div>
                  <div class="space-y-3">
                    <label class="label">Catégorie d'offre *</label>
                    <select id="om-cat" class="input" required><option value="">Sélectionnez une catégorie</option></select>
                  </div>
                </div>

                <div class="flex gap-4 pt-4">
                  <button type="button" @click="closeOM()" class="btn btn-secondary flex-1 justify-center py-5 font-black text-lg">Annuler</button>
                  <button type="submit" id="om-btn" class="btn btn-gold flex-1 justify-center py-5 font-black text-lg shadow-xl shadow-amber-500/30">Enregistrer l'offre ✓</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>

@push('scripts')
<script>
// Initialisation des listes déroulantes du modal
(async () => {
    try {
        const [d, c, r] = await Promise.all([
            API.get('/domaines'),
            API.get('/categories'),
            API.get('/regions')
        ]);
        
        const fill = (id, items, pk) => {
            const s = document.getElementById(id);
            if (!s || !items) return;
            s.innerHTML = '<option value="">Sélectionnez une option</option>';
            items.forEach(x => {
                const o = document.createElement('option');
                o.value = x[pk];
                o.textContent = x.nom || x.ville; // Fallback pour région
                s.appendChild(o);
            });
        };
        
        if (d?.ok) fill('om-dom', await d.json(), 'idDomaine');
        if (c?.ok) fill('om-cat', await c.json(), 'idCategorie');
        if (r?.ok) fill('om-reg', await r.json(), 'idRegion');
    } catch (e) {
        console.error('Erreur chargement selects:', e);
    }
})();

function appE() {
    return {
        tab: 'dashboard',
        ini: '',
        cname: '',
        stats: {},
        offres: [],
        cands: [],
        selOffre: null,
        filtE: '',
        convs: [],
        msgsE: [],
        activMsgC: null,
        newMsgE: '',
        eprofil: {},
        omOpen: false,
        dmOpen: false,
        selCand: null,
        unreadMessages: 0,
        nav: [
            { id: 'dashboard', label: 'Tableau de bord', icon: '📊' },
            { id: 'offres', label: 'Mes offres', icon: '📋' },
            { id: 'candidatures', label: 'Candidats', icon: '👥' },
            { id: 'messages', label: 'Messages', icon: '💬', badge: true },
            { id: 'profil', label: 'Profil', icon: '⚙️' }
        ],
        
        get filteredE() {
            return this.filtE ? this.cands.filter(c => c.statut === this.filtE) : this.cands;
        },
        
        statLabel(s) {
            return { en_attente: 'En attente', en_cours: 'En cours', retenu: 'Retenu ✓', refuse: 'Refusé ✕' }[s] || s;
        },
        
        async init() {
            if (!API.isLogged() || API.type() !== 'entreprise') {
                window.location.href = '/connexion';
                return;
            }
            const u = API.user();
            if (u) {
                this.cname = u.raison_sociale || '';
                this.ini = (u.raison_sociale || '?').substring(0, 2).toUpperCase();
                this.eprofil = { ...u };
            }
            await this.loadAll();
            
            // Fetch fresh profile data
            const resMe = await API.get('/entreprise/me');
            if (resMe?.ok) {
                const freshU = await resMe.json();
                this.eprofil = { ...freshU };
                localStorage.setItem('jp_usr', JSON.stringify(freshU));
            }
            
            // Check for unread messages periodically
            this.checkUnread();
            setInterval(() => this.checkUnread(), 30000); // Every 30 seconds
        },
        
        async checkUnread(){
            const r = await API.get('/entreprise/messages/unread-count');
            if(r?.ok){
                const d = await r.json();
                if (d.count > this.unreadMessages && this.unreadMessages !== 0) {
                    toast('Nouveau message reçu d\'un candidat ! 💬');
                }
                this.unreadMessages = d.count;
            }
        },
        
        async loadAll() {
            try {
                const [st, of, cd] = await Promise.all([
                    API.get('/entreprise/dashboard'),
                    API.get('/entreprise/offres'),
                    API.get('/entreprise/candidatures')
                ]);
                if (st?.ok) this.stats = await st.json();
                if (of?.ok) this.offres = await of.json();
                if (cd?.ok) this.cands = await cd.json();
                
                // Charger les conversations
                const cvs = await API.get('/entreprise/messages');
                if (cvs?.ok) this.convs = await cvs.json();
            } catch (e) {
                console.error('LoadAll error:', e);
            }
        },
        
        openOM(o = null) {
            const title = document.getElementById('om-title');
            const idInput = document.getElementById('om-id');
            const errDiv = document.getElementById('om-err');
            
            // Réinitialiser les erreurs
            if (errDiv) errDiv.classList.add('hidden');
            
            this.omOpen = true;
            
            if (o) {
                title.innerHTML = 'Modifier l\'<span class="text-amber-500">offre</span>';
                idInput.value = o.idOffre;
                document.getElementById('om-titre').value = o.titre || '';
                document.getElementById('om-con').value = o.typeContrat || '';
                document.getElementById('om-loc').value = o.localisation || '';
                document.getElementById('om-dom').value = o.idDomaine || '';
                document.getElementById('om-cat').value = o.idCategorie || '';
                document.getElementById('om-reg').value = o.idRegion || '';
                document.getElementById('om-desc').value = o.description || '';
                document.getElementById('om-comp').value = o.competencesRequises || '';
                document.getElementById('om-exp').value = o.experience || '';
                document.getElementById('om-mode').value = o.mode_travail || '';
                document.getElementById('om-etude').value = o.niveau_etude || '';
                document.getElementById('om-rem').value = o.remuneration || '';
                document.getElementById('om-date').value = o.dateLimite ? o.dateLimite.split('T')[0] : '';
            } else {
                title.innerHTML = 'Publier une <span class="text-amber-500">offre</span>';
                idInput.value = '';
                // Réinitialiser le formulaire
                const form = document.querySelector('#om form');
                if (form) form.reset();
            }
        },
        
        closeOM() {
            this.omOpen = false;
        },
        
        async submitOM() {
            const btn = document.getElementById('om-btn');
            const errDiv = document.getElementById('om-err');
            const id = document.getElementById('om-id').value;
            
            if (!btn || !errDiv) {
                console.error('Modal elements not found');
                return;
            }
            
            btn.disabled = true;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="animate-spin mr-2">⏳</span> Envoi en cours...';
            errDiv.classList.add('hidden');
            
            // Récupérer toutes les valeurs
            const titre = document.getElementById('om-titre')?.value;
            const typeContrat = document.getElementById('om-con')?.value;
            const localisation = document.getElementById('om-loc')?.value;
            const idDomaine = document.getElementById('om-dom')?.value;
            const idCategorie = document.getElementById('om-cat')?.value;
            const idRegion = document.getElementById('om-reg')?.value;
            const description = document.getElementById('om-desc')?.value;
            const competencesRequises = document.getElementById('om-comp')?.value;
            const experience = document.getElementById('om-exp')?.value || '';
            const mode_travail = document.getElementById('om-mode')?.value || '';
            const niveau_etude = document.getElementById('om-etude')?.value || '';
            const remuneration = document.getElementById('om-rem')?.value || null;
            const dateLimite = document.getElementById('om-date')?.value || '';
            
            // Validation rapide
            if (!titre || !typeContrat || !localisation || !idDomaine || !idCategorie || !idRegion || !description || !competencesRequises || !experience || !mode_travail || !niveau_etude || !dateLimite) {
                errDiv.innerHTML = 'Veuillez remplir tous les champs obligatoires.';
                errDiv.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = originalText;
                return;
            }
            
            const data = {
                titre: String(titre).trim(),
                typeContrat: String(typeContrat).trim(),
                localisation: String(localisation).trim(),
                idDomaine: parseInt(idDomaine) || null,
                idCategorie: parseInt(idCategorie) || null,
                idRegion: parseInt(idRegion) || null,
                description: String(description).trim(),
                competencesRequises: String(competencesRequises).trim(),
                experience: String(experience).trim(),
                mode_travail: String(mode_travail).trim(),
                niveau_etude: String(niveau_etude).trim(),
                remuneration: remuneration ? parseFloat(remuneration) : null,
                dateLimite: dateLimite
            };
            
            console.log('Sending data:', data); // Debug log
            
            try {
                let response;
                if (id) {
                    response = await API.put(`/entreprise/offres/${id}`, data);
                } else {
                    response = await API.post('/entreprise/offres', data);
                }
                
                if (response.ok) {
                    this.closeOM();
                    toast(id ? 'Offre mise à jour avec succès !' : 'Offre publiée avec succès !');
                    
                    // Recharger les données
                    await this.loadAll();
                } else {
                    const errorData = await response.json();
                    const errorMessage = errorData.message || (errorData.errors ? Object.values(errorData.errors).flat().join(', ') : 'Une erreur est survenue');
                    errDiv.innerHTML = errorMessage;
                    errDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Submit error:', error);
                errDiv.innerHTML = 'Erreur de connexion au serveur. Veuillez réessayer.';
                errDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        },
        
        async voirC(o) {
            this.selOffre = o;
            const r = await API.get('/entreprise/offres/' + o.idOffre + '/candidatures');
            if (r?.ok) this.cands = await r.json();
            this.tab = 'candidatures';
        },
        
        showDetail(c) {
            this.selCand = c;
            this.dmOpen = true;
        },
        
        async changeStatutC(id, statut) {
            if (!statut) return;
            const r = await API.patch('/entreprise/candidatures/' + id + '/statut', { statut });
            if (r?.ok) {
                const c = this.cands.find(x => x.idCandidature === id);
                if (c) c.statut = statut;
                toast('Statut mis à jour !');
                await this.loadAll();
            } else {
                toast('Erreur lors du changement.', 'error');
            }
        },
        
        async suspendre(o) {
            const r = await API.patch('/entreprise/offres/' + o.idOffre + '/suspendre');
            if (r?.ok) {
                const d = await r.json();
                o.statut = d.statut;
                toast(d.statut === 'suspendue' ? 'Offre suspendue.' : 'Offre réactivée.', 'info');
                await this.loadAll();
            } else {
                const err = await r.json();
                toast(err.message || 'Erreur lors de la modification de l\'offre.', 'error');
            }
        },
        
        async delOffre(id) {
            if (!confirm('Supprimer cette offre définitivement ?')) return;
            const r = await API.del('/entreprise/offres/' + id);
            if (r?.ok) {
                toast('Offre supprimée.', 'info');
                await this.loadAll();
            }
        },
        
        msgCandidat(c) {
            this.activMsgC = c;
            this.tab = 'messages';
            this.openConv({ idCandidat: c.idCandidat, candidat: c });
        },
        
        async openConv(cv) {
            this.activMsgC = cv.candidat;
            const idCand = cv.idCandidat || (cv.candidat ? cv.candidat.idCandidat : null);
            if (!idCand) return;
            const r = await API.get('/entreprise/messages/' + idCand);
            if (r?.ok) {
                this.msgsE = await r.json();
                this.checkUnread(); // Refresh count after reading
                this.$nextTick(() => {
                    const el = document.getElementById('mce');
                    if (el) el.scrollTop = el.scrollHeight;
                });
            }
        },
        
        async sendMsgE() {
            const idCand = this.activMsgC ? this.activMsgC.idCandidat : null;
            if (!this.newMsgE.trim() || !idCand) return;
            const r = await API.post('/entreprise/messages', {
                idCandidat: idCand,
                contenu: this.newMsgE
            });
            if (r?.ok) {
                const m = await r.json();
                this.msgsE.push(m);
                this.newMsgE = '';
                this.$nextTick(() => {
                    const el = document.getElementById('mce');
                    if (el) el.scrollTop = el.scrollHeight;
                });
            }
        },
        
        async saveProfil() {
            const fd = new FormData();
            fd.append('_method', 'PUT');
            ['description', 'pays', 'ville', 'telephone', 'site_web'].forEach(k => {
                if (this.eprofil[k]) fd.append(k, this.eprofil[k]);
            });
            
            const r = await API.post('/entreprise/profile', fd, true);
            if (r?.ok) {
                const u = await r.json();
                localStorage.setItem('jp_usr', JSON.stringify(u));
                this.eprofil = { ...u };
                this.cname = u.raison_sociale || '';
                toast('Profil entreprise mis à jour ! ✓');
            } else {
                toast('Erreur lors de la sauvegarde.', 'error');
            }
        },
        
        async uploadLogo(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const fd = new FormData();
            fd.append('_method', 'PUT');
            fd.append('logo', file);
            
            const r = await API.post('/entreprise/profile', fd, true);
            if (r?.ok) {
                const u = await r.json();
                localStorage.setItem('jp_usr', JSON.stringify(u));
                this.eprofil.logo_path = u.logo_path;
                toast('Logo mis à jour ! ✓');
            } else {
                toast('Erreur lors du téléchargement.', 'error');
            }
        }
    };
}
</script>
@endpush
@endsection