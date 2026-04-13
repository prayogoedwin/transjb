<?php

use App\Helpers\NumberHelper;

if (!function_exists('formatIndonesia')) {
    /**
     * Format angka dengan format Indonesia
     * Pemisah ribuan: titik (.)
     * Pemisah desimal: koma (,)
     */
    function formatIndonesia($value, $decimals = 0): string
    {
        return NumberHelper::formatIndonesia($value, $decimals);
    }
}

if (!function_exists('formatIndonesiaSmartDecimal')) {
    /**
     * Format angka hanya menampilkan desimal jika ada pecahan
     * 
     * Contoh:
     * formatIndonesiaSmartDecimal(1000) => "1.000"
     * formatIndonesiaSmartDecimal(1000.50) => "1.000,5"
     */
    function formatIndonesiaSmartDecimal($value, $maxDecimals = 2): string
    {
        return NumberHelper::formatIndonesiaSmartDecimal($value, $maxDecimals);
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format harga dengan format Indonesia dan prefiks Rp
     */
    function formatCurrency($value, $decimals = 0): string
    {
        return NumberHelper::formatCurrency($value, $decimals);
    }
}

if (!function_exists('formatCurrencySmartDecimal')) {
    /**
     * Format harga hanya menampilkan desimal jika ada pecahan
     * 
     * Contoh:
     * formatCurrencySmartDecimal(10000) => "Rp 10.000"
     * formatCurrencySmartDecimal(10000.50) => "Rp 10.000,5"
     */
    function formatCurrencySmartDecimal($value, $maxDecimals = 0): string
    {
        return NumberHelper::formatCurrencySmartDecimal($value, $maxDecimals);
    }
}

if (!function_exists('formatDecimal')) {
    /**
     * Format desimal dengan format Indonesia
     */
    function formatDecimal($value, $decimals = 2): string
    {
        return NumberHelper::formatDecimal($value, $decimals);
    }
}

if (!function_exists('formatDecimalSmart')) {
    /**
     * Format desimal hanya menampilkan jika ada pecahan
     * 
     * Contoh:
     * formatDecimalSmart(1000) => "1.000"
     * formatDecimalSmart(1000.5) => "1.000,5"
     */
    function formatDecimalSmart($value, $maxDecimals = 2): string
    {
        return NumberHelper::formatDecimalSmart($value, $maxDecimals);
    }
}

if (!function_exists('formatRound')) {
    /**
     * Format angka dengan pembulatan: > 0.50 ke atas, <= 0.50 ke bawah
     * 
     * Contoh:
     * formatRound(1500.50) => "1.500"
     * formatRound(1500.51) => "1.501"
     */
    function formatRound($value): string
    {
        return NumberHelper::formatRound($value);
    }
}

if (!function_exists('formatCurrencyRound')) {
    /**
     * Format harga dengan pembulatan: > 0.50 ke atas, <= 0.50 ke bawah
     * 
     * Contoh:
     * formatCurrencyRound(1500.50) => "Rp 1.500"
     * formatCurrencyRound(1500.51) => "Rp 1.501"
     */
    function formatCurrencyRound($value): string
    {
        return NumberHelper::formatCurrencyRound($value);
    }
}

