<?php

namespace App\Infra\Persistence\SQLite;
use SQLite3;
use SQLite3Result;

class SQLiteRepository
{
    private SQLite3 $db;
    protected string $table;

    public function __construct(string $dbFilePath)
    {
        $this->db = new SQLite3($dbFilePath, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        $this->createTable();
    }

    private function createTable(): void
    {
        $query = "CREATE TABLE IF NOT EXISTS $this->table (id TEXT PRIMARY KEY, json_data TEXT)";
        $this->db->exec($query);
    }

    public function create(array $data): array
    {
        $json = json_encode($data);
        $query = "INSERT INTO $this->table (id, json_data) VALUES (:id, :json)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':json', $json);
        $statement->bindValue(':id', $data['id'], SQLITE3_TEXT);
        $statement->execute();
        return $data;
    }

    public function read(): array
    {
        $query = "SELECT json_data FROM $this->table";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = json_decode($row['json_data'], true);
        }
        return $data;
    }

    public function findById(string $id): ?array
    {
        $query = "SELECT json_data FROM $this->table WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':id', $id, SQLITE3_TEXT);

        $result = $statement->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $data = json_decode($row['json_data'], true);
        $data['id'] = $id;
        return $row ? $data : null;
    }

    public function findByJson(array $criteria): ?array
    {
        $field = key($criteria);
        $value = $criteria[$field];
        $query = "SELECT json_data FROM $this->table WHERE json_extract(json_data, '$.$field') = :value";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':value', $value);
        $result = $statement->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row ? json_decode($row['json_data'], true) : null;
    }

    public function delete(string $id): bool
    {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':id', $id, SQLITE3_TEXT);
        return $statement->execute();
    }

    public function update(string $id, array $data): bool
    {
        unset($data['id']);
        $json = json_encode($data);
        $query = "UPDATE $this->table SET json_data = :json WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':id', $id, SQLITE3_TEXT);
        $statement->bindValue(':json', $json);
        return $statement->execute() instanceof SQLite3Result;
    }
}