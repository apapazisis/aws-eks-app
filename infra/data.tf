data "aws_caller_identity" "current" {}

data "aws_region" "current" {}

data "aws_vpc" "eks" {
  tags = {
    Name = "k8s-vpc"
  }
}

data "aws_subnets" "private" {
  filter {
    name   = "vpc-id"
    values = [data.aws_vpc.eks.id]
  }

  tags = {
    Name = "k8s-private-*"
  }
}

data "aws_eks_cluster" "main" {
  name = var.eks_cluster_name
}