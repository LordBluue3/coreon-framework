<?php

declare(strict_types=1);

/**
 * Coreon Framework (c) 2025 Mikael Oliveira
 * Licensed under the MIT License.
 */

namespace Core;

use PDO;
use PDOException;

class Model
{
    protected static PDO $db;

    public function __construct()
    {
        try {
            if (!isset(self::$db)) {
                self::$db = new PDO(
                    'mysql:host=' . env('DB_HOST') .
                    ';port=' . env('DB_PORT') .
                    ';dbname=' . env('DB_NAME') .
                    ';charset=utf8mb4',
                    env('DB_USER'),
                    env('DB_PASS')
                );

                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    protected function tableName(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName()) . 's';
    }

    public function all(): array
    {
        try {
            $stmt = self::$db->query("SELECT * FROM " . $this->tableName());
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    public function find(int $id): ?array
    {
        try {
            $stmt = self::$db->prepare("SELECT * FROM " . $this->tableName() . " WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    public function create(array $data): ?array
    {
        try {
            $columns = implode(',', array_keys($data));
            $placeholders = implode(',', array_fill(0, count($data), '?'));
            $stmt = self::$db->prepare("INSERT INTO " . $this->tableName() . " ($columns) VALUES ($placeholders)");

            if ($stmt->execute(array_values($data))) {
                $id = self::$db->lastInsertId();
                return $this->find((int) $id);
            }

            return null;
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $columns = array_keys($data);
            $setClause = implode(', ', array_map(fn($col) => "$col = ?", $columns));
    
            $stmt = self::$db->prepare("UPDATE " . $this->tableName() . " SET $setClause WHERE id = ?");
            $stmt->execute([...array_values($data), $id]);
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = self::$db->prepare("DELETE FROM " . $this->tableName() . " WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }
    

    public function query(string $sql, array $params = []): array|bool
    {
        try {
            $stmt = self::$db->prepare($sql);
            $success = $stmt->execute($params);

            if (stripos(trim($sql), 'SELECT') === 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $success;
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    public function where(array $conditions): array
    {
        try {
            $columns = array_keys($conditions);
            $whereClause = implode(' AND ', array_map(fn($col) => "$col = ?", $columns));
            $stmt = self::$db->prepare("SELECT * FROM " . $this->tableName() . " WHERE $whereClause");
            $stmt->execute(array_values($conditions));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    public function paginate(int $perPage = 10, int $page = 1): array
    {
        try {
            $offset = ($page - 1) * $perPage;
            $stmt = self::$db->prepare("SELECT * FROM " . $this->tableName() . " LIMIT ? OFFSET ?");

            $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleException($e);
        }
    }

    protected function handleException(PDOException $e): never
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    
        foreach ($backtrace as $trace) {
            if (isset($trace['file']) && !str_contains($trace['file'], '/core/')) {
                $file = $trace['file'];
                $line = $trace['line'];
                break;
            }
        }
    
        (new \Core\Dumper())->dumpEloquent([
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'origin_file' => $file ?? $e->getFile(),
            'origin_line' => $line ?? $e->getLine(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString()),
        ]);
        die;
    }
}
