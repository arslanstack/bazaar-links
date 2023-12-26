<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Admin\Vendor;
use App\Models\Admin\Maker;
use App\Models\Admin\CarModel;
use App\Models\Admin\Transmission;
use App\Models\Admin\Car;
use App\Models\Admin\Color;
use App\Models\Admin\Auctiongrade;
use App\Models\Admin\Specialbody;
use App\Models\Admin\ModelCode;
use App\Models\Admin\Auctionhouse;
use App\Models\Admin\Country;
use App\Models\Admin\Seaport;
use App\Models\Admin\BiddingAuction;
use App\Models\Admin\Additionalfeature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{

    public function get_all_makers_bk(Request $request)
    {
        $data = Maker::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_all_makers(Request $request)
    {
        $data = Maker::leftJoin('cars', 'makers.id', '=', 'cars.maker_id')
        ->select('makers.id', 'makers.maker_name', 'makers.image_name', DB::raw('COUNT(cars.id) as car_count'))
        ->where('makers.status', 1)
        ->orderBy('makers.id', 'DESC')
        ->groupBy('makers.id', 'makers.maker_name', 'makers.image_name')
        ->get();
        $data->each(function ($maker) {
            $maker->append('image_path');
        });
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_all_additional_feature(Request $request)
    {
        $data = Additionalfeature::orderBy('id', 'DESC')->get();
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_latest_cars(Request $request)
    {
        $cars = Car::with(['maker_data', 'model_data', 'modelcode_data', 'transmission_data', 'color_data', 'grade_data', 'specialbody_data', 'country_data', 'car_images'])
        ->where('status', '1')
        ->where('is_sold', '0')
        ->where('is_deleted', '0')
        ->latest('id')
        ->take(10)
        ->get();

        $carList = $cars->map(function ($car) {
            $carData = [
                'id' => $car->id,
                'country_id' => $car->country_id,
                'vendor_id' => $car->vendor_id,
                'maker_id' => $car->maker_id,
                'model_id' => $car->model_id,
                'car_name' => $car->car_name,
                'reg_year' => $car->reg_year,
                'mileage' => $car->mileage,
                'cc' => $car->cc,
                'transmission_id' => $car->transmission_id,
                'color_id' => $car->color_id,
                'modelcode_id' => $car->modelcode_id,
                'grade_id' => $car->grade_id,
                'body_id' => $car->body_id,
                'lot_no' => $car->lot_no,
                'auction_date' => $car->auction_date,
                'fuel_id' => $car->fuel_id,
                'additional_feature_id' => $car->additional_feature_id,
                'feature_names' => carfeatures_data($car->additional_feature_id),
                'steering_id' => $car->steering_id,
                'chase_no' => $car->chase_no,
                'doors' => $car->doors,
                'seats' => $car->seats,
                'dimension' => $car->dimension,
                'auction_price' => $car->auction_price,
                'drive_id' => $car->drive_id,
                'detail' => $car->detail,
                'status' => $car->status,
                'created_at' => $car->created_at,
                'updated_at' => $car->updated_at,
                'car_name' => $car->car_name,
                'maker_name' => $car->maker_data->maker_name,
                'model' => $car->model_data->model,
                'model_code' => $car->modelcode_data->model_code,
                'transmission' => $car->transmission_data->transmission,
                'transmission_short' => $car->transmission_data->short_name,
                'color' => $car->color_data->color,
                'auction_grade' => $car->grade_data->auction_grade,
                'special_body' => $car->specialbody_data->special_body,
                'country_name' => $car->country_data->name,
            ];

            if ($car->car_images->isNotEmpty()) {
                $carData['image_path'] = $car->car_images->pluck('image_path');
            }
            return $carData;
        });

        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $carList));
    }

    public function get_top_cars_country(Request $request)
    {
        $data = Country::select('countries.name', 'countries.image_name', 'cars.country_id', DB::raw('COUNT(country_id) as car_count'))
        ->leftJoin('cars', 'cars.country_id', '=', 'countries.id')
        ->where('countries.status', 1)
        ->groupBy('cars.country_id', 'countries.name', 'countries.image_name')
        ->orderByDesc('car_count')
        ->limit(6)
        ->get();
        $data->each(function ($country) {
            $country->append('image_path');
        });
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_bodytypes_bk(Request $request)
    {
        $data = Specialbody::where('status', 1)->orderBy('id', 'DESC')->get();
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_bodytypes()
    {
        $data = Specialbody::select('special_bodies.id', 'special_bodies.image_name', 'special_bodies.icon_name', 'special_bodies.special_body', DB::raw('COUNT(cars.id) as car_count'))
        ->leftJoin('cars', 'special_bodies.id', '=', 'cars.body_id')
        ->where('special_bodies.status', 1)
        ->orderBy('special_bodies.id', 'DESC')
        ->groupBy('special_bodies.id', 'special_bodies.special_body', 'special_bodies.image_name', 'special_bodies.icon_name')
        ->get();
        $data->each(function ($bodytype) {
            $bodytype->append('image_path', 'icon_path');
        });
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_total_users(Request $request)
    {
        $data = User::count();
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_total_happy_users(Request $request)
    {
        $data = User::where('status', 1)->count();
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_total_cars_sale(Request $request)
    {
        $data = Car::where('is_sold', '1')->count();
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_total_cars(Request $request)
    {
        $data = Car::where('is_deleted', '0')->count();
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_all_cars(Request $request)
    {
        $query = Car::with(['maker_data', 'model_data', 'modelcode_data', 'country_data', 'transmission_data', 'car_images']);
        $query->where('status', '1');
        $query->where('is_sold', '0');
        $query->where('is_deleted', '0');
        // dd($query->toSql());
        $perPage = $request->input('per_page', 20);
        $results = $query->paginate($perPage);
        $totalRecords = $results->total();
        $carList = $results->map(function ($car) {
            $carData = [
                'id' => $car->id,
                'model_grade' => $car->model_grade,
                'maker_name' => $car->maker_data->maker_name,
                'model' => $car->model_data->model,
                'model_code' => $car->modelcode_data->model_code,
                'country_name' => $car->country_data->name,
                'transmission' => $car->transmission_data->transmission,
                'transmission_short' => $car->transmission_data->short_name,
                'car_name' => $car->car_name,
                'lot_no' => $car->lot_no,
                'country_id' => $car->country_id,
                'vendor_id' => $car->vendor_id,
                'maker_id' => $car->maker_id,
                'model_id' => $car->model_id,
                'reg_year' => $car->reg_year,
                'mileage' => $car->mileage,
                'cc' => $car->cc,
                'transmission_id' => $car->transmission_id,
                'color_id' => $car->color_id,
                'modelcode_id' => $car->modelcode_id,
                'grade_id' => $car->grade_id,
                'body_id' => $car->body_id,
                'auction_date' => $car->auction_date,
                'fuel_id' => $car->fuel_id,
                'additional_feature_id' => $car->additional_feature_id,
                'feature_names' => carfeatures_data($car->additional_feature_id),
                'steering_id' => $car->steering_id,
                'chase_no' => $car->chase_no,
                'doors' => $car->doors,
                'seats' => $car->seats,
                'dimension' => $car->dimension,
                'auction_price' => $car->auction_price,
                'drive_id' => $car->drive_id,
                'status' => $car->status,
                'created_at' => $car->created_at,
            ];
            if ($car->car_images->isNotEmpty()) {
                $carData['image_path'] = $car->car_images->pluck('image_path');
            }
            return $carData;
        });
        return response()->json(['msg' => 'success', 'response' => 'success', 'totalRecords' => $totalRecords, 'data' => $carList]);
    }

    public function get_latest_sold_cars(Request $request)
    {
        $cars = Car::with(['maker_data', 'model_data', 'modelcode_data', 'transmission_data', 'color_data', 'grade_data', 'specialbody_data', 'country_data', 'car_images'])
        ->where('status', '1')
        ->where('is_sold', '1')
        ->latest('sold_at')
        ->take(10)
        ->get();

        $carList = $cars->map(function ($car) {
            $carData = [
                'id' => $car->id,
                'country_id' => $car->country_id,
                'vendor_id' => $car->vendor_id,
                'maker_id' => $car->maker_id,
                'model_id' => $car->model_id,
                'car_name' => $car->car_name,
                'reg_year' => $car->reg_year,
                'mileage' => $car->mileage,
                'cc' => $car->cc,
                'transmission_id' => $car->transmission_id,
                'color_id' => $car->color_id,
                'modelcode_id' => $car->modelcode_id,
                'grade_id' => $car->grade_id,
                'body_id' => $car->body_id,
                'lot_no' => $car->lot_no,
                'auction_date' => $car->auction_date,
                'fuel_id' => $car->fuel_id,
                'additional_feature_id' => $car->additional_feature_id,
                'feature_names' => carfeatures_data($car->additional_feature_id),
                'steering_id' => $car->steering_id,
                'chase_no' => $car->chase_no,
                'doors' => $car->doors,
                'seats' => $car->seats,
                'dimension' => $car->dimension,
                'auction_price' => $car->auction_price,
                'sold_price' => get_sold_price($car->id),
                'drive_id' => $car->drive_id,
                'detail' => $car->detail,
                'status' => $car->status,
                'created_at' => $car->created_at,
                'updated_at' => $car->updated_at,
                'car_name' => $car->car_name,
                'maker_name' => $car->maker_data->maker_name,
                'model' => $car->model_data->model,
                'model_code' => $car->modelcode_data->model_code,
                'transmission' => $car->transmission_data->transmission,
                'transmission_short' => $car->transmission_data->short_name,
                'color' => $car->color_data->color,
                'auction_grade' => $car->grade_data->auction_grade,
                'special_body' => $car->specialbody_data->special_body,
                'country_name' => $car->country_data->name,
            ];

            if ($car->car_images->isNotEmpty()) {
                $carData['image_path'] = $car->car_images->pluck('image_path');
            }
            return $carData;
        });

        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $carList));
    }

    public function get_upcomming_cars(Request $request)
    {
        $cars = Car::with(['maker_data', 'model_data', 'modelcode_data', 'transmission_data', 'color_data', 'grade_data', 'specialbody_data', 'country_data', 'car_images'])
        ->where('status', '1')
        ->where('is_sold', '0')
        ->where('is_deleted', '0')
        ->where('auction_date', '>', Carbon::now())
        ->orderBy('auction_date')
        ->take(10)
        ->get();

        $carList = $cars->map(function ($car) {
            $carData = [
                'id' => $car->id,
                'country_id' => $car->country_id,
                'vendor_id' => $car->vendor_id,
                'maker_id' => $car->maker_id,
                'model_id' => $car->model_id,
                'car_name' => $car->car_name,
                'reg_year' => $car->reg_year,
                'mileage' => $car->mileage,
                'cc' => $car->cc,
                'transmission_id' => $car->transmission_id,
                'color_id' => $car->color_id,
                'modelcode_id' => $car->modelcode_id,
                'grade_id' => $car->grade_id,
                'body_id' => $car->body_id,
                'lot_no' => $car->lot_no,
                'auction_date' => $car->auction_date,
                'fuel_id' => $car->fuel_id,
                'additional_feature_id' => $car->additional_feature_id,
                'feature_names' => carfeatures_data($car->additional_feature_id),
                'steering_id' => $car->steering_id,
                'chase_no' => $car->chase_no,
                'doors' => $car->doors,
                'seats' => $car->seats,
                'dimension' => $car->dimension,
                'auction_price' => $car->auction_price,
                'drive_id' => $car->drive_id,
                'detail' => $car->detail,
                'status' => $car->status,
                'created_at' => $car->created_at,
                'updated_at' => $car->updated_at,
                'car_name' => $car->car_name,
                'maker_name' => $car->maker_data->maker_name,
                'model' => $car->model_data->model,
                'model_code' => $car->modelcode_data->model_code,
                'transmission' => $car->transmission_data->transmission,
                'transmission_short' => $car->transmission_data->short_name,
                'color' => $car->color_data->color,
                'auction_grade' => $car->grade_data->auction_grade,
                'special_body' => $car->specialbody_data->special_body,
                'country_name' => $car->country_data->name,
            ];

            if ($car->car_images->isNotEmpty()) {
                $carData['image_path'] = $car->car_images->pluck('image_path');
            }
            return $carData;
        });

        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $carList));
    }

    public function get_countries(Request $request)
    {
        $data = Country::where('status', 1)->orderBy('name', 'ASC')->get();
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

    public function get_country_seaports($county_id)
    {
        $data = Seaport::where('status', 1)->where('country_id', $county_id)->orderBy('sea_port', 'ASC')->get();
        return response()->json(array('msg' => 'success', 'response' => 'success', 'data' => $data));
    }

}
