<?php

namespace Efaturacim\Util\Backup;

use Efaturacim\Util\Utils\Console\Console;
use Exception;

class MyBackup
{
    private $config;
    private $configFile;

    public function __construct(string $configFile)
    {
        $this->configFile = $configFile;
        $this->loadConfig();
    }

    public static function backupWithConfig(string $configFile): void
    {
        $backup = new self($configFile);
        $backup->backup();
    }

    private function loadConfig(): void
    {
        if (!file_exists($this->configFile)) {
            throw new Exception("Config file not found: " . $this->configFile);
        }

        $content = file_get_contents($this->configFile);
        $this->config = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in config file: " . json_last_error_msg());
        }

        if (!isset($this->config['jobs']) || !is_array($this->config['jobs'])) {
            throw new Exception("Invalid config structure: 'jobs' array is missing.");
        }
    }

    public function backup(): void
    {
        $name = $this->config['name'] ?? 'Unknown';
        Console::title("Starting Backup for: " . $name, Console::COLOR_CYAN);

        foreach ($this->config['jobs'] as $index => $job) {
            $type = $job['type'] ?? 'unknown';
            Console::info("Processing job " . ($index + 1) . " of type: " . $type);

            try {
                switch ($type) {
                    case 'mysqldump':
                        $this->runMysqlDump($job);
                        break;
                    case 'rsync':
                        $this->runRsync($job);
                        break;
                    default:
                        Console::warning("Unknown job type: " . $type);
                        break;
                }
            } catch (Exception $e) {
                Console::error("Job failed: " . $e->getMessage());
            }
        }

        Console::success("Backup process completed.");
    }

    private function runMysqlDump(array $job): void
    {
        $required = ['host', 'user', 'database', 'path'];
        $this->validateJob($job, $required);

        $host = $job['host'];
        $port = $job['port'] ?? 3306;
        $user = $job['user'];
        $password = $job['password'] ?? '';
        $database = $job['database'];
        $path = rtrim($job['path'], '/');
        $format = $job['format'] ?? '{database}_{date}.sql.gz';
        $ignoreTables = $job['ignore_tables'] ?? [];

        if (!is_dir($path)) {
            if (!mkdir($path, 0755, true)) {
                throw new Exception("Could not create backup directory: " . $path);
            }
        }

        $date = date('Y-m-d_H-i-s');
        $filename = str_replace(['{database}', '{date}'], [$database, $date], $format);
        $outputFile = $path . DIRECTORY_SEPARATOR . $filename;

        $command = "mysqldump -h " . escapeshellarg($host) . " -P " . escapeshellarg($port) . " -u " . escapeshellarg($user);

        if (!empty($password)) {
            $command .= " -p" . escapeshellarg($password);
        }

        $command .= " " . escapeshellarg($database);

        foreach ($ignoreTables as $table) {
            $command .= " --ignore-table=" . escapeshellarg($database . "." . $table);
        }

        // Check if output file ends with .gz
        if (substr($filename, -3) === '.gz') {
            $command .= " | gzip > " . escapeshellarg($outputFile);
        } else {
            $command .= " > " . escapeshellarg($outputFile);
        }

        Console::execWithDirectOutput($command);

        if (file_exists($outputFile) && filesize($outputFile) > 0) {
            Console::success("Database backup created: " . $outputFile);
        } else {
            throw new Exception("Database backup failed or file is empty: " . $outputFile);
        }
    }

    private function runRsync(array $job): void
    {
        $required = ['local', 'remote'];
        $this->validateJob($job, $required);

        $local = $job['local'];
        $remote = $job['remote'];

        // Ensure local path ends with / if it's a directory sync, rsync behavior depends on it
        // But user provided config should be respected. 
        // Usually for backup syncing folder content: /source/ /dest/

        $command = "rsync -avz " . escapeshellarg($local) . " " . escapeshellarg($remote);

        Console::execWithDirectOutput($command);
        Console::success("Rsync completed from $local to $remote");
    }

    private function validateJob(array $job, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (!isset($job[$field])) {
                throw new Exception("Missing required field in job: " . $field);
            }
        }
    }
}
