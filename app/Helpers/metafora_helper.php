<?php

declare(strict_types=1);

if (!function_exists('format_rupiah')) {
    /**
     * Format number as Rupiah currency string.
     */
    function format_rupiah(float|int|string|null $nominal, bool $withSymbol = true): string
    {
        $amount = (float) ($nominal ?? 0);
        $formatted = number_format($amount, 0, ',', '.');
        return $withSymbol ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('money_round')) {
    /**
     * Standard half-up round for monetary values using arbitrary precision.
     */
    function money_round(string|float|int $amount, int $decimals = 2): string
    {
        $val = (string) $amount;
        if (!str_contains($val, '.')) {
            return bcadd($val, '0', $decimals);
        }
        $isNegative = str_starts_with($val, '-');
        $adder = ($isNegative ? '-0.' : '0.') . str_repeat('0', $decimals) . '5';
        $added = bcadd($val, $adder, $decimals + 1);
        return bcadd($added, '0', $decimals);
    }
}

if (!function_exists('money_add')) {
    function money_add(string|float|int $a, string|float|int $b, int $scale = 2): string
    {
        return bcadd((string) $a, (string) $b, $scale);
    }
}

if (!function_exists('money_sub')) {
    function money_sub(string|float|int $a, string|float|int $b, int $scale = 2): string
    {
        return bcsub((string) $a, (string) $b, $scale);
    }
}

if (!function_exists('money_mul')) {
    function money_mul(string|float|int $a, string|float|int $b, int $scale = 2): string
    {
        return bcmul((string) $a, (string) $b, $scale);
    }
}

if (!function_exists('money_div')) {
    function money_div(string|float|int $a, string|float|int $b, int $scale = 2): string
    {
        if (bccomp((string) $b, '0', 6) === 0) {
            throw new \InvalidArgumentException("Division by zero in money_div: cannot divide {$a} by {$b}");
        }
        return bcdiv((string) $a, (string) $b, $scale);
    }
}

if (!function_exists('format_tanggal')) {
    function format_tanggal(?string $date, string $format = 'd/m/Y'): string
    {
        if (empty($date)) {
            return '-';
        }
        $time = strtotime($date);
        return $time ? date($format, $time) : '-';
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime(?string $date, string $format = 'd/m/Y H:i'): string
    {
        if (empty($date)) {
            return '-';
        }
        $time = strtotime($date);
        return $time ? date($format, $time) : '-';
    }
}

if (!function_exists('current_user')) {
    function current_user(): ?array
    {
        return session()->get('user');
    }
}

if (!function_exists('has_role')) {
    function has_role(string|array $roles): bool
    {
        $user = current_user();
        if (!$user) {
            return false;
        }

        $userRole = $user['role_name'] ?? '';
        if (is_array($roles)) {
            return in_array($userRole, $roles, true);
        }

        return $userRole === $roles;
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get currency prefix symbol.
     */
    function currency_symbol(string $currency = 'IDR'): string
    {
        return match (strtoupper(trim($currency))) {
            'USD'   => '$',
            'SGD'   => 'S$',
            'CNY'   => '¥',
            'EUR'   => '€',
            default => 'Rp',
        };
    }
}

if (!function_exists('format_valas')) {
    /**
     * Format monetary amount according to currency code.
     */
    function format_valas(float|int|string|null $nominal, string $currency = 'IDR'): string
    {
        $amount = (float) ($nominal ?? 0);
        $curr = strtoupper(trim($currency));

        return match ($curr) {
            'USD'   => '$ ' . number_format($amount, 2, '.', ','),
            'SGD'   => 'S$ ' . number_format($amount, 2, '.', ','),
            'CNY'   => '¥ ' . number_format($amount, 2, '.', ','),
            'EUR'   => '€ ' . number_format($amount, 2, ',', '.'),
            default => 'Rp ' . number_format($amount, 0, ',', '.'),
        };
    }
}

if (!function_exists('export_csv_file')) {
    /**
     * Stream database records as clean UTF-8 CSV with Excel-compatible BOM.
     * Works natively in Microsoft Excel, Google Sheets, LibreOffice, and CSV parsers.
     */
    function export_csv_file(string $filename, array $headers, array $rows): void
    {
        if (ob_get_level()) {
            ob_end_clean();
        }

        if (!str_ends_with(strtolower($filename), '.csv')) {
            $filename .= '.csv';
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // Output UTF-8 BOM for Microsoft Excel Windows compatibility
        fputs($out, "\xEF\xBB\xBF");

        fputcsv($out, $headers);
        foreach ($rows as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }
}

if (!function_exists('has_permission')) {
    /**
     * Check if current authenticated user has a specific permission or any of given permissions.
     * Supports:
     * - Single permission string: has_permission('master_produk')
     * - Array of permission strings: has_permission(['master_produk', 'master_pelanggan']) (returns true if any match)
     * - Module level check: has_permission('module:pembelian') (returns true if user has any permission under this module)
     */
    function has_permission(string|array $permissions): bool
    {
        $user = current_user();
        if (!$user) {
            return false;
        }

        $roleName = strtolower($user['role_name'] ?? '');
        if ($roleName === 'superadmin' || str_contains($roleName, 'super admin')) {
            return true;
        }

        $roleId = (int) ($user['role_id'] ?? 0);
        if ($roleId <= 0 && !empty($user['id'])) {
            try {
                $db = \Config\Database::connect();
                $uRow = $db->table('users')->select('role_id')->where('id', (int) $user['id'])->get()->getRowArray();
                if ($uRow && !empty($uRow['role_id'])) {
                    $roleId = (int) $uRow['role_id'];
                }
            } catch (\Throwable $e) {}
        }

        if ($roleId <= 0) {
            return false;
        }

        static $permCache = [];
        static $moduleCache = [];
        if (!isset($permCache[$roleId])) {
            $db = \Config\Database::connect();
            if ($db->tableExists('role_permissions') && $db->tableExists('permissions')) {
                $rows = $db->table('role_permissions rp')
                    ->select('p.name, p.module')
                    ->join('permissions p', 'p.id = rp.permission_id')
                    ->where('rp.role_id', $roleId)
                    ->get()
                    ->getResultArray();
                $permCache[$roleId] = array_column($rows, 'name');
                $moduleCache[$roleId] = array_values(array_unique(array_filter(array_column($rows, 'module'))));
            } else {
                $permCache[$roleId] = [];
                $moduleCache[$roleId] = [];
            }
        }

        $userPerms = $permCache[$roleId] ?? [];
        $userModules = $moduleCache[$roleId] ?? [];

        $checkList = is_array($permissions) ? $permissions : [$permissions];
        foreach ($checkList as $p) {
            $p = trim((string) $p);
            if (str_starts_with($p, 'module:')) {
                $modName = strtolower(substr($p, 7));
                if (in_array($modName, $userModules, true)) {
                    return true;
                }
            } elseif (in_array($p, $userPerms, true)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('has_module_access')) {
    /**
     * Check if current user has access to any feature in the specified module.
     */
    function has_module_access(string $module): bool
    {
        return has_permission('module:' . strtolower($module));
    }
}

if (!function_exists('terbilang')) {
    /**
     * Convert numbers into Indonesian words (e.g. 1500000 -> "Satu Juta Lima Ratus Ribu Rupiah").
     */
    function terbilang(float|int|string $nominal): string
    {
        $angka = abs((float) $nominal);
        $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        $hasil = '';

        if ($angka < 12) {
            $hasil = ' ' . $baca[(int) $angka];
        } elseif ($angka < 20) {
            $hasil = terbilang($angka - 10) . ' Belas';
        } elseif ($angka < 100) {
            $hasil = terbilang((int) ($angka / 10)) . ' Puluh' . terbilang($angka % 10);
        } elseif ($angka < 200) {
            $hasil = ' Seratus' . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $hasil = terbilang((int) ($angka / 100)) . ' Ratus' . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $hasil = ' Seribu' . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $hasil = terbilang((int) ($angka / 1000)) . ' Ribu' . terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $hasil = terbilang((int) ($angka / 1000000)) . ' Juta' . terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $hasil = terbilang((int) ($angka / 1000000000)) . ' Miliar' . terbilang(fmod($angka, 1000000000));
        } else {
            $hasil = terbilang((int) ($angka / 1000000000000)) . ' Triliun' . terbilang(fmod($angka, 1000000000000));
        }

        $res = trim(preg_replace('/\s+/', ' ', $hasil));
        return !empty($res) ? $res . ' Rupiah' : 'Nol Rupiah';
    }
}
