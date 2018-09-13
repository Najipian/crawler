<?php
/**
 * Created by IntelliJ IDEA.
 * User: Najipian
 * Date: 9/12/2018
 */

// It may take a while to crawl a site ...
set_time_limit(10000);

require_once("lib/dbClient.php");
require_once("lib/crawler.php");

// url to crawl and how deep
$url = 'https://www.homegate.ch/mieten/immobilien/kanton-zuerich/trefferliste';
$linkDepth = 3;

// init database connection
$dbClient = new dbClient();

// apply crawling process
$urlCrawler = new crawler($url , 1);
$urlCrawler->save_links();
