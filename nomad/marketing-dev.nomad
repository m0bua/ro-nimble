######### marketing consumer #########
job "dev-nimble" {
    datacenters = ["RZK-DC"]
    type = "service"
    constraint {
        attribute = "${meta.env}"
        value = "dev"
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
        "environment" = "dev"
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
                args = ["ms", "promotion.push.goods"]
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
                "REDIS_HOST" = "10.10.29.61"
                "REDIS_PORT" = "6379"
                "REDIS_DB" = "0"
                "AMQP_MS_HOST" = "10.10.28.72"
                "AMQP_MS_PORT" = "5672"
                "AMQP_MS_USERNAME" = "admin"
                "AMQP_MS_EXCHANGE" = "marketing.promotion_push"
                "GQL_GOODS_SERVICE_URL" = "http://mdm-goods-demo.test.kube/graphql"
                "GQL_GOODS_SERVICE_LOGIN" = "basic"
                "ELASTIC_HOSTS" = "10.10.29.62:9200"
                "ELASTIC_AUTH_USER" = ""
                "ELASTIC_AUTH_PASS" = ""
                "CONSUMER_MAX_ERRORS_COUNT" = "1"
            }
            template {
                data = <<EOH
AMQP_MS_PASSWORD="{{with secret "IVV/data/dev/mardrmq"}}{{.Data.data.admin}}{{end}}"
GQL_GOODS_SERVICE_PASSWORD="{{with secret "IVV/data/dev/nimble"}}{{.Data.data.GQL_GOODS_SERVICE_PASSWORD}}{{end}}"
REDIS_PASSWORD="{{with secret "IVV/data/dev/nimble"}}{{.Data.data.REDIS_PASSWORD}}{{end}}"
EOH
                destination = "/opt/nomad/file.env"
                change_mode = "noop"
                env = true
            }
            resources {
                memory = 512
                cpu = 500
            }
        }
    }
}
