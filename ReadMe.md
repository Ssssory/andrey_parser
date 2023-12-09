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