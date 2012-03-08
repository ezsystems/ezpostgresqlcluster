# Installing PostgreSQL Cluster #

Reminder: this extension only provide support for DFS, the Network File System based cluster handler.

Most of the setup steps can be found in the online documentation, in the Technical Documentation / Clustering chapter.

## Uncompress the archive ##

Uncompress the extension inside the extension/ folder of your eZ Publish installation.

## Enable the extension ##

Edit your `site.ini override, usually the global one, `settings/override/site.ini.append.php`.

Enable the extension:

```ini
[ExtensionSettings]
ActiveExtensions[]=ezpostgresqlcluster
```

## Regenerate the autoload ##

From your eZ Publish doc root, execute:

```
php bin/php/ezpgenerateautoloads.php -e
```

## Enable and configure postgresql for DFS in file.ini ##

Edit your `file.ini` override, usually  the global one, `settings/override/file.ini.append.php`. Either edit or add the following in this file:

```ini
[ClusteringSettings]
FileHandler=eZDFSFileHandler

[eZDFSClusteringSettings]
DBBackend=eZDFSFileHandlerPostgresqlBackend
MountPointPath=/media/nfs
DBHost=localhost
DBName=ezpgit_master
DBPort=5433
DBUser=wwwdata
DBPassword=publish
```

The first entry, `ClusteringSettings.FileHandler` sets eZDFS as the cluster handler mode. `eZDFSClusteringSettings.DBBackend` sets postgreSQL as the database used by eZDFS. More details about these settings can be found in the online documentation.

## Set the cluster gateway ##

Edit / create the `config.cluster.php` file at the eZ Publish root. Add these lines to it:

```php
define( 'CLUSTER_STORAGE_GATEWAY_PATH', 'ezpostgresqlcluster/clustering/dfs/gateway.php' );

define( 'CLUSTER_STORAGE_BACKEND',  'dfspostgresql'  );
define( 'CLUSTER_STORAGE_HOST',     'localhost' );
define( 'CLUSTER_STORAGE_PORT',     5433 );
define( 'CLUSTER_STORAGE_USER',     'dbuser' );
define( 'CLUSTER_STORAGE_PASS',     'dbpassword' );
define( 'CLUSTER_STORAGE_DB',       'ezpcluster' );
define( 'CLUSTER_STORAGE_CHARSET',  'utf8' );
define( 'CLUSTER_MOUNT_POINT_PATH', '/media/nfs' );
```

The first liine sets the `CLUSTER_STORAGE_GATEWAY_PATH` constants, that provides the path to a custom gateway implementation file.

Extra documentation about these settings can be found in the online documentation.

## Finish up configuration #"

Follow the last steps of the online documentation to set the rewrite rules and clusterize your existing files.