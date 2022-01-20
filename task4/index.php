<?php

require_once "mysqlquery.php";

interface InterfaceBox
{
	public function setData(string $key, $value): bool;

	public function getData(string $key);

	public function save();

	public function load();
}
abstract class AbstractBox implements InterfaceBox
{
	protected array $data = [];

	public function setData(string $key, $value): bool
	{
		$this->data[] = [$key, $value];
		if (isset($this->data[$key])) {
			return true;
		}
		return false;
	}

	public function getData(string $key)
	{
		if (($key = array_search($key, array_column($this->data, 0))) !== false) {
			return $this->data[$key][1];
		}
		return null;
	}

	abstract public function save();

	abstract public function load();
}
class FileBox extends AbstractBox
{
	protected $filename = "filebox.txt";
	public function save(): bool
	{
		touch($this->filename);

		return file_put_contents($this->filename, json_encode($this->data));
	}
	public function load(): bool
	{
		$this->data = json_decode(file_get_contents($this->filename));
		if (is_null($this->data)) {
			return false;
		}
		return true;
	}
}
class DbBox extends AbstractBox
{
	protected MysqlQueryBuilder $builder;
	public function __construct()
	{
		$this->builder = MysqlQueryBuilder::table('data');
	}
	public function save(): bool
	{
		foreach ($this->data as $data) {
			if (!$this->builder->insert(['key_id', 'value'], $data)->set()) {
			 	return false;
			}
		}

		return true;
	}
	public function load()
	{
		foreach ($this->builder->all() as $fetch) {
			$this->data[] = [$fetch['key_id'], $fetch['value']];
		}
	}
}
class Box
{
	protected static ?Box $instance = null;
	protected InterfaceBox $interfaceBox;
	protected function __construct(InterfaceBox $interfaceBox)
	{
		$this->interfaceBox = $interfaceBox;
	}

	protected function __wakeup()
	{	
	}

	protected function __sleep()
	{	
	}

	protected function __clone()
	{	
	}

	public static function init(InterfaceBox $interfaceBox): Box
	{
		self::$instance ??= new static($interfaceBox);

        return self::$instance;
	}
	public function setData(string $key, $value)
	{
		$this->interfaceBox->setData($key, $value);
	}
	public function getData(string $key)
	{
		return $this->interfaceBox->getData($key);
	}
	public function save()
	{
		$this->interfaceBox->save();
	}
	public function load()
	{
		$this->interfaceBox->load();
	}
}

$box = Box::init(new DbBox);
$box->setData("1", "11111");
$box->setData("2", "222222");
$box->setData("3", "3333");
$box->setData("4", "44444");
 var_dump($box->getData("4"));
$box->save();
$box->load();
var_dump($box->getData("2"));
var_dump($box->getData("4"));

?>