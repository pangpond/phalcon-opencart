#installation Phalcon Framework
copy database config file and set DSN

```
cp ./app/config/config.ini.dist ./app/config/config.ini
composer install
sudo chmod 777 public/uploads/members
sudo chmod 777 public/uploads/users
sudo chmod 777 public/tools/backup
sudo chmod 777 public/uploads/academy/
touch logs/access.log
sudo chmod 777 logs/access.log
```


create .phalcon at root of phalcon
```
trace .phalcon
```


```
phalcon model --get-set  --force --name TableName
```


```
phalcon scaffold --table-name TableName
```


##setup application

```
chmod 777 public/uploads/members/
chmod 777 public/uploads/academies/
chmod 777 logs/access.log
```


## Configuration Dom PDF
Enable font lib in dompdf by un-comment these three lines in **vendor/dompdf/dompdf/dompdf_config.custom.inc.php** file

```
define("DOMPDF_FONT_DIR", DOMPDF_DIR."/lib/fonts/");
define("DOMPDF_FONT_CACHE", DOMPDF_DIR."/lib/fonts/");
define("DOMPDF_UNICODE_ENABLED", true);
```

to enable php page function, open **vendor/dompdf/dompdf/dompdf_config.inc.php** and change define to ```true```  

```
def("DOMPDF_ENABLE_PHP", true);
```

###load font  
copy **php-font-lib** from **vendor/phenx/php-font-lib** to **vendor/dompdf/dompdf/lib/php-font-lib**

```
$ cp -R vendor/phenx/php-font-lib/* vendor/dompdf/dompdf/lib/php-font-lib
```

load_font.php usage:  
change directory to **vendor/dompdf/dompdf** first

```
$ cd vendor/dompdf/dompdf
```

```
$ ./load_font.php font-family n_file [b_file] [i_file] [bi_file]
```

**font_family** - the name of the font, e.g. Verdana, 'Times New Roman', monospace, sans-serif.  
**n_file** - the .pfb or .ttf file for the normal, non-bold, non-italic face of the font.  
**{b|i|bi}file** - the files for each of the respective (bold, italic, bold-italic) faces.    


Examples:

```
./load_font.php 'THSarabun' ../../../fonts/THSarabun.ttf ../../../fonts/THSarabunBold.ttf ../../../fonts/THSarabunItalic.ttf ../../../fonts/THSarabunBoldItalic.ttf
```


http://fonts.snm-portal.com/

#todo
```
content CRUD log file
people form
people code last id
change mvc to multiple
แยก people login ออกจาก user login
zipcode table
create table Areas_Province
โรงเรียนเขต 6 ชื่อขาด
null zipcode academies table
ขอรูปทำบัตร
register flow


add area/academy to index view
store area/province to session auth
filter index view by role and session auth
complete basic and advanced search

```


ALTER TABLE `academy`
  DROP `area`;

ALTER TABLE `province_director` CHANGE `prople_id` `people_id` INT(11) UNSIGNED NOT NULL;
ALTER TABLE `people_academy` CHANGE `prople_id` `people_id` INT(11) UNSIGNED NOT NULL;
ALTER TABLE `people` ADD `geo_id` TINYINT NOT NULL AFTER `address2`;

ALTER TABLE `area` ADD `last_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ;

TRUNCATE `people`;
TRUNCATE `people_academy`;
TRUNCATE `people_meta`;

ALTER TABLE  `users` CHANGE  `id`  `user_id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ;
ALTER TABLE `members` CHANGE `code` `code` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE  `members` DROP INDEX  `code` ,
ADD UNIQUE  `citizenid` (  `citizenid` ) COMMENT  '';