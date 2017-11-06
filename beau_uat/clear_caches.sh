# Clear memcached
echo 'flush_all' | nc localhost 11211

# Clear varnish
varnishadm -S /etc/varnish/secret -T 127.0.0.1:6082 url.purge .

# Clear APC
curl http://bft-tyres.corp.nextdigital.com/clear_apc.php

# Restart nginx
service nginx restart
