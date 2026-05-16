<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Database;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common SQL database engines (driver names, not server versions). Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum DatabaseEngine: string
{
    use EnumExtension;

    case Mysql = 'mysql';

    case Mariadb = 'mariadb';

    case Pgsql = 'pgsql';

    case Sqlite = 'sqlite';

    case Sqlserver = 'sqlserver';
}
