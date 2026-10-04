resource "kubernetes_service_v1" "backend" {
  metadata {
    name      = "backend"
    namespace = var.api_namespace
  }

  spec {
    selector = {
      app = "backend"
    }
    port {
      name        = "http"
      protocol    = "TCP"
      port        = 80
      target_port = "http"
    }
    type = "ClusterIP"
  }

  depends_on = [kubernetes_namespace_v1.namespace]
}
