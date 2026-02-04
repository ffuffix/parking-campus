<?php 

namespace App\Services;

use Illuminate\Support\Str;

class LicensePlateGenerator
{
    public function generate(string $format = '???-####'): string
    {
        return preg_replace_callback('/[?#*]/', function ($match) {
            return match ($match[0]) {
                '?' =>  chr(rand(65, 90)), // Random A-Z
                '#' =>  rand(0, 9), // Random 0-9
                '*' =>  rand(0, 1) ? chr(rand(65, 90)) : rand(0, 9), // Mixed
                default => $match[0],
            };
        }, $format);
    }

    public function generateBulk(int $quantity, string $format = '???-####'): array
    {
        $plates = [];
        
        while (count($plates) < $quantity) {
            $plate = $this->generate($format);
            $plates[$plate] = true;
        }

        return array_keys($plates);
    }
}