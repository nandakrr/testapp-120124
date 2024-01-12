<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;
use Session;

class SiteMapController extends Controller
{
    public function index()
    {
        
        $rootPath = str_replace('/app','',app_path());
        $xml = simplexml_load_file($rootPath.'/sitemap.xml');

        $list = $xml->url;


        $sitemapText = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
              http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';


            $sitemapText .="\n";
            $sitemapText .='<url>
                <loc>https://www.bigtoe.yoga/</loc>
                <lastmod>2019-11-21T06:27:36+00:00</lastmod>
                <priority>1.00</priority>
                </url>

                <url>
                    <loc>https://www.bigtoe.yoga/classes</loc>
                    <lastmod>2019-11-21T06:27:36+00:00</lastmod>
                    <priority>0.80</priority>
                </url>

                <url>
                    <loc>https://www.bigtoe.yoga/appointments</loc>
                    <lastmod>2019-11-21T06:27:36+00:00</lastmod>
                    <priority>0.80</priority>
                </url>

                <url>
                    <loc>https://www.bigtoe.yoga/become-a-partner-studio</loc>
                    <lastmod>2019-11-21T06:27:36+00:00</lastmod>
                    <priority>0.80</priority>
                </url>

                <url>
                    <loc>https://www.bigtoe.yoga/become-a-partner-provider</loc>
                    <lastmod>2019-11-21T06:27:36+00:00</lastmod>
                    <priority>0.80</priority>
                </url>

                <url>
                    <loc>https://www.bigtoe.yoga/privacy</loc>
                    <lastmod>2019-11-21T06:27:36+00:00</lastmod>
                    <priority>0.80</priority>
                </url>

                <url>
                    <loc>https://www.bigtoe.yoga/contact</loc>
                    <lastmod>2019-11-21T06:27:36+00:00</lastmod>
                    <priority>0.80</priority>
                </url>';
            $sitemapText .="\n";

// massage list we will get here 
$massageList = new \App\Http\Controllers\FrontMassageController();

$allCityList = $massageList->getAllListForSiteMap();

if($allCityList)
{
    foreach($allCityList as $citySlug => $singleList)
    {
        $sitemapText .="\n";
        
        $sitemapText .="\t\t\t\t <url>
        \t\t<loc>".\URL::to($citySlug)."</loc>
        \t\t<lastmod>".$list[0]->lastmod."</lastmod>
        \t\t<priority>0.80</priority>
        </url>";
        $sitemapText .="\n";

    }
}

// Yoga list we will get here 
$yogaList = new \App\Http\Controllers\FrontYogaController();

$yogaCityList = $yogaList->getAllListForSiteMap();

if($yogaCityList)
{
    foreach($yogaCityList as $citySlug => $singleList)
    {
        $sitemapText .="\n";
        
        $sitemapText .="\t\t\t\t <url>
        \t\t<loc>".\URL::to($citySlug)."</loc>
        \t\t<lastmod>".$list[0]->lastmod."</lastmod>
        \t\t<priority>0.80</priority>
        </url>";
        $sitemapText .="\n";

    }
}

$sitemapText .= '</urlset>';
$sitemap = fopen($rootPath."/sitemap.xml", "w") or die("Unable to open file!");

fwrite($sitemap, $sitemapText);
fclose($sitemap);
         
echo "site map update process DONE";

    }
}
?>