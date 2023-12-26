<?php

namespace App\Imports;

use App\Models\Admin\Car;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CarsImport implements ToModel, WithHeadingRow, WithChunkReading
{

    public function model(array $row)
    {
        return new Car([
            'vendor_id' => $row['vendor'],
            'maker_id' => $row['maker'],
            'model_id' => $row['model'],
            'transmission_id' => $row['transmission'],
            'modelcode_id' => $row['modelcode'],
            'color_id' => $row['color'],
            'grade_id' => $row['grade'],
            'body_id' => $row['body'],
            'additional_feature_id' => $row['additional_feature'],
            'country_id' => $row['country'],
            'fuel_id' => $row['fuel_type'],
            'drive_id' => $row['drive'],
            'steering_id' => $row['steering'],
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
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
