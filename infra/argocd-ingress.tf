resource "kubernetes_ingress_v1" "argocd_ingress_tls" {
  metadata {
    name      = "${var.argocd_subdomain}-ingress"
    namespace = "argocd"
    annotations = {
      # ALB configuration
      "alb.ingress.kubernetes.io/scheme"      = "internet-facing"
      "alb.ingress.kubernetes.io/target-type" = "ip"

      # SSL/TLS configuration
      "alb.ingress.kubernetes.io/listen-ports"    = "[{\"HTTP\": 80}, {\"HTTPS\": 443}]"
      "alb.ingress.kubernetes.io/ssl-redirect"    = "443"
      "alb.ingress.kubernetes.io/certificate-arn" = aws_acm_certificate.argocd_tls.arn

      # Health check configuration
      "alb.ingress.kubernetes.io/healthcheck-path"     = "/"
      "alb.ingress.kubernetes.io/healthcheck-protocol" = "HTTP"

      # Load balancer attributes
      "alb.ingress.kubernetes.io/load-balancer-attributes" = "idle_timeout.timeout_seconds=60"

      # Tags for the ALB
      "alb.ingress.kubernetes.io/tags" = "Environment=production,ManagedBy=Terraform,Name=${var.argocd_subdomain}-ingress"

      # ALB group annotation
      "alb.ingress.kubernetes.io/group.name" = "argocd-ingress-group"
    }
  }

  depends_on = [
    aws_acm_certificate_validation.argocd_tls,
  ]

  spec {
    ingress_class_name = "alb"


    tls {
      hosts = [
        "${var.argocd_subdomain}.${var.domain_name}"
      ]
    }

    rule {
      host = "${var.api_subdomain}.${var.domain_name}"

      http {
        path {
          path      = "/"
          path_type = "Prefix"
          backend {
            service {
              name = "argocd-server"
              port {
                number = 80
              }
            }
          }
        }
      }
    }
  }
}
