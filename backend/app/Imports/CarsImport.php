<?php

namespace App\Imports;

use App\Models\Admin\Car;
use App\Models\Admin\Vendor;
use App\Models\Admin\Maker;
use App\Models\Admin\CarModel;
use App\Models\Admin\ModelCode;
use App\Models\Admin\Transmission;
use App\Models\Admin\Color;
use App\Models\Admin\Auctiongrade;
use App\Models\Admin\Specialbody;
use App\Models\Admin\Additionalfeature;
use App\Models\Admin\Country;
use App\Models\Admin\Auctionhouse;

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
        $vendor_id = $this->getVendorId($row['vendor']);
        $maker_id = $this->getMakerId($row['maker'], $vendor_id);
        $model_id = $this->getModelId($row['model'], $maker_id, $vendor_id);
        $modelcode_id = $this->getModelCodeId($row['modelcode'], $model_id, $maker_id);

        $transmission_id = $this->getTransmissionId($row['transmission']);
        $color_id = $this->getColorId($row['color']);
        $grade_id = $this->getGradeId($row['grade']);
        $body_id = $this->getBodyId($row['body']);
        $additional_feature_id = $this->getFeatureId($row['additional_feature']);
        $country_id = $this->getCountryId($row['country']);
        $house_id = $this->getHouseId($row['auction_house']);

        $fuel_id =  $this->get_fuel_id($row['fuel_type']);
        $drive_id =  $this->get_drive_id($row['drive']);
        $steering_id =  $this->get_steering_id($row['steering']);

        return new Car([
            'batch_id' => $this->batch_id,
            'vendor_id' => $vendor_id,
            'maker_id' => $maker_id,
            'model_id' => $model_id,
            'modelcode_id' => $modelcode_id,

            'transmission_id' => $transmission_id,
            'color_id' => $color_id,
            'grade_id' => $grade_id,
            'body_id' => $body_id,
            'additional_feature_id' => implode(',', $additional_feature_id),
            'country_id' => $country_id,
            'house_id' => $house_id,

            'fuel_id' => $fuel_id,
            'drive_id' => $drive_id,
            'steering_id' => $steering_id,

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

    public function getVendorId($vendor_name)
    {
        $record = Vendor::firstOrNew(['vendor_name' => $vendor_name]);
        if (!$record->exists) {
            $record->save();
        }
        return $record->id;
    }
    public function getMakerId($maker_name, $vendor_id)
    {
        $record = Maker::where('maker_name', $maker_name)->first();
        if ($record !== null) {
            return $record->id;
        } else {
            $newRecord = Maker::create([
                'vendor_id' => $vendor_id,
                'maker_name' => $maker_name,
            ]);
            return $newRecord->id;
        }
    }
    public function getModelId($model, $maker_id, $vendor_id)
    {
        $record = CarModel::where('model', $model)->first();
        if ($record !== null) {
            return $record->id;
        } else {
            $newRecord = CarModel::create([
                'vendor_id' => $vendor_id,
                'maker_id' => $maker_id,
                'model' => $model,
            ]);
            return $newRecord->id;
        }
    }
    public function getModelCodeId($model_code, $model_id, $maker_id)
    {
        $record = ModelCode::where('model_code', $model_code)->first();
        if ($record !== null) {
            return $record->id;
        } else {
            $newRecord = ModelCode::create([
                'model_id' => $model_id,
                'maker_id' => $maker_id,
                'model_code' => $model_code,
            ]);
            return $newRecord->id;
        }
    }

    public function getTransmissionId($transmission)
    {
        $record = Transmission::where('transmission', $transmission)->first();
        if ($record !== null) {
            return $record->id;
        } else {
            $newRecord = Transmission::create([
                'transmission' => $transmission,
                'short_name' => $this->getFirstLetters($transmission),
            ]);
            return $newRecord->id;
        }
    }
    public function getColorId($color)
    {
        $record = Color::firstOrNew(['color' => $color]);
        if (!$record->exists) {
            $record->save();
        }
        return $record->id;
    }
    public function getGradeId($auction_grade)
    {
        $record = Auctiongrade::firstOrNew(['auction_grade' => $auction_grade]);
        if (!$record->exists) {
            $record->save();
        }
        return $record->id;
    }
    public function getBodyId($special_body)
    {
        $record = Specialbody::firstOrNew(['special_body' => $special_body]);
        if (!$record->exists) {
            $record->save();
        }
        return $record->id;
    }
    public function getFeatureId($additional_feature)
    {
        $featureNames = explode(',', $additional_feature);
        $additional_feature_id = [];
        foreach ($featureNames as $featureName) {
            $record = Additionalfeature::firstOrNew(['additional_feature' => trim($featureName)]);
            if (!$record->exists) {
                $record->save();
            }
            $additional_feature_id[] = $record->id;
        }
        return $additional_feature_id;
    }
    public function getCountryId($country_name)
    {
        $record = Country::firstOrNew(['name' => $country_name]);
        if (!$record->exists) {
            $record->save();
        }
        return $record->id;
    }
    public function getHouseId($house_name)
    {
        $record = Auctionhouse::firstOrNew(['house_name' => $house_name]);
        if (!$record->exists) {
            $record->save();
        }
        return $record->id;
    }
    public function get_fuel_id($value) {
        if($value == 'gasoline'){
            $fuel_id = 1;
        }else if($value == 'diesel'){
            $fuel_id = 2;
        }else if($value == 'hybrid'){
            $fuel_id = 3;
        }else if($value == 'electric'){
            $fuel_id = 4;
        }else if($value == 'other'){
            $fuel_id = 5;
        }else{
            $fuel_id = 1;
        }
        return $fuel_id;
    }
    public function get_drive_id($value) {
        if($value == '2wd'){
            $drive_id = 1;
        }else if($value == '4wd'){
            $drive_id = 2;
        }else{
            $drive_id = 1;
        }
        return $drive_id;
    }
    public function get_steering_id($value) {
        if($value == 'right'){
            $steering_id = 1;
        }else if($value == 'left'){
            $steering_id = 2;
        }else{
            $steering_id = 1;
        }
        return $steering_id;
    }
    public function getFirstLetters($inputString) {
        return implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', $inputString)));
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
