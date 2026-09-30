resource "kubernetes_service_v1" "backend" {
  metadata {
    name      = "backend"
    namespace = var.app_namepace
  }

  spec {
    selector = {
      app = "backend"
    }
    port {
      port = 80
    }
    type = "ClusterIP"
  }

  depends_on = [kubernetes_namespace_v1.namespace]
}
