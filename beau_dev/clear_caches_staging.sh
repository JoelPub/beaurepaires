# Clear magento cache (FS)
rm -rf var/cache/*
rm -rf var/full_page_cache/*
rm -rf /tmp/zend*

# Clear memcached
echo 'flush_all' | nc localhost 11211

# Clear varnish
# varnishadm -S /etc/varnish/secret -T 127.0.0.1:6082 url.purge .

# Clear APC
# curl http://127.0.0.1/clear_apc.php
# Php 5.5+ has opcode cache built in.

# Restart php5-fpm
service php5-fpm restart

# Restart nginx
service nginx restart
