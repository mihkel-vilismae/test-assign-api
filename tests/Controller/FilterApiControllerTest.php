<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FilterApiControllerTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return \App\Kernel::class;
    }
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/filters/get');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreate(): void
    {
        $client = static::createClient();
        $payload = [
            'name' => 'Test Filter',
            'selection' => 'Test Selection',
            'criteria' => [
                [
                    'type' => 'type1',
                    'comparator' => 'equals',
                    'value' => 'value1',
                ],
                [
                    'type' => 'type2',
                    'comparator' => 'greater_than',
                    'value' => 'value2',
                ],
            ],
        ];

        $client->request(
            'POST',
            '/api/filters/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $payload = [
            'name' => 'Updated Filter',
            'selection' => 'Updated Selection',
            'criteria' => [
                [
                    'type' => 'type1',
                    'comparator' => 'equals',
                    'value' => 'new_value1',
                ],
            ],
        ];

        $client->request(
            'PUT',
            '/api/filters/update/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/filters/delete/1');

        $this->assertResponseStatusCodeSame(204);
    }
}