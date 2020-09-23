######### goods consumer #########
job "dev-nimble-goods" {
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
        "service" = "nimble-goods"
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
                args = ["gs", "catalog"]
                auth {
                    username = "${DEPLOY_USER}"
                    password = "${DEPLOY_PASSWORD}"
                }
                volumes = [
                    "/opt/nomad/storage-goods:/nimble/storage"
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
                "AMQP_GS_HOST" = "10.10.29.204"
                "AMQP_GS_PORT" = "5672"
                "AMQP_GS_USERNAME" = "catalog"
                "GQL_GOODS_SERVICE_URL" = "http://mdm-goods-demo.test.kube/graphql"
                "GQL_GOODS_SERVICE_LOGIN" = "basic"
                "ELASTIC_HOSTS" = "10.10.29.62:9200"
                "ELASTIC_AUTH_USER" = ""
                "ELASTIC_AUTH_PASS" = ""
                "CONSUMER_MAX_ERRORS_COUNT" = "1"
            }
            template {
                data = <<EOH
AMQP_GS_PASSWORD="{{with secret "IVV/data/dev/goodsrmq"}}{{.Data.data.AMQP_GS_PASSWORD}}{{end}}"
GQL_GOODS_SERVICE_PASSWORD="{{with secret "IVV/data/dev/nimble"}}{{.Data.data.GQL_GOODS_SERVICE_PASSWORD}}{{end}}"
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
