# detpars

#### cron

start cron

```
make start-cron
```
vim /etc/cron.d/www-crontab
vim /var/log/cron.log

#### ssl

new ssl
```
docker-compose run --rm  certbot certonly --webroot --webroot-path /var/www/certbot/ -d
```

update ssl
```
docker-compose run --rm certbot renew
docker restart detpars_nginx
```

### sql dump

export 


```
docker exec -i detpars_db mysqldump -u laravel -p'123456' laravel > "dump_$(date +'%Y-%m-%d').sql"

scp username@remote_server_ip:/srv/parser/dump.sql ./dump.sql
```

import

```
docker exec -i detpars_db mysql -u laravel -p'123456' laravel < "dump_2024-05-04.sql"
```
