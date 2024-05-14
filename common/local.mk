env:
	docker compose -f common/docker-compose.yml $(shell if [ -f ./docker-compose.yml ]; then echo -f docker-compose.yml; fi) up