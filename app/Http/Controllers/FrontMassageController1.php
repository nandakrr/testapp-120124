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
    protected $allCities,$contentByCities;
    
    function __construct()
    {
        // When new city come : add new array "FUNCTION_NAME" => "CITY NAME" THEN create new function with same name 
        $this->allCities = ["massage-los-angeles" => "Los Angeles","massage-new-york" => "New York","massage-miami" => "Miami","massage-houston" => "Houston","massage-las-vegas" => "Las Vegas","massage-san-francisco" => "San Francisco","massage-san-diego" => "San Diego","massage-phoenix" => "Phoenix","massage-dallas" => "Dallas","massage-austin" => "Austin","massage-tampa-fl" => "Tampa","massage-Atlanta-ga" => "Atlanta","massage-Philadelphia-pa" => "Philadelphia","massage-detroit-mi" => "Detroit","massage-tucson-az" => "Tucson","massage-scottsdale-az" => "Scottsdale","massage-columbus-oh" => "Columbus","massage-charlotte-nc" => "Charlotte","massage-indianapolis-in" => "Indianapolis","massage-cleveland-oh" => "Cleveland","massage-boston-ma" => "Boston","massage-washingtondc" => "Washington Dc","massage-jacksonville-fl" => "Jacksonville","massage-albuquerque-nm" => "Albuquerque","massage-arlington-tx" => "Arlington","massage-aspen-co" => "Aspen","massage-baltimore-md" => "Baltimore","massage-chicago-il" => "Chicago","massage-coloradosprings-co" => "Colorado Springs","massage-denver-co" => "Denver","massage-elpaso-tx" => "El Paso","massage-fortworth-tx" => "Fort Worth","massage-fresno-ca" => "Fresno","massage-hollywood-fl" => "Hollywood","massage-honolulu-hi" => "Honolulu","massage-kansascity-mo" => "Kansas City","massage-longbeach-ca" => "Long Beach","massage-louisville-ky" => "Louisville","massage-memphis-tn" => "Memphis","massage-montereybay-ca" => "Bonterey Bay","massage-mesa-az" => "Mesa","massage-milwaukee-wi" => "Milwaukee","massage-nashville-tn" => "Nashville","massage-newark-nj" => "Newark","massage-oakland-ca" => "Oakland","massage-oklahoma-ok" => "Oklahoma","massage-omaha-ne" => "Omaha","massage-orlando-fl" => "Orlando","massage-philadelphia-pa" => "Philadelphia","massage-portland=-or" => "Portland","massage-raleigh-nc" => "Raleigh","massage-sacramento-ca" => "Sacramento","massage-sanantonio-tx" => "Sanantonio","massage-sanjose-ca" => "San Jose","massage-sarasota-fl" => "Sarasota","massage-seattle-wa" => "Seattle","massage-stpetersburg-fl" => "St Petersburg","massage-tulsa-ok" => "Tulsa","massage-virginiabeach-va" => "Virginia Beach"];

        $this->contentByCities = ["massage-los-angeles" => "contentLosAngeles","massage-new-york" => "contentNewYork","massage-miami" => "contentMiami","massage-houston" => "contentHouston","massage-las-vegas" => "contentLasVegas","massage-san-francisco" => "contentSanFrancisco","massage-san-diego" => "contentSanDiego","massage-phoenix" => "contentPhoenix","massage-dallas" => "contentDallas","massage-austin" => "contentAustin","massage-tampa-fl" => "contentTampaFL","massage-atlanta-ga" => "contentAtlantaGA","massage-philadelphia-pa" => "contentPhiladelphiaPA","massage-detroit-mi" => "contentDetroitMI","massage-tucson-az" => "contentTucsonAZ","massage-scottsdale-az" => "contentScottsdaleAZ","massage-columbus-oh" => "contentColumbubsOH","massage-charlotte-nc" => "contentCharlotteNC","massage-indianapolis-in" => "contentIndianapolisIN","massage-cleveland-oh" => "contentClevelandOH","massage-boston-ma" => "contentBostonMA","massage-washingtondc" => "contentWashingtonDC","massage-jacksonville-fl" => "contentJacksonvilleFL","massage-albuquerque-nm" => "contentAlbuquerqueNM","massage-arlington-tx" => "contentArlingtonTX","massage-aspen-co" => "contentAspenCO","massage-baltimore-md" => "contentBaltimoreMD","massage-chicago-il" => "contentChicagoIL","massage-coloradosprings-co" => "contentColoradoSpringsCO","massage-denver-co" => "contentDenverCO","massage-elpaso-tx" => "contentElPasoTX","massage-fortworth-tx" => "contentFortWorthTX","massage-fresno-ca" => "contentFresnoCA","massage-hollywood-fl" => "contentHollywoodFL","massage-honolulu-hi" => "contentHonoluluHI","massage-kansascity-mo" => "contentKansasCityMO","massage-longbeach-ca" => "contentLongBeachCA","massage-louisville-ky" => "contentLouisvilleKY","massage-memphis-tn" => "contentMemphisTN","massage-montereybay-ca" => "contentMontereybayCA","massage-mesa-az" => "contentMesaAZ","massage-milwaukee-wi" => "contentMilwaukeeWI","massage-nashville-tn" => "contentNashvilleTN","massage-newark-nj" => "contentNewarkNJ","massage-oakland-ca" => "contentOaklandCA","massage-oklahoma-ok" => "contentOklahomaOK","massage-omaha-ne" => "contentOmahaNE","massage-orlando-fl" => "contentOrlandoFL","massage-philadelphia-pa" => "contentPhiladelphiaPA","massage-portland-or" => "contentPortlandOR","massage-raleigh-nc" => "contentRaleighNC","massage-sacramento-ca" => "contentSacramentoCA","massage-sanantonio-tx" => "contentSanAntonioTX","massage-sanjose-ca" => "contentSanJoseCA","massage-sarasota-fl" => "contentSarasotaFL","massage-seattle-wa" => "contentSeattleWA","massage-stpetersburg-fl" => "contentSt.PetersburgFL","massage-tucson-az" => "contentTucsonAZ","massage-tulsa-ok" => "contentTulsaOK","massage-virginiabeach-va" => "contentVirginiaBeachVA"];

    }

    public function index(Request $request,$cityName)
    {
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
        $content['top_title'] = 'Book in-home Massage<br>in Miami<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Miami or anywhere else.';
        $content['pricing_title'] = 'Pricing in Miami';
        $content['pricing_price'] = '$79.99/hr';
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
            ['title' => 'Tori C.','testimonial_content' => "I'm obsessed with this app! I've been using it frequently- if I am in Miami, I try to get 2 massages sessions every month. This is full on bodywork- I personally find it relaxing. My provider was extremely knowledgeable. I've suggested this app to 4 of my friends and every person loved it. I can't write enough amazing things about my experience with this app. My husband also gets massage sessions using this app and we've never been disappointed."],
            ['title' => 'Perry K.','testimonial_content' => 'I was looking for an affordable massage center close to home but couldn’t find any so I went on Google to search for a mobile Massage service & BigToe app came up. I booked my 1st massage session with their top provider Ana as she had great reviews of how amazing she was, so I decided to give her a try. Now, let me tell you, she has magic healing hands!! I felt renewed & refreshed. My body felt amazing. So, naturally I had to keep using the app every week to feel human & relaxed.']
        ];

        return $content;
    }

    protected function contentNewYork()
    {
        $content['top_title'] = 'Book in-home Massage<br>in New York<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in New York or anywhere else.';
        $content['pricing_title'] = 'Pricing in New York';
        $content['pricing_price'] = '$79.99/hr';
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
            ['title' => 'Becky A.','testimonial_content' => "Really, really good massage. My massage therapist was extremely skillful and made my day so much better. I can't thank her enough. I'm looking forward to my next massage with her. The app is pretty great too!"],
            ['title' => 'Sam B.','testimonial_content' => "Imagine going to Los Angeles and getting an in-room massage and then coming back to New York and using the same app... These guys have spoiled me big time and I'm loving it...the therapists on the app are punctual and skillful"],
            ['title' => 'Alex S.','testimonial_content' => 'You guys are the real deal. Never before have I been able to find the right combination of price and quality. Most of the other options are either too expensive, or too meh.....me and some of my friends are superfans now.']
        ];

        return $content;
    }

    protected function contentLosAngeles()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Los Angeles<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Los Angeles or anywhere else.';
        $content['pricing_title'] = 'Pricing in Los Angeles';
        $content['pricing_price'] = '$79.99/hr';
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
            ['title' => 'Amy A.','testimonial_content' => 'My co-worker Rey recommended this app. I was new to LA and didn`t know many places for massage so I simply downloaded the app and got Sara as my provider. She came on time, we had a wonderful session for a good 2 hours and I felt super relaxed. I STRONGLY recommend BigToe app to EVERYONE. This on-demand massage service is truly outstanding.'],
            ['title' => 'Sam B.','testimonial_content' => ' Imagine going to Los Angeles and getting an in-room massage and then coming back to New York and using the same app... These guys have spoiled me big time and I`m loving it...the therapists on the app are punctual and skillful'],
            ['title' => 'Nathan P.','testimonial_content' => 'As a beginner, I found this app and it is so easy to navigate. Each step in the scheduling of a massage session was so clear and precise. I believe everyone should use this massage at home service especially in LA where every other subscription cost more than your expectation. The therapist was truly awesome in so many ways and assured me that I am in good hands.']
        ];

        return $content;
    }


protected function contentHouston()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Houston<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Houston or anywhere else.';
        $content['pricing_title'] = 'Pricing in Houston';
        $content['pricing_price'] = '$79.99/hr';
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
            ['title' => 'Becky A.','testimonial_content' => "Really, really good massage. My massage therapist was extremely skillful and made my day so much better. I can't thank her enough. I'm looking forward to my next massage with her. The app is pretty great too!"],
            ['title' => 'Elise Y.','testimonial_content' => "I am still in the new customer phase, but so far I am very impressed.. All of my questions were answered prior to sending to the therapist and I felt very comfortable with my decision to choose Bigtoe for my first ever massage therapy. Sounds like they have a great service and I am excited to book another session soon!."],
            ['title' => 'Poonam H.','testimonial_content' => 'The guy on chat offered me some help in scheduling my session and was able to clear up all the questions I had - Booked on the spot. Read many reviews, compared places, different prices and I feel this is the best option. The provider was expert and friendly. Thankful for the ease of their mobile application and no hidden charges. Will ask for service again!']
        ];

        return $content;
    }
	
	protected function contentLasVegas()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Las Vegas<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Las Vegas or anywhere else.';
        $content['pricing_title'] = 'Pricing in Las vegas';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
    }
	
	protected function contentSanFrancisco()
    {
        $content['top_title'] = 'Book in-home Massage<br>in San Francisco<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Francisco Bay Area or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Francisco';
        $content['pricing_price'] = '$79.99/hr';
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
            ['title' => 'Gordon G.','testimonial_content' => "The therapist came to my home at exactly 10:00 PM which I requested. The way of massage was very unique and impressive. I ended up making a commitment to use this app more often with myself. I was surprised with the professionalism when they asked for my feedback after the session about my experience with the app and therapist. I am very satisfied with it."],
            ['title' => 'Lizzy B.','testimonial_content' => "I instantly felt extremely good and relaxed after my session. The therapist was so understanding and was keen to discover exactly what sort of pressure I wanted, as well as which areas I needed her to focus more attention on. The massage itself was full of satisfaction and powerful at the same time. It felt that I could have started fulfilling this need for my body a long time ago - I highly recommend this mobile massage service. I’ll definitely use it again."],
            ['title' => 'Georgina K.','testimonial_content' => 'Bigtoe app is the best. My massage therapist was super talented, kind and gave a great massage. She paid attention to my requests whether it is to avoid or concentrate. I have been getting massage therapy using this app for a few months now and love it.']
        ];

        return $content;
    }
	
	protected function contentSanDiego()
    {
        $content['top_title'] = 'Book in-home Massage<br>in San Diego<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Diego or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Diego';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
    }
	
	protected function contentPhoenix()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Phoenix<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Phoenix or anywhere else.';
        $content['pricing_title'] = 'Pricing in Phoenix';
        $content['pricing_price'] = '$79.99/hr';
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
            ['title' => 'Nancy P.','testimonial_content' => "I am a new member to bigtoe mobile massage service. I was getting a series of lymphatic massage sessions from a local therapist. One day she didn’t come for the session so I had to go online and look for an alternative. I luckily found them and asked a few questions on chat. They gave the best price for 7 lymphatic sessions. It was hard for me to expect good service at such an affordable price as compared to what I was paying to my local therapist but I gave them a chance and booked just one session to check if they are good or not. Luckily, they proved me wrong and now I am getting my 8th session for them this wednesday. They are simply awesome."],
            ['title' => 'Serge B.','testimonial_content' => "Customer service and therapy has been great so far and they even worked with me on a personal request that is not generally offered by all companies. They really deserve my endorsement."],
            ['title' => 'Paula S.','testimonial_content' => 'After doing much research on the affordable massage sessions, I decided to go with BigToe. Since making that decision, I have had an amazing experience. They are super responsive and honest, I got 3 sessions from them and am still planning to have it two or three times every month but I have no doubt that the future sessions will be perfect and leave me with a dazzling smile.']
        ];

        return $content;
    }
	
	protected function contentDallas()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Dallas<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Dallas or anywhere else.';
        $content['pricing_title'] = 'Pricing in Dallas';
        $content['pricing_price'] = '$79.99/hr';
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
            ['title' => 'Cory A.','testimonial_content' => "I am 35. I never had the opportunity to try this type of service. I chose Bigtoe after researching the options- they did the job! I was a bit skeptical at first because I had never heard of them before but considering the price, I gave it a try and now I am proud of my decision. Overall I recommend the service. I Immediately referred my sister when I completed my session with their provider."],
            ['title' => 'Tracy K.','testimonial_content' => "Sarah was my Therapist! She was very knowledgeable and helped me to use them for my weekly massage session. The price is unbeatable and their service is great. Highly recommend it. The call I received from them after the session helped me to believe that they do care about their customers!"],
            ['title' => 'Alex S.','testimonial_content' => 'You guys are the real deal. Never before have I been able to find the right combination of price and quality. Most of the other options are either too expensive, or too meh.....me and some of my friends are superfans now.']
        ];

        return $content;
    }
	
	protected function contentAustin()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Austin<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Austin or anywhere else.';
        $content['pricing_title'] = 'Pricing in Austin';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
    }
	
	protected function contentTampaFL()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Tampa<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Austin or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tampa';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
    }
	
	protected function contentAtlantaGA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Atlanta<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Atlanta or anywhere else.';
        $content['pricing_title'] = 'Pricing in Atlanta';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
    }
	
	protected function contentPhiladelphiaPA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Philadelphia<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Philadelphia or anywhere else.';
        $content['pricing_title'] = 'Pricing in Philadelphia';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
    }
	
	protected function contentDetroitMI()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Detroit<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Detroit or anywhere else.';
        $content['pricing_title'] = 'Pricing in Detroit';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
	protected function contentTucsonAZ()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Tucson<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Tucson or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tucson';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
	protected function contentScottsdaleAZ()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Scottsdale<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Scottsdale or anywhere else.';
        $content['pricing_title'] = 'Pricing in Scottsdale';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentColumbubsOH()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Columbus<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Columbus or anywhere else.';
        $content['pricing_title'] = 'Pricing in Columbus';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
	protected function contentCharlotteNC()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Charlotte<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Charlotte or anywhere else.';
        $content['pricing_title'] = 'Pricing in Charlotte';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
	protected function contentIndianapolisIN()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Indianapolis<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Indianapolis or anywhere else.';
        $content['pricing_title'] = 'Pricing in Indianapolis';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
	protected function contentClevelandOH()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Cleveland<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Cleveland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Cleveland';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
	protected function contentBostonMA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Boston<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Boston or anywhere else.';
        $content['pricing_title'] = 'Pricing in Boston';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
	protected function contentWashingtonDC()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Washington DC<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Washington DC or anywhere else.';
        $content['pricing_title'] = 'Pricing in Washington DC';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
	protected function contentJacksonvilleFL()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Jacksonville<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Jacksonville or anywhere else.';
        $content['pricing_title'] = 'Pricing in Jacksonville';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentAlbuquerqueNM()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Albuquerque<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Albuquerque or anywhere else.';
        $content['pricing_title'] = 'Pricing in Albuquerque';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentArlingtonTX()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Arlington<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Arlington or anywhere else.';
        $content['pricing_title'] = 'Pricing in Arlington';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentAspenCO()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Aspen<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Aspen or anywhere else.';
        $content['pricing_title'] = 'Pricing in Aspen';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentBaltimoreMD()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Baltimore<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Baltimore or anywhere else.';
        $content['pricing_title'] = 'Pricing in Baltimore';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentChicagoIL()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Chicago<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Chicago or anywhere else.';
        $content['pricing_title'] = 'Pricing in Chicago';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentColoradoSpringsCO()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Colorado Springs<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Colorado Springs or anywhere else.';
        $content['pricing_title'] = 'Pricing in Colorado Springs';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentdenverCO()
    {
        $content['top_title'] = 'Book in-home Massage<br>in denver<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in denver or anywhere else.';
        $content['pricing_title'] = 'Pricing in denver';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
		
	}
		
		protected function contentElPasoTX()
    {
        $content['top_title'] = 'Book in-home Massage<br>in El Paso<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in El Paso or anywhere else.';
        $content['pricing_title'] = 'Pricing in El Paso';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentFortWorthTX()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Fort Worth<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Fort Worth or anywhere else.';
        $content['pricing_title'] = 'Pricing in Fort Worth';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentFresnoCA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Fresno<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Fresno or anywhere else.';
        $content['pricing_title'] = 'Pricing in Fresno';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentHollywoodFL()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Hollywood<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Hollywood or anywhere else.';
        $content['pricing_title'] = 'Pricing in Hollywood';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentHonoluluHI()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Honolulu<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Honolulu or anywhere else.';
        $content['pricing_title'] = 'Pricing in Honolulu';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentKansasCityMO()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Kansas City<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Kansas City or anywhere else.';
        $content['pricing_title'] = 'Pricing in Kansas City';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
		protected function contentLongBeachCA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Long Beach<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Long Beach or anywhere else.';
        $content['pricing_title'] = 'Pricing in Long Beach';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentLouisvilleKY()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Louisville<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Louisville or anywhere else.';
        $content['pricing_title'] = 'Pricing in Louisville';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
		protected function contentMemphisTN()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Memphis<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Memphis or anywhere else.';
        $content['pricing_title'] = 'Pricing in Memphis';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
		protected function contentMontereybayCA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Monterey Bay<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Monterey Bay or anywhere else.';
        $content['pricing_title'] = 'Pricing in Monterey Bay';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentMesaAZ()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Mesa<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Mesa or anywhere else.';
        $content['pricing_title'] = 'Pricing in Mesa';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentMilwaukeeWI()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Milwaukee<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Milwaukee or anywhere else.';
        $content['pricing_title'] = 'Pricing in Milwaukee';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentNashvilleTN()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Nashville<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Nashville or anywhere else.';
        $content['pricing_title'] = 'Pricing in Nashville';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentNewarkNJ()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Newark<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Newark or anywhere else.';
        $content['pricing_title'] = 'Pricing in Newark';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentOaklandCA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Oakland<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Oakland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Oakland';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}

		protected function contentOrlandoFL()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Orlando<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Orlando or anywhere else.';
        $content['pricing_title'] = 'Pricing in Orlando';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		

		
		protected function contentPortlandOR()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Portland<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Portland or anywhere else.';
        $content['pricing_title'] = 'Pricing in Portland';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentRaleighNC()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Raleigh<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Raleigh or anywhere else.';
        $content['pricing_title'] = 'Pricing in Raleigh';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentSacramentoCA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Sacramento<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Sacramento or anywhere else.';
        $content['pricing_title'] = 'Pricing in Sacramento';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentSanAntonioTX()
    {
        $content['top_title'] = 'Book in-home Massage<br>in San Antonio<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Antonio or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Antonio';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentSanJoseCA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in San Jose<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in San Jose or anywhere else.';
        $content['pricing_title'] = 'Pricing in San Jose';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
	
		protected function contentSarasotaFL()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Sarasota<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Sarasota or anywhere else.';
        $content['pricing_title'] = 'Pricing in Sarasota';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentSeattleWA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Seattle<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Seattle or anywhere else.';
        $content['pricing_title'] = 'Pricing in Seattle';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentStPetersburgFL()
    {
        $content['top_title'] = 'Book in-home Massage<br>in St.Petersburg<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in St.Petersburg or anywhere else.';
        $content['pricing_title'] = 'Pricing in St.Petersburg';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		

		protected function contentTulsaOK()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Tulsa<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Tulsa or anywhere else.';
        $content['pricing_title'] = 'Pricing in Tulsa';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
		protected function contentVirginiaBeachVA()
    {
        $content['top_title'] = 'Book in-home Massage<br>in Virginia Beach<br>in Seconds';
        $content['top_content'] = 'Use the Bigtoe app to get an awesome massage therapist to come to wherever you are in Virginia Beach or anywhere else.';
        $content['pricing_title'] = 'Pricing in Virginia Beach';
        $content['pricing_price'] = '$79.99/hr';
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

        return $content;
	}
		
}
