resource "kubernetes_ingress_v1" "app_ingress_tls" {
  metadata {
    name      = "${var.api_subdomain}-ingress"
    namespace = var.app_namepace
    annotations = {
      # ALB configuration
      "alb.ingress.kubernetes.io/scheme"      = "internet-facing"
      "alb.ingress.kubernetes.io/target-type" = "ip"

      # SSL/TLS configuration
      "alb.ingress.kubernetes.io/listen-ports"    = "[{\"HTTP\": 80}, {\"HTTPS\": 443}]"
      "alb.ingress.kubernetes.io/ssl-redirect"    = "443"
      "alb.ingress.kubernetes.io/certificate-arn" = aws_acm_certificate.cert_api.arn

      # Health check configuration
      "alb.ingress.kubernetes.io/healthcheck-path"     = "/"
      "alb.ingress.kubernetes.io/healthcheck-protocol" = "HTTP"

      "alb.ingress.kubernetes.io/ssl-policy" = "ELBSecurityPolicy-TLS13-1-2-Res-2021-06"

      # Load balancer attributes
      "alb.ingress.kubernetes.io/load-balancer-attributes" = "idle_timeout.timeout_seconds=60"

      # Tags for the ALB
      "alb.ingress.kubernetes.io/tags" = "Environment=${var.environment},ManagedBy=Terraform,Name=${var.api_subdomain}-ingress"

      # ALB group annotation
      "alb.ingress.kubernetes.io/group.name" = "api-ingress-group"
    }
  }

  depends_on = [
    kubernetes_namespace_v1.namespace,
    aws_acm_certificate_validation.cert_api
  ]

  spec {
    ingress_class_name = "alb"

    rule {
      host = "${var.api_subdomain}.${var.domain_name}"

      http {
        # Route for backend API
        path {
          path      = "/"
          path_type = "Prefix"
          backend {
            service {
              name = kubernetes_service_v1.backend.metadata[0].name
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
