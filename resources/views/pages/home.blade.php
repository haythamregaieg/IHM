@extends('layouts.app')
@section('title','JobPlatform — Trouvez votre emploi idéal en Tunisie')
@section('content')

<!-- HERO SECTION MODERNE & GUIDÉE -->
<div class="relative overflow-hidden bg-white pt-24 pb-32">
  <div class="absolute inset-0 bg-grid opacity-[0.03] pointer-events-none"></div>
  <div class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-brand-500 rounded-full blur-[140px] opacity-[0.08]"></div>
  <div class="absolute top-1/2 -left-24 w-80 h-80 bg-gold-400 rounded-full blur-[120px] opacity-[0.08]"></div>

  <div class="relative max-w-7xl mx-auto px-6 text-center lg:text-left">
    <div class="flex flex-col lg:flex-row items-center gap-20">
      <div class="flex-1 lg:max-w-2xl">
        <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-brand-50 text-brand-600 text-sm font-bold uppercase tracking-widest mb-10 animate-fade-up">
          <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-brand-600"></span>
          </span>
          <span id="hero-count" class="font-black">Chargement des opportunités...</span>
        </div>
        
        <h1 class="text-6xl lg:text-7xl font-black text-slate-900 leading-[1.1] mb-8 animate-fade-up" style="animation-delay: 0.1s; font-family: Outfit">
          Trouvez votre <br>
          <span class="text-brand-600 underline decoration-brand-200 underline-offset-8">prochain job</span> <br>
          simplement.
        </h1>
        
        <p class="text-xl text-slate-500 mb-12 leading-relaxed animate-fade-up max-w-lg mx-auto lg:mx-0" style="animation-delay: 0.2s">
          La plateforme la plus simple de Tunisie pour trouver un emploi ou recruter des talents. Laissez-vous guider.
        </p>

        <!-- DEUX CHEMINS CLAIRS - TRÈS VISIBLES -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-16 animate-fade-up" style="animation-delay: 0.3s">
          <div class="relative group">
            <div class="absolute -inset-2 bg-brand-500/20 rounded-[40px] blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <a href="/offres" class="relative flex flex-col items-center gap-6 p-8 bg-brand-600 text-white rounded-[32px] shadow-2xl hover:bg-brand-700 transition-all">
              <div class="w-20 h-20 bg-white/20 rounded-[24px] flex items-center justify-center text-5xl group-hover:scale-110 transition-transform">🔍</div>
              <div class="text-center">
                <div class="font-black text-2xl mb-1">Je cherche un job</div>
                <div class="text-brand-100 font-medium">Voir toutes les offres</div>
              </div>
            </a>
          </div>
          <div class="relative group">
            <div class="absolute -inset-2 bg-gold-500/20 rounded-[40px] blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <a href="/inscription?type=entreprise" class="relative flex flex-col items-center gap-6 p-8 bg-white border-2 border-slate-100 text-slate-900 rounded-[32px] shadow-xl hover:border-gold-500 transition-all">
              <div class="w-20 h-20 bg-gold-50 rounded-[24px] flex items-center justify-center text-5xl group-hover:scale-110 transition-transform">📢</div>
              <div class="text-center">
                <div class="font-black text-2xl mb-1">Je veux recruter</div>
                <div class="text-slate-400 font-medium">Publier une annonce</div>
              </div>
            </a>
          </div>
        </div>

        <!-- RECHERCHE RAPIDE -->
        <div class="bg-white p-4 rounded-[32px] shadow-2xl border border-slate-100 flex flex-col md:flex-row gap-4 animate-fade-up" style="animation-delay: 0.4s">
          <div class="flex-1 relative">
            <div class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-slate-300">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input id="hs-q" type="text" placeholder="Quel métier cherchez-vous ? (ex: Chauffeur, Développeur...)" class="input border-transparent bg-transparent pl-16 h-16 focus:bg-transparent focus:shadow-none font-bold text-lg">
          </div>
          <button onclick="doSearch()" class="btn btn-primary h-16 px-12 text-xl shadow-xl shadow-brand-500/30">Trouver mon job</button>
        </div>
      </div>

      <div class="flex-1 hidden lg:block relative animate-fade-up" style="animation-delay: 0.2s">
        <div class="relative">
          <div class="absolute -inset-10 bg-brand-50 rounded-[60px] -rotate-3 opacity-50"></div>
          <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80&w=1000" alt="Equipe au travail" class="relative rounded-[48px] shadow-2xl object-cover h-[600px] w-full border-8 border-white">
          
          <!-- Guide visuel -->
          <div class="absolute -bottom-12 -right-12 card p-10 shadow-3xl border-none bg-white max-w-sm animate-bounce" style="animation-duration: 4s">
            <div class="flex items-center gap-5 mb-5">
              <div class="w-14 h-14 bg-green-500 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-green-500/20">✓</div>
              <div class="font-black text-2xl text-slate-800">C'est facile !</div>
            </div>
            <p class="text-slate-500 font-bold leading-relaxed">Pas besoin d'être un expert en informatique. On vous accompagne à chaque clic.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SECTION : GUIDE ÉTAPE PAR ÉTAPE -->
<section class="py-32 bg-slate-50 relative overflow-hidden">
  <div class="absolute top-0 right-0 w-64 h-64 bg-brand-100 rounded-full blur-[100px] opacity-20"></div>
  <div class="absolute bottom-0 left-0 w-64 h-64 bg-gold-100 rounded-full blur-[100px] opacity-20"></div>

  <div class="max-w-7xl mx-auto px-6 relative z-10">
    <div class="text-center max-w-3xl mx-auto mb-24">
      <h2 class="text-5xl font-black text-slate-900 mb-8">Comment ça <span class="text-brand-600 underline decoration-brand-200 underline-offset-8">marche</span> ?</h2>
      <p class="text-xl text-slate-500 font-bold">C'est aussi simple que 1, 2, 3. Laissez-vous porter.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
      <!-- Étape 1 -->
      <div class="relative text-center group">
        <div class="w-32 h-32 bg-white rounded-[40px] shadow-2xl flex items-center justify-center text-5xl mx-auto mb-10 group-hover:scale-110 transition-transform duration-500 relative z-10 border-4 border-brand-50">👤</div>
        <div class="absolute top-16 left-1/2 w-full h-1 bg-slate-200 -z-0 hidden md:block rounded-full"></div>
        <div class="absolute -top-4 -right-4 w-12 h-12 bg-brand-600 text-white rounded-2xl flex items-center justify-center font-black text-xl z-20 shadow-lg">1</div>
        <h3 class="text-2xl font-black text-slate-900 mb-4">Inscrivez-vous</h3>
        <p class="text-slate-500 font-bold text-lg px-4">Créez votre compte en quelques secondes avec votre nom et email.</p>
      </div>

      <!-- Étape 2 -->
      <div class="relative text-center group">
        <div class="w-32 h-32 bg-white rounded-[40px] shadow-2xl flex items-center justify-center text-5xl mx-auto mb-10 group-hover:scale-110 transition-transform duration-500 relative z-10 border-4 border-brand-50">🔍</div>
        <div class="absolute top-16 left-1/2 w-full h-1 bg-slate-200 -z-0 hidden md:block rounded-full"></div>
        <div class="absolute -top-4 -right-4 w-12 h-12 bg-brand-600 text-white rounded-2xl flex items-center justify-center font-black text-xl z-20 shadow-lg">2</div>
        <h3 class="text-2xl font-black text-slate-900 mb-4">Trouvez un job</h3>
        <p class="text-slate-500 font-bold text-lg px-4">Cherchez parmi des centaines d'offres près de chez vous.</p>
      </div>

      <!-- Étape 3 -->
      <div class="relative text-center group">
        <div class="w-32 h-32 bg-brand-600 rounded-[40px] shadow-2xl flex items-center justify-center text-5xl mx-auto mb-10 group-hover:scale-110 transition-transform duration-500 relative z-10 text-white shadow-brand-500/30">🚀</div>
        <div class="absolute -top-4 -right-4 w-12 h-12 bg-brand-600 text-white rounded-2xl flex items-center justify-center font-black text-xl z-20 shadow-lg border-4 border-white">3</div>
        <h3 class="text-2xl font-black text-slate-900 mb-4">Postulez !</h3>
        <p class="text-slate-500 font-bold text-lg px-4">Un clic suffit pour envoyer votre profil aux recruteurs.</p>
      </div>
    </div>
  </div>
</section>

<!-- OFFRES RÉCENTES -->
<section class="max-w-7xl mx-auto px-6 py-32">
  <div class="flex flex-col md:flex-row items-end justify-between gap-12 mb-20">
    <div class="max-w-2xl">
      <h2 class="text-6xl font-black text-slate-900 mb-6 tracking-tight">Jobs à la <span class="text-brand-600">une</span></h2>
      <p class="text-xl text-slate-500 font-bold leading-relaxed">Les meilleures entreprises recrutent en ce moment. Ne laissez pas passer votre chance !</p>
    </div>
    <a href="/offres" class="btn btn-secondary h-16 px-12 group font-black text-lg rounded-[24px]">
      Voir tout le catalogue 
      <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
    </a>
  </div>
  
  <div id="offres-home" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
    <!-- Skeleton loader -->
    <template x-for="i in 6">
      <div class="card p-12 animate-pulse bg-white border-slate-50 rounded-[40px]">
        <div class="h-20 w-20 bg-slate-50 rounded-[28px] mb-10"></div>
        <div class="h-10 bg-slate-50 rounded-2xl mb-6 w-3/4"></div>
        <div class="h-6 bg-slate-50 rounded-xl w-1/2 mb-12"></div>
        <div class="h-16 bg-slate-50 rounded-3xl w-full"></div>
      </div>
    </template>
  </div>
</section>

<!-- SECTION : AIDE & ACCOMPAGNEMENT -->
<section class="py-32 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <div class="bg-brand-50 rounded-[56px] p-12 md:p-24 flex flex-col lg:flex-row items-center gap-20">
      <div class="flex-1">
        <div class="w-20 h-20 bg-brand-600 text-white rounded-3xl flex items-center justify-center text-4xl mb-10 shadow-xl shadow-brand-500/20">💡</div>
        <h2 class="text-5xl font-black text-slate-900 mb-8 leading-tight">Besoin d'un <br><span class="text-brand-600">coup de main ?</span></h2>
        <p class="text-xl text-slate-600 font-bold mb-12 leading-relaxed">Vous n'êtes pas à l'aise avec les ordinateurs ? Aucun problème. Notre interface est pensée pour être la plus simple au monde.</p>
        
        <div class="space-y-8">
          <div class="flex items-start gap-6">
            <div class="w-10 h-10 rounded-full bg-brand-200 flex items-center justify-center text-brand-700 font-black flex-shrink-0 mt-1">1</div>
            <p class="text-lg font-bold text-slate-700">Pas de formulaires compliqués à remplir.</p>
          </div>
          <div class="flex items-start gap-6">
            <div class="w-10 h-10 rounded-full bg-brand-200 flex items-center justify-center text-brand-700 font-black flex-shrink-0 mt-1">2</div>
            <p class="text-lg font-bold text-slate-700">Des icônes claires pour vous guider partout.</p>
          </div>
          <div class="flex items-start gap-6">
            <div class="w-10 h-10 rounded-full bg-brand-200 flex items-center justify-center text-brand-700 font-black flex-shrink-0 mt-1">3</div>
            <p class="text-lg font-bold text-slate-700">Un support technique toujours à votre écoute.</p>
          </div>
        </div>
      </div>
      
      <div class="flex-1 w-full">
        <div class="card p-12 shadow-3xl border-none bg-white rounded-[48px]">
          <h3 class="text-3xl font-black text-slate-900 mb-10 text-center">Questions fréquentes</h3>
          <div class="space-y-4" x-data="{open:null}">
            <div class="border border-slate-100 rounded-2xl overflow-hidden">
              <button @click="open=open===0?null:0"
                      :aria-expanded="open===0"
                      aria-controls="faq-0"
                      class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
                <span class="font-black text-lg text-slate-800" :class="open===0?'text-brand-600':''">C'est gratuit ?</span>
                <span class="text-brand-300 text-2xl font-bold transition-transform duration-300" :class="open===0?'rotate-45':''">+</span>
              </button>
              <div id="faq-0" x-show="open===0" x-collapse class="px-6 pb-5">
                <p class="text-slate-500 font-bold leading-relaxed">Oui ! Pour tous les candidats, c'est 100% gratuit, pour toujours.</p>
              </div>
            </div>
            <div class="border border-slate-100 rounded-2xl overflow-hidden">
              <button @click="open=open===1?null:1"
                      :aria-expanded="open===1"
                      aria-controls="faq-1"
                      class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
                <span class="font-black text-lg text-slate-800" :class="open===1?'text-brand-600':''">Comment s'inscrire ?</span>
                <span class="text-brand-300 text-2xl font-bold transition-transform duration-300" :class="open===1?'rotate-45':''">+</span>
              </button>
              <div id="faq-1" x-show="open===1" x-collapse class="px-6 pb-5">
                <p class="text-slate-500 font-bold leading-relaxed">Cliquez sur le bouton bleu "S'inscrire" en haut de la page.</p>
              </div>
            </div>
            <div class="border border-slate-100 rounded-2xl overflow-hidden">
              <button @click="open=open===2?null:2"
                      :aria-expanded="open===2"
                      aria-controls="faq-2"
                      class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
                <span class="font-black text-lg text-slate-800" :class="open===2?'text-brand-600':''">Et si j'ai un problème ?</span>
                <span class="text-brand-300 text-2xl font-bold transition-transform duration-300" :class="open===2?'rotate-45':''">+</span>
              </button>
              <div id="faq-2" x-show="open===2" x-collapse class="px-6 pb-5">
                <p class="text-slate-500 font-bold leading-relaxed">Notre équipe est là pour vous aider à chaque étape par email ou téléphone.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SECTION : APPEL À L'ACTION FINAL -->
<section class="py-32 overflow-hidden">
  <div class="max-w-7xl mx-auto px-6">
    <div class="bg-slate-900 rounded-[64px] p-16 md:p-32 relative overflow-hidden text-center">
      <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-brand-500/20 blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
      <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-gold-500/10 blur-[120px] translate-y-1/2 -translate-x-1/2"></div>
      
      <div class="relative z-10 max-w-4xl mx-auto">
        <h2 class="text-5xl md:text-7xl font-black text-white mb-12 leading-tight">Prêt à changer <br><span class="text-brand-500">de vie ?</span></h2>
        <p class="text-2xl text-slate-400 mb-16 font-bold leading-relaxed">Rejoignez des milliers de tunisiens qui ont trouvé leur bonheur sur JobPlatform.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-8">
          <a href="/inscription?type=candidat" class="btn btn-brand btn-lg py-7 px-16 text-2xl shadow-3xl shadow-brand-500/40 rounded-[28px]">Créer mon compte gratuit</a>
          <a href="/offres" class="btn btn-ghost text-white border-2 border-slate-700 hover:bg-slate-800 py-7 px-16 text-2xl rounded-[28px]">Parcourir les offres</a>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
(async function(){
  try{
    const res = await API.get('/offres');
    if(res?.ok){
      const data = await res.json();
      const total = data.total || 0;
      document.getElementById('hero-count').textContent = total + ' Opportunités en direct';
      
      const items = data.data || [];
      const cc = {
        CDI: 'bg-green-50 text-green-600 border-green-100',
        CDD: 'bg-blue-50 text-blue-600 border-blue-100',
        Stage: 'bg-amber-50 text-amber-600 border-amber-100',
        Freelance: 'bg-purple-50 text-purple-600 border-purple-100',
        Alternance: 'bg-slate-50 text-slate-600 border-slate-100'
      };

      document.getElementById('offres-home').innerHTML = items.slice(0, 6).map(o => `
        <div class="card p-12 group hover:border-brand-200 hover:shadow-3xl hover:shadow-brand-500/10 flex flex-col h-full relative overflow-hidden rounded-[40px]">
          <div class="absolute top-0 right-0 w-40 h-40 bg-brand-50 rounded-bl-[120px] -translate-y-full group-hover:translate-y-0 transition-transform duration-700 ease-out -z-0 opacity-40"></div>
          
          <div class="relative z-10 flex flex-col h-full">
            <div class="flex justify-between items-start mb-10">
              <div class="w-20 h-20 rounded-[28px] bg-white border border-slate-100 flex items-center justify-center text-brand-600 font-black text-4xl shadow-sm group-hover:scale-110 transition-transform duration-500 overflow-hidden">
                ${o.entreprise?.logo_path ? `<img src="/storage/${o.entreprise.logo_path}" alt="Logo ${o.entreprise?.raison_sociale || 'entreprise'}" class="w-full h-full object-cover">` : (o.entreprise?.raison_sociale || '?').charAt(0).toUpperCase()}
              </div>
              <span class="px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest border ${cc[o.typeContrat] || 'bg-slate-50 text-slate-500 border-slate-100'}">
                ${o.typeContrat}
              </span>
            </div>
            
            <div class="flex-1">
              <h3 class="text-3xl font-black text-slate-900 mb-4 group-hover:text-brand-600 transition-colors leading-tight">${o.titre}</h3>
              <p class="font-black text-slate-400 mb-10 flex items-center gap-3 text-lg">
                <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                ${o.entreprise?.raison_sociale || 'Entreprise anonyme'}
              </p>
            </div>

            <div class="flex flex-wrap gap-8 pt-10 border-t border-slate-50 mb-10">
              <div class="flex items-center gap-3 text-base font-black text-slate-500">
                <span class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-lg">📍</span>
                ${o.localisation}
              </div>
              <div class="flex items-center gap-3 text-base font-black text-slate-500">
                <span class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-lg">📅</span>
                ${o.dateLimite ? new Date(o.dateLimite).toLocaleDateString('fr-FR') : 'N/A'}
              </div>
            </div>
            
            ${o.dejaPostule ? `
              <button disabled class="btn btn-primary w-full justify-center py-5 text-xl font-black rounded-3xl opacity-50 cursor-not-allowed">Déjà postulé</button>
            ` : `
              <a href="/offres/${o.idOffre}" class="btn btn-secondary w-full justify-center py-5 text-xl font-black rounded-3xl group-hover:btn-primary transition-all duration-300">
                Voir les détails
              </a>
            `}
          </div>
        </div>
      `).join('') || `
        <div class="col-span-full py-40 text-center">
          <div class="text-8xl mb-10 grayscale opacity-20">🔎</div>
          <h3 class="text-3xl font-black text-slate-900 mb-4">Aucune offre pour le moment</h3>
          <p class="text-xl text-slate-400 font-bold">Revenez plus tard pour découvrir de nouvelles opportunités.</p>
        </div>
      `;
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
