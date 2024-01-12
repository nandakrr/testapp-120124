<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;
use Session;

class FrontYogaController extends Controller
{
    protected $allCities,$contentByCities,$priceCommonSection;
    
    function __construct()
    {
        // When new city come : add new array "FUNCTION_NAME" => "CITY NAME" THEN create new function with same name 
        $this->allCities = ["private-yoga-los-angeles" => "Los Angeles","private-yoga-new-york" => "New York","private-yoga-miami" => "Miami","private-yoga-houston" => "Houston","private-yoga-las-vegas" => "Las Vegas","private-yoga-san-francisco" => "San Francisco","private-yoga-san-diego" => "San Diego","private-yoga-phoenix" => "Phoenix","private-yoga-dallas" => "Dallas","private-yoga-austin" => "Austin","private-yoga-tampa-fl" => "Tampa","private-yoga-atlanta-ga" => "Atlanta","private-yoga-philadelphia-pa" => "Philadelphia","private-yoga-detroit-mi" => "Detroit","private-yoga-tucson-az" => "Tucson","private-yoga-scottsdale-az" => "Scottsdale","private-yoga-columbus-oh" => "Columbus","private-yoga-charlotte-nc" => "Charlotte","private-yoga-indianapolis-in" => "Indianapolis","private-yoga-cleveland-oh" => "Cleveland","private-yoga-boston-ma" => "Boston","private-yoga-washington-dc" => "Washington DC","private-yoga-jacksonville-fl" => "Jacksonville","private-yoga-albuquerque-nm" => "Albuquerque","private-yoga-arlington-tx" => "Arlington","private-yoga-aspen-co" => "Aspen","private-yoga-baltimore-md" => "Baltimore","private-yoga-chicago-il" => "Chicago","private-yoga-colorado-springs-co" => "Colorado Springs","private-yoga-denver-co" => "Denver","private-yoga-el-paso-tx" => "El Paso","private-yoga-fort-worth-tx" => "Fort Worth","private-yoga-fresno-ca" => "Fresno","private-yoga-hollywood-fl" => "Hollywood","private-yoga-honolulu-hi" => "Honolulu","private-yoga-kansas-city-mo" => "Kansas City","private-yoga-long-beach-ca" => "Long Beach","private-yoga-louisville-ky" => "Louisville","private-yoga-memphis-tn" => "Memphis","private-yoga-monterey-bay-ca" => "Monterey Bay","private-yoga-mesa-az" => "Mesa","private-yoga-milwaukee-wi" => "Milwaukee","private-yoga-nashville-tn" => "Nashville","private-yoga-newark-nj" => "Newark","private-yoga-oakland-ca" => "Oakland","private-yoga-oklahoma-city-ok" => "Oklahoma City","private-yoga-omaha-ne" => "Omaha","private-yoga-orlando-fl" => "Orlando","private-yoga-portland-or" => "Portland","private-yoga-raleigh-nc" => "Raleigh","private-yoga-sacramento-ca" => "Sacramento","private-yoga-san-antonio-tx" => "San Antonio","private-yoga-san-jose-ca" => "San Jose","private-yoga-sarasota-fl" => "Sarasota","private-yoga-seattle-wa" => "Seattle","private-yoga-stpetersburg-fl" => "St Petersburg","private-yoga-tulsa-ok" => "Tulsa","private-yoga-virginia-beach-va" => "Virginia Beach"];

        $this->contentByCities = ["private-yoga-los-angeles" => "contentLosAngeles","private-yoga-new-york" => "contentNewYork","private-yoga-miami" => "contentMiami","private-yoga-houston" => "contentHouston","private-yoga-las-vegas" => "contentLasVegas","private-yoga-san-francisco" => "contentSanFrancisco","private-yoga-san-diego" => "contentSanDiego","private-yoga-phoenix" => "contentPhoenix","private-yoga-dallas" => "contentDallas","private-yoga-austin" => "contentAustin","private-yoga-tampa-fl" => "contentTampaFL","private-yoga-atlanta-ga" => "contentAtlantaGA","private-yoga-philadelphia-pa" => "contentPhiladelphiaPA","private-yoga-detroit-mi" => "contentDetroitMI","private-yoga-tucson-az" => "contentTucsonAZ","private-yoga-scottsdale-az" => "contentScottsdaleAZ","private-yoga-columbus-oh" => "contentColumbusOH","private-yoga-charlotte-nc" => "contentCharlotteNC","private-yoga-indianapolis-in" => "contentIndianapolisIN","private-yoga-cleveland-oh" => "contentClevelandOH","private-yoga-boston-ma" => "contentBostonMA","private-yoga-washington-dc" => "contentWashingtonDC","private-yoga-jacksonville-fl" => "contentJacksonvilleFL","private-yoga-albuquerque-nm" => "contentAlbuquerqueNM","private-yoga-arlington-tx" => "contentArlingtonTX","private-yoga-aspen-co" => "contentAspenCO","private-yoga-baltimore-md" => "contentBaltimoreMD","private-yoga-chicago-il" => "contentChicagoIL","private-yoga-colorado-springs-co" => "contentColoradoSpringsCO","private-yoga-denver-co" => "contentDenverCO","private-yoga-el-paso-tx" => "contentElPasoTX","private-yoga-fort-worth-tx" => "contentFortWorthTX","private-yoga-fresno-ca" => "contentFresnoCA","private-yoga-hollywood-fl" => "contentHollywoodFL","private-yoga-honolulu-hi" => "contentHonoluluHI","private-yoga-kansas-city-mo" => "contentKansasCityMO","private-yoga-long-beach-ca" => "contentLongBeachCA","private-yoga-louisville-ky" => "contentLouisvilleKY","private-yoga-memphis-tn" => "contentMemphisTN","private-yoga-monterey-bay-ca" => "contentMontereybayCA","private-yoga-mesa-az" => "contentMesaAZ","private-yoga-milwaukee-wi" => "contentMilwaukeeWI","private-yoga-nashville-tn" => "contentNashvilleTN","private-yoga-newark-nj" => "contentNewarkNJ","private-yoga-oakland-ca" => "contentOaklandCA","private-yoga-oklahoma-city-ok" => "contentOklahomaCityOK","private-yoga-omaha-ne" => "contentOmahaNE","private-yoga-orlando-fl" => "contentOrlandoFL","private-yoga-portland-or" => "contentPortlandOR","private-yoga-raleigh-nc" => "contentRaleighNC","private-yoga-sacramento-ca" => "contentSacramentoCA","private-yoga-san-antonio-tx" => "contentSanAntonioTX","private-yoga-san-jose-ca" => "contentSanJoseCA","private-yoga-sarasota-fl" => "contentSarasotaFL","private-yoga-seattle-wa" => "contentSeattleWA","private-yoga-stpetersburg-fl" => "contentStPetersburgFL","private-yoga-tucson-az" => "contentTucsonAZ","private-yoga-tulsa-ok" => "contentTulsaOK","private-yoga-virginia-beach-va" => "contentVirginiaBeachVA"];
		
		$this->priceCommonSection['flow'] = "Fluid movement through energetic postures matched with breath to create strength and focus"; 
        $this->priceCommonSection['alignment']     = "Focuses on precise way to do poses to maximize their benefits and minimize the risk of injury"; 
        $this->priceCommonSection['restorative']      = "Opens body through passive stretching using long holds to allow your muscles to relax deeply"; 
        $this->priceCommonSection['prenatal']    = "Poses for pregnant women to develop proper breathing and increase strength and flexibility"; 
        $this->priceCommonSection['meditation'] = "Nonjudgmental focusing of mind on an object or activity to achieve focus, mental stability and clarity"; 
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
            return view('yoga-new',compact('data'));
        }
    }


    protected function contentMiami()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Miami in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Miami or anywhere else.';
        $content['pricing_title'] = 'Pricing in Miami';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Miami, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
        $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }

    protected function contentNewYork()
    {
        $content['top_title'] = 'Book Private Yoga Classes in New York in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in New York or anywhere else.';
        $content['pricing_title'] = 'Pricing in New York';
        $content['pricing_price'] = '$109.99/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
		$content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];

        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Becky A.','testimonial_content' => "Really, really good massage. My massage therapist was extremely skillful and made my day so much better. I can't thank her enough. I'm looking forward to my next massage with her. The app is pretty great too!"],
            ['title' => 'Sam B.','testimonial_content' => "Imagine going to Los Angeles and getting an in-room massage and then coming back to New York and using the same app... These guys have spoiled me big time and I'm loving it...the therapists on the app are punctual and skillful"],
            ['title' => 'Alex S.','testimonial_content' => 'You guys are the real deal. Never before have I been able to find the right combination of price and quality. Most of the other options are either too expensive, or too meh.....me and some of my friends are superfans now.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '109.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '109.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '109.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '109.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '109.99' ],
                ];
        return $content;
    }

    protected function contentLosAngeles()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Los Angeles in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Los Angeles or anywhere else.';
        $content['pricing_title'] = 'Pricing in Los Angeles';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
       $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Amy A.','testimonial_content' => 'My co-worker Rey recommended this app. I was new to LA and didn`t know many places for massage so I simply downloaded the app and got Sara as my provider. She came on time, we had a wonderful session for a good 2 hours and I felt super relaxed. I STRONGLY recommend BigToe app to EVERYONE. This on-demand massage service is truly outstanding.'],
            ['title' => 'Sam B.','testimonial_content' => ' Imagine going to Los Angeles and getting an in-room massage and then coming back to New York and using the same app... These guys have spoiled me big time and I`m loving it...the therapists on the app are punctual and skillful'],
            ['title' => 'Nathan P.','testimonial_content' => 'As a beginner, I found this app and it is so easy to navigate. Each step in the scheduling of a massage session was so clear and precise. I believe everyone should use this massage at home service especially in LA where every other subscription cost more than your expectation. The therapist was truly awesome in so many ways and assured me that I am in good hands.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }


protected function contentHouston()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Houston in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Houston or anywhere else.';
        $content['pricing_title'] = 'Pricing in Houston';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Becky A.','testimonial_content' => "Really, really good massage. My massage therapist was extremely skillful and made my day so much better. I can't thank her enough. I'm looking forward to my next massage with her. The app is pretty great too!"],
            ['title' => 'Elise Y.','testimonial_content' => "I am still in the new customer phase, but so far I am very impressed.. All of my questions were answered prior to sending to the therapist and I felt very comfortable with my decision to choose Bigtoe for my first ever massage therapy. Sounds like they have a great service and I am excited to book another session soon!."],
            ['title' => 'Poonam H.','testimonial_content' => 'The guy on chat offered me some help in scheduling my session and was able to clear up all the questions I had - Booked on the spot. Read many reviews, compared places, different prices and I feel this is the best option. The provider was expert and friendly. Thankful for the ease of their mobile application and no hidden charges. Will ask for service again!']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
	protected function contentLasVegas()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Las Vegas in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Las Vegas or anywhere else.';
        $content['pricing_title'] = 'Pricing in Las vegas';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
	protected function contentSanFrancisco()
    {
        $content['top_title'] = 'Book Private Yoga Classes in San Francisco in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in San Francisco Bay Area or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Francisco';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Gordon G.','testimonial_content' => "The therapist came to my home at exactly 10:00 PM which I requested. The way of massage was very unique and impressive. I ended up making a commitment to use this app more often with myself. I was surprised with the professionalism when they asked for my feedback after the session about my experience with the app and therapist. I am very satisfied with it."],
            ['title' => 'Lizzy B.','testimonial_content' => "I instantly felt extremely good and relaxed after my session. The therapist was so understanding and was keen to discover exactly what sort of pressure I wanted, as well as which areas I needed her to focus more attention on. The massage itself was full of satisfaction and powerful at the same time. It felt that I could have started fulfilling this need for my body a long time ago - I highly recommend this mobile massage service. I’ll definitely use it again."],
            ['title' => 'Georgina K.','testimonial_content' => 'Bigtoe app is the best. My massage therapist was super talented, kind and gave a great massage. She paid attention to my requests whether it is to avoid or concentrate. I have been getting massage therapy using this app for a few months now and love it.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
	protected function contentSanDiego()
    {
        $content['top_title'] = 'Book Private Yoga Classes in San Diego in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in San Diego or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Diego';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
	protected function contentPhoenix()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Phoenix in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Phoenix or anywhere else.';
        $content['pricing_title'] = 'Pricing in Phoenix';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Nancy P.','testimonial_content' => "I am a new member to bigtoe mobile massage service. I was getting a series of lymphatic massage sessions from a local therapist. One day she didn’t come for the session so I had to go online and look for an alternative. I luckily found them and asked a few questions on chat. They gave the best price for 7 lymphatic sessions. It was hard for me to expect good service at such an affordable price as compared to what I was paying to my local therapist but I gave them a chance and booked just one session to check if they are good or not. Luckily, they proved me wrong and now I am getting my 8th session for them this wednesday. They are simply awesome."],
            ['title' => 'Serge B.','testimonial_content' => "Customer service and therapy has been great so far and they even worked with me on a personal request that is not generally offered by all companies. They really deserve my endorsement."],
            ['title' => 'Paula S.','testimonial_content' => 'After doing much research on the affordable massage sessions, I decided to go with BigToe. Since making that decision, I have had an amazing experience. They are super responsive and honest, I got 3 sessions from them and am still planning to have it two or three times every month but I have no doubt that the future sessions will be perfect and leave me with a dazzling smile.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
	protected function contentDallas()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Dallas in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Dallas or anywhere else.';
        $content['pricing_title'] = 'Pricing in Dallas';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Cory A.','testimonial_content' => "I am 35. I never had the opportunity to try this type of service. I chose Bigtoe after researching the options- they did the job! I was a bit skeptical at first because I had never heard of them before but considering the price, I gave it a try and now I am proud of my decision. Overall I recommend the service. I Immediately referred my sister when I completed my session with their provider."],
            ['title' => 'Tracy K.','testimonial_content' => "Sarah was my Therapist! She was very knowledgeable and helped me to use them for my weekly massage session. The price is unbeatable and their service is great. Highly recommend it. The call I received from them after the session helped me to believe that they do care about their customers!"],
            ['title' => 'Alex S.','testimonial_content' => 'You guys are the real deal. Never before have I been able to find the right combination of price and quality. Most of the other options are either too expensive, or too meh.....me and some of my friends are superfans now.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
	protected function contentAustin()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Austin in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Austin or anywhere else.';
        $content['pricing_title'] = 'Pricing in Austin';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
		protected function contentTampaFL()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Tampa in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Austin or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tampa';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
	protected function contentAtlantaGA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Atlanta in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Atlanta or anywhere else.';
        $content['pricing_title'] = 'Pricing in Atlanta';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
	protected function contentPhiladelphiaPA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Philadelphia in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Philadelphia or anywhere else.';
        $content['pricing_title'] = 'Pricing in Philadelphia';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
    }
	
	protected function contentDetroitMI()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Detroit in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Detroit or anywhere else.';
        $content['pricing_title'] = 'Pricing in Detroit';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
	protected function contentTucsonAZ()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Tucson in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Tucson or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tucson';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
	protected function contentScottsdaleAZ()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Scottsdale in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Scottsdale or anywhere else.';
        $content['pricing_title'] = 'Pricing in Scottsdale';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentColumbusOH()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Columbus in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Columbus or anywhere else.';
        $content['pricing_title'] = 'Pricing in Columbus';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
	protected function contentCharlotteNC()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Charlotte in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Charlotte or anywhere else.';
        $content['pricing_title'] = 'Pricing in Charlotte';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
	protected function contentIndianapolisIN()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Indianapolis in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Indianapolis or anywhere else.';
        $content['pricing_title'] = 'Pricing in Indianapolis';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
	protected function contentClevelandOH()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Cleveland in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Cleveland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Cleveland';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
	protected function contentBostonMA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Boston in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Boston or anywhere else.';
        $content['pricing_title'] = 'Pricing in Boston';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
	protected function contentWashingtonDC()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Washington DC in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Washington DC or anywhere else.';
        $content['pricing_title'] = 'Pricing in Washington DC';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
	protected function contentJacksonvilleFL()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Jacksonville in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Jacksonville or anywhere else.';
        $content['pricing_title'] = 'Pricing in Jacksonville';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentAlbuquerqueNM()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Albuquerque in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Albuquerque or anywhere else.';
        $content['pricing_title'] = 'Pricing in Albuquerque';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentArlingtonTX()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Arlington in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Arlington or anywhere else.';
        $content['pricing_title'] = 'Pricing in Arlington';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentAspenCO()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Aspen in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Aspen or anywhere else.';
        $content['pricing_title'] = 'Pricing in Aspen';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentBaltimoreMD()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Baltimore in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Baltimore or anywhere else.';
        $content['pricing_title'] = 'Pricing in Baltimore';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentChicagoIL()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Chicago in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Chicago or anywhere else.';
        $content['pricing_title'] = 'Pricing in Chicago';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentColoradoSpringsCO()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Colorado Springs in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Colorado Springs or anywhere else.';
        $content['pricing_title'] = 'Pricing in Colorado Springs';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentdenverCO()
    {
        $content['top_title'] = 'Book Private Yoga Classes in denver in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in denver or anywhere else.';
        $content['pricing_title'] = 'Pricing in denver';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
		
	}
		
		protected function contentElPasoTX()
    {
        $content['top_title'] = 'Book Private Yoga Classes in El Paso in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in El Paso or anywhere else.';
        $content['pricing_title'] = 'Pricing in El Paso';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentFortWorthTX()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Fort Worth in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Fort Worth or anywhere else.';
        $content['pricing_title'] = 'Pricing in Fort Worth';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentFresnoCA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Fresno in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Fresno or anywhere else.';
        $content['pricing_title'] = 'Pricing in Fresno';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentHollywoodFL()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Hollywood in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Hollywood or anywhere else.';
        $content['pricing_title'] = 'Pricing in Hollywood';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentHonoluluHI()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Honolulu in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Honolulu or anywhere else.';
        $content['pricing_title'] = 'Pricing in Honolulu';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentKansasCityMO()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Kansas City in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Kansas City or anywhere else.';
        $content['pricing_title'] = 'Pricing in Kansas City';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
		protected function contentLongBeachCA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Long Beach in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Long Beach or anywhere else.';
        $content['pricing_title'] = 'Pricing in Long Beach';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentLouisvilleKY()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Louisville in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Louisville or anywhere else.';
        $content['pricing_title'] = 'Pricing in Louisville';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
		protected function contentMemphisTN()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Memphis in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Memphis or anywhere else.';
        $content['pricing_title'] = 'Pricing in Memphis';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
		protected function contentMontereybayCA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Monterey Bay in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Monterey Bay or anywhere else.';
        $content['pricing_title'] = 'Pricing in Monterey Bay';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentMesaAZ()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Mesa in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Mesa or anywhere else.';
        $content['pricing_title'] = 'Pricing in Mesa';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentMilwaukeeWI()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Milwaukee in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Milwaukee or anywhere else.';
        $content['pricing_title'] = 'Pricing in Milwaukee';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentNashvilleTN()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Nashville in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Nashville or anywhere else.';
        $content['pricing_title'] = 'Pricing in Nashville';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
	protected function contentOklahomaCityOK()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Oklahoma City in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Oklahoma City or anywhere else.';
        $content['pricing_title'] = 'Pricing in Oklahoma City';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentNewarkNJ()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Newark in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Newark or anywhere else.';
        $content['pricing_title'] = 'Pricing in Newark';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentOaklandCA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Oakland in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Oakland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Oakland';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}

		protected function contentOrlandoFL()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Orlando in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Orlando or anywhere else.';
        $content['pricing_title'] = 'Pricing in Orlando';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		

		
		protected function contentPortlandOR()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Portland in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Portland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Portland';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentRaleighNC()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Raleigh in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Raleigh or anywhere else.';
        $content['pricing_title'] = 'Pricing in Raleigh';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentSacramentoCA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Sacramento in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Sacramento or anywhere else.';
        $content['pricing_title'] = 'Pricing in Sacramento';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentSanAntonioTX()
    {
        $content['top_title'] = 'Book Private Yoga Classes in San Antonio in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in San Antonio or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Antonio';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentSanJoseCA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in San Jose in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in San Jose or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Jose';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
		protected function contentSarasotaFL()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Sarasota in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Sarasota or anywhere else.';
        $content['pricing_title'] = 'Pricing in Sarasota';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentSeattleWA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Seattle in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Seattle or anywhere else.';
        $content['pricing_title'] = 'Pricing in Seattle';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentStPetersburgFL()
    {
        $content['top_title'] = 'Book Private Yoga Classes in St.Petersburg in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in St.Petersburg or anywhere else.';
        $content['pricing_title'] = 'Pricing in St.Petersburg';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		

		protected function contentTulsaOK()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Tulsa in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Tulsa or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tulsa';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
	protected function contentOmahaNE()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Omaha in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Omaha or anywhere else.';
        $content['pricing_title'] = 'Pricing in Omaha';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
		
		protected function contentVirginiaBeachVA()
    {
        $content['top_title'] = 'Book Private Yoga Classes in Virginia Beach in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome yoga teacher to come to wherever you are in Virginia Beach or anywhere else.';
        $content['pricing_title'] = 'Pricing in Virginia Beach';
        $content['pricing_price'] = '$99.99/hr/class';
        $content['pricing_content'] = '*For new clients. Prices can vary afterwards based on demand and supply.';
        $content['services_1'] = [
            'title' => 'Create Request',
            'content' => 'Choose preferred gender of teacher, location and time on the app and send booking request to local yoga teachers',
        ];
        $content['services_2'] = [
            'title' => 'Yoga Teachers Respond',
            'content' => 'The certified and experienced yoga teachers on our platform either accept your request, or propose alternate times.',
        ];
        $content['services_3'] = [
            'title' => 'Session Confirmed',
            'content' => 'If a teacher accepts one of your requested start times, the booking gets confirmed automatically. If they proposed alternate times, you have the option to confirm them or not.',
        ];
        $content['services_4'] = [
            'title' => 'Relax and Enjoy',
            'content' => 'Your confirmed yoga teacher will arrive at your location 5-10 minutes before the start of your session',
        ];
        $content['testimonials_title'] = 'What they say in ';
        $content['testimonials'] = [
            ['title' => 'Naomi R.','testimonial_content' => "I really enjoyed my message session! My therapist was very friendly and kind. This was my first experience with this app and now I wonder why I waited so long. It really was such a wonderful experience. What I was impressed with was how this app encouraged me to explore other massage varieties, since that was my first time and I didn’t know much about it."],
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Houston, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];
       $content['price']['category'] = [
                    ['title' => 'Flow','description' => $this->priceCommonSection['flow'],'price'=> '99.99' ],
                    ['title' => 'Alignment','description' => $this->priceCommonSection['alignment'],'price'=> '99.99' ],
                    ['title' => 'Restorative','description' => $this->priceCommonSection['restorative'],'price'=> '99.99' ],
                    ['title' => 'Prenatal','description' => $this->priceCommonSection['prenatal'],'price'=> '99.99' ],
                    ['title' => 'Meditation','description' => $this->priceCommonSection['meditation'],'price'=> '99.99' ],
                ];
        return $content;
	}
	
}