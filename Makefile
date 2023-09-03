#!make
include .env

up:
	docker-compose up -d
local-up:
	docker-compose up
stop:
	docker-compose stop
bash:
	docker exec -it ${PROJECT_NAME}_app bash
bash-root:
	docker exec -it -u root ${PROJECT_NAME}_app bash
bash-js:
	docker exec -it -u node ${PROJECT_NAME}_consumer bash
start-cron:
	docker exec -u root ${PROJECT_NAME}_app service cron start
stop-cron:
	docker exec -u root ${PROJECT_NAME}_app service cron stop
restart-cron:
	docker exec -u root ${PROJECT_NAME}_app /etc/init.d/cron reload