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

variable "api_subdomain" {
  description = "Subdomain for the application."
  type        = string
}

variable "api_namespace" {
  description = "Kubernetes namespace for the application."
  type        = string
}

variable "domain_name" {
  description = "Domain name for the application."
  type        = string
}

variable "argocd_subdomain" {
  description = "Subdomain for the ArgoCD application."
  type        = string
}

variable "argocd_namespace" {
  description = "Kubernetes namespace for the ArgoCD application."
  type        = string
}