<?php

namespace App\Imports;

use App\Models\Admin\Car;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class CarsImport implements ToModel, WithHeadingRow, WithChunkReading
{

    private $batch_id;

    public function __construct($batch_id)
    {
        $this->batch_id = $batch_id;
        // Log::info("File ID in constructor updated: $this->batch_id");
    }

    public function model(array $row)
    {
        return new Car([
            'batch_id' => $this->batch_id,
            'vendor_id' => find_records('vendors', $row['vendor'], 'vendor_name'),
            'maker_id' => find_maker_records($row['maker'], $row['vendor']),
            'model_id' => find_model_records($row['model'], $row['maker'], $row['vendor']),
            'modelcode_id' => find_modelcode_records($row['modelcode'], $row['model'], $row['maker']),

            'transmission_id' => find_records('transmissions', $row['transmission'], 'transmission'),
            'color_id' => find_records('colors', $row['color'], 'color'),
            'grade_id' => find_records('auction_grades', $row['grade'], 'auction_grade'),
            'body_id' => find_records('special_bodies', $row['body'], 'special_body'),
            'additional_feature_id' => find_records('additional_features', $row['additional_feature'], 'additional_feature'),
            'country_id' => find_records('countries', $row['country'], 'name'),
            'house_id' => find_records('auction_houses', $row['auction_house'], 'house_name'),

            'fuel_id' => get_fuel_id($row['fuel_type']),
            'drive_id' => get_drive_id($row['drive']),
            'steering_id' => get_steering_id($row['steering']),

            'car_name' => $row['car_name'],
            'reg_year' => $row['reg_year'],
            'mileage' => $row['mileage'],
            'cc' => $row['cc'],
            'lot_no' => $row['lot_no'],
            'auction_date' => $row['auction_date'],
            'chase_no' => $row['chase_no'],
            'doors' => $row['doors'],
            'seats' => $row['seats'],
            'dimension' => $row['dimension'],
            'auction_price' => $row['auction_price'],
            'model_grade' => $row['model_grade'],
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
