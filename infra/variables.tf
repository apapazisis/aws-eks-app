variable "project" {
  description = "Project name used in tags."
  type        = string
}

variable "environment" {
  description = "Environment name."
  type        = string
}

variable "region" {
  description = "AWS region."
  type        = string
}

variable "eks_cluster_name" {
  description = "Name of the EKS cluster whose nodes connect to the database."
  type        = string
}

variable "app_subdomain" {
  description = "Subdomain for the application."
  type        = string
}

variable "app_namepace" {
  description = "Kubernetes namespace for the application."
  type        = string
}