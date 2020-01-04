<?php

namespace EntityGenerator\Driver;

use Nyholm\DSN;

class PdoDriver implements DriverInterface
{
    /** @var \PDO */
    private $pdo;

    /** @var string */
    private $schema;

    public function __construct(DSN $dsn)
    {
        $pdoDsn = sprintf('%s:dbname=%s;host=%s:%s', $dsn->getProtocol(), $dsn->getDatabase(), $dsn->getFirstHost(), $dsn->getFirstPort());

        $this->pdo = new \PDO($pdoDsn, $dsn->getUsername(), $dsn->getPassword());
        $this->schema = $dsn->getDatabase();
    }

    /**
     * @inheritDoc
     */
    public function getTables(): iterable
    {
        $statement = $this->pdo->prepare('SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_TYPE = "BASE TABLE" AND TABLE_SCHEMA = ?');
        $statement->execute([$this->schema]);

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getCreateStatement(string $tableName): string
    {
        $createTable = $this->pdo->query('SHOW CREATE TABLE ' . $tableName)->fetch();

        return $createTable['Create Table'];
    }
}
