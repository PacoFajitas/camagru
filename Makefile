COMPOSE            := docker compose
COMPOSE_FILE       := docker-compose.yml
SERVICES           := nginx server
VOLUMES            := $(shell docker volume ls -q | grep camagru)

all: up

generate-cert:
	@mkdir -p ./gateway/certbot/conf
	@openssl req -x509 -nodes -days 365 \
	-newkey rsa:2048 \
	-keyout ./gateway/certbot/conf/privkey.pem \
	-out ./gateway/certbot/conf/fullchain.pem \
	-subj "/C=ES/ST=Spain/L=Barcelona/O=42/CN=localhost"
	@echo "✅ Certificado autofirmado generado en ./gateway/certbot/conf"


configure-rootless:
	@echo "🔧 Configurando entorno Docker rootless en sgoinfre..."
	@rm -rf ~/sgoinfre/docker ~/sgoinfre/tmp_docker
	@mkdir -p ~/sgoinfre/docker ~/sgoinfre/tmp_docker
	@rm -f $$HOME/.docker_rootless_env
	@echo 'export DOCKER_HOME="~/sgoinfre/docker"' > $$HOME/.docker_rootless_env
	@echo 'export DOCKER_TMPDIR="~/sgoinfre/tmp_docker"' >> $$HOME/.docker_rootless_env
	@echo 'export XDG_RUNTIME_DIR="$$XDG_RUNTIME_DIR"' >> $$HOME/.docker_rootless_env
	@echo 'export DOCKER_HOST="unix://$$XDG_RUNTIME_DIR/docker.sock"' >> $$HOME/.docker_rootless_env
	@echo 'export PATH="$$HOME/.local/bin:$$PATH"' >> $$HOME/.docker_rootless_env
	@echo "✅ Entorno regenerado en ~/.docker_rootless_env"
	@echo "⚙️  Aplicando entorno temporalmente (solo para este Make)..."
	@. $$HOME/.docker_rootless_env

up: configure-rootless generate-cert
	@echo "🚀 Levantando todos los servicios..."
	@mkdir -p ./server/data
	@chmod -R 777 ./server/data
	@docker network create camagru_net
	@$(COMPOSE) up -d

build:
	@echo "🔧 Build y up (todos los servicios)..."
	@$(COMPOSE) up -d --build

stop:
	@echo "🔛 Deteniendo servicios..."
	@$(COMPOSE) stop

down: 
	@echo "🧹 Bajando contenedores..."
	@$(COMPOSE) down

clean:
	@echo "🔥 Borrando volúmenes de Docker asociados al proyecto..."
	@docker volume rm -f $(VOLUMES) 2>/dev/null || true

fclean: down clean
	@echo "🔥 Borrando todas las imágenes de Docker (esto es destructivo)..."
	@rm -rf ./server/data
	@docker network rm camagru_net
	@docker rmi -f $$(docker images -q) 2>/dev/null || true

re: fclean up

.PHONY: all configure-rootless up build stop down clean fclean re 