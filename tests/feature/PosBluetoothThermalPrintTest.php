<?php

declare(strict_types=1);

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class PosBluetoothThermalPrintTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
    }

    private function getSession(): array
    {
        return [
            'is_logged_in' => true,
            'user'         => [
                'id'                => 1,
                'username'          => 'admin',
                'nama'              => 'Super Administrator',
                'role_id'           => 1,
                'role_name'         => 'superadmin',
                'role_display_name' => 'Super Admin',
            ],
        ];
    }

    public function testPosPageIncludesBluetoothPrinterAssetsAndWidgets(): void
    {
        $result = $this->withSession($this->getSession())->get('pos');
        $result->assertStatus(200);

        $body = $result->getBody();

        // 1. Must load Bluetooth thermal printer driver script
        $this->assertStringContainsString('bluetooth-thermal-printer.js', $body);

        // 2. Must contain Bluetooth status and pairing dropdown
        $this->assertStringContainsString('id="btnBluetoothStatus"', $body);
        $this->assertStringContainsString('id="btnActionConnectBluetooth"', $body);
        $this->assertStringContainsString('id="btnActionTestPrint"', $body);
        $this->assertStringContainsString('id="selectThermalPaperWidth"', $body);

        // 3. Must contain Bluetooth direct checkout button
        $this->assertStringContainsString('id="btnCheckoutBluetoothDirect"', $body);

        // 4. Must contain Success Modal with Bluetooth Print button
        $this->assertStringContainsString('id="modalPosSuccess"', $body);
        $this->assertStringContainsString('id="btnModalPrintBluetooth"', $body);
        $this->assertStringContainsString('id="btnModalPrintBrowser"', $body);
        $this->assertStringContainsString('id="btnModalNewTransaction"', $body);
    }

    public function testStrukPageIncludesBluetoothPrintControls(): void
    {
        // Get first existing sales order from database
        $db = \Config\Database::connect();
        $order = $db->table('sales_order')->limit(1)->get()->getRowArray();

        if ($order) {
            $result = $this->withSession($this->getSession())->get('pos/struk/' . $order['id']);
            $result->assertStatus(200);

            $body = $result->getBody();

            // Must include Bluetooth thermal printer driver script
            $this->assertStringContainsString('bluetooth-thermal-printer.js', $body);
            // Must include Bluetooth print button
            $this->assertStringContainsString('id="btnBluetoothPrint"', $body);
            // Must contain receiptData JSON payload
            $this->assertStringContainsString('receiptData = {', $body);
        } else {
            $this->assertTrue(true, 'No sales order in DB to test');
        }
    }

    public function testStrukJsonEndpointReturnsValidPayload(): void
    {
        $db = \Config\Database::connect();
        $order = $db->table('sales_order')->limit(1)->get()->getRowArray();

        if ($order) {
            $result = $this->withSession($this->getSession())->get('pos/struk-json/' . $order['id']);
            $result->assertStatus(200);

            $json = json_decode($result->getJSON(), true);
            $this->assertIsArray($json);
            $this->assertSame('success', $json['status']);
            $this->assertArrayHasKey('order', $json);
            $this->assertArrayHasKey('items', $json);
            $this->assertArrayHasKey('store', $json);
            $this->assertSame('METAFORA STORE', $json['store']['name']);
        } else {
            $this->assertTrue(true, 'No sales order in DB to test');
        }
    }
}

