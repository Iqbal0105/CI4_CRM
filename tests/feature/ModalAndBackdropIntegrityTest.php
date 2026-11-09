<?php

declare(strict_types=1);

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class ModalAndBackdropIntegrityTest extends CIUnitTestCase
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
                'id'          => 1,
                'username'    => 'superadmin',
                'nama'        => 'Super Administrator',
                'role_id'     => 1,
                'role_name'   => 'superadmin',
            ],
        ];
    }

    public function testMainLayoutHasNoBlurGlitchStyles(): void
    {
        $mainLayout = file_get_contents(APPPATH . 'Views/layouts/main.php');

        // 1. Ensure .modal-backdrop does not have blur filter
        $this->assertDoesNotMatchRegularExpression(
            '/\.modal-backdrop[^{]*\{[^}]*backdrop-filter:\s*blur/s',
            $mainLayout,
            'Modal backdrop must not use backdrop-filter blur to prevent page blur lock'
        );

        // 2. Ensure .sidebar-backdrop does not have blur filter and has display none
        $this->assertDoesNotMatchRegularExpression(
            '/\.sidebar-backdrop[^{]*\{[^}]*backdrop-filter:\s*blur/s',
            $mainLayout,
            'Sidebar backdrop must not use backdrop-filter blur'
        );
        $this->assertMatchesRegularExpression(
            '/\.sidebar-backdrop\s*\{[^}]*display:\s*none\s*!important/s',
            $mainLayout,
            'Sidebar backdrop must have display: none !important by default to avoid click interception'
        );

        // 3. Ensure global modal hoisting and cleanup JS exists
        $this->assertStringContainsString('appendTo(\'body\')', $mainLayout);
        $this->assertStringContainsString('show.bs.modal', $mainLayout);
        $this->assertStringContainsString('hidden.bs.modal', $mainLayout);
    }

    public function testAllModalsInModulesHaveCenteredDialog(): void
    {
        $modulesDir = ROOTPATH . 'modules';
        $views = glob($modulesDir . '/*/Views/**/*.php');

        $violatingModals = [];

        foreach ($views as $viewFile) {
            $content = file_get_contents($viewFile);
            if (!str_contains($content, 'class="modal')) {
                continue;
            }

            // Find all <div class="modal-dialog...
            preg_match_all('/<div\s+class="modal-dialog([^"]*)"/i', $content, $matches);
            foreach ($matches[1] as $classes) {
                if (!str_contains($classes, 'modal-dialog-centered')) {
                    $violatingModals[] = basename($viewFile) . ' -> modal-dialog' . $classes;
                }
            }
        }

        $this->assertEmpty(
            $violatingModals,
            'All modals must include modal-dialog-centered. Violations: ' . implode(', ', $violatingModals)
        );
    }

    public function testCorePagesRenderWithProperLayoutAndClickability(): void
    {
        $pages = [
            'master/supplier',
            'master/kategori',
            'master/satuan',
            'stok',
            'keuangan/utang',
            'keuangan/piutang',
            'keuangan/jurnal',
            'rfm',
            'rfm/customer',
            'crm',
            'dev/crud',
        ];

        foreach ($pages as $page) {
            $result = $this->withSession($this->getSession())->get($page);
            $result->assertStatus(200);
            $body = $result->getBody();

            // Must contain modal handling script from layout
            $this->assertStringContainsString('show.bs.modal', $body, "Page {$page} must include modal stacking script");
            // Must have clean top navbar
            $this->assertStringContainsString('top-navbar', $body, "Page {$page} must render top navbar");
        }
    }
}

