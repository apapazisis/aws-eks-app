resource "kubernetes_namespace_v1" "namespace" {
  metadata {
    annotations = {
      name = var.api_namespace
    }

    name = var.api_namespace
  }
}