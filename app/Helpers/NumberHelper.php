<?php

namespace App\Helpers;

class NumberHelper
{
    /**
     * Format angka dengan format Indonesia
     * Pemisah ribuan: titik (.)
     * Pemisah desimal: koma (,)
     * 
     * @param float|int $value Nilai angka
     * @param int $decimals Jumlah desimal (default: 0)
     * @return string
     * 
     * Contoh:
     * formatIndonesia(1000) => "1.000"
     * formatIndonesia(1000.50) => "1.000,50"
     * formatIndonesia(1000.5, 2) => "1.000,50"
     */
    public static function formatIndonesia($value, $decimals = 0): string
    {
        return number_format($value, $decimals, ',', '.');
    }

    /**
     * Format angka hanya menampilkan desimal jika ada pecahan
     * Pemisah ribuan: titik (.)
     * Pemisah desimal: koma (,)
     * 
     * @param float|int $value Nilai angka
     * @param int $maxDecimals Jumlah desimal maksimal (default: 2)
     * @return string
     * 
     * Contoh:
     * formatIndonesiaSmartDecimal(1000) => "1.000"
     * formatIndonesiaSmartDecimal(1000.50) => "1.000,5"
     * formatIndonesiaSmartDecimal(1000.5, 2) => "1.000,5"
     * formatIndonesiaSmartDecimal(1000.123, 2) => "1.000,12"
     */
    public static function formatIndonesiaSmartDecimal($value, $maxDecimals = 2): string
    {
        // Cek apakah nilai memiliki pecahan
        if ($value == (int)$value) {
            // Tidak ada pecahan, tampilkan tanpa desimal
            return number_format($value, 0, ',', '.');
        }

        // Ada pecahan, tampilkan desimal dengan menghapus trailing zeros
        $formatted = number_format((float)$value, $maxDecimals, ',', '.');
        // Hapus trailing zeros setelah koma
        $formatted = preg_replace('/,?0+$/', '', $formatted);
        return $formatted;
    }

    /**
     * Format harga dengan format Indonesia dan prefiks Rp
     * 
     * @param float|int $value Nilai harga
     * @param int $decimals Jumlah desimal (default: 0)
     * @return string
     * 
     * Contoh:
     * formatCurrency(10000) => "Rp 10.000"
     * formatCurrency(10000.50, 2) => "Rp 10.000,50"
     */
    public static function formatCurrency($value, $decimals = 0): string
    {
        return 'Rp ' . self::formatIndonesia($value, $decimals);
    }

    /**
     * Format harga hanya menampilkan desimal jika ada pecahan
     * 
     * @param float|int $value Nilai harga
     * @param int $maxDecimals Jumlah desimal maksimal (default: 0)
     * @return string
     * 
     * Contoh:
     * formatCurrencySmartDecimal(10000) => "Rp 10.000"
     * formatCurrencySmartDecimal(10000.50, 0) => "Rp 10.000,5"
     * formatCurrencySmartDecimal(10000.50, 2) => "Rp 10.000,5"
     */
    public static function formatCurrencySmartDecimal($value, $maxDecimals = 0): string
    {
        return 'Rp ' . self::formatIndonesiaSmartDecimal($value, $maxDecimals);
    }

    /**
     * Format desimal dengan format Indonesia (untuk stok/satuan)
     * 
     * @param float $value Nilai desimal
     * @param int $decimals Jumlah desimal (default: 2)
     * @return string
     * 
     * Contoh:
     * formatDecimal(1000.5) => "1.000,50"
     * formatDecimal(100.25, 3) => "100,250"
     */
    public static function formatDecimal($value, $decimals = 2): string
    {
        return self::formatDecimalSmart($value, $decimals);
    }

    /**
     * Format desimal hanya menampilkan jika ada pecahan
     * 
     * @param float $value Nilai desimal
     * @param int $maxDecimals Jumlah desimal maksimal (default: 2)
     * @return string
     * 
     * Contoh:
     * formatDecimalSmart(1000) => "1.000"
     * formatDecimalSmart(1000.5) => "1.000,5"
     * formatDecimalSmart(1000.50, 2) => "1.000,5"
     */
    public static function formatDecimalSmart($value, $maxDecimals = 2): string
    {
        return self::formatIndonesiaSmartDecimal($value, $maxDecimals);
    }

    /**
     * Format angka dengan pembulatan: > 0.50 ke atas, <= 0.50 ke bawah
     * 
     * @param float|int $value Nilai angka
     * @return string
     * 
     * Contoh:
     * formatRound(1500.50) => "1.500"
     * formatRound(1500.51) => "1.501"
     * formatRound(1500.49) => "1.500"
     */
    public static function formatRound($value): string
    {
        // Ambil bagian desimal
        $decimal = $value - floor($value);
        
        // Jika desimal > 0.50, bulatkan ke atas, kalau tidak ke bawah
        if ($decimal > 0.50) {
            $rounded = ceil($value);
        } else {
            $rounded = floor($value);
        }
        
        return number_format($rounded, 0, ',', '.');
    }

    /**
     * Format harga dengan pembulatan: > 0.50 ke atas, <= 0.50 ke bawah
     * 
     * @param float|int $value Nilai harga
     * @return string
     * 
     * Contoh:
     * formatCurrencyRound(1500.50) => "Rp 1.500"
     * formatCurrencyRound(1500.51) => "Rp 1.501"
     */
    public static function formatCurrencyRound($value): string
    {
        return 'Rp ' . self::formatRound($value);
    }
}

