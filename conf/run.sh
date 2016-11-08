#!/bin/bash
echo -n "Enter storage directory [ENTER]: "
read storage
docker run -h $HOSTNAME -i -t -d -p 80:80/tcp -p 443:443/tcp -p sFport-eFport:sFport-eFport/tcp -p sPort-ePort:sPort-ePort/tcp  -v $storage:/local/sierra-service/data sierra-service
