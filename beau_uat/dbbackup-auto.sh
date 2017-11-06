./mage-dbdump.sh -c ## Clear temp tables
./mage-dbdump.sh -d -z -A ## Backup and gzip database
mv var/db.sql.gz sql/
tar -zcvf sql/media.tgz media
git add sql
git commit -m 'Update media and DB to latest version'
git push origin master
