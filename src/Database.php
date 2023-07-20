<?php

declare(strict_types=1);

namespace Me\BjoernBuettner;

use DateTime;
use DateTimeImmutable;
use Exception;
use Me\BjoernBuettner\Exceptions\InvalidEntityException;
use PDO;
use PDOStatement;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\String\UnicodeString;

class Database
{
    private ?PDO $database = null;

    public function __construct(
        private readonly string $databaseDatabase,
        private readonly string $databaseHost,
        private readonly string $databaseUser,
        private readonly string $databasePassword,
    ) {
    }

    /**
     * @param array<string> $parameters
     * @return array<object>
     */
    public function load(string $class, array $parameters = []): array
    {
        try {
            $rc = new ReflectionClass($class);
            if (!$rc->isInstantiable()) {
                throw new InvalidEntityException("class $class is not instantiable");
            }
            if (!$rc->getConstructor() === null) {
                throw new InvalidEntityException("class $class has no usable constructor");
            }
            $table = (new UnicodeString($rc->getShortName()))->snake()->toString();
            $query = "SELECT * FROM {$table} WHERE 1;";
            foreach (array_keys($parameters) as $key) {
                $column = (new UnicodeString($key))->snake()->toString();
                $query .= " AND {$column} = :{$key}";
            }
            $statement = $this->get()->prepare($query);
            foreach ($parameters as $key => $value) {
                $this->bindValue($statement, $value, $key);
            }
            return array_map(
                function (array $assoc) use ($rc): object {
                    $args = [];
                    foreach ($rc->getConstructor()->getParameters() as $parameter) {
                        $name = (new UnicodeString($parameter->getName()))->snake()->toString();
                        $args[] = match ($parameter->getType()?->getName()) {
                            'int' => (int)$assoc[$name],
                            'float' => (float)$assoc[$name],
                            'bool' => $assoc[$name] === '1',
                            'array' => explode(',', $assoc[$name]),
                            'DateTime' => new DateTime($assoc[$name]),
                            'DateTimeImmutable' => new DateTimeImmutable($assoc[$name]),
                            default => (string)$assoc[$name],
                        };
                    }
                    return $rc->newInstanceArgs($args);
                },
                $statement->fetchAll(PDO::FETCH_ASSOC) ?: []
            );
        } catch (Exception $e) {
            throw new InvalidEntityException("unable to construct class $class", 0, $e);
        }
    }
    public function store(object $object): void
    {
        try {
            $rc = new ReflectionClass($object);
            $aid = $rc->getProperty('aid');
            if (is_integer($aid->getValue($object))) {
                $this->insert($object, $rc, $aid);
                return;
            }
            $this->update($object, $rc, $aid);
        } catch (ReflectionException $e) {
            throw new InvalidEntityException("Unable to store object", 0, $e);
        }
    }
    private function bindValue(PDOStatement $statement, mixed $value, string $name): void
    {
        $column = (new UnicodeString($name))->snake()->toString();
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        $statement->bindValue(
            $column,
            $value,
            $value === null ? PDO::PARAM_NULL : match (gettype($value)) {
                'integer' => PDO::PARAM_INT,
                'boolean' => PDO::PARAM_BOOL,
                default => PDO::PARAM_STR
            }
        );
    }

    private function get(): PDO
    {
        if (null === $this->database) {
            $this->database = new PDO(
                "mysql:host={$this->databaseHost};dbname={$this->databaseDatabase}",
                $this->databaseUser,
                $this->databasePassword,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return $this->database;
    }

    private function insert(object $object, ReflectionClass $rc, ReflectionProperty $aid): void
    {
        $properties = $rc->getProperties();
        $columns = [];
        foreach ($properties as $property) {
            if ($property->getName() === 'aid') {
                continue;
            }
            $columns[] = (new UnicodeString($property->getName()))->snake()->toString();
        }
        $name = (new UnicodeString($rc->getShortName()))->snake()->toString();
        $sql = "INSERT INTO {$name} (" . implode(',', $columns) . ") VALUES (:" . implode(',:', $columns) . ")";
        $statement = $this->get()->prepare($sql);
        foreach ($properties as $property) {
            if ($property->getName() === 'aid') {
                continue;
            }
            $this->bindValue($statement, $property->getValue($object), $property->getName());
        }
        $statement->execute();
        $aid->setValue($object, $this->get()->lastInsertId());
    }

    private function update(object $object, ReflectionClass $rc, ReflectionProperty $aid): void
    {
        $properties = $rc->getProperties();
        $columns = [];
        foreach ($properties as $property) {
            if ($property->getName() === 'aid') {
                continue;
            }
            $column = (new UnicodeString($property->getName()))->snake()->toString();
            $columns[] = "{$column} = :{$column}";
        }
        $name = (new UnicodeString($rc->getShortName()))->snake()->toString();
        $sql = "UPDATE {$name} SET " . implode(',', $columns) . " WHERE aid = :aid";
        $statement = $this->get()->prepare($sql);
        foreach ($properties as $property) {
            $this->bindValue($statement, $property->getValue($object), $property->getName());
        }
        $statement->execute();
    }
}
