<?php

namespace Tests\Unit\DTOs\Auth;

use App\DTOs\Auth\LoginDTO;
use PHPUnit\Framework\TestCase;

class LoginDTOTest extends TestCase
{
    /**
     * Test that a LoginDTO can be created from request data.
     */
    public function test_can_create_from_request_data(): void
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $dto = LoginDTO::fromRequest($data);

        $this->assertEquals('test@example.com', $dto->email);
        $this->assertEquals('password123', $dto->password);
    }
}
