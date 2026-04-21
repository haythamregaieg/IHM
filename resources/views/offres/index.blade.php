@extends('layouts.app')
@section('title',"Offres d'emploi — JobPlatform")
@section('content')
<div class="bg-[#F8FAFC] min-h-[calc(100vh-64px)] transition-colors duration-300">
  <!-- Hero Search Section -->
  <div class="bg-white border-b border-slate-100 py-12 lg:py-16 relative overflow-hidden transition-colors duration-300">
    <div class="absolute top-0 right-0 w-96 h-96 bg-brand-50 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl opacity-50"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 text-center">
      <h1 class="text-4xl lg:text-6xl font-black text-slate-900 tracking-tight mb-4" style="font-family:Outfit">Trouvez votre <span class="text-brand-600">futur job</span></h1>
      <p class="text-slate-500 font-medium text-lg lg:text-xl max-w-2xl mx-auto mb-10">Explorez des milliers d'offres d'emploi vérifiées en Tunisie et propulsez votre carrière.</p>
      
      <div class="card p-2 flex flex-col md:flex-row gap-2 shadow-2xl shadow-brand-500/10 max-w-4xl mx-auto border-slate-100">
        <div class="flex-[2] relative">
          <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input id="f-q" type="text" class="input border-transparent bg-slate-50/50 pl-14 h-14 font-medium focus:bg-white" placeholder="Titre du poste, mots-clés..." onkeydown="if(event.key==='Enter')load(1)">
        </div>
        <div class="flex-1 relative border-l border-slate-100">
          <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <select id="f-reg" class="input border-transparent bg-slate-50/50 pl-14 h-14 appearance-none cursor-pointer font-bold text-slate-700 focus:bg-white">
            <option value="">Toute la Tunisie</option>
          </select>
        </div>
        <button onclick="load(1)" class="btn btn-primary px-10 h-14 text-lg">Rechercher</button>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
    <div class="flex flex-col lg:flex-row gap-10">
      <!-- Sidebar filtres -->
      <aside class="w-full lg:w-80 flex-shrink-0">
        <div class="lg:sticky lg:top-24 space-y-8">
          <div class="card p-8 border-slate-100 transition-colors duration-300">
            <h3 class="font-black text-slate-900 mb-8 flex items-center gap-2">
              <span>🎯</span> Filtres avancés
            </h3>
            
            <div class="space-y-6">
              <div class="space-y-2">
                <label class="label text-[10px] font-black uppercase tracking-widest text-slate-400">Domaine d'activité</label>
                <select id="f-dom" class="input bg-slate-50 border-transparent text-sm h-12 font-bold text-slate-700 cursor-pointer focus:bg-white transition-all"><option value="">Tous les domaines</option></select>
              </div>
              
              <div class="space-y-2">
                <label class="label text-[10px] font-black uppercase tracking-widest text-slate-400">Type de contrat</label>
                <select id="f-con" class="input bg-slate-50 border-transparent text-sm h-12 font-bold text-slate-700 cursor-pointer focus:bg-white transition-all">
                  <option value="">Tous les types</option>
                  <option>CDI</option>
                  <option>CDD</option>
                  <option>Stage</option>
                  <option>Freelance</option>
                  <option>Alternance</option>
                </select>
              </div>

              <div class="space-y-2">
                <label class="label text-[10px] font-black uppercase tracking-widest text-slate-400">Rémunération min (DT)</label>
                <input id="f-rmin" type="number" min="0" class="input bg-slate-50 border-transparent text-sm h-12 font-bold text-slate-700 focus:bg-white transition-all" placeholder="Ex: 1500">
              </div>

              <div class="pt-6 border-t border-slate-50 flex flex-col gap-3">
                <button onclick="load(1)" class="btn btn-secondary w-full justify-center h-12 text-sm">Appliquer les filtres</button>
                <button onclick="reset()" class="text-xs font-black text-slate-400 hover:text-red-500 transition-colors py-2">↺ Réinitialiser tout</button>
              </div>
            </div>
          </div>

          <!-- Premium Banner -->
          <div class="card p-8 bg-gradient-to-br from-brand-600 to-brand-900 text-white relative overflow-hidden group border-none">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl transition-transform group-hover:scale-150 duration-700"></div>
            <div class="relative z-10">
              <h4 class="font-black text-lg mb-2">Soyez le premier informé !</h4>
              <p class="text-brand-100 text-xs font-medium mb-6 leading-relaxed">Activez les alertes emploi pour recevoir les nouvelles offres directement dans votre boîte mail.</p>
              <button class="w-full py-3 bg-white text-brand-600 rounded-xl font-black text-xs hover:bg-brand-50 transition-all active:scale-95">Activer les alertes</button>
            </div>
          </div>
        </div>
      </aside>

      <!-- Résultats -->
      <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
          <div>
            <h2 id="rc" class="text-xl font-black text-slate-900 tracking-tight">Chargement...</h2>
            <p class="text-slate-400 text-sm font-medium">Offres triées par pertinence</p>
          </div>
          <div class="flex items-center gap-3">
            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Trier par</span>
            <select id="f-sort" onchange="load(1)" class="input !py-2 !h-auto !w-auto text-xs font-black border-slate-100 shadow-sm cursor-pointer">
              <option value="latest">Plus récentes</option>
              <option value="oldest">Plus anciennes</option>
              <option value="salary">Salaire déc.</option>
              <option value="salary_asc">Salaire croiss.</option>
              <option value="popular">Plus populaires</option>
              <option value="deadline">Expire bientôt</option>
            </select>
          </div>
        </div>

        <div id="list" class="space-y-6">
          <!-- Skeletons Loading -->
          <div class="animate-pulse space-y-6">
            <template x-for="i in 3">
              <div class="card p-8 h-48 bg-slate-50 border-transparent"></div>
            </template>
          </div>
        </div>

        <!-- Pagination Modernisée -->
        <div id="pag" class="flex justify-center gap-3 mt-12 flex-wrap"></div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
const cc = {
  CDI: 'badge-blue',
  CDD: 'badge-amber',
  Stage: 'badge-green',
  Freelance: 'badge-purple',
  Alternance: 'badge-slate'
};

const domainIcons = {
  'informatique & technologie': '💻',
  'finance & comptabilite': '💰',
  'marketing & communication': '📣',
  'ressources humaines': '🤝',
  'sante & medecine': '⚕️',
  'ingenierie & industrie': '⚙️',
  'commerce & vente': '🛒',
  'education & formation': '🎓',
  'droit & juridique': '⚖️',
  'architecture & btp': '🏗️',
  'mecaniques': '🔧'
};

function getDomainIcon(domainName = '') {
  const key = domainName
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '');
  return domainIcons[key] || '💼';
}

function getOfferImage(o) {
  const normalizedDomain = (o?.domaine?.nom || '')
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '');

  const byDomain = {
    informatique: [
      'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1200&q=80'
    ],
    finance: [
      'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1200&q=80'
    ],
    marketing: [
      'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1432888622747-4eb9a8efeb07?auto=format&fit=crop&w=1200&q=80'
    ],
    ressources: [
      'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80'
    ],
    sante: [
      'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1584515933487-779824d29309?auto=format&fit=crop&w=1200&q=80'
    ],
    ingenierie: [
      'https://images.unsplash.com/photo-1581092446327-9f89c3f3f2c5?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?auto=format&fit=crop&w=1200&q=80'
    ],
    commerce: [
      'https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1200&q=80'
    ],
    education: [
      'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1200&q=80'
    ],
    droit: [
      'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1593115057322-e94b77572f20?auto=format&fit=crop&w=1200&q=80'
    ],
    architecture: [
      'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1200&q=80'
    ],
    mecanique: [
      'https://images.unsplash.com/photo-1487754180451-c456f719a1fc?auto=format&fit=crop&w=1200&q=80',
      'https://images.unsplash.com/photo-1530046339160-ce3e530c7d2f?auto=format&fit=crop&w=1200&q=80'
    ]
  };

  const matchedKey = Object.keys(byDomain).find(k => normalizedDomain.includes(k));
  const selectedPool = matchedKey ? byDomain[matchedKey] : [
    'https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1200&q=80'
  ];

  const numericId = Number(o?.idOffre) || 0;
  return selectedPool[numericId % selectedPool.length];
}

function escapeHtml(str = '') {
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

function renderOfferCard(o) {
  return `
    <div class="card card-hover p-8 group border-slate-100 animate-slide-up relative overflow-hidden bg-white transition-colors duration-300">
      <div class="absolute top-0 right-0 w-32 h-32 bg-brand-50 rounded-full -translate-y-1/2 translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
      <div class="flex flex-col md:flex-row gap-8 items-start relative z-10">
        <div class="w-16 h-16 rounded-[24px] flex items-center justify-center text-white font-black text-2xl flex-shrink-0 shadow-xl shadow-brand-500/10 transition-transform duration-500 group-hover:scale-110 overflow-hidden" 
             style="background:linear-gradient(135deg,#2B5CE6,#1535a0)">
          ${o.entreprise?.logo_path ? `<img src="/storage/${o.entreprise.logo_path}" class="w-full h-full object-cover">` : (o.entreprise?.raison_sociale || '?').charAt(0).toUpperCase()}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-4 mb-3 flex-wrap">
            <div>
              <h3 class="text-2xl font-black text-slate-900 group-hover:text-brand-600 transition-colors leading-tight mb-1" style="font-family:Outfit">${escapeHtml(o.titre)}</h3>
              <div class="flex items-center gap-2">
                <span class="text-base font-bold text-brand-600">${escapeHtml(o.entreprise?.raison_sociale || 'Entreprise')}</span>
                <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                <span class="text-sm font-bold text-slate-400">Publiée ${new Date(o.datePublication).toLocaleDateString('fr-FR')}</span>
                <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                <span class="text-sm font-bold ${new Date(o.dateLimite) < new Date() ? 'text-red-500' : 'text-amber-600'}">Expire le ${new Date(o.dateLimite).toLocaleDateString('fr-FR')}</span>
              </div>
            </div>
            <span class="badge ${cc[o.typeContrat] || 'badge-slate'} px-4 py-1.5 text-xs font-black uppercase tracking-widest">${escapeHtml(o.typeContrat || 'Contrat')}</span>
          </div>
          
          <div class="mb-6 relative rounded-2xl overflow-hidden border border-slate-100 h-28 bg-slate-100">
            <img src="${getOfferImage(o)}"
                 alt="Illustration de l'offre ${escapeHtml(o.titre)}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/55 via-slate-900/25 to-transparent"></div>
            <div class="absolute bottom-2 left-3 text-white text-[10px] font-black uppercase tracking-widest">Aperçu du poste</div>
          </div>
          
          <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2 bg-slate-50 px-3.5 py-1.5 rounded-xl text-[10px] font-black text-slate-500 uppercase tracking-widest">
              <span>📍</span> ${escapeHtml(o.region?.ville || o.localisation || 'Tunisie')}
            </div>
            ${o.remuneration ? `
              <div class="flex items-center gap-2 bg-green-50 px-3.5 py-1.5 rounded-xl text-[10px] font-black text-green-600 uppercase tracking-widest">
                <span>💰</span> ${Number(o.remuneration).toLocaleString()} DT / mois
              </div>
            ` : ''}
            <div class="flex items-center gap-2 bg-brand-50 px-3.5 py-1.5 rounded-xl text-[10px] font-black text-brand-600 uppercase tracking-widest">
              <span>${getDomainIcon(o.domaine?.nom || '')}</span> ${escapeHtml(o.domaine?.nom || 'Général')}
            </div>
            ${o.experience ? `
              <div class="flex items-center gap-2 bg-purple-50 px-3.5 py-1.5 rounded-xl text-[10px] font-black text-purple-600 uppercase tracking-widest">
                <span>⚡</span> ${escapeHtml(o.experience)}
              </div>
            ` : ''}
            ${o.mode_travail ? `
              <div class="flex items-center gap-2 bg-blue-50 px-3.5 py-1.5 rounded-xl text-[10px] font-black text-blue-600 uppercase tracking-widest">
                <span>🏠</span> ${escapeHtml(o.mode_travail)}
              </div>
            ` : ''}
            ${o.niveau_etude ? `
              <div class="flex items-center gap-2 bg-emerald-50 px-3.5 py-1.5 rounded-xl text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                <span>🎓</span> ${escapeHtml(o.niveau_etude)}
              </div>
            ` : ''}
          </div>

        </div>
        <div class="flex md:flex-col gap-2 w-full md:w-auto mt-4 md:mt-0">
          <a href="/offres/${o.idOffre}" class="btn w-full md:w-44 justify-center py-3.5 px-6 border-2 border-amber-700 bg-amber-600 text-white font-black shadow-md hover:bg-amber-700 hover:border-amber-800 hover:shadow-lg transition-all">
            Voir détails
          </a>
          ${new Date(o.dateLimite) < new Date() ? `
            <button disabled class="btn w-full md:w-44 justify-center py-3.5 px-6 border-2 border-red-200 bg-red-50 text-red-600 opacity-70 cursor-not-allowed font-black">
              Offre expirée
            </button>
          ` : o.dejaPostule ? `
            <button disabled class="btn w-full md:w-44 justify-center py-3.5 px-6 border-2 border-emerald-300 bg-emerald-50 text-emerald-700 opacity-70 cursor-not-allowed font-black">
              Déjà postulé
            </button>
          ` : `
            <a href="/offres/${o.idOffre}" class="btn w-full md:w-44 justify-center py-3.5 px-6 border-2 border-brand-900 bg-brand-800 text-white font-black shadow-lg shadow-brand-900/30 hover:bg-brand-900 hover:border-brand-900 hover:shadow-brand-900/40 transition-all">
              Postuler maintenant
            </a>
          `}
        </div>
      </div>
    </div>
  `;
}

function renderGroupedOffers(items) {
  const grouped = items.reduce((acc, item) => {
    const key = item?.domaine?.nom || 'Autres domaines';
    if (!acc[key]) acc[key] = [];
    acc[key].push(item);
    return acc;
  }, {});

  return Object.entries(grouped).map(([domain, offers]) => `
    <section class="space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-lg md:text-xl font-black text-slate-900 flex items-center gap-2">
          <span>${getDomainIcon(domain)}</span>
          <span>${escapeHtml(domain)}</span>
        </h3>
        <span class="text-xs font-black uppercase tracking-wider text-slate-400">${offers.length} offre${offers.length > 1 ? 's' : ''}</span>
      </div>
      <div class="space-y-5">
        ${offers.map(renderOfferCard).join('')}
      </div>
    </section>
  `).join('');
}

async function load(page = 1) {
  const q = document.getElementById('f-q').value;
  const dom = document.getElementById('f-dom').value;
  const reg = document.getElementById('f-reg').value;
  const con = document.getElementById('f-con').value;
  const rmin = document.getElementById('f-rmin').value;
  const sort = document.getElementById('f-sort').value;

  let url = `/offres?page=${page}&sort=${sort}`;
  if (q) url += '&q=' + encodeURIComponent(q);
  if (dom) url += '&idDomaine=' + dom;
  if (reg) url += '&idRegion=' + reg;
  if (con) url += '&typeContrat=' + encodeURIComponent(con);
  if (rmin) url += '&remunMin=' + rmin;

  const listEl = document.getElementById('list');
  // Loading skeletons
  listEl.innerHTML = Array(3).fill(0).map(() => `
    <div class="card p-8 h-48 bg-slate-50 border-transparent animate-pulse flex gap-6">
      <div class="w-16 h-16 bg-slate-100 rounded-[24px]"></div>
      <div class="flex-1 space-y-4">
        <div class="h-6 bg-slate-100 rounded-full w-1/3"></div>
        <div class="h-4 bg-slate-100 rounded-full w-1/4"></div>
        <div class="h-4 bg-slate-100 rounded-full w-full"></div>
      </div>
    </div>
  `).join('');

  try {
    const res = await API.get(url);
    if (!res?.ok) throw new Error();
    
    const data = await res.json();
    const items = data.data || [];
    
    document.getElementById('rc').textContent = `${data.total || 0} offre${data.total > 1 ? 's' : ''} disponible${data.total > 1 ? 's' : ''}`;

    if (!items.length) {
      listEl.innerHTML = `
        <div class="text-center py-24 bg-white rounded-[48px] border-2 border-dashed border-slate-100 animate-slide-up">
          <div class="text-7xl mb-8 grayscale opacity-30">🔍</div>
          <h3 class="text-2xl font-black text-slate-900 mb-3">Aucune offre trouvée</h3>
          <p class="text-slate-400 font-medium mb-10 max-w-sm mx-auto">Nous n'avons pas trouvé d'offres correspondant à vos filtres actuels. Essayez d'élargir votre recherche.</p>
          <button onclick="reset()" class="btn btn-secondary px-10">Voir toutes les offres</button>
        </div>
      `;
      return;
    }

    listEl.innerHTML = dom ? items.map(renderOfferCard).join('') : renderGroupedOffers(items);

    // Pagination
    const lp = data.last_page || 1;
    const pg = document.getElementById('pag');
    pg.innerHTML = lp > 1 ? Array.from({ length: lp }, (_, i) => `
      <button onclick="load(${i + 1})" 
              class="w-10 h-10 rounded-xl font-black text-sm transition-all flex items-center justify-center
              ${i + 1 === page ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/30 scale-110' : 'bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 active:scale-95'}">
        ${i + 1}
      </button>
    `).join('') : '';
    
    // Scroll to top of list
    if (page > 1) window.scrollTo({ top: 0, behavior: 'smooth' });

  } catch (e) {
    listEl.innerHTML = '<div class="card p-12 text-center text-red-500 font-black border-red-100 bg-red-50">Erreur lors du chargement des offres. Veuillez actualiser la page.</div>';
  }
}

function reset() {
  ['f-q', 'f-dom', 'f-reg', 'f-con', 'f-rmin'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  load(1);
}

// Data fetching
(async () => {
  const [d, r] = await Promise.all([API.get('/domaines'), API.get('/regions')]);
  
  const fill = (sid, items, pk) => {
    const s = document.getElementById(sid);
    if (!s || !items) return;
    s.innerHTML = sid === 'f-dom'
      ? '<option value="">Tous les domaines</option>'
      : '<option value="">Toute la Tunisie</option>';
    items.forEach(x => {
      const o = document.createElement('option');
      o.value = x[pk];
      o.textContent = x.nom || x.ville; // Fallback pour région
      s.appendChild(o);
    });
  };

  if (d?.ok) fill('f-dom', await d.json(), 'idDomaine');
  if (r?.ok) fill('f-reg', await r.json(), 'idRegion');

  // Handle URL params
  const p = new URLSearchParams(location.search);
  const map = { q: 'f-q', idDomaine: 'f-dom', idRegion: 'f-reg', typeContrat: 'f-con' };
  Object.keys(map).forEach(k => {
    const v = p.get(k);
    const el = document.getElementById(map[k]);
    if (v && el) el.value = v;
  });

  load(1);
})();
</script>
@endpush
@endsection
