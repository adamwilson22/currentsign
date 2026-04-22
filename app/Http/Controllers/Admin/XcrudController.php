<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class XcrudController extends Controller {

    public function users() {
        return view('xcrud.users');
    }
    
    
    
    
    
/*******************************************/    
    
public function car_list() {
    return view('xcrud.cars.car_list');
}

public function car_classes() {
    return view('xcrud.cars.car_classes');
}

public function car_makers() {
    return view('xcrud.cars.car_makers');
}

public function car_models() {
    return view('xcrud.cars.car_models');
}

public function car_years() {
    return view('xcrud.cars.car_years');
}


public function car_body_colors() {
    return view('xcrud.cars.car_body_colors');
}

public function car_extra_features() {
    return view('xcrud.cars.car_extra_features');
}

public function car_no_of_seats() {
    return view('xcrud.cars.car_no_of_seats');
}

public function car_rim_sizes() {
    return view('xcrud.cars.car_rim_sizes');
}

public function car_types() {
    return view('xcrud.cars.car_types');
}

public function car_plate_sources() {
    return view('xcrud.cars.car_plate_sources');
}

public function car_plate_codes() {
    return view('xcrud.cars.car_plate_codes');
}

public function car_plate_designs() {
    return view('xcrud.cars.car_plate_designs');
}


public function car_regional_specs() {
    return view('xcrud.cars.car_regional_specs');
}
    
    
public function car_service_history() {
    return view('xcrud.cars.car_service_history');
}    
    
public function car_horse_powers() {
    return view('xcrud.cars.car_horse_powers');
}    
    
public function car_drive_types() {
    return view('xcrud.cars.car_drive_types');
} 
    
    
public function car_seats() {
    return view('xcrud.cars.car_seats');
} 
public function car_additional_details() {
    return view('xcrud.cars.car_additional_details');
} 
 
public function car_interior_colors() {
    return view('xcrud.cars.car_interior_colors');
} 

public function car_cities() {
    return view('xcrud.cars.car_cities');
} 
 
  

 
/*******************************************/    

    
    
    
    
    
 
/*******************************************/    
    
public function motorbike_list() {
    return view('xcrud.motorbikes.motorbike_list');
}

public function motorbike_classes() {
    return view('xcrud.motorbikes.motorbike_classes');
}

public function motorbike_makers() {
    return view('xcrud.motorbikes.motorbike_makers');
}

public function motorbike_models() {
    return view('xcrud.motorbikes.motorbike_models');
}

public function motorbike_years() {
    return view('xcrud.motorbikes.motorbike_years');
}


public function motorbike_body_colors() {
    return view('xcrud.motorbikes.motorbike_body_colors');
}

public function motorbike_extra_features() {
    return view('xcrud.motorbikes.motorbike_extra_features');
}

public function motorbike_no_of_seats() {
    return view('xcrud.motorbikes.motorbike_no_of_seats');
}

public function motorbike_rim_sizes() {
    return view('xcrud.motorbikes.motorbike_rim_sizes');
}

public function motorbike_types() {
    return view('xcrud.motorbikes.motorbike_types');
}

public function motorbike_plate_sources() {
    return view('xcrud.motorbikes.motorbike_plate_sources');
}

public function motorbike_plate_codes() {
    return view('xcrud.motorbikes.motorbike_plate_codes');
}

public function motorbike_plate_designs() {
    return view('xcrud.motorbikes.motorbike_plate_designs');
}


public function motorbike_regional_specs() {
    return view('xcrud.motorbikes.motorbike_regional_specs');
}
    
    
public function motorbike_service_history() {
    return view('xcrud.motorbikes.motorbike_service_history');
}    
    
public function motorbike_horse_powers() {
    return view('xcrud.motorbikes.motorbike_horse_powers');
}    
    
public function motorbike_drive_types() {
    return view('xcrud.motorbikes.motorbike_drive_types');
} 
    
    
public function motorbike_seats() {
    return view('xcrud.motorbikes.motorbike_seats');
} 
public function motorbike_additional_details() {
    return view('xcrud.motorbikes.motorbike_additional_details');
} 
 
 
public function motorbike_interior_colors() {
    return view('xcrud.motorbikes.motorbike_interior_colors');
} 


public function motorbike_cities() {
    return view('xcrud.motorbikes.motorbike_cities');
} 



 
/*******************************************/    









/*******************************************/    
    
public function trucks_and_heavy_vehicle_list() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_list');
}

public function trucks_and_heavy_vehicle_classes() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_classes');
}

public function trucks_and_heavy_vehicle_makers() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_makers');
}

public function trucks_and_heavy_vehicle_models() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_models');
}

public function trucks_and_heavy_vehicle_years() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_years');
}


public function trucks_and_heavy_vehicle_body_colors() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_body_colors');
}

public function trucks_and_heavy_vehicle_extra_features() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_extra_features');
}

public function trucks_and_heavy_vehicle_no_of_seats() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_no_of_seats');
}

public function trucks_and_heavy_vehicle_rim_sizes() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_rim_sizes');
}

public function trucks_and_heavy_vehicle_types() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_types');
}

public function trucks_and_heavy_vehicle_plate_sources() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_plate_sources');
}

public function trucks_and_heavy_vehicle_plate_codes() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_plate_codes');
}

public function trucks_and_heavy_vehicle_plate_designs() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_plate_designs');
}


public function trucks_and_heavy_vehicle_regional_specs() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_regional_specs');
}
    
    
public function trucks_and_heavy_vehicle_service_history() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_service_history');
}    
    
public function trucks_and_heavy_vehicle_horse_powers() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_horse_powers');
}    
    
public function trucks_and_heavy_vehicle_drive_types() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_drive_types');
} 
    
    
public function trucks_and_heavy_vehicle_seats() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_seats');
} 
public function trucks_and_heavy_vehicle_additional_details() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_additional_details');
} 

public function trucks_and_heavy_vehicle_interior_colors() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_interior_colors');
} 

 
public function trucks_and_heavy_vehicle_cities() {
    return view('xcrud.trucks_and_heavy_vehicles.trucks_and_heavy_vehicle_cities');
}  
 
/*******************************************/    


public function auto_accessories_parts_type() {
    return view('xcrud.auto_accessories_parts.auto_accessories_parts_type');
} 

public function auto_accessories_parts_city() {
    return view('xcrud.auto_accessories_parts.auto_accessories_parts_city');
} 
public function auto_accessories_parts_sub_category() {
    return view('xcrud.auto_accessories_parts.auto_accessories_parts_sub_category');
} 
public function auto_accessories_parts_condition() {
    return view('xcrud.auto_accessories_parts.auto_accessories_parts_condition');
} 
public function auto_accessories_parts_age() {
    return view('xcrud.auto_accessories_parts.auto_accessories_parts_age');
} 
/*******************************************/    



public function boat_yacht_types() {
    return view('xcrud.boats_yachts.boat_yacht_types');
} 
public function boat_yacht_cities() {
    return view('xcrud.boats_yachts.boat_yacht_cities');
} 
public function boat_yacht_makers() {
    return view('xcrud.boats_yachts.boat_yacht_makers');
} 
public function boat_yacht_conditions() {
    return view('xcrud.boats_yachts.boat_yacht_conditions');
} 
public function boat_yacht_age() {
    return view('xcrud.boats_yachts.boat_yacht_age');
} 
/*******************************************/    









public function number_plate_city() {
    return view('xcrud.numbers_plates.number_plate_city');
} 
public function number_plate_type() {
    return view('xcrud.numbers_plates.number_plate_type');
} 
public function number_plate_sources() {
    return view('xcrud.numbers_plates.number_plate_sources');
} 
public function number_plate_codes() {
    return view('xcrud.numbers_plates.number_plate_codes');
} 
public function number_plate_designs() {
    return view('xcrud.numbers_plates.number_plate_designs');
} 
/*******************************************/    








    public function category() {
        return view('xcrud.category');

    } 

    public function sub_category() {
        return view('xcrud.sub_categories');

    } 

    public function cities() {
        return view('xcrud.cities');

    } 
    
    public function order_list() {
        return view('xcrud.order_list');

    }     

    public function packages() {
        return view('xcrud.packages');

    }   


    public function test_drive() {
        return view('xcrud.test_drive');

    }   
    
    
    public function advertisements() {
        return view('xcrud.advertisements');

    }       
    

    public function get_model_by_id() {
        
        $id = $_REQUEST['id'];
        $data = \DB::select("SELECT * FROM `cars_models` WHERE `car_model_car_maker_id` = '$id'");
        
        $option = "";
        foreach($data as $data_in){
            $option .= "<option value='".$data_in->car_model_id."' >".$data_in->car_model_name."</option>";
        }
        
        echo $option;
        die;
    }   



}
