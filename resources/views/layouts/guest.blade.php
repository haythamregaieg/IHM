<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
          tailwind.config = {
            theme: {
              extend: {
                fontFamily: {
                  sans: ['Figtree', 'sans-serif'],
                },
              },
            },
          }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        <!-- Scroll to top button -->
        <button id="scroll-top-btn"
          onclick="window.scrollTo({top:0,behavior:'smooth'})"
          title="Retour en haut"
          style="display:none;position:fixed;bottom:2rem;right:2rem;z-index:999;width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#2B5CE6,#1a44c9);color:#fff;border:none;cursor:pointer;box-shadow:0 8px 24px rgba(43,92,230,0.35);transition:opacity .3s ease,transform .3s ease;align-items:center;justify-content:center">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
          </svg>
        </button>
        <script>
        (function(){
          var btn = document.getElementById('scroll-top-btn');
          window.addEventListener('scroll', function(){
            if(window.scrollY > 300){
              btn.style.display = 'flex';
              btn.style.opacity = '1';
              btn.style.transform = 'translateY(0)';
            } else {
              btn.style.opacity = '0';
              btn.style.transform = 'translateY(12px)';
              setTimeout(function(){ if(window.scrollY<=300) btn.style.display='none'; }, 300);
            }
          }, {passive:true});
          btn.addEventListener('mouseenter', function(){ this.style.transform='translateY(-3px) scale(1.08)'; this.style.boxShadow='0 12px 32px rgba(43,92,230,0.45)'; });
          btn.addEventListener('mouseleave', function(){ this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 24px rgba(43,92,230,0.35)'; });
        })();
        </script>
    </body>
</html>
