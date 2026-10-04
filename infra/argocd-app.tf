resource "kubernetes_manifest" "app" {
  manifest = {
    apiVersion = "argoproj.io/v1alpha1"
    kind       = "Application"
    metadata = {
      name      = "devopsdozo"
      namespace = "argocd"
    }
    spec = {
      project = "default"
      source = {
        repoURL        = "https://github.com/apapazisis/aws-eks-app.git"
        targetRevision = "main"
        path           = "k8s/manifests"
      }
      destination = {
        server    = "https://kubernetes.default.svc"
        namespace = var.api_namespace
      }

      syncPolicy = {
        automated = {
          prune    = true
          selfHeal = true
        }
      }
    }
  }
}

