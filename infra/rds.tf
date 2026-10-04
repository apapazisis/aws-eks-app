# resource "aws_db_subnet_group" "database" {
#   name       = "${var.environment}-postgres"
#   subnet_ids = data.aws_subnets.private.ids
# }

# resource "aws_security_group" "database" {
#   name        = "${var.environment}-postgres"
#   description = "PostgreSQL access from EKS nodes"
#   vpc_id      = data.aws_vpc.eks.id
# }

# # Managed node groups get the EKS cluster security group, so pods reach RDS through it.
# resource "aws_vpc_security_group_ingress_rule" "database_from_eks" {
#   security_group_id            = aws_security_group.database.id
#   referenced_security_group_id = data.aws_eks_cluster.main.vpc_config[0].cluster_security_group_id
#   from_port                    = 5432
#   to_port                      = 5432
#   ip_protocol                  = "tcp"
# }

# resource "aws_db_instance" "database" {
#   identifier                  = "${var.environment}-postgres"
#   engine                      = "postgres"
#   engine_version              = "18.6"
#   instance_class              = "db.t4g.micro"
#   allocated_storage           = 20
#   max_allocated_storage       = 50
#   storage_type                = "gp3"
#   storage_encrypted           = true
#   db_name                     = "apapazisis"
#   username                    = "master"
#   manage_master_user_password = true
#   multi_az                    = false
#   db_subnet_group_name        = aws_db_subnet_group.database.name
#   vpc_security_group_ids      = [aws_security_group.database.id]
#   publicly_accessible         = false
#   auto_minor_version_upgrade  = true
#   backup_retention_period     = 0
#   copy_tags_to_snapshot       = false
#   deletion_protection         = false
#   skip_final_snapshot         = true
# }
