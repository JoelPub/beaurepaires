# README #

This README would normally document whatever steps are necessary to get your application up and running.

###下载安装mysql(windows), xampp(mac:xampp-osx-5.6.31-0-installer.dmg), mysqlworkbench(mysql-workbench-community-6.3.9-osx-x86_64.dmg)

###改密码###
update mysql.user set password=password('wangliang') where user=‘root'; 
commit; 
flush privileges; 
###建库beaurepaires### 
###建用户apdgystage###
###导入mysql  database  beau_bft_uat_19092017.sql###
my.cnf: max_allowed_packet = 10240M innodb_log_file_size = 1024M 
###克隆beau_uat to E:\software\xampp\htdocs\beau_uat###
###改beau_uat权限为全部可写###
###Duplicate the file app/etc/local.xml.shared_dev to app/etc/ local.xml###
<resources>
            <db>
                <table_prefix><![CDATA[]]></table_prefix>
            </db>
            <default_setup>
                <connection>
                    <host><![CDATA[localhost:3306]]></host>
                    <username><![CDATA[root]]></username>
                    <password><![CDATA[wangliang]]></password>
                    <dbname><![CDATA[beaurepaires]]></dbname>
                    <initStatements><![CDATA[SET NAMES utf8]]></initStatements>
                    <model><![CDATA[mysql4]]></model>
                    <type><![CDATA[pdo_mysql]]></type>
                    <pdoType><![CDATA[]]></pdoType>
                    <active>1</active>
                </connection>
            </default_setup>
        </resources>
###Httpd.conf(Windows:  E:\software\xampp\apache\conf\httpd.conf; MAC: Applications/XAMPP/xamppfiles/etc/httpd.conf)###

 uncomment ：Include conf/extra/httpd-vhosts.conf

###update beaurepaires.core_config_data set value='http://beaurepaires.local/' where value like '%staging21%' ;###
select * from beaurepaires.core_config_data where value like '%beaurepaires.local%' ;
commit;
###httpd-vhosts.conf###  
<VirtualHost *:80>
DocumentRoot "E:\software\xampp\htdocs\beau_uat"
ServerName beaurepaires.local
<Directory "E:\software\xampp\htdocs\beau_uat">
        Require all granted
    </Directory>
</VirtualHost>
###C:\Windows\System32\drivers\etc\hosts
MAC: /private/etc/hosts###
                       127.0.0.1 beaurepaires.local 

