up:
	docker-compose up
bash:
	docker exec -it app bash
bash-root:
	docker exec -it -u root app bash