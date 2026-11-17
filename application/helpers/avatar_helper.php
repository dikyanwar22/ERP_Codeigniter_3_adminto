<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * URL foto profile user.
 *
 * Nilai kolom ci_users.foto bisa berupa:
 * - path relatif, contoh: assets/public/avatar/abc123.jpg
 * - nama file saja, contoh: abc123.jpg (diasumsikan ada di folder avatar)
 * - URL penuh (http/https)
 *
 * Jika file tidak ditemukan / kosong, gunakan avatar default.
 */
if ( ! function_exists('avatar_url')) {
    function avatar_url($foto = null, $default = 'assets/public/avatar/default.svg')
    {
        $foto = is_string($foto) ? trim($foto) : '';

        if ($foto !== '')
        {
            if (preg_match('#^https?://#i', $foto))
            {
                return $foto;
            }

            $foto = ltrim(str_replace('\\', '/', $foto), '/');

            if (strpos($foto, 'assets/') !== 0)
            {
                $foto = 'assets/public/avatar/'.$foto;
            }

            if (file_exists(FCPATH.$foto))
            {
                return base_url($foto);
            }
        }

        return base_url($default);
    }
}

/**
 * Path absolut file foto profile (untuk hapus file lama).
 * Mengembalikan NULL jika bukan file lokal di folder avatar.
 */
if ( ! function_exists('avatar_path')) {
    function avatar_path($foto = null)
    {
        $foto = is_string($foto) ? trim($foto) : '';
        if ($foto === '' OR preg_match('#^https?://#i', $foto))
        {
            return NULL;
        }

        $foto = ltrim(str_replace('\\', '/', $foto), '/');
        if (strpos($foto, 'assets/') !== 0)
        {
            $foto = 'assets/public/avatar/'.$foto;
        }

        $path = FCPATH.$foto;
        return file_exists($path) ? $path : NULL;
    }
}
