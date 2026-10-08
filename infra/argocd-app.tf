resource "kubernetes_manifest" "app" {
  manifest = {
    apiVersion = "argoproj.io/v1alpha1"
    kind       = "Application"
    metadata = {
      name      = "devopsdozo"
      namespace = var.argocd_namespace
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
          enabled    = true
          prune      = true
          selfHeal   = true
          allowEmpty = false
        }
        syncOptions = [
          "ApplyOutOfSyncOnly=true",
          "Validate=false"
        ]
        retry = {
          limit = 5
          backoff = {
            duration    = "5s"
            factor      = 2
            maxDuration = "3m"
          }
        }
      }
    }
  }
}

