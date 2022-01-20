<?php

class conn
{
	protected PDO $pdo;

	protected $fetchMode = PDO::FETCH_ASSOC;

	public function __construct()
	{
		$config = ['driver' => 'mysql',
		        'host' => '127.0.0.1',
		        'port' => 5432,
		        'user' => 'root',
		        'password' => '',
		        'dbname' => 'astrio'];

		$dsn = "{$config['driver']}:dbname={$config['dbname']};host={$config['host']}";

		$this->pdo = new PDO($dsn, $config['user'], $config['password']);
	}
	public function setFetchMode(array $fetchMode): void
	{
		$this->fetchMode = $fetchMode;
	}
	protected function executeGet(string $sql, ?array $params = null): PDOStatement
	{
		$statement = $this->pdo->prepare($sql);
		$statement->setFetchMode($this->fetchMode);
		$statement->execute($params);

		return $statement;
	}
	protected function executeSet(string $sql, ?array $params = null): bool
	{
		$statement = $this->pdo->prepare($sql);
		$statement->setFetchMode($this->fetchMode);
		return $statement->execute($params);
	}
	public function fetch(string $sql, array $params, ?array $fetchMode = null)
	{
		return $this->executeGet($sql, $params)->fetch($fetchMode);
	}
	public function fetchAll(string $sql, ?array $params = null, ?array $fetchMode = null): array
	{
		return $this->executeGet($sql, $params)->fetchAll($fetchMode);
	}
	public function fetchColumn(string $sql, ?array $params = null, ?int $column = null)
	{
		return $this->executeGet($sql, $params)->fetchColumn($column);
	}
	public function fetchObject(string $sql, ?array $params = null, ?string $className, ?array $ctor_args)
	{
		return $this->executeGet($sql, $params)->fetchColumn($className, );
	}
	public function insert(string $sql, array $params): bool
	{
		return $this->executeSet($sql, $params);
	}

}