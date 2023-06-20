FROM mysql:latest
# membuat image dengan versi mysql yang terbaru

COPY course.sql /docker-entrypoint-initdb.d/
# mengcopy database kepada dir tersebut. hal ini mengakibatkan database untuk dijalankan pada saat di run
ENV MYSQL_ALLOW_EMPTY_PASSWORD =1