=== Memorial Alto do Cabrito — Tema Headless ===

Versão: 1.0.0
Requer WordPress: 6.4+
Requer PHP: 8.0+

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

SOBRE
─────
Este tema configura o WordPress como CMS headless para o frontend
React em ~/Projetos/MemorialAltodoCabrito.

Não renderiza nenhuma interface pública — todo o front-end é servido
pelo Vite/React.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

PLUGINS NECESSÁRIOS
───────────────────
• Advanced Custom Fields (ACF) — copiado de /Local Sites/ibcm/
  Ativar em: WP Admin > Plugins > Advanced Custom Fields > Ativar

• Classic Editor (opcional, mas recomendado para o ACF WYSIWYG)
  Ativar em: WP Admin > Plugins > Classic Editor > Ativar

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

CUSTOM POST TYPES REGISTRADOS
──────────────────────────────
┌──────────────────┬──────────────────────────────┬────────────────────────────┐
│ CPT              │ REST Base                    │ Conteúdo                   │
├──────────────────┼──────────────────────────────┼────────────────────────────┤
│ acervo           │ /wp/v2/acervo                │ Fotos e docs históricos    │
│ figura_notavel   │ /wp/v2/figura-notavel        │ Pessoas notáveis do bairro │
│ hemeroteca       │ /wp/v2/hemeroteca            │ Recortes de jornal/mídia   │
│ projeto          │ /wp/v2/projeto               │ Projetos do grupo          │
│ noticia          │ /wp/v2/noticia               │ Notícias e eventos         │
└──────────────────┴──────────────────────────────┴────────────────────────────┘

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

ENDPOINTS REST API
──────────────────

Endpoints padrão do WordPress (públicos, leitura):
  GET /wp/v2/acervo
  GET /wp/v2/figura-notavel
  GET /wp/v2/hemeroteca
  GET /wp/v2/projeto
  GET /wp/v2/noticia

Endpoints customizados (memorial/v1):
  GET /wp-json/memorial/v1/opcoes   → Todos os campos da Options Page
  GET /wp-json/memorial/v1/home     → Dados agregados da home (1 request só)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

PRIMEIROS PASSOS (após ativar o tema)
──────────────────────────────────────
1. Ative o plugin Advanced Custom Fields
   WP Admin > Plugins > ACF > Ativar

2. Ative o Classic Editor (para WYSIWYG melhor no ACF)

3. Acesse "Config. Memorial" no menu lateral do admin
   Configure: hero slides, texto da história, timeline, parceiros, contato

4. Cadastre conteúdo nos CPTs:
   - Acervo: fotos históricas do bairro
   - Figuras Notáveis: pessoas importantes da comunidade
   - Hemeroteca: recortes de jornais
   - Projetos: projetos do grupo comunitário
   - Notícias: eventos e acontecimentos

5. No frontend React, use a URL base:
   http://memorialaltodocabrito.local/wp-json/

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

CORS
────
O tema já configura CORS para permitir acesso de:
  • http://localhost:5173   (Vite dev)
  • http://localhost:4173   (Vite preview)
  • http://localhost:3000   (alt dev)

Para adicionar o domínio de produção, editar functions.php:
  $allowed_origins = [ ... 'https://seudominio.com.br' ]

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
