######### marketing consumer #########
job "nimble" {
    datacenters = ["RZK-DC"]
    type = "service"
    constraint {
        attribute = "${meta.env}"
        value = "prod"
    }
    constraint {
        attribute = "${meta.role}"
        value = "app"
    }
    constraint {
        attribute = "${meta.service}"
        value = "ivv"
    }
    meta {
        "service" = "nimble"
        "environment" = "prod"
    }

    group "nimble" {

        count = 1

        task "consumer" {
            vault {
                policies = ["ivv-catalog"]
            }
            driver = "docker"
            config {
                image = "${CI_REGISTRY_IMAGE}:${CI_COMMIT_SHA}"
                force_pull = true
                network_mode = "host"
                args = ["ms", "promotion_goods_marketing_to_ivv_queue"]
                auth {
                    username = "${DEPLOY_USER}"
                    password = "${DEPLOY_PASSWORD}"
                }
                volumes = [
                    "/opt/nomad/storage:/nimble/storage"
                ]
            }
            env {
                "APP_NAME"= "Lumen"
                "APP_ENV" = "local"
                "APP_DEBUG" = "true"
                "CACHE_DRIVER" = "file"
                "QUEUE_CONNECTION" = "sync"
                "REDIS_HOST" = "10.10.12.169"
                "REDIS_PORT" = "6379"
                "REDIS_DB" = "0"
                "AMQP_MS_HOST" = "rmq.rozetka.company"
                "AMQP_MS_PORT" = "5672"
                "AMQP_MS_USERNAME" = "ivv_subscriber"
                "AMQP_MS_EXCHANGE" = "marketing_service"
                "GQL_GOODS_SERVICE_URL" = "http://mdm-goods-prod.prod.kube/graphql"
                "GQL_GOODS_SERVICE_LOGIN" = "catalog"
                "ELASTIC_HOSTS" = "10.10.12.152:9200,10.10.12.148:9200,10.10.12.149:9200"
                "ELASTIC_AUTH_USER" = ""
                "ELASTIC_AUTH_PASS" = ""
                "CONSUMER_MAX_ERRORS_COUNT" = "100"
            }
            template {
                data = <<EOH
AMQP_MS_PASSWORD="{{with secret "IVV/data/prod/nimble"}}{{.Data.data.AMQP_MS_PASSWORD}}{{end}}"
GQL_GOODS_SERVICE_PASSWORD="{{with secret "IVV/data/prod/nimble"}}{{.Data.data.GQL_GOODS_SERVICE_PASSWORD}}{{end}}"
EOH
                destination = "/opt/nomad/file.env"
                change_mode = "noop"
                env = true
            }
            resources {
                memory = 1024
                cpu = 2000
            }
        }
    }
}
