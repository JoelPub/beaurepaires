# Beurepaires

Welcome to the Beaurepairs repo!

## Quickstart

1.	Set up dev environment for Magento. (eg install a LAMP stack, Netbeans, Xdebug, Git, Phpunit)
2.	Install optional helper apps: (eg Composer, n98-magerun, Modman)
3.	Create an empty database: mage_beau
4.	Set up beau.local as an Apache virtual host
5.	Add SSH key to your bitbucket account as per https://confluence.atlassian.com/x/YwV9E
6.	Clone this repo to your virtual host directory:

```
#!php

git clone git@bitbucket.org:apdmageprojects/beaurepaires.git
git pull origin master
```


7.      Browse to sql directory, import the database and media dirs: 

Download Database SQL file from following URL
https://bitbucket.org/apdmageprojects/beaurepaires/downloads/db.sql.gz
```
#!php
gunzip db.sql.gz 
mysql -uUsername -pPassword mage_beau < db.sql
```
Download media files from following URL
https://bitbucket.org/apdmageprojects/beaurepaires/downloads/media.tgz

Move media.tgz in Magento root path and Run following command to extract it.
```
#!php
tar -zxvf media.tgz
```

## Linked Jira Project:

https://apdgroup.atlassian.net/browse/BFT


## To backup database and media dirs:
NB: 
Only run this on the Shared Dev / Staging or Live server (whichever is most current) to create a production-like environment for local development. 
Don't run on your local dev server. 

```
#!php
git checkout db_dump
cd [magento root]
./mage-dbdump.sh -d -z -A
mv var/db.sql.gz sql/
tar -zcvf sql/media.tgz media
git add sql
git commit -m 'Update media and DB to latest version'
git push origin db_dump
```

## SOLR, Redis, Varnish install

# SOLR:

Howto for Ubuntu: http://antrecu.com/blog/install-apache-solr-36-ubuntu-1404


# Redis:

To install on Ubuntu:

```
#!php
sudo apt-get install redis-server php5-redis
```

For other distros, use your google-fu. Or disable the module app/etc/modules/Cm_RedisSession.xml

-------------------------------

# Front-end dev

The project has been set up with Foundation + Compass/libsass + Bower.

## Get up and running

1) Requirements
  * Ruby 1.9+
  * [Node.js](http://nodejs.org)
  * [compass](http://compass-style.org/): `gem install compass`
  * [bower](http://bower.io): `npm install bower -g`

2) Next go to our Magento theme called "polar" -> skin/frontend/polar/default/

3) Bundler is used to ensure all the correct libraries are used. If you don't have it, run `gem install bundler`

4) Run `bundle` (only once per project)

5) Be sure to run compass watcher `bundle exec compass watch` in order to make any changes.

## Upgrading

If you'd like to upgrade to a newer version of your dependencies down the road such as Foundation just run:
`bower update`

PS1: All FE push the Bower dependencies to the Master repo so the BE don't need to run `bower install`. 
PS2: The static front-end files including CSS, HTML, JS are in here -> skin/frontend/polar/default/