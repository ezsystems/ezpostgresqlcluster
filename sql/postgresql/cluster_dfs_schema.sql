CREATE TABLE ezdfsfile (
    datatype    varchar(255)     DEFAULT 'application/octet-stream' NOT NULL,
    name        text            NOT NULL,
    name_trunk  text            NOT NULL,
    name_hash   character(32)   DEFAULT '' NOT NULL PRIMARY KEY,
    scope       varchar(20)     DEFAULT '' NOT NULL,
    size        integer         DEFAULT 0 NOT NULL,
    mtime       integer         DEFAULT 0 NOT NULL,
    expired     integer         DEFAULT 0 NOT NULL
);

CREATE INDEX ezdfsfile_name  ON ezdfsfile ( name );
CREATE INDEX ezdfsfile_mtime ON ezdfsfile ( mtime );

CREATE TABLE ezdfsfile_cache (
    datatype    varchar(255)     DEFAULT 'application/octet-stream' NOT NULL,
    name        text            NOT NULL,
    name_trunk  text            NOT NULL,
    name_hash   character(32)   DEFAULT '' NOT NULL PRIMARY KEY,
    scope       varchar(20)     DEFAULT '' NOT NULL,
    size        integer         DEFAULT 0 NOT NULL,
    mtime       integer         DEFAULT 0 NOT NULL,
    expired     integer         DEFAULT 0 NOT NULL
);

CREATE INDEX ezdfsfile_cache_name  ON ezdfsfile_cache ( name );
CREATE INDEX ezdfsfile_cache_mtime ON ezdfsfile_cache ( mtime );
