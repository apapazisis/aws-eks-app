output "environment" {
  description = "Environment this state belongs to."
  value       = var.environment
}

# output "database_endpoint" {
#   description = "PostgreSQL endpoint (host:port)."
#   value       = aws_db_instance.database.endpoint
# }

# output "database_master_secret_arn" {
#   description = "ARN of the Secrets Manager secret holding the master credentials."
#   value       = aws_db_instance.database.master_user_secret[0].secret_arn
# }
