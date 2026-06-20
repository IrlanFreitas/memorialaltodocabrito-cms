# Memorial Alto do Cabrito — CMS Headless

Este repositório contém o **tema WordPress headless** que serve de CMS para o Memorial Alto do Cabrito. O WordPress não renderiza nenhuma interface pública — ele só expõe Custom Post Types e campos ACF via REST API. Todo o front-end é um projeto React separado, em `~/Projetos/MemorialAltodoCabrito`, que consome esses dados.

## O que está versionado aqui

Esta instalação local do WordPress (núcleo, plugins, uploads, `wp-config.php`) **não** é versionada. Só o tema customizado é rastreado pelo git:

```
wp-content/themes/memorial-altodocabrito/
├── style.css       # Cabeçalho do tema (nome, descrição, versão)
├── functions.php   # Toda a lógica: CPTs, ACF, CORS, segurança REST, endpoints custom
├── index.php       # Stub exigido pelo WordPress (tema sem front-end próprio)
└── readme.txt      # Guia rápido de instalação/ativação
```

Núcleo do WP, plugins de terceiros, `wp-content/uploads` e `wp-config.php` (credenciais do banco) ficam fora do repositório — ver `.gitignore`.

## Plugins necessários

- **Advanced Custom Fields (ACF)** — usado para todos os campos customizados dos CPTs e da Options Page.
- **Classic Editor** (opcional, recomendado para o WYSIWYG do ACF).

## Custom Post Types

| CPT (slug) | REST base | Conteúdo |
|---|---|---|
| `acervo` | `/wp/v2/acervo` | Fotos e documentos históricos — categorias `hemeroteca` / `biblioteca` |
| `figura_notavel` | `/wp/v2/figura-notavel` | Pessoas notáveis da comunidade |
| `midia` | `/wp/v2/midia` | Conteúdo audiovisual — subcategorias `fototeca` / `videoteca` / `audioteca` |
| `hemeroteca` | `/wp/v2/hemeroteca` | **Legado** — migrado para `acervo` com `categoria: hemeroteca` |
| `projeto` | `/wp/v2/projeto` | Projetos realizados pelo grupo comunitário |
| `timeline` | `/wp/v2/timeline` | Marcos históricos do bairro (linha do tempo) |
| `noticia` | `/wp/v2/noticia` | Notícias e eventos (passados/futuros) |
| `campanha` | `/wp/v2/campanha` | Slides do Hero Carousel da home |
| `grupo_comunitario` | `/wp/v2/grupo_comunitario` | Página "Quem Somos" (post único) |

Cada CPT tem seu próprio field group ACF em `functions.php`, com campos específicos (ex.: `acervo` tem `categoria`/`tipo`/`veiculo`; `campanha` tem `imagem`/`subtitulo`/`cta_texto`/`cta_url`/`ordem`/`ativo`). Os campos ACF são incluídos automaticamente na resposta REST de cada CPT.

## Endpoints customizados (`memorial/v1`)

| Endpoint | Descrição |
|---|---|
| `GET /wp-json/memorial/v1/opcoes` | Todos os campos da Options Page (hero, história, grupo comunitário, parceiros, contato) |
| `GET /wp-json/memorial/v1/home` | Dados agregados da home em uma única requisição (acervo, figuras, mídia, projetos, notícias, timeline em destaque) |
| `GET /wp-json/memorial/v1/timeline` | Timeline completa, ordenada cronologicamente |

## CORS

`functions.php` libera CORS para:
- `http://localhost:5173` (Vite dev)
- `http://localhost:4173` (Vite preview)
- `http://localhost:3000` (dev alternativo)
- domínio de produção (atualizar a lista `$allowed_origins` ao deployar)

## Segurança

- `rest_pre_dispatch` bloqueia métodos de escrita (`POST`/`PUT`/`PATCH`/`DELETE`) na REST API para usuários não autenticados — a API é somente leitura para o público.

## Primeiros passos (ambiente local)

1. Ative o plugin **Advanced Custom Fields**.
2. Ative o **Classic Editor** (opcional).
3. Acesse **Config. Memorial** no admin para configurar hero, história, parceiros e contato (Options Page).
4. Cadastre conteúdo nos CPTs listados acima.
5. No frontend React, configure `VITE_WP_API_URL` apontando para `http://memorialaltodocabrito.local/wp-json/`.

## Projeto relacionado

Frontend React (Vite + TypeScript): `~/Projetos/MemorialAltodoCabrito`. Os tipos TypeScript em `src/types/cms.ts` devem ser mantidos em espelho com os field groups ACF deste tema.
