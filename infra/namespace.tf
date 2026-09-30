resource "kubernetes_namespace_v1" "namespace" {
  metadata {
    annotations = {
      name = var.app_namepace
    }

    name = var.app_namepace
  }
}