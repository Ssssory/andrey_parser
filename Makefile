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
bash-parser:
	docker compose exec -it parser sh
start-cron:
	docker exec -u root ${PROJECT_NAME}_app service cron start
stop-cron:
	docker exec -u root ${PROJECT_NAME}_app service cron stop
restart-cron:
	docker exec -u root ${PROJECT_NAME}_app /etc/init.d/cron reload
log-cron:
	docker exec -u root ${PROJECT_NAME}_app tail -f /var/log/cron.log
start-all:
	docker exec ${PROJECT_NAME}_app /usr/bin/crontab /var/www/crontab
	docker exec -u root ${PROJECT_NAME}_app service supervisor start
backup-db:
	docker exec -u root ${PROJECT_NAME}_db /usr/bin/mysqldump -u laravel --password=123456 laravel > /var/lib/mysql/backup.sql
	