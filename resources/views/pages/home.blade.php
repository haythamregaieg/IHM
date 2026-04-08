@extends('layouts.app')
@section('title','JobPlatform — Trouvez votre emploi idéal en Tunisie')
@section('content')

<!-- HERO SECTION MODERNE & GUIDÉE -->
<div class="relative overflow-hidden bg-white pt-16 pb-20">
  <div class="absolute inset-0 bg-grid opacity-[0.03] pointer-events-none"></div>
  <div class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-brand-500 rounded-full blur-[140px] opacity-[0.08]"></div>
  <div class="absolute top-1/2 -left-24 w-80 h-80 bg-gold-400 rounded-full blur-[120px] opacity-[0.08]"></div>

  <div class="relative max-w-7xl mx-auto px-6 text-center lg:text-left">
    <div class="flex flex-col lg:flex-row items-center gap-12">
      <div class="flex-1 lg:max-w-2xl">
        <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-brand-50 text-brand-600 text-xs font-bold uppercase tracking-widest mb-6 animate-fade-up">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-600"></span>
          </span>
          <span id="hero-count" class="font-bold">Chargement...</span>
        </div>
        
        <h1 class="text-5xl lg:text-6xl font-bold text-slate-900 leading-[1.1] mb-6 animate-fade-up" style="animation-delay: 0.1s; font-family: Outfit">
          Trouvez votre <br>
          <span class="text-brand-600 underline decoration-brand-200 underline-offset-8">prochain job</span> <br>
          simplement.
        </h1>
        
        <!-- DEUX CHEMINS CLAIRS - COMPACT -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8 animate-fade-up" style="animation-delay: 0.2s">
          <a href="/offres" class="group relative flex items-center gap-4 p-5 bg-brand-600 text-white rounded-2xl shadow-xl hover:bg-brand-700 transition-all overflow-hidden">
            <div class="absolute right-0 bottom-0 text-6xl opacity-10 group-hover:scale-125 transition-transform">🔍</div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">🔍</div>
            <div>
              <div class="font-bold text-lg">Je cherche un job</div>
              <div class="text-brand-100 text-xs font-medium">Voir toutes les offres</div>
            </div>
          </a>
          <a href="/inscription?type=entreprise" class="group relative flex items-center gap-4 p-5 bg-white border-2 border-slate-100 text-slate-900 rounded-2xl shadow-sm hover:border-gold-500 transition-all overflow-hidden">
            <div class="absolute right-0 bottom-0 text-6xl opacity-10 group-hover:scale-125 transition-transform">📢</div>
            <div class="w-12 h-12 bg-gold-50 rounded-xl flex items-center justify-center text-2xl">📢</div>
            <div>
              <div class="font-bold text-lg">Je veux recruter</div>
              <div class="text-slate-400 text-xs font-medium">Publier une annonce</div>
            </div>
          </a>
        </div>

        <!-- RECHERCHE RAPIDE -->
        <div class="bg-white p-2 rounded-2xl shadow-xl border border-slate-100 flex flex-col md:flex-row gap-2 animate-fade-up" style="animation-delay: 0.3s">
          <div class="flex-1 relative">
            <div class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-300">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input id="hs-q" type="text" placeholder="Quel métier cherchez-vous ?" class="input border-transparent bg-transparent pl-14 h-14 focus:bg-transparent focus:shadow-none font-bold text-base">
          </div>
          <button onclick="doSearch()" class="btn btn-primary h-14 px-8 text-lg shadow-lg shadow-brand-500/30">Trouver mon job</button>
        </div>
      </div>

      <div class="flex-1 hidden lg:block relative animate-fade-up" style="animation-delay: 0.2s">
        <div class="relative">
          <div class="absolute -inset-6 bg-brand-50 rounded-[40px] -rotate-2 opacity-50"></div>
          <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80&w=800" alt="Equipe au travail" class="relative rounded-[32px] shadow-xl object-cover h-[450px] w-full border-4 border-white">
          
          <div class="absolute -bottom-6 -right-6 card p-6 shadow-2xl border-none bg-white max-w-[240px] animate-bounce" style="animation-duration: 4s">
            <div class="flex items-center gap-3 mb-2">
              <div class="w-10 h-10 bg-green-500 text-white rounded-xl flex items-center justify-center text-xl">✓</div>
              <div class="font-bold text-lg text-slate-800">C'est facile !</div>
            </div>
            <p class="text-slate-500 text-xs font-bold leading-relaxed">Pas besoin d'être un expert. On vous accompagne à chaque clic.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SECTION : EXPLORER PAR DOMAINE (Remplace Jobs à la une) -->
<section class="py-20 bg-slate-50 relative overflow-hidden">
  <div class="max-w-7xl mx-auto px-6">
    <div class="text-center max-w-2xl mx-auto mb-16">
      <h2 class="text-4xl font-bold text-slate-900 mb-4 tracking-tight">Explorez par <span class="text-brand-600">domaine</span></h2>
      <p class="text-lg text-slate-500 font-bold">Trouvez l'opportunité qui correspond à votre expertise parmi nos catégories.</p>
    </div>

    <div id="domaines-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <!-- Skeleton loader -->
      <template x-for="i in 8">
        <div class="card p-8 animate-pulse bg-white border-slate-50 rounded-3xl h-48 flex flex-col items-center justify-center">
          <div class="w-16 h-16 bg-slate-50 rounded-2xl mb-4"></div>
          <div class="h-4 bg-slate-50 rounded-full w-24"></div>
        </div>
      </template>
    </div>
  </div>
</section>

<!-- SECTION : COMMENT ÇA MARCHE (Plus compacte) -->
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <div class="flex items-center gap-6 group">
        <div class="w-20 h-20 bg-brand-50 text-3xl flex items-center justify-center rounded-2xl group-hover:scale-110 transition-transform">👤</div>
        <div>
          <h3 class="font-bold text-slate-900 text-xl">Inscrivez-vous</h3>
          <p class="text-slate-500 text-sm font-bold">En quelques secondes seulement.</p>
        </div>
      </div>
      <div class="flex items-center gap-6 group">
        <div class="w-20 h-20 bg-brand-50 text-3xl flex items-center justify-center rounded-2xl group-hover:scale-110 transition-transform">🔍</div>
        <div>
          <h3 class="font-bold text-slate-900 text-xl">Trouvez un job</h3>
          <p class="text-slate-500 text-sm font-bold">Près de chez vous.</p>
        </div>
      </div>
      <div class="flex items-center gap-6 group">
        <div class="w-20 h-20 bg-brand-600 text-white text-3xl flex items-center justify-center rounded-2xl group-hover:scale-110 transition-transform shadow-lg shadow-brand-500/20">🚀</div>
        <div>
          <h3 class="font-bold text-slate-900 text-xl">Postulez !</h3>
          <p class="text-slate-500 text-sm font-bold">Un clic suffit pour postuler.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SECTION : APPEL À L'ACTION FINAL (Très compacte) -->
<section class="py-14">
  <div class="max-w-7xl mx-auto px-6">
    <div class="bg-slate-900 rounded-[40px] p-8 md:p-14 relative overflow-hidden text-center">
      <!-- Ornements de fond -->
      <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-500/20 blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
      <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-brand-400/10 blur-[100px] translate-y-1/2 -translate-x-1/2"></div>
      
      <div class="relative z-10 max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-5xl font-bold text-white mb-5 leading-tight" style="font-family:Outfit">Prêt à changer <br><span class="text-brand-400">de vie ?</span></h2>
        <p class="text-lg text-slate-400 mb-8 font-medium">Rejoignez des milliers de tunisiens et trouvez l'opportunité qui vous correspond vraiment.</p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
          <a href="/inscription?type=candidat" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-900 rounded-2xl text-lg font-bold shadow-2xl hover:bg-brand-50 hover:scale-105 transition-all duration-300">
            Créer mon compte gratuit
          </a>
          <a href="/offres" class="w-full sm:w-auto px-8 py-4 bg-transparent text-white border-2 border-white/30 rounded-2xl text-lg font-bold hover:bg-white/10 hover:border-white transition-all duration-300">
            Parcourir les offres
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
(async function(){
  try{
    // Fetch stats and domains
    const [resOffres, resDomaines] = await Promise.all([
      API.get('/offres'),
      API.get('/domaines')
    ]);

    if(resOffres?.ok){
      const data = await resOffres.json();
      document.getElementById('hero-count').textContent = (data.total || 0) + ' Opportunités';
    }

    if(resDomaines?.ok){
      const domaines = await resDomaines.json();
      const icons = {
        "Informatique & Technologie": "💻",
        "Finance & Comptabilite": "💰",
        "Marketing & Communication": "📣",
        "Ressources Humaines": "🤝",
        "Sante & Medecine": "⚕️",
        "Ingenierie & Industrie": "⚙️",
        "Commerce & Vente": "🛒",
        "Education & Formation": "🎓",
        "Droit & Juridique": "⚖️",
        "Architecture & BTP": "🏗️",
        "M\u00e9caniques": "🔧"
      };

      document.getElementById('domaines-grid').innerHTML = domaines.map(d => `
        <a href="/offres?idDomaine=${d.idDomaine}" class="card p-8 group hover:border-brand-500 hover:shadow-2xl hover:shadow-brand-500/10 transition-all text-center flex flex-col items-center justify-center gap-4 bg-white rounded-3xl border-slate-100">
          <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center text-4xl group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300">
            ${icons[d.nom] || "💼"}
          </div>
          <h3 class="font-bold text-slate-900 group-hover:text-brand-600 transition-colors">${d.nom}</h3>
        </a>
      `).join('');
    }
  }catch(e){console.error(e)}
})();

function doSearch(){
  const q=document.getElementById('hs-q').value;
  window.location.href=`/offres?q=${encodeURIComponent(q)}`;
}
</script>
@endpush
@endsection
