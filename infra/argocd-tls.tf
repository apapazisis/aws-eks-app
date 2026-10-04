resource "aws_acm_certificate" "argocd_tls" {
  domain_name       = "argocd.t.apapazisis.de"
  validation_method = "DNS"
  key_algorithm     = "EC_secp384r1"

  lifecycle {
    create_before_destroy = true
  }
}

resource "aws_route53_record" "argocd_tls" {
  for_each = {
    for dvo in aws_acm_certificate.argocd_tls.domain_validation_options : dvo.domain_name => {
      name   = dvo.resource_record_name
      record = dvo.resource_record_value
      type   = dvo.resource_record_type
    }
  }

  allow_overwrite = true
  name            = each.value.name
  records         = [each.value.record]
  ttl             = 60
  type            = each.value.type
  zone_id         = data.aws_route53_zone.main.zone_id
}

resource "aws_acm_certificate_validation" "argocd_tls" {
  certificate_arn         = aws_acm_certificate.argocd_tls.arn
  validation_record_fqdns = [for record in aws_route53_record.argocd_tls : record.fqdn]
}