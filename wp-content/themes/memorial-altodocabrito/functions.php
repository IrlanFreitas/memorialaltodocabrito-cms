<?php

/**
 * Memorial Alto do Cabrito — functions.php
 *
 * Este arquivo configura o WordPress como CMS headless para o
 * frontend React em ~/Projetos/MemorialAltodoCabrito.
 *
 * Estrutura:
 *  1. CPTs (Custom Post Types)
 *  2. CORS — permite acesso do frontend React
 *  3. Segurança REST — bloqueia escrita para não autenticados
 *  4. ACF Options Page
 *  5. ACF Field Groups (um por CPT + página de opções)
 *  6. Endpoint customizado /memorial/v1/opcoes
 *  7. Filtro para incluir campos ACF na resposta REST
 */

// ─── 1. CUSTOM POST TYPES ────────────────────────────────────────────────────

function memorial_register_post_types()
{
  // ── Acervo (fotos e documentos históricos) ────────────────────────────────
  register_post_type('acervo', [
    'labels'              => [
      'name'               => 'Acervo',
      'singular_name'      => 'Item do Acervo',
      'add_new_item'       => 'Adicionar Item ao Acervo',
      'edit_item'          => 'Editar Item do Acervo',
      'new_item'           => 'Novo Item',
      'view_item'          => 'Ver Item',
      'search_items'       => 'Buscar no Acervo',
      'not_found'          => 'Nenhum item encontrado',
      'not_found_in_trash' => 'Nenhum item na lixeira',
    ],
    'public'              => true,
    'show_in_rest'        => true,
    'rest_base'           => 'acervo',
    'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields'],
    'menu_icon'           => 'dashicons-format-gallery',
    'has_archive'         => false,
    'rewrite'             => ['slug' => 'acervo'],
    'menu_position'       => 5,
  ]);

  // ── Figuras Notáveis ──────────────────────────────────────────────────────
  register_post_type('figura_notavel', [
    'labels'              => [
      'name'               => 'Figuras Notáveis',
      'singular_name'      => 'Figura Notável',
      'add_new_item'       => 'Adicionar Figura Notável',
      'edit_item'          => 'Editar Figura Notável',
      'new_item'           => 'Nova Figura Notável',
      'view_item'          => 'Ver Figura Notável',
      'search_items'       => 'Buscar Figuras Notáveis',
      'not_found'          => 'Nenhuma figura encontrada',
      'not_found_in_trash' => 'Nenhuma figura na lixeira',
    ],
    'public'              => true,
    'show_in_rest'        => true,
    'rest_base'           => 'figura-notavel',
    'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields'],
    'menu_icon'           => 'dashicons-groups',
    'has_archive'         => false,
    'rewrite'             => ['slug' => 'figuras-notaveis'],
    'menu_position'       => 6,
  ]);

  // ── Mídia (Fototeca, Videoteca, Audioteca) ───────────────────────────────
  register_post_type('midia', [
    'labels'              => [
      'name'               => 'Mídia',
      'singular_name'      => 'Item de Mídia',
      'add_new_item'       => 'Adicionar Item de Mídia',
      'edit_item'          => 'Editar Item de Mídia',
      'new_item'           => 'Novo Item',
      'view_item'          => 'Ver Item',
      'search_items'       => 'Buscar na Mídia',
      'not_found'          => 'Nenhum item encontrado',
      'not_found_in_trash' => 'Nenhum item na lixeira',
    ],
    'public'              => true,
    'show_in_rest'        => true,
    'rest_base'           => 'midia',
    'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields'],
    'menu_icon'           => 'dashicons-format-video',
    'has_archive'         => false,
    'rewrite'             => ['slug' => 'midia'],
    'menu_position'       => 7,
  ]);

  // ── Hemeroteca (recortes de mídia/jornal) — LEGADO ────────────────────────
  register_post_type('hemeroteca', [
    'labels'              => [
      'name'               => 'Hemeroteca',
      'singular_name'      => 'Recorte',
      'add_new_item'       => 'Adicionar Recorte',
      'edit_item'          => 'Editar Recorte',
      'new_item'           => 'Novo Recorte',
      'view_item'          => 'Ver Recorte',
      'search_items'       => 'Buscar Recortes',
      'not_found'          => 'Nenhum recorte encontrado',
    ],
    'public'              => true,
    'show_in_rest'        => true,
    'rest_base'           => 'hemeroteca',
    'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields'],
    'menu_icon'           => 'dashicons-media-document',
    'has_archive'         => false,
    'rewrite'             => ['slug' => 'hemeroteca'],
    'menu_position'       => 7,
  ]);

  // ── Projetos do Grupo Comunitário ─────────────────────────────────────────
  register_post_type('projeto', [
    'labels'              => [
      'name'               => 'Projetos',
      'singular_name'      => 'Projeto',
      'add_new_item'       => 'Adicionar Projeto',
      'edit_item'          => 'Editar Projeto',
      'new_item'           => 'Novo Projeto',
      'view_item'          => 'Ver Projeto',
      'search_items'       => 'Buscar Projetos',
      'not_found'          => 'Nenhum projeto encontrado',
    ],
    'public'              => true,
    'show_in_rest'        => true,
    'rest_base'           => 'projeto',
    'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields'],
    'menu_icon'           => 'dashicons-hammer',
    'has_archive'         => false,
    'rewrite'             => ['slug' => 'projetos'],
    'menu_position'       => 8,
  ]);

  // ── Timeline (marcos históricos do bairro) ────────────────────────────────
  register_post_type('timeline', [
    'labels'              => [
      'name'               => 'Timeline',
      'singular_name'      => 'Marco Histórico',
      'add_new_item'       => 'Adicionar Marco',
      'edit_item'          => 'Editar Marco',
      'new_item'           => 'Novo Marco',
      'view_item'          => 'Ver Marco',
      'search_items'       => 'Buscar Marcos',
      'not_found'          => 'Nenhum marco encontrado',
      'not_found_in_trash' => 'Nenhum marco na lixeira',
    ],
    'public'              => true,
    'show_in_rest'        => true,
    'rest_base'           => 'timeline',
    'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields'],
    'menu_icon'           => 'dashicons-clock',
    'has_archive'         => false,
    'rewrite'             => ['slug' => 'timeline'],
    'menu_position'       => 10,
  ]);

  // ── Notícias / Eventos ────────────────────────────────────────────────────
  register_post_type('noticia', [
    'labels'              => [
      'name'               => 'Notícias',
      'singular_name'      => 'Notícia',
      'add_new_item'       => 'Adicionar Notícia',
      'edit_item'          => 'Editar Notícia',
      'new_item'           => 'Nova Notícia',
      'view_item'          => 'Ver Notícia',
      'search_items'       => 'Buscar Notícias',
      'not_found'          => 'Nenhuma notícia encontrada',
    ],
    'public'              => true,
    'show_in_rest'        => true,
    'rest_base'           => 'noticia',
    'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    'menu_icon'           => 'dashicons-megaphone',
    'has_archive'         => false,
    'rewrite'             => ['slug' => 'noticias'],
    'menu_position'       => 9,
  ]);

  // ── Campanha (slides do Hero Carousel) ────────────────────────────────────
  register_post_type('campanha', [
    'labels'              => [
      'name'               => 'Campanhas',
      'singular_name'      => 'Campanha',
      'add_new_item'       => 'Adicionar Campanha',
      'edit_item'          => 'Editar Campanha',
      'new_item'           => 'Nova Campanha',
      'view_item'          => 'Ver Campanha',
      'search_items'       => 'Buscar Campanhas',
      'not_found'          => 'Nenhuma campanha encontrada',
      'not_found_in_trash' => 'Nenhuma campanha na lixeira',
    ],
    'public'              => true,
    'show_in_rest'        => true,
    'rest_base'           => 'campanha',
    'supports'            => ['title', 'thumbnail', 'custom-fields'],
    'menu_icon'           => 'dashicons-images-alt2',
    'has_archive'         => false,
    'rewrite'             => ['slug' => 'campanhas'],
    'menu_position'       => 11,
  ]);

  // ── Grupo Comunitário (Quem Somos) ────────────────────────────────────────
  register_post_type('grupo_comunitario', [
    'labels'              => [
      'name'               => 'Grupo Comunitário',
      'singular_name'      => 'Grupo Comunitário',
      'add_new_item'       => 'Adicionar Grupo Comunitário',
      'edit_item'          => 'Editar Grupo Comunitário',
      'new_item'           => 'Novo Grupo Comunitário',
      'view_item'          => 'Ver Grupo Comunitário',
      'search_items'       => 'Buscar Grupo Comunitário',
      'not_found'          => 'Nenhum registro encontrado',
      'not_found_in_trash' => 'Nenhum registro na lixeira',
    ],
    'public'              => true,
    'show_in_rest'        => true,
    'rest_base'           => 'grupo_comunitario',
    'supports'            => ['title', 'thumbnail', 'custom-fields'],
    'menu_icon'           => 'dashicons-groups',
    'has_archive'         => false,
    'rewrite'             => ['slug' => 'grupo-comunitario'],
    'menu_position'       => 12,
  ]);
}
add_action('init', 'memorial_register_post_types');


// ─── 2. CORS ─────────────────────────────────────────────────────────────────

function memorial_add_cors_headers()
{
  $allowed_origins = [
    'http://localhost:5173',              // Vite dev server
    'http://localhost:4173',              // Vite preview
    'http://localhost:3000',              // Alt dev server
    'https://memorialaltodocabrito.com', // Produção (atualizar quando deployar)
  ];

  $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

  if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Credentials: false');
  }

  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    status_header(200);
    exit();
  }
}
add_action('init', 'memorial_add_cors_headers');


// ─── 3. SEGURANÇA REST — bloquear escrita para não autenticados ───────────────

function memorial_restrict_rest_write($result, $_server, $request)
{
  if (in_array($request->get_method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
    if (! is_user_logged_in()) {
      return new WP_Error(
        'rest_forbidden',
        'Escrita não permitida para usuários não autenticados.',
        ['status' => 403]
      );
    }
  }
  return $result;
}
add_filter('rest_pre_dispatch', 'memorial_restrict_rest_write', 10, 3);


// ─── 4. ACF OPTIONS PAGE ─────────────────────────────────────────────────────

add_action('acf/init', 'memorial_acf_options_page');
function memorial_acf_options_page()
{
  if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
      'page_title' => 'Configurações do Memorial',
      'menu_title' => 'Config. Memorial',
      'menu_slug'  => 'memorial-config',
      'capability' => 'manage_options',
      'position'   => 2,
      'icon_url'   => 'dashicons-star-filled',
    ]);
  }
}


// ─── 5. ACF FIELD GROUPS ─────────────────────────────────────────────────────

add_action('acf/include_fields', 'memorial_register_acf_fields');
function memorial_register_acf_fields()
{
  if (! function_exists('acf_add_local_field_group')) {
    return;
  }

  // ══ Field Group: Acervo ════════════════════════════════════════════════════
  // Duas subcategorias: hemeroteca (jornal/revista/recorte/boletim) e
  // biblioteca (livro/tese/livreto/ebook/documento-textual)
  acf_add_local_field_group([
    'key'      => 'group_memorial_acervo',
    'title'    => 'Dados do Item do Acervo',
    'fields'   => [
      [
        'key'          => 'field_acervo_categoria',
        'label'        => 'Categoria',
        'name'         => 'categoria',
        'type'         => 'select',
        'instructions' => 'Hemeroteca: jornais, revistas, recortes, boletins. Biblioteca: livros, teses, documentos textuais.',
        'choices'      => [
          'hemeroteca' => 'Hemeroteca',
          'biblioteca' => 'Biblioteca',
        ],
        'default_value' => 'hemeroteca',
        'return_format' => 'value',
        'ui'           => 1,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_acervo_tipo',
        'label'        => 'Tipo',
        'name'         => 'tipo',
        'type'         => 'select',
        'instructions' => 'Hemeroteca: jornal | revista | recorte | boletim. Biblioteca: livro | tese | livreto | ebook | documento-textual.',
        'choices'      => [
          // Hemeroteca
          'jornal'            => 'Jornal',
          'revista'           => 'Revista',
          'recorte'           => 'Recorte',
          'boletim'           => 'Boletim',
          // Biblioteca
          'livro'             => 'Livro',
          'tese'              => 'Tese / Dissertação',
          'livreto'           => 'Livreto',
          'ebook'             => 'E-book',
          'documento-textual' => 'Documento Textual',
        ],
        'default_value' => 'jornal',
        'return_format' => 'value',
        'ui'           => 1,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_acervo_descricao',
        'label'        => 'Descrição',
        'name'         => 'descricao',
        'type'         => 'textarea',
        'instructions' => 'Descrição detalhada do item. Contexto histórico, pessoas no documento etc.',
        'rows'         => 4,
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_acervo_imagem_principal',
        'label'         => 'Imagem Principal',
        'name'          => 'imagem_principal',
        'type'          => 'image',
        'instructions'  => 'Capa, digitalização do documento, foto do item etc.',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_acervo_galeria',
        'label'         => 'Galeria de Imagens',
        'name'          => 'galeria',
        'type'          => 'gallery',
        'instructions'  => 'Imagens adicionais (verso do documento, páginas internas etc.)',
        'return_format' => 'array',
        'preview_size'  => 'thumbnail',
        'show_in_rest'  => true,
      ],

      // ── Campos Hemeroteca ─────────────────────────────────────────────────
      [
        'key'          => 'field_acervo_veiculo',
        'label'        => 'Veículo / Publicação (Hemeroteca)',
        'name'         => 'veiculo',
        'type'         => 'text',
        'instructions' => 'Nome do jornal, revista ou veículo. Ex: A Tarde, Correio da Bahia.',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_acervo_data_publicacao',
        'label'         => 'Data de Publicação (Hemeroteca)',
        'name'          => 'data_publicacao',
        'type'          => 'date_picker',
        'instructions'  => 'Data de publicação do recorte/matéria.',
        'display_format' => 'd/m/Y',
        'return_format'  => 'Y-m-d',
        'show_in_rest'   => true,
      ],
      [
        'key'          => 'field_acervo_transcricao',
        'label'        => 'Transcrição (Hemeroteca)',
        'name'         => 'transcricao',
        'type'         => 'wysiwyg',
        'instructions' => 'Transcrição completa do texto do recorte, para acessibilidade e busca.',
        'show_in_rest' => true,
      ],

      // ── Campos Biblioteca ─────────────────────────────────────────────────
      [
        'key'          => 'field_acervo_autor',
        'label'        => 'Autor (Biblioteca)',
        'name'         => 'autor',
        'type'         => 'text',
        'instructions' => 'Nome do autor ou autores da obra.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_acervo_ano_publicacao',
        'label'        => 'Ano de Publicação (Biblioteca)',
        'name'         => 'ano_publicacao',
        'type'         => 'text',
        'instructions' => 'Ano de publicação da obra. Ex: 2019.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_acervo_editora',
        'label'        => 'Editora / Instituição (Biblioteca)',
        'name'         => 'editora',
        'type'         => 'text',
        'instructions' => 'Nome da editora ou instituição publicadora. Ex: UFBA, Editora Oduduwa.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_acervo_arquivo_pdf_url',
        'label'        => 'URL do Arquivo PDF (Biblioteca)',
        'name'         => 'arquivo_pdf_url',
        'type'         => 'url',
        'instructions' => 'Link para download ou visualização do PDF (se disponível).',
        'show_in_rest' => true,
      ],

      // ── Campos Comuns ─────────────────────────────────────────────────────
      [
        'key'          => 'field_acervo_data_aproximada',
        'label'        => 'Data Aproximada',
        'name'         => 'data_aproximada',
        'type'         => 'text',
        'instructions' => 'Texto livre. Ex: "Década de 1980", "mar. 1973", "ca. 1960".',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_acervo_data_exata',
        'label'         => 'Data Exata',
        'name'          => 'data_exata',
        'type'          => 'date_picker',
        'instructions'  => 'Preencher se a data exata for conhecida.',
        'display_format' => 'd/m/Y',
        'return_format'  => 'Y-m-d',
        'show_in_rest'   => true,
      ],
      [
        'key'          => 'field_acervo_local',
        'label'        => 'Local / Origem',
        'name'         => 'local',
        'type'         => 'text',
        'instructions' => 'Cidade/estado de origem ou local relacionado ao item.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_acervo_doador',
        'label'        => 'Doado por',
        'name'         => 'doador',
        'type'         => 'text',
        'instructions' => 'Nome de quem doou ou cedeu o item ao acervo.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_acervo_palavras_chave',
        'label'        => 'Palavras-chave',
        'name'         => 'palavras_chave',
        'type'         => 'text',
        'instructions' => 'Separadas por vírgula. Ex: escola, carnaval, rua, festa.',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_acervo_ordem',
        'label'         => 'Ordem de Exibição',
        'name'          => 'ordem',
        'type'          => 'number',
        'instructions'  => 'Número menor aparece primeiro.',
        'default_value' => 99,
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_acervo_destaque',
        'label'         => 'Destaque (exibir na Home)',
        'name'          => 'destaque',
        'type'          => 'true_false',
        'instructions'  => 'Marcar para aparecer na seção Acervo da página inicial.',
        'default_value' => 0,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_acervo_ativo',
        'label'         => 'Publicado no site',
        'name'          => 'ativo',
        'type'          => 'true_false',
        'instructions'  => 'Desmarcar para ocultar do site sem excluir.',
        'default_value' => 1,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
    ],
    'location'     => [[['param' => 'post_type', 'operator' => '==', 'value' => 'acervo']]],
    'show_in_rest' => true,
  ]);


  // ══ Field Group: Figura Notável ════════════════════════════════════════════
  acf_add_local_field_group([
    'key'      => 'group_memorial_figura_notavel',
    'title'    => 'Dados da Figura Notável',
    'fields'   => [
      [
        'key'          => 'field_figura_apelido',
        'label'        => 'Apelido / Nome Conhecido',
        'name'         => 'apelido',
        'type'         => 'text',
        'instructions' => 'Como a pessoa é conhecida no bairro (se diferente do nome completo)',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_figura_foto',
        'label'         => 'Foto',
        'name'          => 'foto',
        'type'          => 'image',
        'instructions'  => 'Retrato da pessoa. Proporção quadrada recomendada.',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'show_in_rest'  => true,
      ],
      [
        'key'          => 'field_figura_periodo',
        'label'        => 'Período de Referência no Bairro',
        'name'         => 'periodo',
        'type'         => 'text',
        'instructions' => 'Ex: "1960 – 2010", "Décadas de 70 e 80", "Nasceu em 1945"',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_figura_area_atuacao',
        'label'        => 'Área de Atuação',
        'name'         => 'area_atuacao',
        'type'         => 'select',
        'choices'      => [
          'cultura'    => 'Cultura & Arte',
          'educacao'   => 'Educação',
          'politica'   => 'Política & Liderança Comunitária',
          'religiao'   => 'Religião',
          'esporte'    => 'Esporte',
          'comercio'   => 'Comércio & Ofícios',
          'saude'      => 'Saúde',
          'historia'   => 'Memória & História',
          'outro'      => 'Outro',
        ],
        'default_value' => 'cultura',
        'return_format' => 'value',
        'ui'           => 1,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_figura_bio',
        'label'        => 'Biografia',
        'name'         => 'bio',
        'type'         => 'wysiwyg',
        'instructions' => 'Texto completo da biografia. Será exibido na página de detalhe.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_figura_resumo',
        'label'        => 'Resumo (para o card da listagem)',
        'name'         => 'resumo',
        'type'         => 'textarea',
        'instructions' => 'Texto curto para o card. Máximo 2-3 linhas.',
        'rows'         => 3,
        'show_in_rest' => true,
      ],
      [
        'key'        => 'field_figura_destaques',
        'label'      => 'Destaques / Conquistas',
        'name'       => 'destaques',
        'type'       => 'repeater',
        'instructions' => 'Fatos marcantes da trajetória',
        'min'        => 0,
        'max'        => 8,
        'layout'     => 'table',
        'show_in_rest' => true,
        'sub_fields' => [
          [
            'key'          => 'field_figura_destaque_texto',
            'label'        => 'Destaque',
            'name'         => 'texto',
            'type'         => 'text',
            'column_width' => '100',
            'show_in_rest' => true,
          ],
        ],
      ],
      [
        'key'        => 'field_figura_galeria',
        'label'      => 'Galeria de Fotos',
        'name'       => 'galeria',
        'type'       => 'gallery',
        'instructions' => 'Fotos adicionais da pessoa',
        'return_format' => 'array',
        'preview_size'  => 'thumbnail',
        'show_in_rest'  => true,
      ],
      [
        'key'          => 'field_figura_ordem',
        'label'        => 'Ordem de Exibição',
        'name'         => 'ordem',
        'type'         => 'number',
        'default_value' => 99,
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_figura_destaque_home',
        'label'         => 'Destaque (exibir na Home)',
        'name'          => 'destaque_home',
        'type'          => 'true_false',
        'default_value' => 0,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_figura_ativo',
        'label'         => 'Publicado no site',
        'name'          => 'ativo',
        'type'          => 'true_false',
        'default_value' => 1,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
    ],
    'location'     => [[['param' => 'post_type', 'operator' => '==', 'value' => 'figura_notavel']]],
    'show_in_rest' => true,
  ]);


  // ══ Field Group: Hemeroteca ════════════════════════════════════════════════
  acf_add_local_field_group([
    'key'      => 'group_memorial_hemeroteca',
    'title'    => 'Dados do Recorte',
    'fields'   => [
      [
        'key'          => 'field_hemeroteca_tipo',
        'label'        => 'Tipo de Mídia',
        'name'         => 'tipo',
        'type'         => 'select',
        'choices'      => [
          'jornal'   => 'Jornal',
          'revista'  => 'Revista',
          'tv'       => 'Televisão',
          'radio'    => 'Rádio',
          'online'   => 'Online / Digital',
          'outro'    => 'Outro',
        ],
        'default_value' => 'jornal',
        'return_format' => 'value',
        'ui'           => 1,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_hemeroteca_veiculo',
        'label'        => 'Veículo / Publicação',
        'name'         => 'veiculo',
        'type'         => 'text',
        'instructions' => 'Ex: A Tarde, Correio da Bahia, Jornal da Bahia',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_hemeroteca_data_publicacao',
        'label'        => 'Data de Publicação',
        'name'         => 'data_publicacao',
        'type'         => 'date_picker',
        'display_format' => 'd/m/Y',
        'return_format' => 'Y-m-d',
        'show_in_rest'  => true,
      ],
      [
        'key'          => 'field_hemeroteca_data_aproximada',
        'label'        => 'Data Aproximada (texto)',
        'name'         => 'data_aproximada',
        'type'         => 'text',
        'instructions' => 'Usar se a data exata for desconhecida. Ex: "Junho de 1987"',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_hemeroteca_imagem_recorte',
        'label'         => 'Imagem do Recorte',
        'name'          => 'imagem_recorte',
        'type'          => 'image',
        'instructions'  => 'Digitalização do recorte. Proporção livre.',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'show_in_rest'  => true,
      ],
      [
        'key'          => 'field_hemeroteca_resumo',
        'label'        => 'Resumo',
        'name'         => 'resumo',
        'type'         => 'textarea',
        'instructions' => 'Breve descrição do que a matéria trata',
        'rows'         => 3,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_hemeroteca_transcricao',
        'label'        => 'Transcrição do Texto',
        'name'         => 'transcricao',
        'type'         => 'wysiwyg',
        'instructions' => 'Transcrição completa para acessibilidade e busca',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_hemeroteca_link_original',
        'label'        => 'Link Original',
        'name'         => 'link_original',
        'type'         => 'url',
        'instructions' => 'URL da matéria online (se disponível)',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_hemeroteca_palavras_chave',
        'label'        => 'Palavras-chave',
        'name'         => 'palavras_chave',
        'type'         => 'text',
        'instructions' => 'Separadas por vírgula',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_hemeroteca_destaque',
        'label'         => 'Destaque (exibir na Home)',
        'name'          => 'destaque',
        'type'          => 'true_false',
        'default_value' => 0,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_hemeroteca_ativo',
        'label'         => 'Publicado no site',
        'name'          => 'ativo',
        'type'          => 'true_false',
        'default_value' => 1,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
    ],
    'location'     => [[['param' => 'post_type', 'operator' => '==', 'value' => 'hemeroteca']]],
    'show_in_rest' => true,
  ]);


  // ══ Field Group: Mídia ════════════════════════════════════════════════════
  // Três subcategorias: fototeca | videoteca | audioteca
  acf_add_local_field_group([
    'key'      => 'group_memorial_midia',
    'title'    => 'Dados do Item de Mídia',
    'fields'   => [
      [
        'key'          => 'field_midia_subcategoria',
        'label'        => 'Subcategoria',
        'name'         => 'subcategoria',
        'type'         => 'select',
        'instructions' => 'Fototeca: imagens. Videoteca: vídeos. Audioteca: áudios e podcasts.',
        'choices'      => [
          'fototeca'  => 'Fototeca',
          'videoteca' => 'Videoteca',
          'audioteca' => 'Audioteca',
        ],
        'default_value' => 'fototeca',
        'return_format' => 'value',
        'ui'           => 1,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_midia_tipo',
        'label'        => 'Tipo',
        'name'         => 'tipo',
        'type'         => 'select',
        'instructions' => 'Fototeca: foto-historica | registro-evento | imagem-comunidade. Videoteca: documentario | entrevista | espetaculo | reportagem. Audioteca: podcast | depoimento-audio | historia-oral | trilha-sonora.',
        'choices'      => [
          // Fototeca
          'foto-historica'     => 'Foto Histórica',
          'registro-evento'    => 'Registro de Evento',
          'imagem-comunidade'  => 'Imagem da Comunidade',
          // Videoteca
          'documentario'       => 'Documentário',
          'entrevista'         => 'Entrevista',
          'espetaculo'         => 'Espetáculo',
          'reportagem'         => 'Reportagem',
          // Audioteca
          'podcast'            => 'Podcast',
          'depoimento-audio'   => 'Depoimento em Áudio',
          'historia-oral'      => 'História Oral',
          'trilha-sonora'      => 'Trilha Sonora',
        ],
        'default_value' => 'foto-historica',
        'return_format' => 'value',
        'ui'           => 1,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_midia_descricao',
        'label'        => 'Descrição',
        'name'         => 'descricao',
        'type'         => 'textarea',
        'instructions' => 'Descrição do item de mídia. Contexto histórico, personagens, evento etc.',
        'rows'         => 4,
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_midia_imagem_principal',
        'label'         => 'Imagem Principal / Thumbnail',
        'name'          => 'imagem_principal',
        'type'          => 'image',
        'instructions'  => 'Para fototeca: a foto. Para vídeo/áudio: thumbnail de capa.',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_midia_galeria',
        'label'         => 'Galeria de Imagens',
        'name'          => 'galeria',
        'type'          => 'gallery',
        'instructions'  => 'Imagens adicionais (apenas para fototeca).',
        'return_format' => 'array',
        'preview_size'  => 'thumbnail',
        'show_in_rest'  => true,
      ],

      // ── Campos Fototeca ────────────────────────────────────────────────────
      [
        'key'          => 'field_midia_creditos_foto',
        'label'        => 'Créditos da Foto (Fototeca)',
        'name'         => 'creditos_foto',
        'type'         => 'text',
        'instructions' => 'Fotógrafo ou arquivo de origem. Ex: Arquivo Municipal de Salvador.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_midia_local',
        'label'        => 'Local (Fototeca)',
        'name'         => 'local',
        'type'         => 'text',
        'instructions' => 'Local onde a foto foi tirada. Ex: Praça Central, Alto do Cabrito.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_midia_pessoas',
        'label'        => 'Pessoas Identificadas (Fototeca)',
        'name'         => 'pessoas',
        'type'         => 'textarea',
        'instructions' => 'Nomes das pessoas identificadas na foto (da esq. para dir.).',
        'rows'         => 3,
        'show_in_rest' => true,
      ],

      // ── Campos Videoteca / Audioteca ──────────────────────────────────────
      [
        'key'          => 'field_midia_url_media',
        'label'        => 'URL do Vídeo / Áudio',
        'name'         => 'url_media',
        'type'         => 'url',
        'instructions' => 'URL do YouTube, Vimeo, SoundCloud ou arquivo hospedado.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_midia_duracao',
        'label'        => 'Duração',
        'name'         => 'duracao',
        'type'         => 'text',
        'instructions' => 'Formato HH:MM:SS ou MM:SS. Ex: "18:42" ou "1:12:00".',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_midia_diretor_credito',
        'label'        => 'Diretor / Apresentador',
        'name'         => 'diretor_credito',
        'type'         => 'text',
        'instructions' => 'Diretor (vídeo) ou apresentador/locutor (áudio).',
        'show_in_rest' => true,
      ],

      // ── Campos Comuns ──────────────────────────────────────────────────────
      [
        'key'           => 'field_midia_data_registro',
        'label'         => 'Data de Registro',
        'name'          => 'data_registro',
        'type'          => 'date_picker',
        'instructions'  => 'Data em que o item foi registrado/produzido.',
        'display_format' => 'd/m/Y',
        'return_format'  => 'Y-m-d',
        'show_in_rest'   => true,
      ],
      [
        'key'          => 'field_midia_data_aproximada',
        'label'        => 'Data Aproximada',
        'name'         => 'data_aproximada',
        'type'         => 'text',
        'instructions' => 'Texto livre quando a data exata é desconhecida. Ex: "c. 1975", "jun. 1992".',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_midia_palavras_chave',
        'label'        => 'Palavras-chave',
        'name'         => 'palavras_chave',
        'type'         => 'text',
        'instructions' => 'Separadas por vírgula. Ex: festa junina, São João, cultura.',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_midia_creditos',
        'label'        => 'Créditos Gerais',
        'name'         => 'creditos',
        'type'         => 'text',
        'instructions' => 'Créditos completos de produção ou doação do item.',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_midia_destaque',
        'label'         => 'Destaque (exibir na Home)',
        'name'          => 'destaque',
        'type'          => 'true_false',
        'instructions'  => 'Marcar para aparecer na seção Mídia da página inicial.',
        'default_value' => 0,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_midia_ativo',
        'label'         => 'Publicado no site',
        'name'          => 'ativo',
        'type'          => 'true_false',
        'instructions'  => 'Desmarcar para ocultar do site sem excluir.',
        'default_value' => 1,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
    ],
    'location'     => [[['param' => 'post_type', 'operator' => '==', 'value' => 'midia']]],
    'show_in_rest' => true,
  ]);


  // ══ Field Group: Projeto ═══════════════════════════════════════════════════
  acf_add_local_field_group([
    'key'      => 'group_memorial_projeto',
    'title'    => 'Dados do Projeto',
    'fields'   => [
      [
        'key'          => 'field_projeto_resumo',
        'label'        => 'Resumo (card)',
        'name'         => 'resumo',
        'type'         => 'textarea',
        'instructions' => 'Texto curto exibido no card de listagem',
        'rows'         => 3,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_projeto_descricao_completa',
        'label'        => 'Descrição Completa',
        'name'         => 'descricao_completa',
        'type'         => 'wysiwyg',
        'instructions' => 'Texto completo para a página de detalhe do projeto',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_projeto_imagem_capa',
        'label'         => 'Imagem de Capa',
        'name'          => 'imagem_capa',
        'type'          => 'image',
        'instructions'  => 'Proporção 16:9 recomendada',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'show_in_rest'  => true,
      ],
      [
        'key'        => 'field_projeto_galeria',
        'label'      => 'Galeria de Fotos',
        'name'       => 'galeria',
        'type'       => 'gallery',
        'return_format' => 'array',
        'preview_size'  => 'thumbnail',
        'show_in_rest'  => true,
      ],
      [
        'key'          => 'field_projeto_ano_inicio',
        'label'        => 'Ano de Início',
        'name'         => 'ano_inicio',
        'type'         => 'number',
        'min'          => 1980,
        'max'          => 2100,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_projeto_ano_fim',
        'label'        => 'Ano de Conclusão',
        'name'         => 'ano_fim',
        'type'         => 'number',
        'instructions' => 'Deixar em branco se o projeto ainda estiver ativo',
        'min'          => 1980,
        'max'          => 2100,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_projeto_status',
        'label'        => 'Status',
        'name'         => 'status',
        'type'         => 'select',
        'choices'      => [
          'ativo'     => 'Em andamento',
          'concluido' => 'Concluído',
          'pausado'   => 'Pausado',
        ],
        'default_value' => 'ativo',
        'return_format' => 'value',
        'ui'           => 1,
        'show_in_rest' => true,
      ],
      [
        'key'        => 'field_projeto_parceiros',
        'label'      => 'Parceiros',
        'name'       => 'parceiros',
        'type'       => 'repeater',
        'instructions' => 'Organizações e instituições parceiras do projeto',
        'min'        => 0,
        'max'        => 20,
        'layout'     => 'table',
        'show_in_rest' => true,
        'sub_fields' => [
          [
            'key'          => 'field_projeto_parceiro_nome',
            'label'        => 'Nome',
            'name'         => 'nome',
            'type'         => 'text',
            'column_width' => '40',
            'show_in_rest' => true,
          ],
          [
            'key'           => 'field_projeto_parceiro_logo',
            'label'         => 'Logo',
            'name'          => 'logo',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'thumbnail',
            'column_width'  => '30',
            'show_in_rest'  => true,
          ],
          [
            'key'          => 'field_projeto_parceiro_url',
            'label'        => 'URL',
            'name'         => 'url',
            'type'         => 'url',
            'column_width' => '30',
            'show_in_rest' => true,
          ],
        ],
      ],
      [
        'key'        => 'field_projeto_numeros_impacto',
        'label'      => 'Números de Impacto',
        'name'       => 'numeros_impacto',
        'type'       => 'repeater',
        'instructions' => 'Estatísticas e dados de impacto do projeto',
        'min'        => 0,
        'max'        => 6,
        'layout'     => 'table',
        'show_in_rest' => true,
        'sub_fields' => [
          [
            'key'          => 'field_projeto_numero_valor',
            'label'        => 'Número',
            'name'         => 'valor',
            'type'         => 'text',
            'instructions' => 'Ex: 500, +1.2k',
            'column_width' => '30',
            'show_in_rest' => true,
          ],
          [
            'key'          => 'field_projeto_numero_label',
            'label'        => 'Descrição',
            'name'         => 'label',
            'type'         => 'text',
            'instructions' => 'Ex: Famílias atendidas',
            'column_width' => '70',
            'show_in_rest' => true,
          ],
        ],
      ],
      [
        'key'          => 'field_projeto_ordem',
        'label'        => 'Ordem de Exibição',
        'name'         => 'ordem',
        'type'         => 'number',
        'default_value' => 99,
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_projeto_destaque',
        'label'         => 'Destaque (exibir na Home)',
        'name'          => 'destaque',
        'type'          => 'true_false',
        'default_value' => 0,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_projeto_ativo',
        'label'         => 'Publicado no site',
        'name'          => 'ativo',
        'type'          => 'true_false',
        'default_value' => 1,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
    ],
    'location'     => [[['param' => 'post_type', 'operator' => '==', 'value' => 'projeto']]],
    'show_in_rest' => true,
  ]);


  // ══ Field Group: Notícia / Evento ══════════════════════════════════════════
  acf_add_local_field_group([
    'key'      => 'group_memorial_noticia',
    'title'    => 'Dados da Notícia / Evento',
    'fields'   => [
      [
        'key'          => 'field_noticia_tipo',
        'label'        => 'Tipo',
        'name'         => 'tipo',
        'type'         => 'select',
        'instructions' => 'Eventos futuros aparecem em branco no site; passados em cinza com resumo',
        'choices'      => [
          'passado' => 'Acontecimento (passado)',
          'futuro'  => 'Evento (futuro / em breve)',
          'noticia' => 'Notícia (sem data de evento)',
        ],
        'default_value' => 'noticia',
        'return_format' => 'value',
        'ui'           => 1,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_noticia_data_evento',
        'label'        => 'Data do Evento',
        'name'         => 'data_evento',
        'type'         => 'date_time_picker',
        'instructions' => 'Preencher apenas quando for um evento com data específica',
        'display_format' => 'd/m/Y H:i',
        'return_format'  => 'Y-m-d H:i:s',
        'show_in_rest'   => true,
      ],
      [
        'key'          => 'field_noticia_local_evento',
        'label'        => 'Local do Evento',
        'name'         => 'local_evento',
        'type'         => 'text',
        'instructions' => 'Endereço ou nome do local (para eventos futuros)',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_noticia_imagem_capa',
        'label'         => 'Imagem de Capa',
        'name'          => 'imagem_capa',
        'type'          => 'image',
        'instructions'  => 'Proporção 16:9 recomendada',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'show_in_rest'  => true,
      ],
      [
        'key'          => 'field_noticia_categoria',
        'label'        => 'Categoria',
        'name'         => 'categoria',
        'type'         => 'select',
        'choices'      => [
          'memoria'    => 'Memória & Cultura',
          'evento'     => 'Evento Comunitário',
          'projeto'    => 'Projeto',
          'educacao'   => 'Educação',
          'saude'      => 'Saúde',
          'esporte'    => 'Esporte & Lazer',
          'arte'       => 'Arte & Expressão',
          'outro'      => 'Outro',
        ],
        'default_value' => 'memoria',
        'return_format' => 'value',
        'ui'           => 1,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_noticia_resumo_evento_passado',
        'label'        => 'Resumo do que foi (evento passado)',
        'name'         => 'resumo_passado',
        'type'         => 'textarea',
        'instructions' => 'Exibido no hover do card quando o evento já ocorreu. Descreva como foi.',
        'rows'         => 3,
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_noticia_ativo',
        'label'         => 'Publicado no site',
        'name'          => 'ativo',
        'type'          => 'true_false',
        'default_value' => 1,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
    ],
    'location'     => [[['param' => 'post_type', 'operator' => '==', 'value' => 'noticia']]],
    'show_in_rest' => true,
  ]);


  // ══ Field Group: Timeline ═════════════════════════════════════════════════
  acf_add_local_field_group([
    'key'      => 'group_memorial_timeline',
    'title'    => 'Dados do Marco Histórico',
    'fields'   => [
      [
        'key'          => 'field_timeline_ano',
        'label'        => 'Ano / Período',
        'name'         => 'ano',
        'type'         => 'text',
        'instructions' => 'Texto livre: "1980", "Década de 1970", "ca. 1965", "1960s"',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_timeline_descricao',
        'label'        => 'Descrição Curta (listagem)',
        'name'         => 'descricao',
        'type'         => 'textarea',
        'instructions' => 'Texto exibido no card da timeline. Máximo 2-3 linhas.',
        'rows'         => 3,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_timeline_conteudo_completo',
        'label'        => 'Conteúdo Completo (página de detalhe)',
        'name'         => 'conteudo_completo',
        'type'         => 'wysiwyg',
        'instructions' => 'Rich text completo exibido ao abrir o marco na página de detalhe.',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_timeline_imagem',
        'label'         => 'Imagem Principal',
        'name'          => 'imagem',
        'type'          => 'image',
        'instructions'  => 'Foto ou documento que representa o marco. Proporção 4:3 ou 16:9.',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_timeline_galeria',
        'label'         => 'Galeria de Imagens',
        'name'          => 'galeria',
        'type'          => 'gallery',
        'instructions'  => 'Imagens adicionais sobre o marco histórico.',
        'return_format' => 'array',
        'preview_size'  => 'thumbnail',
        'show_in_rest'  => true,
      ],
      [
        'key'          => 'field_timeline_tags',
        'label'        => 'Tags / Palavras-chave',
        'name'         => 'tags',
        'type'         => 'text',
        'instructions' => 'Separadas por vírgula. Ex: escola, religião, cultura, política',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_timeline_ordem',
        'label'        => 'Ordem de Exibição',
        'name'         => 'ordem',
        'type'         => 'number',
        'instructions' => 'Número menor aparece primeiro. Use o número do ano para ordenar cronologicamente (ex: 1980, 1995).',
        'default_value' => 9999,
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_timeline_destaque',
        'label'         => 'Destaque (exibir na Home)',
        'name'          => 'destaque',
        'type'          => 'true_false',
        'instructions'  => 'Marcar para aparecer na seção História da página inicial.',
        'default_value' => 0,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
      [
        'key'           => 'field_timeline_ativo',
        'label'         => 'Publicado no site',
        'name'          => 'ativo',
        'type'          => 'true_false',
        'instructions'  => 'Desmarcar para ocultar sem excluir.',
        'default_value' => 1,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
    ],
    'location'     => [[['param' => 'post_type', 'operator' => '==', 'value' => 'timeline']]],
    'show_in_rest' => true,
  ]);


  // ══ Field Group: Campanha ═══════════════════════════════════════════════════
  // Slides do Hero Carousel da home
  acf_add_local_field_group([
    'key'      => 'group_memorial_campanha',
    'title'    => 'Dados da Campanha',
    'fields'   => [
      [
        'key'           => 'field_campanha_imagem',
        'label'         => 'Imagem do Slide',
        'name'          => 'imagem',
        'type'          => 'image',
        'instructions'  => 'Imagem de fundo do slide. Tem prioridade sobre a imagem destacada (thumbnail) do post.',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'show_in_rest'  => true,
      ],
      [
        'key'          => 'field_campanha_subtitulo',
        'label'        => 'Subtítulo',
        'name'         => 'subtitulo',
        'type'         => 'textarea',
        'instructions' => 'Texto exibido abaixo do título no slide do carrossel.',
        'rows'         => 2,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_campanha_cta_texto',
        'label'        => 'Texto do Botão CTA',
        'name'         => 'cta_texto',
        'type'         => 'text',
        'instructions' => 'Ex: "Explore o acervo".',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_campanha_cta_url',
        'label'        => 'URL do Botão CTA',
        'name'         => 'cta_url',
        'type'         => 'text',
        'instructions' => 'Ex: /acervo',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_campanha_ordem',
        'label'        => 'Ordem de Exibição',
        'name'         => 'ordem',
        'type'         => 'number',
        'instructions' => 'Número menor aparece primeiro no carrossel.',
        'default_value' => 99,
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_campanha_ativo',
        'label'         => 'Publicado no site',
        'name'          => 'ativo',
        'type'          => 'true_false',
        'instructions'  => 'Desmarcar para ocultar do carrossel sem excluir.',
        'default_value' => 1,
        'ui'            => 1,
        'show_in_rest'  => true,
      ],
    ],
    'location'     => [[['param' => 'post_type', 'operator' => '==', 'value' => 'campanha']]],
    'show_in_rest' => true,
  ]);


  // ══ Field Group: Grupo Comunitário ══════════════════════════════════════════
  acf_add_local_field_group([
    'key'      => 'group_memorial_grupo_comunitario',
    'title'    => 'Dados do Grupo Comunitário',
    'fields'   => [
      [
        'key'          => 'field_grupo_descricao',
        'label'        => 'Descrição',
        'name'         => 'descricao',
        'type'         => 'textarea',
        'instructions' => 'Texto principal da seção "Quem Somos".',
        'rows'         => 5,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_grupo_missao',
        'label'        => 'Missão',
        'name'         => 'missao',
        'type'         => 'textarea',
        'rows'         => 3,
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_grupo_visao',
        'label'        => 'Visão',
        'name'         => 'visao',
        'type'         => 'textarea',
        'rows'         => 3,
        'show_in_rest' => true,
      ],
      [
        'key'        => 'field_grupo_valores',
        'label'      => 'Valores',
        'name'       => 'valores',
        'type'       => 'repeater',
        'instructions' => 'Lista de valores do grupo comunitário.',
        'min'        => 0,
        'max'        => 10,
        'layout'     => 'table',
        'show_in_rest' => true,
        'sub_fields' => [
          [
            'key'          => 'field_grupo_valor_texto',
            'label'        => 'Valor',
            'name'         => 'texto',
            'type'         => 'text',
            'show_in_rest' => true,
          ],
        ],
      ],
      [
        'key'        => 'field_grupo_membros',
        'label'      => 'Membros',
        'name'       => 'membros',
        'type'       => 'repeater',
        'instructions' => 'Membros exibidos na página do grupo comunitário.',
        'min'        => 0,
        'max'        => 30,
        'layout'     => 'table',
        'show_in_rest' => true,
        'sub_fields' => [
          [
            'key'          => 'field_grupo_membro_nome',
            'label'        => 'Nome',
            'name'         => 'nome',
            'type'         => 'text',
            'column_width' => '35',
            'show_in_rest' => true,
          ],
          [
            'key'          => 'field_grupo_membro_papel',
            'label'        => 'Papel',
            'name'         => 'papel',
            'type'         => 'text',
            'column_width' => '35',
            'show_in_rest' => true,
          ],
          [
            'key'           => 'field_grupo_membro_foto',
            'label'         => 'Foto',
            'name'          => 'foto',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'thumbnail',
            'column_width'  => '30',
            'show_in_rest'  => true,
          ],
        ],
      ],
    ],
    'location'     => [[['param' => 'post_type', 'operator' => '==', 'value' => 'grupo_comunitario']]],
    'show_in_rest' => true,
  ]);


  // ══ Field Group: Options Page (Configurações Globais) ══════════════════════
  acf_add_local_field_group([
    'key'      => 'group_memorial_options',
    'title'    => 'Configurações Globais do Memorial',
    'fields'   => [

      // ── Aba: Hero ──────────────────────────────────────────────────────────
      [
        'key'   => 'field_opt_tab_hero',
        'label' => 'Hero / Carrossel',
        'name'  => '',
        'type'  => 'tab',
      ],
      [
        'key'        => 'field_opt_hero_slides',
        'label'      => 'Slides do Carrossel',
        'name'       => 'hero_slides',
        'type'       => 'repeater',
        'instructions' => 'Imagens e texto de cada slide do carrossel principal',
        'min'        => 1,
        'max'        => 8,
        'layout'     => 'block',
        'show_in_rest' => true,
        'sub_fields' => [
          [
            'key'           => 'field_opt_slide_imagem',
            'label'         => 'Imagem do Slide',
            'name'          => 'imagem',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'show_in_rest'  => true,
          ],
          [
            'key'          => 'field_opt_slide_titulo',
            'label'        => 'Título',
            'name'         => 'titulo',
            'type'         => 'text',
            'show_in_rest' => true,
          ],
          [
            'key'          => 'field_opt_slide_subtitulo',
            'label'        => 'Subtítulo',
            'name'         => 'subtitulo',
            'type'         => 'textarea',
            'rows'         => 2,
            'show_in_rest' => true,
          ],
          [
            'key'          => 'field_opt_slide_cta_texto',
            'label'        => 'Texto do Botão CTA',
            'name'         => 'cta_texto',
            'type'         => 'text',
            'default_value' => 'Explorar acervo →',
            'show_in_rest' => true,
          ],
          [
            'key'          => 'field_opt_slide_cta_url',
            'label'        => 'URL do Botão CTA',
            'name'         => 'cta_url',
            'type'         => 'text',
            'default_value' => '/acervo',
            'show_in_rest' => true,
          ],
        ],
      ],

      // ── Aba: Nossa História ────────────────────────────────────────────────
      [
        'key'   => 'field_opt_tab_historia',
        'label' => 'Nossa História',
        'name'  => '',
        'type'  => 'tab',
      ],
      [
        'key'          => 'field_opt_historia_titulo',
        'label'        => 'Título da Seção',
        'name'         => 'historia_titulo',
        'type'         => 'text',
        'default_value' => 'Nossa História',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_opt_historia_intro',
        'label'        => 'Texto de Introdução',
        'name'         => 'historia_intro',
        'type'         => 'wysiwyg',
        'instructions' => 'Texto principal da seção "Nossa História" na home',
        'show_in_rest' => true,
      ],
      [
        'key'           => 'field_opt_historia_imagem',
        'label'         => 'Imagem da Seção',
        'name'          => 'historia_imagem',
        'type'          => 'image',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'show_in_rest'  => true,
      ],
      [
        'key'        => 'field_opt_timeline',
        'label'      => 'Linha do Tempo',
        'name'       => 'timeline',
        'type'       => 'repeater',
        'instructions' => 'Marcos históricos do bairro e do memorial',
        'min'        => 0,
        'max'        => 30,
        'layout'     => 'block',
        'show_in_rest' => true,
        'sub_fields' => [
          [
            'key'          => 'field_opt_timeline_ano',
            'label'        => 'Ano',
            'name'         => 'ano',
            'type'         => 'number',
            'min'          => 1800,
            'max'          => 2100,
            'show_in_rest' => true,
          ],
          [
            'key'          => 'field_opt_timeline_titulo',
            'label'        => 'Título do Marco',
            'name'         => 'titulo',
            'type'         => 'text',
            'show_in_rest' => true,
          ],
          [
            'key'          => 'field_opt_timeline_descricao',
            'label'        => 'Descrição',
            'name'         => 'descricao',
            'type'         => 'textarea',
            'rows'         => 3,
            'show_in_rest' => true,
          ],
          [
            'key'           => 'field_opt_timeline_imagem',
            'label'         => 'Imagem (opcional)',
            'name'          => 'imagem',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'thumbnail',
            'show_in_rest'  => true,
          ],
        ],
      ],

      // ── Aba: Grupo Comunitário ─────────────────────────────────────────────
      [
        'key'   => 'field_opt_tab_grupo',
        'label' => 'Grupo Comunitário',
        'name'  => '',
        'type'  => 'tab',
      ],
      [
        'key'          => 'field_opt_grupo_texto',
        'label'        => 'Sobre o Grupo Comunitário',
        'name'         => 'grupo_texto',
        'type'         => 'wysiwyg',
        'instructions' => 'Texto da seção "Quem Somos" (o grupo que mantém o memorial)',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_opt_grupo_missao',
        'label'        => 'Missão',
        'name'         => 'grupo_missao',
        'type'         => 'textarea',
        'rows'         => 3,
        'show_in_rest' => true,
      ],
      [
        'key'        => 'field_opt_grupo_membros',
        'label'      => 'Membros do Grupo',
        'name'       => 'grupo_membros',
        'type'       => 'repeater',
        'min'        => 0,
        'max'        => 30,
        'layout'     => 'table',
        'show_in_rest' => true,
        'sub_fields' => [
          [
            'key'          => 'field_opt_membro_nome',
            'label'        => 'Nome',
            'name'         => 'nome',
            'type'         => 'text',
            'column_width' => '35',
            'show_in_rest' => true,
          ],
          [
            'key'          => 'field_opt_membro_papel',
            'label'        => 'Papel',
            'name'         => 'papel',
            'type'         => 'text',
            'column_width' => '35',
            'show_in_rest' => true,
          ],
          [
            'key'           => 'field_opt_membro_foto',
            'label'         => 'Foto',
            'name'          => 'foto',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'thumbnail',
            'column_width'  => '30',
            'show_in_rest'  => true,
          ],
        ],
      ],

      // ── Aba: Parceiros ─────────────────────────────────────────────────────
      [
        'key'   => 'field_opt_tab_parceiros',
        'label' => 'Parceiros',
        'name'  => '',
        'type'  => 'tab',
      ],
      [
        'key'        => 'field_opt_parceiros',
        'label'      => 'Lista de Parceiros',
        'name'       => 'parceiros',
        'type'       => 'repeater',
        'instructions' => 'Logos dos parceiros exibidos na home e no footer',
        'min'        => 0,
        'max'        => 30,
        'layout'     => 'table',
        'show_in_rest' => true,
        'sub_fields' => [
          [
            'key'          => 'field_opt_parceiro_nome',
            'label'        => 'Nome',
            'name'         => 'nome',
            'type'         => 'text',
            'column_width' => '30',
            'show_in_rest' => true,
          ],
          [
            'key'           => 'field_opt_parceiro_logo',
            'label'         => 'Logo',
            'name'          => 'logo',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'thumbnail',
            'column_width'  => '40',
            'show_in_rest'  => true,
          ],
          [
            'key'          => 'field_opt_parceiro_url',
            'label'        => 'URL',
            'name'         => 'url',
            'type'         => 'url',
            'column_width' => '30',
            'show_in_rest' => true,
          ],
        ],
      ],

      // ── Aba: Contato & Footer ──────────────────────────────────────────────
      [
        'key'   => 'field_opt_tab_contato',
        'label' => 'Contato / Footer',
        'name'  => '',
        'type'  => 'tab',
      ],
      [
        'key'          => 'field_opt_endereco',
        'label'        => 'Endereço',
        'name'         => 'endereco',
        'type'         => 'text',
        'default_value' => 'Alto do Cabrito, Salvador — BA',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_opt_email',
        'label'        => 'E-mail',
        'name'         => 'email',
        'type'         => 'email',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_opt_telefone',
        'label'        => 'Telefone / WhatsApp',
        'name'         => 'telefone',
        'type'         => 'text',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_opt_instagram_url',
        'label'        => 'Instagram URL',
        'name'         => 'instagram_url',
        'type'         => 'url',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_opt_facebook_url',
        'label'        => 'Facebook URL',
        'name'         => 'facebook_url',
        'type'         => 'url',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_opt_youtube_url',
        'label'        => 'YouTube URL',
        'name'         => 'youtube_url',
        'type'         => 'url',
        'show_in_rest' => true,
      ],
      [
        'key'          => 'field_opt_texto_footer',
        'label'        => 'Texto do Footer',
        'name'         => 'texto_footer',
        'type'         => 'textarea',
        'instructions' => 'Texto de créditos, avisos legais etc.',
        'rows'         => 3,
        'show_in_rest' => true,
      ],
    ],
    'location'     => [[['param' => 'options_page', 'operator' => '==', 'value' => 'memorial-config']]],
    'show_in_rest' => true,
  ]);
}


// ─── 6. ENDPOINT CUSTOMIZADO /memorial/v1/opcoes ──────────────────────────────
//
//  O endpoint nativo do ACF para options page (/wp/v2/options) requer
//  autenticação. Este endpoint público lê os campos e os serve sem auth.

add_action('rest_api_init', 'memorial_register_rest_routes');
function memorial_register_rest_routes()
{
  register_rest_route('memorial/v1', '/opcoes', [
    'methods'             => 'GET',
    'callback'            => 'memorial_rest_opcoes',
    'permission_callback' => '__return_true',  // leitura pública
  ]);

  // Endpoint agregado: retorna tudo de uma vez (reduz chamadas do frontend)
  register_rest_route('memorial/v1', '/home', [
    'methods'             => 'GET',
    'callback'            => 'memorial_rest_home',
    'permission_callback' => '__return_true',
  ]);

  // Timeline completa (todos os marcos, ordenados cronologicamente)
  register_rest_route('memorial/v1', '/timeline', [
    'methods'             => 'GET',
    'callback'            => 'memorial_rest_timeline',
    'permission_callback' => '__return_true',
  ]);
}

/**
 * Retorna os campos da Options Page do ACF.
 */
function memorial_rest_opcoes(WP_REST_Request $request): WP_REST_Response
{
  if (! function_exists('get_field')) {
    return new WP_REST_Response(['error' => 'ACF não ativo'], 500);
  }

  $campos = [
    // Hero
    'hero_slides'     => get_field('hero_slides', 'option') ?: [],
    // História
    'historia_titulo' => get_field('historia_titulo', 'option'),
    'historia_intro'  => get_field('historia_intro', 'option'),
    'historia_imagem' => get_field('historia_imagem', 'option'),
    'timeline'        => get_field('timeline', 'option') ?: [],
    // Grupo Comunitário
    'grupo_texto'     => get_field('grupo_texto', 'option'),
    'grupo_missao'    => get_field('grupo_missao', 'option'),
    'grupo_membros'   => get_field('grupo_membros', 'option') ?: [],
    // Parceiros
    'parceiros'       => get_field('parceiros', 'option') ?: [],
    // Contato
    'endereco'        => get_field('endereco', 'option'),
    'email'           => get_field('email', 'option'),
    'telefone'        => get_field('telefone', 'option'),
    'instagram_url'   => get_field('instagram_url', 'option'),
    'facebook_url'    => get_field('facebook_url', 'option'),
    'youtube_url'     => get_field('youtube_url', 'option'),
    'texto_footer'    => get_field('texto_footer', 'option'),
  ];

  return new WP_REST_Response($campos, 200);
}

/**
 * Endpoint agregado para a Home — retorna tudo num único request.
 * Reduz o número de chamadas HTTP do frontend React.
 */
function memorial_rest_home(WP_REST_Request $request): WP_REST_Response
{
  if (! function_exists('get_field')) {
    return new WP_REST_Response(['error' => 'ACF não ativo'], 500);
  }

  // Acervo em destaque
  $acervo_query = new WP_Query([
    'post_type'      => 'acervo',
    'post_status'    => 'publish',
    'posts_per_page' => 8,
    'meta_query'     => [
      ['key' => 'destaque', 'value' => '1', 'compare' => '='],
    ],
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'ordem',
    'order'          => 'ASC',
  ]);

  $acervo = array_map(fn($p) => memorial_format_post($p, 'acervo'), $acervo_query->posts);

  // Figuras notáveis em destaque
  $figuras_query = new WP_Query([
    'post_type'      => 'figura_notavel',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'meta_query'     => [
      ['key' => 'destaque_home', 'value' => '1', 'compare' => '='],
    ],
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'ordem',
    'order'          => 'ASC',
  ]);

  $figuras = array_map(fn($p) => memorial_format_post($p, 'figura_notavel'), $figuras_query->posts);

  // Mídia em destaque (fototeca, videoteca, audioteca)
  $midia_query = new WP_Query([
    'post_type'      => 'midia',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'meta_query'     => [
      ['key' => 'destaque', 'value' => '1', 'compare' => '='],
    ],
  ]);

  $midia = array_map(fn($p) => memorial_format_post($p, 'midia'), $midia_query->posts);

  // Projetos em destaque
  $projetos_query = new WP_Query([
    'post_type'      => 'projeto',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'meta_query'     => [
      ['key' => 'destaque', 'value' => '1', 'compare' => '='],
    ],
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'ordem',
    'order'          => 'ASC',
  ]);

  $projetos = array_map(fn($p) => memorial_format_post($p, 'projeto'), $projetos_query->posts);

  // Notícias recentes (últimas 6)
  $noticias_query = new WP_Query([
    'post_type'      => 'noticia',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'orderby'        => 'date',
    'order'          => 'DESC',
  ]);

  $noticias = array_map(fn($p) => memorial_format_post($p, 'noticia'), $noticias_query->posts);

  // Timeline em destaque (para a seção História da home)
  $timeline_query = new WP_Query([
    'post_type'      => 'timeline',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_query'     => [
      ['key' => 'destaque', 'value' => '1', 'compare' => '='],
    ],
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'ordem',
    'order'          => 'ASC',
  ]);

  $timeline = array_map(fn($p) => memorial_format_post($p, 'timeline'), $timeline_query->posts);

  return new WP_REST_Response([
    'opcoes'   => memorial_rest_opcoes($request)->get_data(),
    'acervo'   => $acervo,
    'figuras'  => $figuras,
    'midia'    => $midia,
    'projetos' => $projetos,
    'noticias' => $noticias,
    'timeline' => $timeline,
  ], 200);
}

/**
 * Endpoint /memorial/v1/timeline — todos os marcos históricos, ordem cronológica.
 */
function memorial_rest_timeline(WP_REST_Request $request): WP_REST_Response
{
  if (! function_exists('get_field')) {
    return new WP_REST_Response(['error' => 'ACF não ativo'], 500);
  }

  $somente_destaque = filter_var(
    $request->get_param('destaque'),
    FILTER_VALIDATE_BOOLEAN
  );

  $args = [
    'post_type'      => 'timeline',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'ordem',
    'order'          => 'ASC',
  ];

  if ($somente_destaque) {
    $args['meta_query'] = [
      ['key' => 'destaque', 'value' => '1', 'compare' => '='],
    ];
  }

  $query = new WP_Query($args);
  $itens = array_map(fn($p) => memorial_format_post($p, 'timeline'), $query->posts);

  return new WP_REST_Response($itens, 200);
}

/**
 * Helper: formata um post com todos os campos ACF para retorno via REST.
 */
function memorial_format_post(WP_Post $post, string $type): array
{
  $acf = function_exists('get_fields') ? (get_fields($post->ID) ?: []) : [];

  return [
    'id'         => $post->ID,
    'slug'       => $post->post_name,
    'titulo'     => $post->post_title,
    'conteudo'   => apply_filters('the_content', $post->post_content),
    'excerpt'    => get_the_excerpt($post),
    'data'       => $post->post_date,
    'thumbnail'  => get_the_post_thumbnail_url($post->ID, 'large') ?: null,
    'type'       => $type,
    'acf'        => $acf,
  ];
}


// ─── 7. INCLUIR CAMPOS ACF NA RESPOSTA REST PADRÃO ────────────────────────────
//
//  Quando o frontend usa /wp/v2/acervo etc., os campos ACF ficam em
//  `response.acf`. Isso adiciona o suporte automaticamente a todos os CPTs.

function memorial_add_acf_to_rest()
{
  $post_types = ['acervo', 'midia', 'figura_notavel', 'hemeroteca', 'projeto', 'noticia', 'timeline', 'campanha', 'grupo_comunitario'];

  foreach ($post_types as $pt) {
    add_filter("rest_prepare_{$pt}", function (WP_REST_Response $response, WP_Post $post) {
      if (function_exists('get_fields')) {
        $response->data['acf'] = get_fields($post->ID) ?: [];
      }
      return $response;
    }, 10, 2);
  }
}
add_action('rest_api_init', 'memorial_add_acf_to_rest');
