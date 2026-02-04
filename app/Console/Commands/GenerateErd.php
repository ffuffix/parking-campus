<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GenerateErd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erd:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an ERD (Entity Relationship Diagram) HTML file using Mermaid.js';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Analyzing database schema...");

        $tables = $this->getTables();
        $mermaid = "erDiagram\n";

        foreach ($tables as $table) {
            $tableName = $table['name'];
            $columns = $table['columns'];

            $mermaid .= "    $tableName {\n";
            foreach ($columns as $column) {
                // Laravel Schema methods return objects or arrays depending on version
                $typeName = is_array($column) ? $column['type_name'] : $column->type_name;
                $name = is_array($column) ? $column['name'] : $column->name;
                
                $mermaid .= "        $typeName $name\n";
            }
            $mermaid .= "    }\n";
        }

        $relationships = $this->getRelationships($tables);
        foreach ($relationships as $rel) {
            // Mermaid syntax: Table1 ||--o{ Table2 : label
            $mermaid .= "    {$rel['from']} ||--o{ {$rel['to']} : \"{$rel['column']}\"\n";
        }

        $html = $this->generateHtml($mermaid);
        $path = public_path('erd.html');
        file_put_contents($path, $html);

        // Generate DBML
        $dbml = $this->generateDbmlContent($tables, $relationships);
        $dbmlPath = base_path('database.dbml');
        file_put_contents($dbmlPath, $dbml);

        $this->info("ERD generated successfully!");
        $this->info("HTML View: " . asset('erd.html'));
        $this->info("DBML File: " . $dbmlPath);
    }

    private function generateDbmlContent($tables, $relationships)
    {
        $dbml = "";

        foreach ($tables as $table) {
            $tableName = $table['name'];
            $dbml .= "Table $tableName {\n";
            foreach ($table['columns'] as $column) {
                $typeName = is_array($column) ? $column['type_name'] : $column->type_name;
                $name = is_array($column) ? $column['name'] : $column->name;
                
                $settings = [];
                if ($name === 'id') {
                    $settings[] = 'primary key';
                }
                
                $settingsStr = !empty($settings) ? " [" . implode(', ', $settings) . "]" : "";
                
                $dbml .= "  $name $typeName$settingsStr\n";
            }
            $dbml .= "}\n\n";
        }

        foreach ($relationships as $rel) {
            // DBML syntax: Ref: from_table.column > to_table.id
            // Assuming 'to' table connects on 'id' for now, or implicit foreign key
            // The relationship captured is: from 'users' (table) to 'vehicles' (table) on 'user_id' (col) matches id?
            // Wait, my relationship logic in getRelationships was: 
            // $relationships[] = ['from' => $pluralTarget, 'to' => $tableName, 'column' => $colName];
            // e.g. vehicles.user_id -> users.id 
            // from=users, to=vehicles, column=user_id
            // Mermaid: users ||--o{ vehicles : "user_id"
            // DBML Ref: vehicles.user_id > users.id
            
            // In getRelationships:
            // if we found user_id in vehicles table
            // target=user, pluralTarget=users
            // relationships[] = ['from' => users, 'to' => vehicles, 'column' => user_id]
            
            // So DBML Ref should be: $rel['to'].$rel['column'] > $rel['from'].id
            
            $dbml .= "Ref: {$rel['to']}.{$rel['column']} > {$rel['from']}.id\n";
        }

        return $dbml;
    }

    private function getTables()
    {
        // Get all tables
        $allTables = Schema::getTables();
        $data = [];

        // Define tables to ignore
        $ignored = [
            'migrations', 
            'job_batches', 
            'cache', 
            'cache_locks', 
            'jobs', 
            'failed_jobs', 
            'sessions', 
            'password_reset_tokens'
        ];

        foreach ($allTables as $table) {
            $tableName = is_array($table) ? $table['name'] : $table->name;
            
            if (in_array($tableName, $ignored)) {
                continue;
            }

            $columns = Schema::getColumns($tableName);
            
            $data[] = [
                'name' => $tableName,
                'columns' => $columns,
            ];
        }

        return $data;
    }

    private function getRelationships($tables)
    {
        $relationships = [];
        $tableNames = array_column($tables, 'name');

        foreach ($tables as $table) {
            $tableName = $table['name'];

            // Method 1: Try schema foreign keys (works in recent Laravel/DBAL versions)
            try {
                $foreignKeys = Schema::getForeignKeys($tableName);
                foreach ($foreignKeys as $fk) {
                     // Normalize response (array or object)
                     $foreignTable = is_array($fk) ? $fk['foreign_table'] : $fk->foreign_table;
                     $localColumns = is_array($fk) ? $fk['columns'] : $fk->columns;
                     // Usually an array of columns, take the first one
                     $column = $localColumns[0];

                     $relationships[] = [
                         'from' => $foreignTable,
                         'to' => $tableName,
                         'column' => $column
                     ];
                }
            } catch (\Throwable $e) {
                // Ignore error, fallback to convention
            }

            // Method 2: Convention based (foo_id -> foos)
            // Even if method 1 worked, we might want to catch implicit relationships 
            // but for now let's only do this if Method 1 didn't find anything or for extra safety?
            // Actually, best to avoid duplicates.
            
            if (empty($foreignKeys)) {
                foreach ($table['columns'] as $column) {
                    $colName = is_array($column) ? $column['name'] : $column->name;
                    
                    if (str_ends_with($colName, '_id')) {
                        $target = substr($colName, 0, -3); // remove _id
                        $pluralTarget = Str::plural($target); // user -> users
                        
                        if (in_array($pluralTarget, $tableNames)) {
                            $relationships[] = [
                                'from' => $pluralTarget,
                                'to' => $tableName,
                                'column' => $colName
                            ];
                        }
                    }
                }
            }
        }
        
        // De-duplicate
        return array_map("unserialize", array_unique(array_map("serialize", $relationships)));
    }

    private function generateHtml($mermaid)
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database ERD</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .mermaid { text-align: center; }
    </style>
</head>
<body>
    <h1>Application Entity Relationship Diagram</h1>
    <div class="mermaid">
$mermaid
    </div>
    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
        mermaid.initialize({ 
            startOnLoad: true,
            theme: 'default',
            securityLevel: 'loose',
        });
    </script>
</body>
</html>
HTML;
    }
}
