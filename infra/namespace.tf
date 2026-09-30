resource "kubernetes_namespace" "namespace" {
  metadata {
    annotations = {
      name = var.app_namepace
    }

    name = var.app_namepace
  }
}