<?php
/**
 * Memorial Alto do Cabrito — Tema Headless
 *
 * Este tema não renderiza conteúdo público. Todo o front-end é
 * servido pelo React (Vite) em ~/Projetos/MemorialAltodoCabrito.
 * O WordPress funciona exclusivamente como CMS via REST API.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php bloginfo('name'); ?> — CMS API</title>
</head>
<body style="
  font-family: system-ui, sans-serif;
  background: #000;
  color: #FF9D00;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  margin: 0;
  text-align: center;
">
  <div>
    <h1 style="font-size: 2rem; margin-bottom: 1rem;">
      Memorial Alto do Cabrito
    </h1>
    <p style="color: #ccc; margin-bottom: 2rem;">
      Backend CMS — Acesse o painel de administração
    </p>
    <a href="<?php echo esc_url(admin_url()); ?>"
       style="
         background: #FF9D00;
         color: #000;
         padding: 12px 28px;
         border-radius: 999px;
         text-decoration: none;
         font-weight: 700;
       ">
      Ir para o Admin →
    </a>
    <p style="margin-top: 2rem; color: #666; font-size: 0.85rem;">
      REST API: <a href="<?php echo esc_url(rest_url('memorial/v1/')); ?>"
                  style="color: #FF9D00;">
        <?php echo esc_html(rest_url('memorial/v1/')); ?>
      </a>
    </p>
  </div>
</body>
</html>
