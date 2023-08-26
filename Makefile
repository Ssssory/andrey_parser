up:
	docker-compose up -d
local-up:
	docker-compose up
stop:
	docker-compose stop
bash:
	docker exec -it app bash
bash-root:
	docker exec -it -u root app bash
bash-js:
	docker exec -it -u node parser_consumer_1 bash