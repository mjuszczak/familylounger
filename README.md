familylounger
=============

## Deploy

* Pull down familylounger into `familylounger.com.new`

* Make a backup of the existing site

```shell
rm -rf familylounger.com.last
cp -R familylounger.com familylounger.com.last
```

* Copy the existing "config" into place

```shell
cp familylounger.com.last/app/Config/simple-config.php familylounger.com.new/app/Config/simple-config.php
```

* Create a symlink to the data directory

```shell
rm -rf familylounger.com.new/app/webroot/private
ln -s ~/familylounger.data familylounger.com.new/app/webroot/private
```

* Disable the old site

```shell
rm -rf familylounger.com
```

* Apply any schema updates if needed

* Copy the new site into place.

```shell
mv familylounger.com.new familylounger.com
```

