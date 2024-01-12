<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;
use Session;

class FrontMassageController extends Controller
{
    protected $allCities,$contentByCities,$priceCommonSection;
    
    function __construct()
    {
        // When new city come : add new array "FUNCTION_NAME" => "CITY NAME" THEN create new function with same name 

 //       $this->allCities = ["massage-los-angeles" => "Los Angeles","massage-new-york" => "New York","massage-miami" => "Miami","massage-houston" => "Houston","massage-las-vegas" => "Las Vegas","massage-san-francisco" => "San Francisco","massage-san-diego" => "San Diego","massage-phoenix" => "Phoenix","massage-dallas" => "Dallas","massage-austin" => "Austin", "massage-washington-dc" => "Washington DC","massage-tampa-fl" => "Tampa","massage-albuquerque-nm" => "Albuquerque","massage-arlington-tx" => "Arlington","massage-aspen-co" => "Aspen","massage-atlanta-ga" => "Atlanta","massage-baltimore-md" => "Baltimore","massage-boston-ma" => "Boston","massage-charlotte-nc" => "Charlotte","massage-chicago-il" => "Chicago","massage-cleveland-oh" => "Cleveland","massage-colorado-springs-co" => "Colorado Springs","massage-columbus-oh" => "Columbus","massage-denver-co" => "Denver","massage-detroit-mi" => "Detroit","massage-el-paso-tx" => "El Paso","massage-fort-worth-tx" => "Fort Worth","massage-fresno-ca" => "Fresno","massage-hollywood-fl" => "Hollywood","massage-honolulu-hi" => "Honolulu","massage-indianapolis-in" => "Indianapolis","massage-jacksonville-fl" => "Jacksonville","massage-kansas-city-mo" => "Kansas City","massage-long-beach-ca" => "Long Beach","massage-louisville-ky" => "Louisville","massage-memphis-tn" => "Memphis","massage-monterey-bay-ca" => "Monterey Bay","massage-mesa-az" => "Mesa","massage-milwaukee-wi" => "Milwaukee","massage-nashville-tn" => "Nashville","massage-newark-nj" => "Newark","massage-oakland-ca" => "Oakland","massage-oklahoma-city-ok" => "Oklahoma City","massage-omaha-ne" => "Omaha","massage-orlando-fl" => "Orlando","massage-philadelphia-pa" => "Philadelphia","massage-portland-or" => "Portland","massage-raleigh-nc" => "Raleigh","massage-sacramento-ca" => "Sacramento","massage-san-antonio-tx" => "San Antonio","massage-san-jose-ca" => "San Jose","massage-sarasota-fl" => "Sarasota","massage-scottsdale-az" => "Scottsdale","massage-seattle-wa" => "Seattle","massage-st-petersburg-fl" => "St. Petersburg","massage-tucson-az" => "Tucson","massage-tulsa-ok" => "Tulsa","massage-virginia-beach-va" => "Virginia Beach","massage-manhattan-ny" => "Manhattan","massage-bronx-ny" => "Bronx","massage-brooklyn-ny" => "Brooklyn","massage-queens-ny" => "Queens","massage-hialeah-fl" => "Hialeah","massage-bakersfield-ca" => "Bakersfield","massage-anaheim-ca" => "Anaheim","massage-irvine-ca" => "Irvine","massage-chula-vista-ca" => "Chula Vista","massage-fremont-ca" => "Fremont","massage-san-bernardino-ca" => "San Bernardino","massage-santa-clarita-ca" => "Santa Clarita","massage-huntington-beach-ca" => "Huntington Beach","massage-corona-ca" => "Corona","massage-cutler-bay-fl" => "Cutler Bay","massage-miami-beach-fl" => "Miami Beach","massage-south-beach-fl" => "South Beach","massage-tempe-az" => "Tempe","massage-glendale-az" => "Glendale","massage-peoria-az" => "Peoria","massage-glendale-ca" => "Glendale","massage-jersey-city-nj" => "Jersey City","massage-berkeley-ca" => "Berkeley","massage-santa-clara-ca" => "Santa Clara","massage-irving-tx" => "Irving","massage-aurora-co" => "Aurora","massage-cypress-tx" => "Cypress","massage-sunnyvale-ca" => "Sunnyvale","massage-minneapolis-mn" => "Minneapolis","massage-pittsburgh-pa" => "Pittsburgh","massage-durham-nc" => "Durham","massage-plano-tx" => "Plano","massage-reno-nv" => "Reno","massage-buffalo-ny" => "Buffalo","massage-frisco-tx" => "Frisco","massage-richmond-va" => "Richmond","massage-lancaster-ca" => "Lancaster","massage-augusta-ga" => "Augusta","massage-new-orleans-la" => "New Orleans","massage-alameda-ca" => "Alameda","massage-san-mateo-ca" => "San Mateo","massage-santa-cruz-ca" => "Santa Cruz","massage-santa-barbara-ca" => "Santa Barbara","massage-hoboken-nj" => "Hoboken","massage-westchester-ny" => "Westchester","massage-long-island-ny" => "Long Island","massage-walnut-creek-ca" => "Walnut Creek","massage-escondido-ca" => "Escondido"];
		$this->allCities = ["massage-los-angeles" => "Los Angeles","massage-new-york" => "New York","massage-miami" => "Miami","massage-houston" => "Houston","massage-las-vegas" => "Las Vegas","massage-san-francisco" => "San Francisco","massage-san-diego" => "San Diego","massage-phoenix" => "Phoenix","massage-dallas" => "Dallas","massage-austin" => "Austin", "massage-washington-dc" => "Washington DC","massage-tampa-fl" => "Tampa","massage-albuquerque-nm" => "Albuquerque","massage-arlington-tx" => "Arlington","massage-aspen-co" => "Aspen","massage-atlanta-ga" => "Atlanta","massage-baltimore-md" => "Baltimore","massage-boston-ma" => "Boston","massage-charlotte-nc" => "Charlotte","massage-chicago-il" => "Chicago","massage-cleveland-oh" => "Cleveland","massage-colorado-springs-co" => "Colorado Springs","massage-columbus-oh" => "Columbus","massage-denver-co" => "Denver","massage-detroit-mi" => "Detroit","massage-el-paso-tx" => "El Paso","massage-fort-worth-tx" => "Fort Worth","massage-fresno-ca" => "Fresno","massage-hollywood-fl" => "Hollywood","massage-honolulu-hi" => "Honolulu","massage-indianapolis-in" => "Indianapolis","massage-jacksonville-fl" => "Jacksonville","massage-kansas-city-mo" => "Kansas City","massage-long-beach-ca" => "Long Beach","massage-louisville-ky" => "Louisville","massage-memphis-tn" => "Memphis","massage-monterey-bay-ca" => "Monterey Bay","massage-mesa-az" => "Mesa","massage-milwaukee-wi" => "Milwaukee","massage-nashville-tn" => "Nashville","massage-newark-nj" => "Newark","massage-oakland-ca" => "Oakland","massage-oklahoma-city-ok" => "Oklahoma City","massage-omaha-ne" => "Omaha","massage-orlando-fl" => "Orlando","massage-philadelphia-pa" => "Philadelphia","massage-portland-or" => "Portland","massage-raleigh-nc" => "Raleigh","massage-sacramento-ca" => "Sacramento","massage-san-antonio-tx" => "San Antonio","massage-san-jose-ca" => "San Jose","massage-sarasota-fl" => "Sarasota","massage-scottsdale-az" => "Scottsdale","massage-seattle-wa" => "Seattle","massage-stpetersburg-fl" => "St. Petersburg","massage-tucson-az" => "Tucson","massage-tulsa-ok" => "Tulsa","massage-virginia-beach-va" => "Virginia Beach","massage-manhattan-ny" => "Manhattan","massage-bronx-ny" => "Bronx","massage-brooklyn-ny" => "Brooklyn","massage-queens-ny" => "Queens","massage-hialeah-fl" => "Hialeah","massage-bakersfield-ca" => "Bakersfield","massage-anaheim-ca" => "Anaheim","massage-irvine-ca" => "Irvine","massage-chula-vista-ca" => "Chula Vista","massage-fremont-ca" => "Fremont","massage-san-bernardino-ca" => "San Bernardino","massage-santa-clarita-ca" => "Santa Clarita","massage-huntington-beach-ca" => "Huntington Beach","massage-corona-ca" => "Corona","massage-cutler-bay-fl" => "Cutler Bay","massage-miami-beach-fl" => "Miami Beach","massage-south-beach-fl" => "South Beach","massage-tempe-az" => "Tempe","massage-glendale-az" => "Glendale","massage-peoria-az" => "Peoria","massage-glendale-ca" => "Glendale","massage-jersey-city-nj" => "Jersey City","massage-berkeley-ca" => "Berkeley","massage-santa-clara-ca" => "Santa Clara","massage-irving-tx" => "Irving","massage-aurora-co" => "Aurora","massage-cypress-tx" => "Cypress","massage-sunnyvale-ca" => "Sunnyvale","massage-minneapolis-mn" => "Minneapolis","massage-pittsburgh-pa" => "Pittsburgh","massage-durham-nc" => "Durham","massage-plano-tx" => "Plano","massage-reno-nv" => "Reno","massage-buffalo-ny" => "Buffalo","massage-frisco-tx" => "Frisco","massage-richmond-va" => "Richmond","massage-lancaster-ca" => "Lancaster","massage-augusta-ga" => "Augusta","massage-new-orleans-la" => "New Orleans","massage-alameda-ca" => "Alameda","massage-san-mateo-ca" => "San Mateo","massage-santa-cruz-ca" => "Santa Cruz","massage-santa-barbara-ca" => "Santa Barbara","massage-hoboken-nj" => "Hoboken","massage-westchester-ny" => "Westchester","massage-long-island-ny" => "Long Island","massage-walnut-creek-ca" => "Walnut Creek","massage-escondido-ca" => "Escondido","massage-sugar-land-tx" => "Sugar Land","massage-fort-lauderdale-fl" => "Fort Lauderdale","massage-oceanside-ca" => "Oceanside","massage-chandler-az" => "Chandler","massage-kissimmee-fl" => "Kissimmee","massage-lakeland-fl" => "Lakeland","massage-dublin-ca" => "Dublin","massage-gilbert-az" => "Gilbert","massage-garland-tx" => "Garland","massage-mckinney-tx" => "McKinney"];
        
 //       $this->contentByCities = ["massage-los-angeles" => "contentLosAngeles","massage-new-york" => "contentNewYork","massage-miami" => "contentMiami","massage-houston" => "contentHouston","massage-las-vegas" => "contentLasVegas","massage-san-francisco" => "contentSanFrancisco","massage-san-diego" => "contentSanDiego","massage-phoenix" => "contentPhoenix","massage-dallas" => "contentDallas","massage-austin" => "contentAustin","massage-washington-dc" => "contentWashingtonDC","massage-tampa-fl" => "contentTampaFL","massage-albuquerque-nm" => "contentAlbuquerqueNM","massage-arlington-tx" => "contentArlingtonTX","massage-aspen-co" => "contentAspenCO","massage-atlanta-ga" => "contentAtlantaGA","massage-baltimore-md" => "contentBaltimoreMD","massage-boston-ma" => "contentBostonMA","massage-charlotte-nc" => "contentCharlotteNC","massage-chicago-il" => "contentChicagoIL","massage-cleveland-oh" => "contentClevelandOH","massage-colorado-springs-co" => "contentColoradoSpringsCO","massage-columbus-oh" => "contentColumbusOH","massage-denver-co" => "contentDenverCO","massage-detroit-mi" => "contentDetroitMI","massage-el-paso-tx" => "contentElPasoTX","massage-fort-worth-tx" => "contentFortWorthTX","massage-fresno-ca" => "contentFresnoCA","massage-hollywood-fl" => "contentHollywoodFL","massage-honolulu-hi" => "contentHonoluluHI","massage-indianapolis-in" => "contentIndianapolisIN","massage-jacksonville-fl" => "contentJacksonvilleFL","massage-kansas-city-mo" => "contentKansasCityMO","massage-long-beach-ca" => "contentLongBeachCA","massage-louisville-ky" => "contentLouisvilleKY","massage-memphis-tn" => "contentMemphisTN","massage-monterey-bay-ca" => "contentMontereyBayCA","massage-mesa-az" => "contentMesaAZ","massage-milwaukee-wi" => "contentMilwaukeeWI","massage-nashville-tn" => "contentNashvilleTN","massage-newark-nj" => "contentNewarkNJ","massage-oakland-ca" => "contentOaklandCA","massage-oklahoma-city-ok" => "contentOklahomaCityOK","massage-omaha-ne" => "contentOmahaNE","massage-orlando-fl" => "contentOrlandoFL","massage-philadelphia-pa" => "contentPhiladelphiaPA","massage-portland-or" => "contentPortlandOR","massage-raleigh-nc" => "contentRaleighNC","massage-sacramento-ca" => "contentSacramentoCA","massage-san-antonio-tx" => "contentSanAntonioTX","massage-san-jose-ca" => "contentSanJoseCA","massage-sarasota-fl" => "contentSarasotaFL","massage-scottsdale-az" => "contentScottsdaleAZ","massage-seattle-wa" => "contentSeattleWA","massage-st-petersburg-fl" => "contentStpetersburgFL","massage-tucson-az" => "contentTucsonAZ","massage-tulsa-ok" => "contentTulsaOK","massage-virginia-beach-va" => "contentVirginiaBeachVA","massage-manhattan-ny" => "contentManhattanNY","massage-bronx-ny" => "contentBronxNY","massage-brooklyn-ny" => "contentBrooklynNY","massage-queens-ny" => "contentQueensNY","massage-hialeah-fl" => "contentHialeahFL","massage-bakersfield-ca" => "contentBakersfieldCA","massage-anaheim-ca" => "contentAnaheimCA","massage-irvine-ca" => "contentIrvineCA","massage-chula-vista-ca" => "contentChulaVistaCA","massage-fremont-ca" => "contentFremontCA","massage-san-bernardino-ca" => "contentSanBernardinoCA","massage-santa-clarita-ca" => "contentSantaClaritaCA","massage-huntington-beach-ca" => "contentHuntingtonBeachCA","massage-corona-ca" => "contentCoronaCA","massage-cutler-bay-fl" => "contentCutlerBayFL","massage-miami-beach-fl" => "contentMiamiBeachFL","massage-south-beach-fl" => "contentSouthBeachFL","massage-tempe-az" => "contentTempeAZ","massage-glendale-az" => "contentGlendaleAZ","massage-peoria-az" => "contentPeoriaAZ","massage-glendale-ca" => "contentGlendaleCA","massage-jersey-city-nj" => "contentJerseyCityNJ","massage-berkeley-ca" => "contentBerkeleyCA","massage-santa-clara-ca" => "contentSantaClaraCA","massage-irving-tx" => "contentIrvingTX","massage-aurora-co" => "contentAuroraCO","massage-cypress-tx" => "contentCypressTX","massage-sunnyvale-ca" => "contentSunnyvaleCA","massage-minneapolis-mn" => "contentMinneapolisMN","massage-pittsburgh-pa" => "contentPittsburghPA","massage-durham-nc" => "contentDurhamNC","massage-plano-tx" => "contentPlanoTX","massage-reno-nv" => "contentRenoNV","massage-buffalo-ny" => "contentBuffaloNY","massage-frisco-tx" => "contentFriscoTX","massage-richmond-va" => "contentRichmondVA","massage-lancaster-ca" => "contentLancasterCA","massage-augusta-ga" => "contentAugustaGA","massage-new-orleans-la" => "contentNewOrleansLA","massage-alameda-ca" => "contentAlamedaCA","massage-san-mateo-ca" => "contentSanMateoCA","massage-santa-cruz-ca" => "contentSantaCruzCA","massage-santa-barbara-ca" => "contentSantaBarbaraCA","massage-hoboken-nj" => "contentHobokenNJ","massage-westchester-ny" => "contentWestchesterNY","massage-long-island-ny" => "contentLongIslandNY","massage-walnut-creek-ca" => "contentWalnutCreekCA","massage-escondido-ca" => "contentEscondidoCA"];
		$this->contentByCities = ["massage-los-angeles" => "contentLosAngeles","massage-new-york" => "contentNewYork","massage-miami" => "contentMiami","massage-houston" => "contentHouston","massage-las-vegas" => "contentLasVegas","massage-san-francisco" => "contentSanFrancisco","massage-san-diego" => "contentSanDiego","massage-phoenix" => "contentPhoenix","massage-dallas" => "contentDallas","massage-austin" => "contentAustin","massage-washington-dc" => "contentWashingtonDC","massage-tampa-fl" => "contentTampaFL","massage-albuquerque-nm" => "contentAlbuquerqueNM","massage-arlington-tx" => "contentArlingtonTX","massage-aspen-co" => "contentAspenCO","massage-atlanta-ga" => "contentAtlantaGA","massage-baltimore-md" => "contentBaltimoreMD","massage-boston-ma" => "contentBostonMA","massage-charlotte-nc" => "contentCharlotteNC","massage-chicago-il" => "contentChicagoIL","massage-cleveland-oh" => "contentClevelandOH","massage-colorado-springs-co" => "contentColoradoSpringsCO","massage-columbus-oh" => "contentColumbusOH","massage-denver-co" => "contentDenverCO","massage-detroit-mi" => "contentDetroitMI","massage-el-paso-tx" => "contentElPasoTX","massage-fort-worth-tx" => "contentFortWorthTX","massage-fresno-ca" => "contentFresnoCA","massage-hollywood-fl" => "contentHollywoodFL","massage-honolulu-hi" => "contentHonoluluHI","massage-indianapolis-in" => "contentIndianapolisIN","massage-jacksonville-fl" => "contentJacksonvilleFL","massage-kansas-city-mo" => "contentKansasCityMO","massage-long-beach-ca" => "contentLongBeachCA","massage-louisville-ky" => "contentLouisvilleKY","massage-memphis-tn" => "contentMemphisTN","massage-monterey-bay-ca" => "contentMontereyBayCA","massage-mesa-az" => "contentMesaAZ","massage-milwaukee-wi" => "contentMilwaukeeWI","massage-nashville-tn" => "contentNashvilleTN","massage-newark-nj" => "contentNewarkNJ","massage-oakland-ca" => "contentOaklandCA","massage-oklahoma-city-ok" => "contentOklahomaCityOK","massage-omaha-ne" => "contentOmahaNE","massage-orlando-fl" => "contentOrlandoFL","massage-philadelphia-pa" => "contentPhiladelphiaPA","massage-portland-or" => "contentPortlandOR","massage-raleigh-nc" => "contentRaleighNC","massage-sacramento-ca" => "contentSacramentoCA","massage-san-antonio-tx" => "contentSanAntonioTX","massage-san-jose-ca" => "contentSanJoseCA","massage-sarasota-fl" => "contentSarasotaFL","massage-scottsdale-az" => "contentScottsdaleAZ","massage-seattle-wa" => "contentSeattleWA","massage-stpetersburg-fl" => "contentStpetersburgFL","massage-tucson-az" => "contentTucsonAZ","massage-tulsa-ok" => "contentTulsaOK","massage-virginia-beach-va" => "contentVirginiaBeachVA","massage-manhattan-ny" => "contentManhattanNY","massage-bronx-ny" => "contentBronxNY","massage-brooklyn-ny" => "contentBrooklynNY","massage-queens-ny" => "contentQueensNY","massage-hialeah-fl" => "contentHialeahFL","massage-bakersfield-ca" => "contentBakersfieldCA","massage-anaheim-ca" => "contentAnaheimCA","massage-irvine-ca" => "contentIrvineCA","massage-chula-vista-ca" => "contentChulaVistaCA","massage-fremont-ca" => "contentFremontCA","massage-san-bernardino-ca" => "contentSanBernardinoCA","massage-santa-clarita-ca" => "contentSantaClaritaCA","massage-huntington-beach-ca" => "contentHuntingtonBeachCA","massage-corona-ca" => "contentCoronaCA","massage-cutler-bay-fl" => "contentCutlerBayFL","massage-miami-beach-fl" => "contentMiamiBeachFL","massage-south-beach-fl" => "contentSouthBeachFL","massage-tempe-az" => "contentTempeAZ","massage-glendale-az" => "contentGlendaleAZ","massage-peoria-az" => "contentPeoriaAZ","massage-glendale-ca" => "contentGlendaleCA","massage-jersey-city-nj" => "contentJerseyCityNJ","massage-berkeley-ca" => "contentBerkeleyCA","massage-santa-clara-ca" => "contentSantaClaraCA","massage-irving-tx" => "contentIrvingTX","massage-aurora-co" => "contentAuroraCO","massage-cypress-tx" => "contentCypressTX","massage-sunnyvale-ca" => "contentSunnyvaleCA","massage-minneapolis-mn" => "contentMinneapolisMN","massage-pittsburgh-pa" => "contentPittsburghPA","massage-durham-nc" => "contentDurhamNC","massage-plano-tx" => "contentPlanoTX","massage-reno-nv" => "contentRenoNV","massage-buffalo-ny" => "contentBuffaloNY","massage-frisco-tx" => "contentFriscoTX","massage-richmond-va" => "contentRichmondVA","massage-lancaster-ca" => "contentLancasterCA","massage-augusta-ga" => "contentAugustaGA","massage-new-orleans-la" => "contentNewOrleansLA","massage-alameda-ca" => "contentAlamedaCA","massage-san-mateo-ca" => "contentSanMateoCA","massage-santa-cruz-ca" => "contentSantaCruzCA","massage-santa-barbara-ca" => "contentSantaBarbaraCA","massage-hoboken-nj" => "contentHobokenNJ","massage-westchester-ny" => "contentWestchesterNY","massage-long-island-ny" => "contentLongIslandNY","massage-walnut-creek-ca" => "contentWalnutCreekCA","massage-escondido-ca" => "contentEscondidoCA","massage-sugar-land-tx" => "contentSugarLandTX","massage-fort-lauderdale-fl" => "contentFortLauderdaleFL","massage-oceanside-ca" => "contentOceansideCA","massage-chandler-az" => "contentChandlerAZ","massage-kissimmee-fl" => "contentKissimmeeFL","massage-lakeland-fl" => "contentLakelandFL","massage-dublin-ca" => "contentDublinCA","massage-gilbert-az" => "contentGilbertAZ","massage-garland-tx" => "contentGarlandTX","massage-mckinney-tx" => "contentMcKinneyTX"];


        $this->priceCommonSection['deep_tissue'] = "Strong massage style with deep pressure to reduce muscle tension and promote relaxation"; 
        $this->priceCommonSection['swedish']     = "Kneading and circular movements with medium pressure for stress relief and reducing tension"; 
        $this->priceCommonSection['sports']      = "Uses deep tissue and stretching to improve flexibility, aid performance and muscle recovery"; 
        $this->priceCommonSection['prenatal']    = "Massage style that focuses on relieving tension and improving sleep during pregnancy"; 
        $this->priceCommonSection['reflexology'] = "Use of gentle pressure on specific points along your feet, and possibly hands and ears"; 
        $this->priceCommonSection['lymphatic']   = "Relieves swelling that happens when medical treatment blocks your lymphatic system"; 
        $this->priceCommonSection['couples']   = "Celebrate your friendship or relationship with a couples massage. Two therapists at same time"; 


    }

    public function getAllListForSiteMap()
    {
        return $this->allCities;
    }

    /**
     * This method use for get list of citiew for Route
     */
    public function allrouteCities()
    {
        return $this->allCities ? array_keys($this->allCities) : [];
    }

    public function index(Request $request)
    {
        $cityName = request()->segment(1);
        $data = [];
        if( in_array($cityName,array_keys($this->allCities)) )
        {
            $citySlug = $cityName;
            
            $seodata = DB::table('seo_details')->where('page_url',"{$citySlug}")->first();
            $data['title']   = $seodata->title;
            $data['city_name']  = isset($this->allCities[$cityName]) ? $this->allCities[$cityName] : null;
            $data['desc']    = $seodata->description;
            $data['keyword'] = $seodata->keyword;
            $data['page_content'] = isset($this->contentByCities[$cityName]) ? $this->{$this->contentByCities[$cityName]}() : null;            
            //dd($data);
            return view('massage-new',compact('data'));
        }
    }


    protected function contentMiami()
    {
        $content['top_title'] = 'Book in-home Massage in Miami in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Miami or anywhere else.';
        $content['pricing_title'] = 'Pricing in Miami';
        $content['pricing_price'] = '$99.99	';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Sophia L.','testimonial_content' => "My experience with this app has been nothing short of amazing. The massage therapists are skilled and very professional. As a first-timer, I was pleasantly surprised at how seamless the process was, from booking the appointment to the massage session itself. The app introduced me to various massage techniques, and I am excited to explore them all. If you are in Miami, give this app a try!"],
		['title' => 'Carlos M.','testimonial_content' => "This app is a must-have for anyone in Miami looking for top-notch massage services. I have been using it for the past few months, and each session leaves me feeling rejuvenated and relaxed. The massage therapists are extremely knowledgeable and cater to my specific needs. I have recommended this app to several friends, and they all share the same positive experience. You won't regret trying it out."],
		['title' => 'Mia F.','testimonial_content' => 'I discovered this app while searching for a convenient massage solution in Miami, and it has exceeded my expectations. The massage therapists are friendly, attentive, and highly skilled, providing a truly refreshing experience. Booking appointments is a breeze, and the service has consistently been top-notch. I now use this app regularly to stay relaxed and stress-free. Do yourself a favor and give it a try.']
		];


         $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ],  ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

        return $content;
    }

    protected function contentNewYork()
    {
        $content['top_title'] = 'Book in-home Massage in New York in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Greater New York or anywhere else.';
        $content['pricing_title'] = 'Pricing in New York';
        $content['pricing_price'] = '$109.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
		$content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];

        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Olivia W.','testimonial_content' => "I cannot recommend this app enough for anyone in New York seeking exceptional massage services. Not only are the therapists professional and skilled, but the convenience of having a massage in my own home is unparalleled. I've tried a variety of massage techniques through the app and have always been impressed. It's truly a game changer for busy city life."],
		['title' => 'Jack P.','testimonial_content' => "This app has been a fantastic find for me in New York. As a frequent traveler, I appreciate the option to book a massage session in my hotel room after a long day. The massage therapists are punctual, courteous, and highly experienced, ensuring a relaxing and rejuvenating experience every time. I've recommended the app to colleagues, and they all share the same enthusiasm. Don't hesitate to give it a try."],
		['title' => 'Emma T.','testimonial_content' => 'If you are in New York and looking for a convenient and top-quality massage service, look no further than this app. The massage therapists are professional, accommodating, and exceptionally skilled. The option to have a massage in the comfort of my home makes it the perfect solution for my busy lifestyle. I have been using the app regularly and am consistently impressed by the outstanding service.']
		];


     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '109.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '109.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '109.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '134.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '109.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '134.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '119.99/p' ],
                ];

    return $content;
    }

    protected function contentLosAngeles()
    {
        $content['top_title'] = 'Book in-home Massage in Los Angeles in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Los Angeles or anywhere else.';
        $content['pricing_title'] = 'Pricing in Los Angeles';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
       $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Amy A.','testimonial_content' => 'My co-worker Rey recommended this app. I was new to LA and didn`t know many places for massage so I simply downloaded the app and got Sara as my provider. She came on time, we had a wonderful session for a good 2 hours and I felt super relaxed. I STRONGLY recommend BigToe app to EVERYONE. This on-demand massage service is truly outstanding.'],
            ['title' => 'Sam B.','testimonial_content' => ' Imagine going to Los Angeles and getting an in-room massage and then coming back to New York and using the same app... These guys have spoiled me big time and I`m loving it...the therapists on the app are punctual and skillful'],
            ['title' => 'Nathan P.','testimonial_content' => 'As a beginner, I found this app and it is so easy to navigate. Each step in the scheduling of a massage session was so clear and precise. I believe everyone should use this massage at home service especially in LA where every other subscription cost more than your expectation. The therapist was truly awesome in so many ways and assured me that I am in good hands.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }


protected function contentHouston()
    {
        $content['top_title'] = 'Book in-home Massage in Houston in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Houston or anywhere else.';
        $content['pricing_title'] = 'Pricing in Houston';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Samantha G.','testimonial_content' => "I've been using this app since I moved to Houston and it has been a game changer for me! The massage therapists are professional, skilled, and always punctual. I love the flexibility and convenience of booking a massage right from my phone. The app has a wide variety of massage techniques to choose from, and I've discovered some new favorites. I recommend this app to anyone in Houston looking for a quality massage experience."],
		['title' => 'Daniel M.','testimonial_content' => "This app is truly a lifesaver! With my busy schedule, finding time for self-care can be challenging. But with this app, I can book a massage session at my convenience, and the therapists come right to my home. The service is consistently top-notch, and I've had nothing but great experiences. I've recommended the app to all my friends in Houston, and they're equally impressed. Do yourself a favor and give it a try – you won't be disappointed!"],
		['title' => 'Vanessa T.','testimonial_content' => 'I was hesitant to try a mobile massage service, but this app has completely changed my mind. The massage therapists are professional, well-trained, and really listen to your needs. I have had several sessions now, and each one has left me feeling relaxed and rejuvenated. The convenience of having the massage in the comfort of my own home is unbeatable. If you are in Houston and looking for a fantastic massage experience, look no further than this app.']
		];


     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }
	
	protected function contentLasVegas()
    {
        $content['top_title'] = 'Book in-home Massage in Las Vegas in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Las Vegas or anywhere else.';
        $content['pricing_title'] = 'Pricing in Las vegas';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Natalie D.','testimonial_content' => "This app has been a fantastic find for me in Las Vegas. Whether I need to unwind after a long day or simply want to treat myself, the convenience of having a skilled massage therapist come to my hotel room is unbeatable. The therapists are professional, knowledgeable, and always provide a top-quality experience. I have recommended the app to friends and family, and everyone has been thrilled with their experiences."],
		['title' => 'Jason M.','testimonial_content' => "In a city like Las Vegas, finding reliable and professional massage services can be a challenge. But with this app, it's never been easier. The massage therapists are punctual, skilled, and attentive to my needs, providing a relaxing and rejuvenating experience every time. The ability to book a session in my home is a huge bonus. I've been using the app regularly and couldn't be happier with the results."],
		['title' => 'Ava L.','testimonial_content' => 'I discovered this app while visiting Las Vegas and have been using it ever since. The convenience of having a massage therapist come to my hotel room is unparalleled. The therapists are friendly, professional, and exceptionally talented, leaving me feeling refreshed and relaxed after each session. If you are in Las Vegas, do not miss out on this fantastic massage experience.']
		];



     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }
	
	protected function contentSanFrancisco()
    {
        $content['top_title'] = 'Book in-home Massage in San Francisco in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Francisco Bay Area or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Francisco';
        $content['pricing_price'] = '$109.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Gordon G.','testimonial_content' => "The therapist came to my home at exactly 10:00 PM which I requested. The way of massage was very unique and impressive. I ended up making a commitment to use this app more often with myself. I was surprised with the professionalism when they asked for my feedback after the session about my experience with the app and therapist. I am very satisfied with it."],
            ['title' => 'Lizzy B.','testimonial_content' => "I instantly felt extremely good and relaxed after my session. The therapist was so understanding and was keen to discover exactly what sort of pressure I wanted, as well as which areas I needed her to focus more attention on. The massage itself was full of satisfaction and powerful at the same time. It felt that I could have started fulfilling this need for my body a long time ago - I highly recommend this mobile massage service. I’ll definitely use it again."],
            ['title' => 'Georgina K.','testimonial_content' => 'Bigtoe app is the best. My massage therapist was super talented, kind and gave a great massage. She paid attention to my requests whether it is to avoid or concentrate. I have been getting massage therapy using this app for a few months now and love it.']
        ];

		 $content['testimonials'] = [
		['title' => 'Lucas W.','testimonial_content' => "As a resident of San Francisco, I have tried various massage services over the years, but this app stands out above the rest. The convenience of having a skilled therapist come to my home has been a game changer for me. The therapists are knowledgeable, professional, and attentive to my needs, creating a truly personalized experience. I can't recommend this app enough for anyone seeking top-notch massages in the comfort of their own home."],
		['title' => 'Grace H.','testimonial_content' => "I travel to San Francisco frequently for work, and this app has been a lifesaver for me. After a long day of meetings, having a massage therapist come to my hotel room is the ultimate way to unwind. The therapists are punctual, friendly, and extremely skilled in various massage techniques. This app has become an essential part of my trips to San Francisco, and I can't imagine visiting the city without it."],
		['title' => 'Benjamin T.','testimonial_content' => 'Living in one of the surrounding suburbs of San Francisco, it can be challenging to find convenient, high-quality massage services. This app has made all the difference. The massage therapists come directly to my home and provide exceptional massages that leave me feeling revitalized and stress-free. Each therapist I have encountered has been professional, attentive, and genuinely focused on delivering a fantastic experience. I highly recommend this app for anyone in the San Francisco area.']
		];

    return $content;
    }
	
	protected function contentSanDiego()
    {
        $content['top_title'] = 'Book in-home Massage in San Diego in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Diego or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Diego';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
			$content['testimonials'] = [
			['title' => 'Victoria S.','testimonial_content' => "I have been using this app for a while now in San Diego, and it has consistently provided me with excellent massage experiences. The convenience of having a professional therapist come to my home has made it so much easier to incorporate self-care into my routine. The therapists are knowledgeable, friendly, and tailor the session to my specific needs. I wholeheartedly recommend this app for anyone seeking top-quality massages in San Diego."],
			['title' => 'Dylan R.','testimonial_content' => "Whenever I travel to San Diego for work, I make sure to book a massage session through this app. The ability to have a skilled therapist come to my hotel room after a long day is simply unbeatable. Each therapist I've encountered has been punctual, professional, and provided a rejuvenating and relaxing experience. The app has become an essential part of my visits to San Diego, and I can't imagine traveling without it."],
			['title' => 'Isabella M.','testimonial_content' => 'As a busy professional living in San Diego, finding time for self-care can be challenging. This app has made it so much easier by offering the luxury of having a massage therapist come directly to my home. Each session leaves me feeling refreshed and recharged, ready to tackle the challenges of the week ahead. The therapists are always professional, attentive, and focused on delivering an exceptional experience. If you are in San Diego, do not hesitate to give this app a try.']
			];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }
	
	protected function contentPhoenix()
    {
        $content['top_title'] = 'Book in-home Massage in Phoenix in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Phoenix or anywhere else.';
        $content['pricing_title'] = 'Pricing in Phoenix';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Ella J.','testimonial_content' => "Hey, if you're in Phoenix, you've gotta try this app! I've had some seriously awesome massages right in my own home. The therapists are super friendly and really know their stuff. It's so nice not to have to drive anywhere after a relaxing session. Give it a shot – you won't be disappointed!"],
		['title' => 'Mason B.','testimonial_content' => "I travel to Phoenix for work a lot, and this app has been a game changer for me. I mean, who wouldn't want a massage in their hotel room after a long day? The therapists are always on time and really skilled at what they do. It's become a must-have for my trips to Phoenix. You should definitely try it out!"],
		['title' => 'Lily G.','testimonial_content' => 'Living in Phoenix, I was on the hunt for a solid massage service that could fit into my schedule. This app has been an absolute find! Having a professional therapist come to my home is just fantastic. They are always attentive and tailor the sessions to my needs. Seriously, if you are in Phoenix, this app is a no-brainer.']
		];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }
	
	protected function contentDallas()
    {
        $content['top_title'] = 'Book in-home Massage in Dallas in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Dallas or anywhere else.';
        $content['pricing_title'] = 'Pricing in Dallas';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Ryan S.','testimonial_content' => "I recently moved to Dallas and was searching for a reliable massage service when I stumbled upon this app. It has been an absolute game changer! The therapists are highly skilled, professional, and attentive to my needs. The convenience of booking appointments through the app and having the therapist come to my home has made it so much easier to fit self-care into my busy schedule. I highly recommend this app to anyone in Dallas looking for top-quality massages."],
		['title' => 'Emily H.','testimonial_content' => "This app has been a fantastic addition to my wellness routine in Dallas. The massage therapists are prompt, courteous, and highly skilled in various massage techniques. The convenience of booking a session through the app and having the therapist come to my location has made it an invaluable resource for me. I've recommended it to several of my friends, and they all rave about their experiences as well. Don't miss out on this incredible service!"],
		['title' => 'Aaron L.','testimonial_content' => 'As a busy professional in Dallas, I often struggle to find time for self-care. This app has been a lifesaver, providing easy access to skilled massage therapists who come directly to my home. Each session leaves me feeling relaxed and recharged, ready to tackle the challenges of the week ahead. I have recommended the app to colleagues and friends alike, and everyone has been thrilled with the results. If you are in Dallas, do not hesitate to try it out for yourself.']
		];
     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }
	
	protected function contentAustin()
    {
        $content['top_title'] = 'Book in-home Massage in Austin in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Austin or anywhere else.';
        $content['pricing_title'] = 'Pricing in Austin';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Harper N.','testimonial_content' => "I'm telling you, this app is a must-try if you're in Austin. I've had some incredible massage sessions right at my place, and it's been a game changer. The therapists are super friendly, skilled, and attentive to what I need. Trust me, once you've experienced the convenience and quality of their service, there's no going back!"],
		['title' => 'Carter H.','testimonial_content' => "Visiting Austin for work, I stumbled upon this app and thought I'd give it a shot. Boy, am I glad I did! After a long day, having a massage therapist come to my hotel room was just perfect. They were punctual, professional, and really knew how to help me relax. Now, I can't imagine my Austin trips without it. You've got to try it!"],
		['title' => 'Zoe K.','testimonial_content' => 'As a busy mom living in Austin, finding time for self-care can be tough. This app has been a lifesaver! Having a skilled therapist come to my home has made all the difference. Each session leaves me feeling refreshed and ready to tackle whatever life throws at me. If you are in Austin, do yourself a favor and check out this app – you will not regret it!']
		];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }
	
		protected function contentTampaFL()
    {
        $content['top_title'] = 'Book in-home Massage in Tampa in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Tampa or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tampa';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Chloe F.','testimonial_content' => "Hey there, fellow Tampa folks! You've got to check out this app. I've had some amazing massage sessions right in my own home, and I couldn't be happier. The therapists are friendly, professional, and really know how to help you relax. The convenience of it all is just unbeatable. Give it a try – you won't be disappointed!"],
		['title' => 'Hunter L.','testimonial_content' => "Whenever I'm in Tampa for work, I make sure to book a massage session through this app. Having a skilled therapist come to my hotel room after a long day is an absolute treat. They're always on time, professional, and the quality of the massage is top-notch. Trust me, if you're visiting Tampa, you'll want to have this app handy!"],
		['title' => 'Avery P.','testimonial_content' => 'Living in Tampa, I was looking for a convenient and high-quality massage service. This app has been the answer to my search! Having a professional therapist come to my home has made it so much easier to fit relaxation into my busy schedule. The therapists are attentive, skilled, and really focus on providing an exceptional experience. If you are in Tampa, do not hesitate to try this app – it is truly a fantastic find.']
		];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }
	
	protected function contentAtlantaGA()
    {
        $content['top_title'] = 'Book in-home Massage in Atlanta in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Atlanta or anywhere else.';
        $content['pricing_title'] = 'Pricing in Atlanta';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Mia J.','testimonial_content' => "Atlanta folks, listen up! This app has been an amazing find for me. I've enjoyed some incredible massage sessions right at my home, and I can't recommend it enough. The therapists are super friendly, skilled, and attentive to my needs. The convenience is unbeatable, and it has made fitting relaxation into my routine so much easier. Give it a try – you won't be disappointed!"],
		['title' => 'Liam H.','testimonial_content' => "Whenever I visit Atlanta for work, I make sure to book a massage session through this app. After a long day, having a skilled therapist come to my hotel room is the perfect way to unwind. They're always punctual, professional, and provide a top-quality massage experience. If you're traveling to Atlanta, don't miss out on this fantastic service!"],
		['title' => 'Riley S.','testimonial_content' => 'As a busy professional in Atlanta, finding time for self-care can be challenging. This app has been the solution I needed! Having a skilled massage therapist come to my home has made all the difference. Each session leaves me feeling rejuvenated and ready to tackle the week ahead. The therapists are professional, attentive, and truly focused on providing an exceptional experience.']
		];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }
	
	protected function contentPhiladelphiaPA()
    {
        $content['top_title'] = 'Book in-home Massage in Philadelphia in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Philadelphia or anywhere else.';
        $content['pricing_title'] = 'Pricing in Philadelphia';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Jasmine L.','testimonial_content' => "I had an incredible massage session with a therapist from this app! She was very attentive and really listened to my needs. I had been dealing with some shoulder and neck pain, and she was able to work out all the knots and tension. I felt so much better afterwards! I also appreciated the easy booking process and the ability to choose the type of massage I wanted. I highly recommend this app to anyone in Philly looking for a great massage."],
		['title' => 'Mark T.','testimonial_content' => "As someone who sits at a desk all day for work, I often suffer from back pain and stiffness. But thanks to this app, I've been able to get regular massages and keep my body feeling good. The therapists are all very professional and skilled, and I appreciate that I can book a session at a time that's convenient for me. I also like that I can choose the duration of the massage and the areas of focus. Overall, a great experience."],
		['title' => 'Rachel S.','testimonial_content' => 'I recently used this app to book a couples massage for my partner and I, and it was such a treat! The therapists arrived right on time and were very friendly and personable. They set up everything we needed in our living room and made sure we were comfortable throughout the entire session. It was such a relaxing and romantic experience, and we both felt so much more connected afterwards. We will definitely be using this app again for future massages!']
		];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }
	
	protected function contentDetroitMI()
    {
        $content['top_title'] = 'Book in-home Massage in Detroit in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Detroit or anywhere else.';
        $content['pricing_title'] = 'Pricing in Detroit';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Megan G.','testimonial_content' => "I was in Detroit for a conference and decided to book a massage session using this app. I'm so glad I did! The therapist was very professional and skilled, and I loved the convenience of being able to book the session through the app. She was able to work out all the tension in my neck and shoulders, and I left feeling so much more relaxed and refreshed. I would definitely use this app again next time I'm in Detroit."],
		['title' => 'John D.','testimonial_content' => "I've been using this app for a few months now and I'm so happy with the service. The therapists are all very friendly and experienced, and I appreciate that I can choose the type of massage I want and the therapist I prefer. The app is very easy to use and I can book a session in just a few clicks. I also like that the prices are very reasonable for the quality of service I receive. I would definitely recommend this app to anyone in Detroit looking for a great massage."],
		['title' => 'Samantha K.','testimonial_content' => 'I had an amazing massage session with a therapist from this app. She was very attentive to my needs and made sure I was comfortable throughout the entire session. I particularly loved the heated table and the aromatherapy oils she used. I felt so relaxed and rejuvenated afterwards. I also appreciate the flexibility of being able to book a session at any time of the day. Overall, a great experience and I will definitely be using this app again!']
		];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
	protected function contentTucsonAZ()
    {
        $content['top_title'] = 'Book in-home Massage in Tucson in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Tucson or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tucson';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
	protected function contentScottsdaleAZ()
    {
        $content['top_title'] = 'Book in-home Massage in Scottsdale in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Scottsdale or anywhere else.';
        $content['pricing_title'] = 'Pricing in Scottsdale';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentColumbusOH()
    {
        $content['top_title'] = 'Book in-home Massage in Columbus in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Columbus or anywhere else.';
        $content['pricing_title'] = 'Pricing in Columbus';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
	protected function contentCharlotteNC()
    {
        $content['top_title'] = 'Book in-home Massage in Charlotte in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Charlotte or anywhere else.';
        $content['pricing_title'] = 'Pricing in Charlotte';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
	protected function contentIndianapolisIN()
    {
        $content['top_title'] = 'Book in-home Massage in Indianapolis in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Indianapolis or anywhere else.';
        $content['pricing_title'] = 'Pricing in Indianapolis';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
	protected function contentClevelandOH()
    {
        $content['top_title'] = 'Book in-home Massage in Cleveland in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Cleveland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Cleveland';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
	protected function contentBostonMA()
    {
        $content['top_title'] = 'Book in-home Massage in Boston in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Boston or anywhere else.';
        $content['pricing_title'] = 'Pricing in Boston';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Liam M.','testimonial_content' => "I had a wonderful massage session with a therapist from this app. She was very professional and knowledgeable, and made sure to focus on the areas where I was experiencing the most tension. I felt so much better afterwards! I also appreciated the easy booking process and the ability to choose the type of massage I wanted. The app is very user-friendly and I will definitely be using it again for future massages in Boston."],
		['title' => 'Emily P.','testimonial_content' => "I have been using this app for a few months now and I'm so glad I found it. The therapists are all very skilled and friendly, and I appreciate that I can book a session at any time of the day. I also love the variety of massage types available - there's something for everyone! The prices are very reasonable too, especially given the quality of service. I highly recommend this app to anyone in Boston looking for a great massage."],
		['title' => 'David S.','testimonial_content' => 'I had a couples massage session with my partner using this app and it was such a treat! The therapists were both very professional and made sure we were comfortable throughout the entire session. They were able to work out all the knots and tension in our muscles and we both felt so much more relaxed and connected afterwards. I also appreciate the easy booking process and the ability to choose the duration of the massage. Overall, a great experience and we will definitely be using this app again in the future!']
		];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '109.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '109.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '109.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '134.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '109.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '134.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '119.99/p' ],
                ];

    return $content;
	}
	
	protected function contentWashingtonDC()
    {
        $content['top_title'] = 'Book in-home Massage in Washington DC in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Washington DC or anywhere else.';
        $content['pricing_title'] = 'Pricing in Washington DC';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Maria R.','testimonial_content' => "I recently used this app to book a massage session and I was very impressed with the service. The therapist was very skilled and professional, and made sure to focus on the areas where I was experiencing the most tension. I felt so much better afterwards! I also appreciated the easy booking process and the ability to choose the type of massage I wanted. The prices are very reasonable too, especially given the quality of service. I highly recommend this app to anyone in DC looking for a great massage."],
		['title' => 'Adam B.','testimonial_content' => "I'm so happy I found this app! The therapists are all very friendly and experienced, and I appreciate that I can book a session at any time of the day. I also like that I can choose the type of massage I want and the therapist I prefer. The app is very user-friendly and makes booking a massage so easy. I highly recommend this app to anyone in DC looking for a convenient and high-quality massage service."],
		['title' => 'Jessica L.','testimonial_content' => 'I had a great experience with this app. The therapist was very attentive and made sure I was comfortable throughout the entire session. I also appreciated the variety of massage types available and the ability to choose the duration of the massage. The prices are very reasonable too, especially given the quality of service. Overall, a great experience and I will definitely be using this app again for future massages in DC.']
		];
      $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '109.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '109.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '109.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '134.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '109.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '134.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '119.99/p' ],
                ];

    return $content;
	}
	
	protected function contentJacksonvilleFL()
    {
        $content['top_title'] = 'Book in-home Massage in Jacksonville in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Jacksonville or anywhere else.';
        $content['pricing_title'] = 'Pricing in Jacksonville';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
		$content['testimonials'] = [
		['title' => 'Alexis C.','testimonial_content' => "I had an amazing massage session with a therapist from this app. She was very skilled and attentive to my needs, and I felt so much more relaxed and rejuvenated afterwards. I also appreciated the easy booking process and the ability to choose the type of massage I wanted. The prices are very reasonable too, especially given the quality of service. I highly recommend this app to anyone in Jacksonville looking for a great massage."],
		['title' => 'Justin M.','testimonial_content' => "I'm so glad I found this app! The therapists are all very professional and skilled, and I appreciate that I can book a session at any time of the day. The app is very user-friendly and makes booking a massage so easy. I also like that I can choose the type of massage I want and the therapist I prefer. Overall, a great experience and I will definitely be using this app again for future massages in Jacksonville."],
		['title' => 'Katie L.','testimonial_content' => 'I recently used this app to book a couples massage for my partner and I, and it was such a wonderful experience! The therapists were both very friendly and professional, and made sure we were comfortable throughout the entire session. They were able to work out all the tension in our muscles and we both felt so much more connected and relaxed afterwards. I also appreciate the variety of massage types available. I highly recommend this app to anyone in Jacksonville looking for a great massage service.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentAlbuquerqueNM()
    {
        $content['top_title'] = 'Book in-home Massage in Albuquerque in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Albuquerque or anywhere else.';
        $content['pricing_title'] = 'Pricing in Albuquerque';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentArlingtonTX()
    {
        $content['top_title'] = 'Book in-home Massage in Arlington in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Arlington or anywhere else.';
        $content['pricing_title'] = 'Pricing in Arlington';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentAspenCO()
    {
        $content['top_title'] = 'Book in-home Massage in Aspen in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Aspen or anywhere else.';
        $content['pricing_title'] = 'Pricing in Aspen';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentBaltimoreMD()
    {
        $content['top_title'] = 'Book in-home Massage in Baltimore in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Baltimore or anywhere else.';
        $content['pricing_title'] = 'Pricing in Baltimore';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentChicagoIL()
    {
        $content['top_title'] = 'Book in-home Massage in Chicago in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Chicago or anywhere else.';
        $content['pricing_title'] = 'Pricing in Chicago';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentColoradoSpringsCO()
    {
        $content['top_title'] = 'Book in-home Massage in Colorado Springs in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Colorado Springs or anywhere else.';
        $content['pricing_title'] = 'Pricing in Colorado Springs';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentdenverCO()
    {
        $content['top_title'] = 'Book in-home Massage in denver in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in denver or anywhere else.';
        $content['pricing_title'] = 'Pricing in denver';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
		
	}
		
		protected function contentElPasoTX()
    {
        $content['top_title'] = 'Book in-home Massage in El Paso in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in El Paso or anywhere else.';
        $content['pricing_title'] = 'Pricing in El Paso';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentFortWorthTX()
    {
        $content['top_title'] = 'Book in-home Massage in Fort Worth in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Fort Worth or anywhere else.';
        $content['pricing_title'] = 'Pricing in Fort Worth';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentFresnoCA()
    {
        $content['top_title'] = 'Book in-home Massage in Fresno in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Fresno or anywhere else.';
        $content['pricing_title'] = 'Pricing in Fresno';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentHollywoodFL()
    {
        $content['top_title'] = 'Book in-home Massage in Hollywood in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Hollywood or anywhere else.';
        $content['pricing_title'] = 'Pricing in Hollywood';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentHonoluluHI()
    {
        $content['top_title'] = 'Book in-home Massage in Honolulu in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Honolulu or anywhere else.';
        $content['pricing_title'] = 'Pricing in Honolulu';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentKansasCityMO()
    {
        $content['top_title'] = 'Book in-home Massage in Kansas City in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Kansas City or anywhere else.';
        $content['pricing_title'] = 'Pricing in Kansas City';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
		protected function contentLongBeachCA()
    {
        $content['top_title'] = 'Book in-home Massage in Long Beach in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Long Beach or anywhere else.';
        $content['pricing_title'] = 'Pricing in Long Beach';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentLouisvilleKY()
    {
        $content['top_title'] = 'Book in-home Massage in Louisville in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Louisville or anywhere else.';
        $content['pricing_title'] = 'Pricing in Louisville';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
		protected function contentMemphisTN()
    {
        $content['top_title'] = 'Book in-home Massage in Memphis in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Memphis or anywhere else.';
        $content['pricing_title'] = 'Pricing in Memphis';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
		protected function contentMontereybayCA()
    {
        $content['top_title'] = 'Book in-home Massage in Monterey Bay in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Monterey Bay or anywhere else.';
        $content['pricing_title'] = 'Pricing in Monterey Bay';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentMesaAZ()
    {
        $content['top_title'] = 'Book in-home Massage in Mesa in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Mesa or anywhere else.';
        $content['pricing_title'] = 'Pricing in Mesa';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentMilwaukeeWI()
    {
        $content['top_title'] = 'Book in-home Massage in Milwaukee in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Milwaukee or anywhere else.';
        $content['pricing_title'] = 'Pricing in Milwaukee';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentNashvilleTN()
    {
        $content['top_title'] = 'Book in-home Massage in Nashville in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Nashville or anywhere else.';
        $content['pricing_title'] = 'Pricing in Nashville';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
	protected function contentOklahomaCityOK()
    {
        $content['top_title'] = 'Book in-home Massage in Oklahoma City in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Oklahoma City or anywhere else.';
        $content['pricing_title'] = 'Pricing in Oklahoma City';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentNewarkNJ()
    {
        $content['top_title'] = 'Book in-home Massage in Newark in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Newark or anywhere else.';
        $content['pricing_title'] = 'Pricing in Newark';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentOaklandCA()
    {
        $content['top_title'] = 'Book in-home Massage in Oakland in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Oakland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Oakland';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}

		protected function contentOrlandoFL()
    {
        $content['top_title'] = 'Book in-home Massage in Orlando in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Orlando or anywhere else.';
        $content['pricing_title'] = 'Pricing in Orlando';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		

		
		protected function contentPortlandOR()
    {
        $content['top_title'] = 'Book in-home Massage in Portland in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Portland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Portland';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentRaleighNC()
    {
        $content['top_title'] = 'Book in-home Massage in Raleigh in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Raleigh or anywhere else.';
        $content['pricing_title'] = 'Pricing in Raleigh';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentSacramentoCA()
    {
        $content['top_title'] = 'Book in-home Massage in Sacramento in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Sacramento or anywhere else.';
        $content['pricing_title'] = 'Pricing in Sacramento';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentSanAntonioTX()
    {
        $content['top_title'] = 'Book in-home Massage in San Antonio in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Antonio or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Antonio';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentSanJoseCA()
    {
        $content['top_title'] = 'Book in-home Massage in San Jose in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Jose or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Jose';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
		protected function contentSarasotaFL()
    {
        $content['top_title'] = 'Book in-home Massage in Sarasota in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Sarasota or anywhere else.';
        $content['pricing_title'] = 'Pricing in Sarasota';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentSeattleWA()
    {
        $content['top_title'] = 'Book in-home Massage in Seattle in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Seattle or anywhere else.';
        $content['pricing_title'] = 'Pricing in Seattle';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentStPetersburgFL()
    {
        $content['top_title'] = 'Book in-home Massage in St. Petersburg in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in St. Petersburg or anywhere else.';
        $content['pricing_title'] = 'Pricing in St. Petersburg';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		

		protected function contentTulsaOK()
    {
        $content['top_title'] = 'Book in-home Massage in Tulsa in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Tulsa or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tulsa';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
	protected function contentOmahaNE()
    {
        $content['top_title'] = 'Book in-home Massage in Omaha in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Omaha or anywhere else.';
        $content['pricing_title'] = 'Pricing in Omaha';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
		
		protected function contentVirginiaBeachVA()
    {
        $content['top_title'] = 'Book in-home Massage in Virginia Beach in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Virginia Beach or anywhere else.';
        $content['pricing_title'] = 'Pricing in Virginia Beach';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
	}
	
	    protected function contentManhattanNY()
    {
        $content['top_title'] = 'Book in-home Massage in Manhattan in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Manhattan or anywhere else.';
        $content['pricing_title'] = 'Pricing in Manhattan';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentBronxNY()
    {
        $content['top_title'] = 'Book in-home Massage in Bronx in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Bronx or anywhere else.';
        $content['pricing_title'] = 'Pricing in Bronx';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentBrooklynNY()
    {
        $content['top_title'] = 'Book in-home Massage in Brooklyn in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Brooklyn or anywhere else.';
        $content['pricing_title'] = 'Pricing in Brooklyn';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentQueensNY()
    {
        $content['top_title'] = 'Book in-home Massage in Queens in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Queens or anywhere else.';
        $content['pricing_title'] = 'Pricing in Queens';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentHialeahFL()
    {
        $content['top_title'] = 'Book in-home Massage in Hialeah in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Hialeah or anywhere else.';
        $content['pricing_title'] = 'Pricing in Hialeah';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentBakersfieldCA()
    {
        $content['top_title'] = 'Book in-home Massage in Bakersfield in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Bakersfield or anywhere else.';
        $content['pricing_title'] = 'Pricing in Bakersfield';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentAnaheimCA()
    {
        $content['top_title'] = 'Book in-home Massage in Anaheim in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Anaheim or anywhere else.';
        $content['pricing_title'] = 'Pricing in Anaheim';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentIrvineCA()
    {
        $content['top_title'] = 'Book in-home Massage in Irvine in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Irvine or anywhere else.';
        $content['pricing_title'] = 'Pricing in Irvine';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentChulaVistaCA()
    {
        $content['top_title'] = 'Book in-home Massage in Chula Vista in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Chula Vista or anywhere else.';
        $content['pricing_title'] = 'Pricing in Chula Vista';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentFremontCA()
    {
        $content['top_title'] = 'Book in-home Massage in Fremont in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Fremont or anywhere else.';
        $content['pricing_title'] = 'Pricing in Fremont';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentSanBernardinoCA()
    {
        $content['top_title'] = 'Book in-home Massage in San Bernardino in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Bernardino or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Bernardino';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentSantaClaritaCA()
    {
        $content['top_title'] = 'Book in-home Massage in Santa Clarita in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Santa Clarita or anywhere else.';
        $content['pricing_title'] = 'Pricing in Santa Clarita';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentHuntingtonBeachCA()
    {
        $content['top_title'] = 'Book in-home Massage in Huntington Beach in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Huntington Beach or anywhere else.';
        $content['pricing_title'] = 'Pricing in Huntington Beach';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentCoronaCA()
    {
        $content['top_title'] = 'Book in-home Massage in Corona in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Corona or anywhere else.';
        $content['pricing_title'] = 'Pricing in Corona';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentCutlerBayFL()
    {
        $content['top_title'] = 'Book in-home Massage in Cutler Bay in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Cutler Bay or anywhere else.';
        $content['pricing_title'] = 'Pricing in Cutler Bay';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentMiamiBeachFL()
    {
        $content['top_title'] = 'Book in-home Massage in Miami Beach in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Miami Beach or anywhere else.';
        $content['pricing_title'] = 'Pricing in Miami Beach';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentSouthBeachFL()
    {
        $content['top_title'] = 'Book in-home Massage in South Beach in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in South Beach or anywhere else.';
        $content['pricing_title'] = 'Pricing in South Beach';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentTempeAZ()
    {
        $content['top_title'] = 'Book in-home Massage in Tempe in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Tempe or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tempe';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentGlendaleAZ()
    {
        $content['top_title'] = 'Book in-home Massage in Glendale in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Glendale or anywhere else.';
        $content['pricing_title'] = 'Pricing in Glendale';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentPeoriaAZ()
    {
        $content['top_title'] = 'Book in-home Massage in Peoria in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Peoria or anywhere else.';
        $content['pricing_title'] = 'Pricing in Peoria';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentEscondidoCA()
    {
        $content['top_title'] = 'Book in-home Massage in Escondido in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Escondido or anywhere else.';
        $content['pricing_title'] = 'Pricing in Escondido';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentGlendaleCA()
    {
        $content['top_title'] = 'Book in-home Massage in Glendale in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Glendale or anywhere else.';
        $content['pricing_title'] = 'Pricing in Glendale';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

 

    protected function contentBerkeleyCA()
    {
        $content['top_title'] = 'Book in-home Massage in Berkeley in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Berkeley or anywhere else.';
        $content['pricing_title'] = 'Pricing in Berkeley';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentSantaClaraCA()
    {
        $content['top_title'] = 'Book in-home Massage in Santa Clara in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Santa Clara or anywhere else.';
        $content['pricing_title'] = 'Pricing in Santa Clara';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentIrvingTX()
    {
        $content['top_title'] = 'Book in-home Massage in Irving in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Irving or anywhere else.';
        $content['pricing_title'] = 'Pricing in Irving';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentAuroraCO()
    {
        $content['top_title'] = 'Book in-home Massage in Aurora in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Aurora or anywhere else.';
        $content['pricing_title'] = 'Pricing in Aurora';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentCypressTX()
    {
        $content['top_title'] = 'Book in-home Massage in Cypress in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Cypress or anywhere else.';
        $content['pricing_title'] = 'Pricing in Cypress';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentSunnyvaleCA()
    {
        $content['top_title'] = 'Book in-home Massage in Sunnyvale in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Sunnyvale or anywhere else.';
        $content['pricing_title'] = 'Pricing in Sunnyvale';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentMinneapolisMN()
    {
        $content['top_title'] = 'Book in-home Massage in Minneapolis in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Minneapolis or anywhere else.';
        $content['pricing_title'] = 'Pricing in Minneapolis';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentPittsburghPA()
    {
        $content['top_title'] = 'Book in-home Massage in Pittsburgh in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Pittsburgh or anywhere else.';
        $content['pricing_title'] = 'Pricing in Pittsburgh';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentDurhamNC()
    {
        $content['top_title'] = 'Book in-home Massage in Durham in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Durham or anywhere else.';
        $content['pricing_title'] = 'Pricing in Durham';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentPlanoTX()
    {
        $content['top_title'] = 'Book in-home Massage in Plano in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Plano or anywhere else.';
        $content['pricing_title'] = 'Pricing in Plano';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentRenoNV()
    {
        $content['top_title'] = 'Book in-home Massage in Reno in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Reno or anywhere else.';
        $content['pricing_title'] = 'Pricing in Reno';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentBuffaloNY()
    {
        $content['top_title'] = 'Book in-home Massage in Buffalo in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Buffalo or anywhere else.';
        $content['pricing_title'] = 'Pricing in Buffalo';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentFriscoTX()
    {
        $content['top_title'] = 'Book in-home Massage in Frisco in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Frisco or anywhere else.';
        $content['pricing_title'] = 'Pricing in Frisco';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentRichmondVA()
    {
        $content['top_title'] = 'Book in-home Massage in Richmond in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Richmond or anywhere else.';
        $content['pricing_title'] = 'Pricing in Richmond';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentLancasterCA()
    {
        $content['top_title'] = 'Book in-home Massage in Lancaster in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Lancaster or anywhere else.';
        $content['pricing_title'] = 'Pricing in Lancaster';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentAugustaGA()
    {
        $content['top_title'] = 'Book in-home Massage in Augusta in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Augusta or anywhere else.';
        $content['pricing_title'] = 'Pricing in Augusta';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentNewOrleansLA()
    {
        $content['top_title'] = 'Book in-home Massage in New Orleans in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in New Orleans or anywhere else.';
        $content['pricing_title'] = 'Pricing in New Orleans';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentAlamedaCA()
    {
        $content['top_title'] = 'Book in-home Massage in Alameda in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Alameda or anywhere else.';
        $content['pricing_title'] = 'Pricing in Alameda';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }


    protected function contentSanMateoCA()
    {
        $content['top_title'] = 'Book in-home Massage in San Mateo in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Mateo or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Mateo';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentSantaCruzCA()
    {
        $content['top_title'] = 'Book in-home Massage in Santa Cruz in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Santa Cruz or anywhere else.';
        $content['pricing_title'] = 'Pricing in Santa Cruz';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentSantaBarbaraCA()
    {
        $content['top_title'] = 'Book in-home Massage in Santa Barbara in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Santa Barbara or anywhere else.';
        $content['pricing_title'] = 'Pricing in Santa Barbara';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentJerseyCityNJ()
    {
        $content['top_title'] = 'Book in-home Massage in Jersey City in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Jersey City or anywhere else.';
        $content['pricing_title'] = 'Pricing in Jersey City';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentHobokenNJ()
    {
        $content['top_title'] = 'Book in-home Massage in Hoboken in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Hoboken or anywhere else.';
        $content['pricing_title'] = 'Pricing in Hoboken';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentWestchesterNY()
    {
        $content['top_title'] = 'Book in-home Massage in Westchester in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Westchester or anywhere else.';
        $content['pricing_title'] = 'Pricing in Westchester';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentLongIslandNY()
    {
        $content['top_title'] = 'Book in-home Massage in Long Island in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Long Island or anywhere else.';
        $content['pricing_title'] = 'Pricing in Long Island';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentWalnutCreekCA()
    {
        $content['top_title'] = 'Book in-home Massage in Walnut Creek in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Walnut Creek or anywhere else.';
        $content['pricing_title'] = 'Pricing in Walnut Creek';
        $content['pricing_price'] = '$99.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

     $content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

    return $content;
    }

    protected function contentSugarLandTX()
    {
        $content['top_title'] = 'Book in-home Massage in Sugar Land in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Sugar Land or anywhere else.';
        $content['pricing_title'] = 'Pricing in Sugar Land';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];       

		return $content;
    }

    protected function contentFortLauderdaleFL()
    {
        $content['top_title'] = 'Book in-home Massage in Fort Lauderdale in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Fort Lauderdale or anywhere else.';
        $content['pricing_title'] = 'Pricing in Fort Lauderdale';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
		
		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];
        return $content;
    }

    protected function contentOceansideCA()
    {
        $content['top_title'] = 'Book in-home Massage in Oceanside in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Oceanside or anywhere else.';
        $content['pricing_title'] = 'Pricing in Oceanside';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

        return $content;
    }

    protected function contentChandlerAZ()
    {
        $content['top_title'] = 'Book in-home Massage in Chandler in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Chandler or anywhere else.';
        $content['pricing_title'] = 'Pricing in Chandler';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];
				
        return $content;
    }

    protected function contentKissimmeeFL()
    {
        $content['top_title'] = 'Book in-home Massage in Kissimmee in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Kissimmee or anywhere else.';
        $content['pricing_title'] = 'Pricing in Kissimmee';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];       
				
		return $content;
    }

    protected function contentLakelandFL()
    {
        $content['top_title'] = 'Book in-home Massage in Lakeland in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Lakeland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Lakeland';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];        
				
		return $content;
    }

    protected function contentDublinCA()
    {
        $content['top_title'] = 'Book in-home Massage in Dublin in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Dublin or anywhere else.';
        $content['pricing_title'] = 'Pricing in Dublin';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

        return $content;
    }

    protected function contentGilbertAZ()
    {
        $content['top_title'] = 'Book in-home Massage in Gilbert in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Gilbert or anywhere else.';
        $content['pricing_title'] = 'Pricing in Gilbert';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

        return $content;
    }

    protected function contentGarlandTX()
    {
        $content['top_title'] = 'Book in-home Massage in Garland in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Garland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Garland';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ]; 

		return $content;
    }

    protected function contentMcKinneyTX()
    {
        $content['top_title'] = 'Book in-home Massage in McKinney in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in McKinney or anywhere else.';
        $content['pricing_title'] = 'Pricing in McKinney';
        $content['pricing_price'] = '$79.99';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of therapist, location and time on the app and send booking request to local therapists',
        ];
        $content['services_2'] = [
            'title' => 'Therapists Respond',
            'content' => 'The certified and experienced therapists on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a therapist accepts your exact request, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed therapist will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

		$content['price']['category'] = [
                    ['title' => 'Deep Tissue','description' => $this->priceCommonSection['deep_tissue'],'price'=> '99.99' ],
                    ['title' => 'Swedish','description' => $this->priceCommonSection['swedish'],'price'=> '99.99' ],
                    ['title' => 'Sports','description' => $this->priceCommonSection['sports'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '124.99' ],
                    ['title' => 'Reflexology','description' => $this->priceCommonSection['reflexology'],'price'=> '99.99' ],
                    ['title' => 'Lymphatic','description' => $this->priceCommonSection['lymphatic'],'price'=> '124.99' ], ['title' => 'Couples','description' => $this->priceCommonSection['couples'],'price'=> '109.99/p' ],
                ];

		return $content;
    }


	
}