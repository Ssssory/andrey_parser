# detpars

#### cron

start cron

```
make start-cron
```

#### ssl

new ssl
```
docker-compose run --rm  certbot certonly --webroot --webroot-path /var/www/certbot/ -d
```

update ssl
```
docker-compose run --rm certbot renew
```