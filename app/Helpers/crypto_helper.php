<?php

use Config\Services;

/**
 * Enkripsi data jadi URL-safe string
 */
if (!function_exists('encrypt_url')) {
    function encrypt_url(string $data): string
    {
        if (empty($data)) {
            return '';
        }
        try {
            $encrypter = Services::encrypter();
            $encrypted = $encrypter->encrypt($data);
            return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
        } catch (\Throwable $th) {
            return '';
        }
    }
}

/**
 * Dekripsi data dari URL-safe string
 */

if (!function_exists('decrypt_url')) {
    function decrypt_url(string $data): string
    {
        if (empty($data)) {
            return '';
        }
        try {
            $base64 = strtr($data, '-_', '+/');
            $mod = strlen($base64) % 4;
            if ($mod) {
                $base64 .= str_repeat('=', 4 - $mod);
            }
            $decoded = base64_decode($base64);

            $encrypter = Services::encrypter();
            return $encrypter->decrypt($decoded);
        } catch (\Throwable $th) {
            return '';
        }
    }
}

function encode_short($id)
{
    $key = 25091991; // bebas, rahasia
    return base_convert($id + $key, 10, 36);
}

function decode_short($encoded)
{
    $key = 25091991;
    return base_convert($encoded, 36, 10) - $key;
}


if (!function_exists('encrypt_array_url')) {
    function encrypt_array_url(array $data): string
    {
        $encrypter = Services::encrypter();
        $json = json_encode($data); // array -> string JSON
        $encrypted = $encrypter->encrypt($json);
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }
}

if (!function_exists('decrypt_array_url')) {
    function decrypt_array_url(string $encrypted): array
    {
        $encrypter = Services::encrypter();
        $decoded = base64_decode(strtr($encrypted, '-_', '+/'));
        $json = $encrypter->decrypt($decoded);
        return json_decode($json, true); // string JSON -> array
    }
}


if (!function_exists('format_rupiah_to_number')) {
    function format_rupiah_to_number($input)
    {
        // Hilangkan titik pemisah ribuan
        $cleaned = str_replace('.', '', $input);

        // Ganti koma menjadi titik desimal
        $normalized = str_replace(',', '.', $cleaned);

        // Jika angka valid, proses lebih lanjut
        if (is_numeric($normalized)) {
            // Kalau desimalnya 0 atau .00 → jadikan integer
            if (fmod((float)$normalized, 1) == 0.0) {
                return (int)$normalized;
            } else {
                return (float)$normalized;
            }
        }

        // Kalau bukan angka valid, return null (atau false, terserah kamu)
        return null;
    }
}
if (!function_exists('hapus_62')) {
    function hapus_62($no_telp)
    {
        // Hapus semua yang bukan angka
        $no_telp = preg_replace('/[^0-9]/', '', $no_telp);

        // Jika diawali 62, ubah menjadi 0
        if (substr($no_telp, 0, 2) == '62') {
            $no_telp = '0' . substr($no_telp, 2);
        }
        return $no_telp;
    }
}
