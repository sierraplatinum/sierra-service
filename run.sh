#!/bin/bash
echo -n "Enter storage directory [ENTER]: "
read storage
docker run -h $HOSTNAME -i -t -d -p 80:80/tcp -p 443:443/tcp -p 20000-20010:20000-20010/tcp -p 50000-50010:50000-50010/tcp  -v $storage:/local/sierra-service/data sierra-service
