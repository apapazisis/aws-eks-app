resource "kubernetes_config_map_v1" "backend_configs" {
  metadata {
    name      = "backend-configs"
    namespace = var.api_namespace
  }

  data = {
    "nginx.conf" = <<-EOT
      server {
          listen 80;
          server_name localhost;
          root /var/www/html/public;
          index index.php;

          location /healthy {
            default_type text/plain;
            access_log off;
            return 200;
          }

          location / {
              try_files $uri $uri/ /index.php?$query_string;
          }

          location ~ \.php$ {
              try_files $uri =404;
              include fastcgi_params;
              fastcgi_split_path_info ^(.+\.php)(/.+)$;
              fastcgi_pass 127.0.0.1:9000;
              fastcgi_index index.php;
              fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
              fastcgi_param PATH_INFO $fastcgi_path_info;
          }
      }
    EOT

    "www.conf" = <<-EOT
      user = www-data
      group = www-data
      listen = 127.0.0.1:9000
      pm = dynamic
      pm.max_children = 5
      pm.start_servers = 2
      pm.min_spare_servers = 1
      pm.max_spare_servers = 3
      pm.status_path = /status
    EOT

    "index.php" = <<-EOT
      <?php

      use Illuminate\Foundation\Application;
      use Illuminate\Http\Request;

      define('LARAVEL_START', microtime(true));

      // Determine if the application is in maintenance mode...
      if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
          require $maintenance;
      }

      // Register the Composer autoloader...
      require __DIR__.'/../vendor/autoload.php';

      // Bootstrap Laravel and handle the request...
      /** @var Application $app */
      $app = require_once __DIR__.'/../bootstrap/app.php';

      $app->handleRequest(Request::capture());
    EOT
  }

  depends_on = [kubernetes_namespace_v1.namespace]
}
