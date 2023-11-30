<?php

namespace App\Models;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class UpgradeMigration extends Migration
{
    protected function getTableName(string $tableName): string
    {
        if (Schema::hasTable($tableName)) {
            return $tableName;
        }

        throw new RuntimeException('Could not find appropriate table for table name '.$tableName);
    }
}
