<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * DFS/PostgreSQL cluster gateway
 */
class ezpDfsPostgresqlClusterGateway extends ezpClusterGateway
{
    protected $port = 5433;

    public function connect()
    {
        try
        {
            $this->db = new PDO( "pgsql:host=$this->host;dbname=$this->name;port=$this->port", $this->user, $this->password );
            if ( $this->db->exec( "SET NAMES '$this->charset'" ) === false )
            {
                throw new RuntimeException( "Failed to set database charset to '$this->charset' " );
            }
        }
        catch ( PDOException $e )
        {
            throw new RuntimeException( $e->getMessage );
        }
    }
    
    /**
     * Returns the database table name to use for the specified file.
     *
     * For files detected as cache files the cache table is returned, if not
     * the generic table is returned.
     *
     *
     * @param string $filePath
     * @return string The database table name
     */
     protected function dbTable( $filePath )
     {
         $cacheDir = defined( 'CLUSTER_METADATA_CACHE_PATH' ) ? CLUSTER_METADATA_CACHE_PATH : "/cache/";
         $storageDir = defined( 'CLUSTER_METADATA_STORAGE_PATH' ) ? CLUSTER_METADATA_STORAGE_PATH : "/storage/";

         if ( strpos( $filePath, $cacheDir ) !== false && strpos( $filePath, $storageDir ) === false )
         {
             return defined( 'CLUSTER_METADATA_TABLE_CACHE' ) ? CLUSTER_METADATA_TABLE_CACHE : 'ezdfsfile_cache';
         }

         return 'ezdfsfile';
     }

    public function fetchFileMetadata( $filepath )
    {
        $filePathHash = md5( $filepath );
        $sql = "SELECT * FROM {$this->dbTable( $filepath )} WHERE name_hash='$filePathHash'" ;
        if ( !$stmt = $this->db->query( $sql ) )
            throw new RuntimeException( "Failed to fetch file metadata for '$filepath'" );

        if ( $stmt->rowCount() == 0 )
        {
            return false;
        }

        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    public function passthrough( $filepath, $filesize, $offset = false, $length = false )
    {
        $dfsFilePath = CLUSTER_MOUNT_POINT_PATH . '/' . $filepath;

        if ( !file_exists( $dfsFilePath ) )
            throw new RuntimeException( "Unable to open DFS file '$dfsFilePath'" );

        $fp = fopen( $dfsFilePath, 'rb' );
        if ( $offset !== false && @fseek( $fp, $offset ) === -1 )
            throw new RuntimeException( "Failed to seek offset $offset on file '$filepath'" );
        if ( $offset === false && $length === false )
            fpassthru( $fp );
        else
            echo fread( $fp, $length );

        fclose( $fp );
    }

    public function close()
    {
        unset( $this->db );
    }
}

ezpClusterGateway::setGatewayClass( 'ezpDfsPostgresqlClusterGateway' );