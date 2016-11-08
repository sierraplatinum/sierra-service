FROM fvigotti/webserver-nginx-supervisor
RUN apt-get update && \
	apt-get install -y sqlite3 php5-cli php5-curl php5-fpm php5-gd php5-mcrypt php5-mysql php5-pgsql php5-sqlite ssmtp && \
	apt-get clean && \
	echo -n > /var/lib/apt/extended_states
RUN rm -rf /etc/nginx/addon.d /etc/php5/fpm/pool.d && \
	mkdir -p /etc/nginx/addon.d /etc/php5/fpm/pool.d
RUN \
  echo oracle-java8-installer shared/accepted-oracle-license-v1-1 select true | debconf-set-selections && \
  add-apt-repository -y ppa:webupd8team/java && \
  apt-get update && \
  apt-get install -y oracle-java8-installer && \
  rm -rf /var/lib/apt/lists/* && \
  rm -rf /var/cache/oracle-jdk8-installer
ENV JAVA_HOME /usr/lib/jvm/java-8-oracle
ADD etc /etc
ADD conf/init88.sh /config/init88.sh
ADD conf/clean.sh /config/clean.sh
ADD service /data/http/
RUN chown -R core:core /data/http
ADD local /local
RUN keytool -genkey -alias ftptest -keyalg RSA -keystore /local/sierra-service/ftpserver.jks -keysize 4096 -storepass password -dname "CN=sierra, OU=sierra, O=sierra, L=sierra, S=sierra, C=sierra" -keypass password
RUN chown -R core:core /local
EXPOSE 80
EXPOSE 443
EXPOSE ftpPorts
EXPOSE tcpPorts


