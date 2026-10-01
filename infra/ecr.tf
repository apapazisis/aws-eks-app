resource "aws_ecr_repository" "repository" {
  name                 = "${var.environment}-app"
  image_tag_mutability = "IMMUTABLE"
}

