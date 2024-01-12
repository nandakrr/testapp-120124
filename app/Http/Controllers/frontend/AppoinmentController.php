<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller; // ON live remove 
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Traits\CommonTrait;
use Validator;
use Illuminate\Support\Str;
use Session;

class AppoinmentController extends Controller
{
    public $priceCommonSection;

    
	public function swedishMassage()
    {
        return view('appointment.swedish-massage');
    }
	
	public function deepTissueMassage()
    {
        return view('appointment.deep-tissue-massage');
    }
	
	public function sportsMassage()
    {
        return view('appointment.sports-massage');
    }
	
	public function prenatalMassage()
    {
        return view('appointment.prenatal-massage');
    }
	
	public function reflexologyMassage()
    {
        return view('appointment.reflexology-massage');
    }
	
	public function lymphaticMassage()
    {
        return view('appointment.lymphatic-massage');
    }
	
	public function couplesMassage()
    {
        return view('appointment.couples-massage');
    }
	
	public function flowYoga()
    {
        return view('appointment.flow-yoga');
    }
	
	public function alignmentYoga()
    {
        return view('appointment.alignment-yoga');
    }
	
	public function restorativeYoga()
    {
        return view('appointment.restorative-yoga');
    }
	
	public function prenatalYoga()
    {
        return view('appointment.prenatal-yoga');
    }
	
	public function meditation()
    {
        return view('appointment.meditation');
    }
		
	
    public function inHomeMassage()
    {
        $this->priceCommonSection['deep_tissue'] = "Strong massage style with deep pressure to reduce muscle tension and promote relaxation"; 
        $this->priceCommonSection['swedish']     = "Kneading and circular movements with medium pressure for stress relief and reducing tension"; 
        $this->priceCommonSection['sports']      = "Uses deep tissue and stretching to improve flexibility, aid performance and muscle recovery"; 
        $this->priceCommonSection['prenatal']    = "Massage style that focuses on relieving tension and improving sleep during pregnancy"; 
        $this->priceCommonSection['reflexology'] = "Use of gentle pressure on specific points along your feet, and possibly hands and ears"; 
        $this->priceCommonSection['lymphatic']   = "Relieves swelling that happens when medical treatment or illness blocks your lymphatic system"; 
        $this->priceCommonSection['couples']   = "Celebrate your friendship or relationship with a couples massage. Two therapists at same time"; 


        $data['category'] = [
            ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'] ],
            ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'] ],
            ['title' => 'Sports','description' => $this->priceCommonSection['sports'] ],
            ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'] ],
            ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'] ],
            ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'] ], 
            ['title' => 'Couples','description' => $this->priceCommonSection['couples'] ],
        ];

        return view('appointment.in-home-massage',compact('data'));
    }
	
	
	public function privateYoga()
    {
        $this->priceCommonSection['flow'] = "Fluid movement through energetic postures matched with breath to create strength and focus"; 
        $this->priceCommonSection['alignment']     = "Focuses on precise way to do poses to maximize their benefits and minimize the risk of injury"; 
        $this->priceCommonSection['restorative']      = "Opens body through passive stretching using long holds to allow your muscles to relax deeply"; 
        $this->priceCommonSection['prenatal']    = "Poses for pregnant women to develop proper breathing and increase strength and flexibility"; 
        $this->priceCommonSection['meditation'] = "Nonjudgmental focusing of mind on an object or activity to achieve focus, mental stability and clarity"; 


        $data['category'] = [
            ['title' => 'Flow','description' => $this->priceCommonSection['flow'] ],
            ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'] ],
            ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'] ],
            ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'] ],
            ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'] ],
        ];

        return view('appointment.private-yoga',compact('data'));
    }
	

    public function mobileMassage()
    {
        return view('appointment.mobile-massage');
    }
}