<?php

use App\Models\User;
use PHPUnit\Framework\TestCase;
use Core\Model;

require_once __DIR__ . '/../bootstrap/bootstrap.php';

class UserModelTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        $this->user = new User();

        // Acessa a instância PDO da Model usando Reflection
        $reflection = new \ReflectionClass($this->user);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $db = $property->getValue();

        // Limpa a tabela
        $db->exec('DELETE FROM users');
    }

    public function testCreateAndFind(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => password_hash('123456', PASSWORD_BCRYPT),
        ];

        $created = $this->user->create($data);

        $this->assertIsArray($created);
        $this->assertArrayHasKey('id', $created);

        $found = $this->user->find((int)$created['id']);
        $this->assertEquals('Test User', $found['name']);
    }

    public function testUpdate(): void
    {
        $data = [
            'name' => 'Update Test',
            'email' => 'update@example.com',
            'password' => password_hash('123456', PASSWORD_BCRYPT),
        ];

        $created = $this->user->create($data);

        $updated = $this->user->update((int)$created['id'], ['name' => 'Updated Name']);
        $this->assertTrue($updated);

        $found = $this->user->find((int)$created['id']);
        $this->assertEquals('Updated Name', $found['name']);
    }

    public function testDelete(): void
    {
        $data = [
            'name' => 'Delete Test',
            'email' => 'delete@example.com',
            'password' => password_hash('123456', PASSWORD_BCRYPT),
        ];

        $created = $this->user->create($data);

        $deleted = $this->user->delete((int)$created['id']);
        $this->assertTrue($deleted);

        $found = $this->user->find((int)$created['id']);
        $this->assertNull($found);
    }

    public function testAllReturnsArray(): void
    {
        $all = $this->user->all();
        $this->assertIsArray($all);
    }

    public function testWhereReturnsMatchingRows(): void
    {
        $this->user->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'pass',
        ]);

        $results = $this->user->where(['email' => 'john@example.com']);
        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertEquals('John Doe', $results[0]['name']);
    }

    public function testQuerySelect(): void
    {
        $this->user->create([
            'name' => 'Query Select',
            'email' => 'query@example.com',
            'password' => 'pass',
        ]);

        $results = $this->user->query('SELECT * FROM users WHERE email = ?', ['query@example.com']);
        $this->assertIsArray($results);
        $this->assertCount(1, $results);
    }

    public function testQueryInsert(): void
    {
        $result = $this->user->query(
            'INSERT INTO users (name, email, password) VALUES (?, ?, ?)',
            ['Query Insert', 'insert@example.com', 'pass']
        );
        $this->assertTrue($result !== false);
    }

    public function testPaginate(): void
    {
        // Inserir 15 registros
        for ($i = 1; $i <= 15; $i++) {
            $this->user->create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => 'pass',
            ]);
        }

        $page1 = $this->user->paginate(10, 1);
        $page2 = $this->user->paginate(10, 2);

        $this->assertCount(10, $page1);
        $this->assertCount(5, $page2);
    }
}
