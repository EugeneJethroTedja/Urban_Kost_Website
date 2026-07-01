<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// ═══════════════════════════════════════════════════════════════
// Migration: Sinkronisasi tabel service_catalog yang sudah ada
//
// Struktur lama di database Anda:
//   id, name, description, monthly_price, is_active, created_at, updated_at
//
// Kolom yang DITAMBAHKAN (tidak menghapus kolom lama):
//   key         → identifier unik (diisi otomatis dari name)
//   label       → sama dengan name (alias)
//   price       → sama dengan monthly_price (alias)
//   icon        → emoji, default null
//   sort_order  → urutan tampil, default 0
//
// Data lama AMAN — tidak ada yang dihapus.
// ═══════════════════════════════════════════════════════════════

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_catalog', function (Blueprint $table) {
            // Tambah kolom key jika belum ada
            if (!Schema::hasColumn('service_catalog', 'key')) {
                $table->string('key')->nullable()->after('id');
            }
            // Tambah kolom label jika belum ada
            if (!Schema::hasColumn('service_catalog', 'label')) {
                $table->string('label')->nullable()->after('key');
            }
            // Tambah kolom price jika belum ada
            if (!Schema::hasColumn('service_catalog', 'price')) {
                $table->unsignedBigInteger('price')->default(0)->after('label');
            }
            // Tambah kolom icon jika belum ada
            if (!Schema::hasColumn('service_catalog', 'icon')) {
                $table->string('icon', 20)->nullable()->after('price');
            }
            // Tambah kolom sort_order jika belum ada
            if (!Schema::hasColumn('service_catalog', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('icon');
            }
        });

        // ── Isi kolom baru dari data lama ──────────────────────
        // Ambil semua record lama dan generate key dari name
        $rows = DB::table('service_catalog')->get();

        foreach ($rows as $row) {
            $updateData = [];

            // Buat key dari name: lowercase, spasi → underscore, hapus karakter aneh
            if (empty($row->key ?? null)) {
                $key = strtolower($row->name ?? 'service_' . $row->id);
                $key = preg_replace('/[^a-z0-9_]/', '_', $key);
                $key = preg_replace('/_+/', '_', $key);
                $key = trim($key, '_');
                // Hindari duplikat key
                $existingKeys = DB::table('service_catalog')
                    ->where('id', '!=', $row->id)
                    ->pluck('key')
                    ->toArray();
                $originalKey = $key;
                $counter = 2;
                while (in_array($key, $existingKeys)) {
                    $key = $originalKey . '_' . $counter++;
                }
                $updateData['key'] = $key;
            }

            // Isi label dari name jika kosong
            if (empty($row->label ?? null)) {
                $updateData['label'] = $row->name ?? '';
            }

            // Isi price dari monthly_price jika kolom monthly_price ada
            if (empty($row->price ?? null)) {
                if (isset($row->monthly_price)) {
                    $updateData['price'] = (int) $row->monthly_price;
                }
            }

            // Sort order default berdasarkan id
            if (empty($row->sort_order ?? null)) {
                $updateData['sort_order'] = $row->id;
            }

            // Isi icon default berdasarkan keyword di name
            if (empty($row->icon ?? null)) {
                $name = strtolower($row->name ?? '');
                $icon = match(true) {
                    str_contains($name, 'ac') || str_contains($name, 'air conditioner') => '',
                    str_contains($name, 'wifi') || str_contains($name, 'wi-fi')          => '',
                    str_contains($name, 'laundry') || str_contains($name, 'cuci')        => '',
                    str_contains($name, 'parkir') || str_contains($name, 'parking')      => '',
                    str_contains($name, 'bersih') || str_contains($name, 'cleaning')     => '',
                    str_contains($name, 'sarapan') || str_contains($name, 'breakfast')   => '',
                    str_contains($name, 'kulkas') || str_contains($name, 'fridge')       => '',
                    str_contains($name, 'tv') || str_contains($name, 'televisi')         => '',
                    str_contains($name, 'dispenser') || str_contains($name, 'air panas') => '',
                    default                                                               => '',
                };
                $updateData['icon'] = $icon;
            }

            if (!empty($updateData)) {
                DB::table('service_catalog')->where('id', $row->id)->update($updateData);
            }
        }

        // ── Jadikan key unique (setelah semua diisi) ───────────
        // Hanya tambah unique index jika belum ada
        try {
            Schema::table('service_catalog', function (Blueprint $table) {
                $table->unique('key', 'service_catalog_key_unique');
            });
        } catch (\Exception $e) {
            // Index sudah ada, abaikan
        }
    }

    public function down(): void
    {
        Schema::table('service_catalog', function (Blueprint $table) {
            // Hapus unique index dulu sebelum drop column
            try { $table->dropUnique('service_catalog_key_unique'); } catch (\Exception $e) {}

            $colsToDrop = ['key', 'label', 'price', 'icon', 'sort_order'];
            foreach ($colsToDrop as $col) {
                if (Schema::hasColumn('service_catalog', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};