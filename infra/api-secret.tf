resource "kubernetes_secret_v1" "backend_secrets" {
  metadata {
    name      = "backend-secrets"
    namespace = var.api_namespace
  }

  data = {
    ".env" = <<-EOT
      APP_NAME=Laravel
      APP_ENV=local
      APP_KEY=base64:cUPmwHx4LXa4Z25HhzFiWCf7TlQmSqnt98pnuiHmzgY=
      APP_DEBUG=true
      APP_URL=http://localhost

      APP_LOCALE=en
      APP_FALLBACK_LOCALE=en
      APP_FAKER_LOCALE=en_US

      APP_MAINTENANCE_DRIVER=file
      # APP_MAINTENANCE_STORE=database

      PHP_CLI_SERVER_WORKERS=4

      BCRYPT_ROUNDS=12

      LOG_CHANNEL=stack
      LOG_STACK=single
      LOG_DEPRECATIONS_CHANNEL=null
      LOG_LEVEL=debug

      DB_CONNECTION=pgsql
      DB_READ_HOST="CHANGE_ME"
      DB_WRITE_HOST="CHANGE_ME"
      DB_PORT=5432
      DB_DATABASE="CHANGE_ME"
      DB_USERNAME="CHANGE_ME"
      DB_PASSWORD="CHANGE_ME"

      SESSION_DRIVER=cookie
      SESSION_LIFETIME=120
      SESSION_ENCRYPT=false
      SESSION_PATH=/
      SESSION_DOMAIN=null

      BROADCAST_CONNECTION=log
      FILESYSTEM_DISK=local
      QUEUE_CONNECTION=database

      CACHE_STORE=database
      # CACHE_PREFIX=

      MEMCACHED_HOST=127.0.0.1

      REDIS_CLIENT=phpredis
      REDIS_HOST=CHANGE_ME
      REDIS_PASSWORD="CHANGE_ME"
      REDIS_PORT=6379

      MAIL_MAILER=log
      MAIL_SCHEME=null
      MAIL_HOST=127.0.0.1
      MAIL_PORT=2525
      MAIL_USERNAME=null
      MAIL_PASSWORD=null
      MAIL_FROM_ADDRESS="hello@example.com"
      MAIL_FROM_NAME="$${APP_NAME}"

      AWS_ACCESS_KEY_ID=
      AWS_SECRET_ACCESS_KEY=
      AWS_DEFAULT_REGION=us-east-1
      AWS_BUCKET=
      AWS_USE_PATH_STYLE_ENDPOINT=false

      VITE_APP_NAME="$${APP_NAME}"
    EOT
  }

  depends_on = [kubernetes_namespace_v1.namespace]
}
