<?php

namespace Efaturacim\Util\Backup;

use Efaturacim\Util\Utils\Console\Console;
use Efaturacim\Util\Utils\Options;
use Exception;

class MyBackup
{
    private $config;
    private $configFile;

    public function __construct(?string $configFile = null)
    {
        $this->config = ['name' => 'Unknown', 'jobs' => []];
        if ($configFile) {
            $this->configFile = $configFile;
            $this->loadConfig();
        }
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

    public function setName(string $name): self
    {
        $this->config['name'] = $name;
        return $this;
    }

    public function addJob(array $job): self
    {
        $this->config['jobs'][] = $job;
        return $this;
    }

    public function addMySqlDump(string $host, string $user, string $password, string $database, string $path, array $options = []): self
    {
        $job = [
            'type' => 'mysqldump',
            'host' => $host,
            'user' => $user,
            'password' => $password,
            'database' => $database,
            'path' => $path,
        ];

        $job = array_merge($job, $options);

        return $this->addJob($job);
    }

    public function addRsync(string $local, string $remote, array $options = []): self
    {
        $job = [
            'type' => 'rsync',
            'local' => $local,
            'remote' => $remote,
        ];

        $job = array_merge($job, $options);

        return $this->addJob($job);
    }

    public function saveConfig(string $jsonPath): void
    {
        $json = json_encode($this->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (file_put_contents($jsonPath, $json) === false) {
            throw new Exception("Failed to save config to: " . $jsonPath);
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
        $options = new Options($job);

        if (!$options->hasKey(['host', 'hostname'])) {
            throw new Exception("Missing required field: host");
        }
        if (!$options->hasKey(['user', 'username', 'user_name'])) {
            throw new Exception("Missing required field: user");
        }
        if (!$options->hasKey(['database', 'db', 'dbname'])) {
            throw new Exception("Missing required field: database");
        }
        if (!$options->hasKey(['path', 'dir', 'directory'])) {
            throw new Exception("Missing required field: path");
        }

        $host = $options->getAs(['host', 'hostname'], 'localhost');
        $port = $options->getAsInt(['port'], 3306);
        $user = $options->getAs(['user', 'username', 'user_name'], '');
        $password = $options->getAs(['password', 'pass', 'pwd'], '');
        $database = $options->getAs(['database', 'db', 'dbname'], '');
        $path = rtrim($options->getAs(['path', 'dir', 'directory'], ''), '/');
        $format = $options->getAs(['format', 'filename_format'], '{database}_{date}.sql.gz');
        $ignoreTables = $options->getAs(['ignore_tables', 'exclude_tables'], []);

        if (!is_dir($path)) {
            if (!mkdir($path, 0755, true)) {
                throw new Exception("Could not create backup directory: " . $path);
            }
        }

        $date = date('Y-m-d');
        $dateTime = date('Y-m-d_H-i-s');
        $filename = str_replace(['{database}', '{date}', "{datetime}"], [$database, $date, $dateTime], $format);
        $outputFile = $path . DIRECTORY_SEPARATOR . $filename;

        $command = "mysqldump -h " . escapeshellarg($host) . " -P " . escapeshellarg($port) . " -u " . escapeshellarg($user);

        if (!empty($password)) {
            $command .= " -p" . escapeshellarg($password);
        }

        $command .= " " . escapeshellarg($database);

        if ($options->getAsBool(['complete_insert', 'complete-insert'], true)) {
            $command .= " --complete-insert";
        }

        if ($options->getAsBool(['insert_ignore', 'insert-ignore'], false)) {
            $command .= " --insert-ignore";
        }

        if (is_array($ignoreTables)) {
            foreach ($ignoreTables as $table) {
                $command .= " --ignore-table=" . escapeshellarg($database . "." . $table);
            }
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
        $options = new Options($job);

        if (!$options->hasKey(['local', 'source', 'src'])) {
            throw new Exception("Missing required field: local");
        }
        if (!$options->hasKey(['remote', 'dest', 'destination'])) {
            throw new Exception("Missing required field: remote");
        }

        $local = $options->getAs(['local', 'source', 'src'], '');
        $remote = $options->getAs(['remote', 'dest', 'destination'], '');

        // Ensure local path ends with / if it's a directory sync, rsync behavior depends on it
        // But user provided config should be respected.
        // Usually for backup syncing folder content: /source/ /dest/

        $command = "rsync -avz " . escapeshellarg($local) . " " . escapeshellarg($remote);

        Console::execWithDirectOutput($command);
        Console::success("Rsync completed from $local to $remote");
    }

    private function validateJob(array $job, array $requiredFields): void
    {
        // Deprecated in favor of Options class validation inside run methods
    }
}
