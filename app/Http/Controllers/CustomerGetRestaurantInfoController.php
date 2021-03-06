<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\RestaurantTag;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;

class CustomerGetRestaurantInfoController extends Controller
{
    public function getAllRestaurant(Request $request){
        $restaurants = Restaurant::paginate(20);
        for($i = 0;$i<count($restaurants);$i++){
            $restaurants[$i]['tags'] = $restaurants[$i]->tags()->get();
        }
        $data = [
            "status" => 200,
            "method" => "getAllRestaurant",
            "message" => "success",
            "data"=> $restaurants
        ];
        return response()->json($data, 200);
    }
    public function getRestaurantByID(Request $request){
        $request->validate([
            'ID' => 'required|string',
        ]);
        $restaurants = Restaurant::where('id','=',$request->ID)->first();
        $restaurants['tags'] = $restaurants->tags()->get();

        $data = [
            "status" => 200,
            "method" => "getRestaurantByID",
            "message" => "success",
            "data"=> $restaurants
        ];
        return response()->json($data, 200);
    }
    public function getRestaurantByKeyword(Request $request){
        $request->validate([
            'Keyword' => 'required|string',
        ]);
        $restaurants = Restaurant::where('name','like','%'.$request->Keyword.'%')->get();
        for($i = 0;$i<count($restaurants);$i++){
            $restaurants[$i]['tags'] = $restaurants[$i]->tags()->get();
        }
        $data = [
            "status" => 200,
            "method" => "getRestaurantByKeyword",
            "message" => "success",
            "data"=> $restaurants
        ];
        return response()->json($data, 200);
    }
    public function getRestaurantByTag(Request $request){
        $request->validate([
            'tag_id' => 'required|int',
        ]);
        $tag= RestaurantTag::where('id','=',$request->tag_id)->first(); //單一的Tag模型
        $restaurants = $tag->restaurants()->get();
        for($i = 0;$i<count($restaurants);$i++){
            $restaurants[$i]['tags'] = $restaurants[$i]->tags()->get();
        }
        $data = [
            "status" => 200,
            "method" => "getRestaurantByTag",
            "message" => "success",
            "data" => $restaurants
        ];
        return response()->json($data, 200);
    }
    public function getAllDish(Request $request)
    {
        $customer = $request->user();
        $request->validate([
            'ID' => 'required|int',
        ]);
        $Dish = Dish::where('restaurant_id','=', $request->ID)->get();
        $data = [
            "status" => 200,
            "method" => "getAllDish",
            "message" => "success",
            "data" => $Dish
        ];
        return response()->json($data, 200);
    }
}
