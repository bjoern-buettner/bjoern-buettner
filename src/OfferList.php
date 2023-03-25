<?php

namespace Me\BjoernBuettner;

use PDO;

class OfferList
{
    public static function get(): array
    {
        $data = [];
        $database = new PDO(
            'mysql:host=' . $_ENV['DATABASE_HOST'] . ';dbname=' . $_ENV['DATABASE_DATABASE'],
            $_ENV['DATABASE_USER'],
            $_ENV['DATABASE_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]
        );
        foreach ($database->query('SELECT * FROM category')->fetchAll(PDO::FETCH_ASSOC) as $category) {
            $cat = [
                'de' => $category['de'],
                'en' => $category['en'],
                'chooseable' => $category['chooseable']==='1',
                'tasks' => []
            ];
            $stmt = $database->prepare('SELECT * FROM task WHERE category=:category');
            $stmt->execute([':category' => $category['aid']]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $task) {
                $cat['tasks'][] = [
                    'title' => [
                        'en' => $task['en_name'],
                        'de' => $task['de_name'],
                    ],
                    'description' => [
                        'en' => $task['en_description'],
                        'de' => $task['de_description'],
                    ],
                    'price' => $task['price'],
                    'fee_type' => $task['fee_type'],
                ];
            }
            $data[] = $cat;
        }
        return $data;
    }
}
